<?php
require_once "{$_SERVER["DOCUMENT_ROOT"]}/bootstrap.php";
require_once "lib/core/db.php";
require_once "lib/core/api.php";
require_once "lib/core/uuid.php";
require_once "lib/users.php";

readonly class AuthSession extends DBTable {
    public const SESSION_VALIDITY_SECS = 10 * 24 * 60 * 60; // 10 days
    private const KEY_HASH_ALGO = 'sha256';

    protected function __construct(
        public string $id,
        private string $keyHash,
        public string $userId,
        public DateTime $createdAt,
        public bool $forceExpired,
    ) {}

    public static function validateId(string $key): string {
        return validateUUID($key);
    }

    public static function validateKey(string $key): string {
        return validateUUID($key, "key");
    }

    public static function sqlValidityCheck(?string $alias): string {
        $table = self::tableAliasPrefix($alias);
        $seconds = self::SESSION_VALIDITY_SECS;
        return <<<sql
        $table`forceExpired` = 0
        AND date_add($table`createdAt`, INTERVAL $seconds SECOND) > now()
        sql;
    }

    public function expired(Database $db): bool {
        $validityCheck = AuthSession::sqlValidityCheck("s");
        $query = $db->createStatement(<<<sql
            SELECT s.*
            FROM `AuthSessions` s
            WHERE s.`id` = ?
                AND $validityCheck
            sql);
        $ok = $query->bind(SqlValueType::String->createParam($this->id))->execute();
        if (!$ok) {
            return false;
        }
        $result = $query->expectResult();
        return $result->totalRows == 0;
    }

    public static function fromKey(Database $db, string $key): self|false {
        $key = self::validateKey($key);

        $validityCheck = AuthSession::sqlValidityCheck("s");
        $query = $db->createStatement(<<<sql
            SELECT s.*
            FROM `AuthSessions` s
            WHERE s.`keyHash` = ?
                AND $validityCheck
            sql);
        $keyHash = hash(self::KEY_HASH_ALGO, $key);
        $ok = $query->bind(SqlValueType::String->createParam($keyHash))->execute();
        if (!$ok) {
            return false;
        }
        $result = $query->expectResult();
        if ($result->totalRows === 0) {
            return false;
        }
        $auth = self::fromTableRow($result->fetchOne());
        if ($auth === false) {
            return false;
        } else {
            return $auth;
        }
    }

    public static function createUserSession(Database $db, string $userId): string|false {
        $userId = User::validateId($userId);

        $query = $db->createStatement(<<<sql
            INSERT INTO `AuthSessions`(`id`, `keyHash`, `userId`) VALUES (?, ?, ?)
            sql);
        $id = uuidv4();
        $key = uuidv4();
        $keyHash = hash(self::KEY_HASH_ALGO, $key);
        $ok = $query->bind(
            SqlValueType::String->createParam($id),
            SqlValueType::String->createParam($keyHash),
            SqlValueType::String->createParam($userId),
        )->execute();
        if ($ok) {
            return $key;
        } else {
            return false;
        }
    }
}

readonly class LoginSession {
    private const AUTH_KEY_COOKIE_ATTR = "auth_key";
    private const AUTH_KEY_COOKIE_PATH = "/";

    public function __construct(
        public AuthSession $auth,
        public User $user,
    ) {}

    public static function register(
        Database $db, 
        string $username, 
        string $email, 
        string $password
    ): self|false {
        if (User::searchEmail($db, $email)) {
            throw new InvalidArgumentException("User already exists");
        }
        $user = User::create($db, $username, $email, $password);
        if ($user === false) {
            return false;
        }
        return self::login($db, $email, $password);
    }

    public static function login(Database $db, string $email, string $password): self|false {
        $user = User::fromEmailAndPassword($db, $email, $password);
        if ($user === false) {
            return false;
        }
        $authKey = AuthSession::createUserSession($db, $user->id);
        if ($authKey === false) {
            return false;
        }
        $auth = AuthSession::fromKey($db, $authKey);
        if ($auth === false) {
            return false;
        }

        $login = new self(auth: $auth, user: $user);
        setcookie(
            self::AUTH_KEY_COOKIE_ATTR,
            $authKey,
            [
                "expires" => time() + AuthSession::SESSION_VALIDITY_SECS,
                "path" => self::AUTH_KEY_COOKIE_PATH,
            ],
        );
        return $login;
    }

    public function logout(Database $db): bool {
        $query = $db->createStatement(<<<sql
            UPDATE `AuthSessions`
            SET `forceExpired` = true
            WHERE `id` = ?
            sql);
        $ok = $query->bind(SqlValueType::String->createParam($this->auth->id))->execute();
        if ($ok) {
            unset($_COOKIE[self::AUTH_KEY_COOKIE_ATTR]);
            setcookie(
                self::AUTH_KEY_COOKIE_ATTR,
                "",
                [
                    "expires" => time(),
                    "path" => self::AUTH_KEY_COOKIE_PATH,
                ],
            );
        }
        return $ok;
    }

    private static function fromAuthSessionKey(Database $db, string $key): self|false {
        $auth = AuthSession::fromKey($db, $key);
        if ($auth === false) {
            return false;
        }

        $user = User::fromAuthSessionId($db, $auth->id);
        if ($user === false) {
            return false;
        }

        return new self(auth: $auth, user: $user);
    }

    public static function autoLogin(Database $db): self|false {
        $authSessionKey = $_COOKIE[self::AUTH_KEY_COOKIE_ATTR] ?? null;
        if ($authSessionKey === null) {
            return false;
        }
        
        $login = self::fromAuthSessionKey($db, $authSessionKey);
        if ($login === false || $login->auth->expired($db)) {
            return false;
        } else {
            return $login;
        }
    }

    public static function autoLoginOrRedirect(Database $db, string $redirect = "/login/"): self {
        $login = self::autoLogin($db);
        if ($login === false) {
            $res = new ApiResponse();
            $res->redirect($redirect);
        } else {
            return $login;
        }
    }
}

?>