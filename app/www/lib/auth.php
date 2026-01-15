<?php
require_once "{$_SERVER["DOCUMENT_ROOT"]}/bootstrap.php";
require_once "lib/core/db.php";
require_once "lib/core/api.php";
require_once "lib/core/uuid.php";
require_once "lib/users.php";

readonly class AuthSession extends DBTable {
    public const SESSION_VALIDITY_SECS = 10 * 24 * 60 * 60; // 10 days
    public const KEY_HASH_ALGO = 'sha256';

    protected function __construct(
        public string $id,
        public string $keyHash,
        public string $userId,
        public DateTime $createdAt,
        public bool $forceExpired,
    ) {}

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
}

readonly class LoginSession {
    private const AUTH_KEY_COOKIE_ATTR = "auth_key";
    private const AUTH_KEY_COOKIE_PATH = "/";
    private const LOGIN_SESSION_ATTR = "login";

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
        // TODO: call User::create, reuse login logic
    }

    public static function login(Database $db, string $email, string $password): self|false {
        $user = User::fromEmailAndPassword($db, $email, $password);
        if ($user === false) {
            return false;
        }
        $authKey = $user->createAuthSession($db);
        if ($authKey === false) {
            return false;
        }
        $auth = AuthSession::fromKey($db, $authKey);
        if ($auth === false) {
            return false;
        }

        $login = new self(auth: $auth, user: $user);
        $_SESSION[self::LOGIN_SESSION_ATTR] = serialize($login);
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
            unset($_SESSION[self::LOGIN_SESSION_ATTR]);
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
        $serializedLogin = $_SESSION[self::LOGIN_SESSION_ATTR] ?? null;
        if ($serializedLogin !== null) {
            $login = unserialize($serializedLogin);
            if (!$login->auth->expired($db)) {
                return $login;
            }
        }

        $authSessionKey = $_COOKIE[self::AUTH_KEY_COOKIE_ATTR] ?? null;
        if ($authSessionKey === null) {
            return false;
        }
        
        $login = self::fromAuthSessionKey($db, $authSessionKey);
        if ($login === false || $login->auth->expired($db)) {
            return false;
        } else {
            $_SESSION[self::LOGIN_SESSION_ATTR] = serialize($login);
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