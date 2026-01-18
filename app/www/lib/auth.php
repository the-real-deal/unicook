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
    ) {}

    public static function validateId(string $key): string {
        return validateUUID($key, "Auth session id");
    }

    public static function validateKey(string $key): string {
        return validateUUID($key, "Auth session key");
    }

    public static function sqlValidityCheck(?string $alias): string {
        $table = self::tableAliasPrefix($alias);
        $seconds = self::SESSION_VALIDITY_SECS;
        return <<<sql
        date_add($table`createdAt`, INTERVAL $seconds SECOND) > now()
        sql;
    }

    public function expired(Database $db): bool {
        $validityCheck = self::sqlValidityCheck("s");
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

    public static function fromKey(Database $db, string $key, bool $valid = true): self|false {
        $key = self::validateKey($key);

        $validityCheck = $valid ? self::sqlValidityCheck("s") : "1";
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
        }
        return $auth;
    }

    public static function createUserSession(Database $db, User $user): string|false {
        $query = $db->createStatement(<<<sql
            INSERT INTO `AuthSessions`(`id`, `keyHash`, `userId`) VALUES (?, ?, ?)
            sql);
        $id = uuidv4();
        $key = uuidv4();
        $keyHash = hash(self::KEY_HASH_ALGO, $key);
        $ok = $query->bind(
            SqlValueType::String->createParam($id),
            SqlValueType::String->createParam($keyHash),
            SqlValueType::String->createParam($user->id),
        )->execute();
        if (!$ok) {
            return false;
        }
        return $key;
    }
}

readonly class LoginSession {
    public const AUTH_KEY_COOKIE_ATTR = "auth_key";
    public const AUTH_KEY_COOKIE_PATH = "/";

    public function __construct(
        public AuthSession $auth,
        public User $user,
    ) {}

    public static function register(
        Database $db, 
        string $username, 
        string $email, 
        string $password
    ): string|false {
        if (User::searchEmail($db, $email)) {
            throw new InvalidArgumentException("User already exists");
        }
        $user = User::create($db, $username, $email, $password);
        if ($user === false) {
            return false;
        }
        return self::login($db, $email, $password);
    }

    public static function login(Database $db, string $email, string $password): string|false {
        $user = User::fromEmailAndPassword($db, $email, $password);
        if ($user === false) {
            return false;
        }
        $authKey = AuthSession::createUserSession($db, $user);
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
        return $authKey;
    }

    public function logout(Database $db): bool {
        $query = $db->createStatement(<<<sql
            DELETE FROM `AuthSessions` s
            WHERE s.`id` = ?
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

    private static function fromAuthSessionKey(Database $db, string $key, bool $valid = true): self|false {
        $auth = AuthSession::fromKey($db, $key, $valid);
        if ($auth === false) {
            return false;
        }

        $user = User::fromAuthSession($db, $auth);
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
        if ($login === false) {
            return false;
        }
        return $login;
    }

    public static function autoLoginOrRedirect(Database $db, string $redirect = "/login/"): self {
        $login = self::autoLogin($db);
        if ($login === false) {
            $res = new ApiResponse();
            $res->redirect($redirect);
        }
        return $login;
    }
}

?>