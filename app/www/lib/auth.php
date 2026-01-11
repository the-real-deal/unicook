<?php
require_once "{$_SERVER['DOCUMENT_ROOT']}/bootstrap.php";
require_once "lib/core/db.php";

$db = Database::connectDefault();

readonly class AuthSession extends DBTable {
    public const SESSION_VALIDITY_SECS = 10 * 24 * 60 * 60; // 10 days

    protected function __construct(
        public string $id,
        public string $key,
        public string $userId,
        public DateTime $createdAt,
        public bool $forceExpired,
    ) {}

    public function expired(): bool {
        $now = new DateTime();
        $seconds = self::SESSION_VALIDITY_SECS;
        // https://www.php.net/manual/en/dateinterval.construct.php
        $secondsInterval = new DateInterval("PT{$seconds}S");
        return $this->forceExpired || $this->createdAt->add($secondsInterval) <= $now;
    }

    public static function sqlValidityCheck(?string $alias): string {
        $table = self::tableAliasPrefix($alias);
        $seconds = self::SESSION_VALIDITY_SECS;
        return <<<sql
        NOT $table`forceExpired` 
        AND date_add($table`createdAt`, INTERVAL $seconds SECOND) > now()
        sql;
    }

    public static function fromKey(Database $db, string $key): self|false {
        $validityCheck = AuthSession::sqlValidityCheck("s");
        $query = $db->createStatement(<<<sql
            SELECT s.*
            FROM `AuthSessions` s
            WHERE s.`key` = ?
                AND $validityCheck
            sql);
        $ok = $query->bind(SqlValueType::String->createParam($key))->execute();
        if (!$ok) {
            return false;
        }
        $result = $query->expectResult();
        if ($result->totalRows === 0) {
            return false;
        }
        return self::fromTableRow($result->fetchOne());
    }
}

readonly class User extends DBTable {
    protected function __construct(
        public string $id,
        public string $username,
        public string $email,
        public string $passwordHash,
        public ?string $avatarId,
        public bool $isAdmin,
        public DateTime $createdAt,
        public bool $deleted,
    ) {}

    public static function fromAuthSessionId(Database $db, string $sessionId): self|false {
        $query = $db->createStatement(<<<sql
            SELECT u.*
            FROM `Users` u
                JOIN `AuthSessions` s ON u.`id` = s.`userId`
            WHERE s.`id` = ?
            sql);
        $ok = $query->bind(SqlValueType::String->createParam($sessionId))->execute();
        if (!$ok) {
            return false;
        }

        $result = $query->expectResult();
        if ($result->totalRows === 0) {
            return false;
        }

        return self::fromTableRow($result->fetchOne());
    }

    public static function fromEmailAndPassword(Database $db, string $email, string $password): self|false {
        $query = $db->createStatement(<<<sql
            SELECT u.*
            FROM `Users` u
            WHERE u.`email` = ?
            sql);
        $ok = $query->bind(SqlValueType::String->createParam($email))->execute();
        if (!$ok) {
            return false;
        }
        $result = $query->expectResult();
        if ($result->totalRows === 0) {
            return false;
        }
        
        $user = self::fromTableRow($result->fetchOne());
        $passwordMatches = password_verify($password, $user->passwordHash);
        if (!$passwordMatches) {
            return false;
        }
        return $user;
    }

    private function getMostRecentAuthSession(Database $db): ?AuthSession {
        $query = $db->createStatement(<<<sql
            SELECT s.* 
            FROM `AuthSessions` s
            WHERE s.`userId` = ? 
            ORDER BY s.`createdAt` DESC
            sql);
        $ok = $query->bind(SqlValueType::String->createParam($this->id))->execute();
        if (!$ok) {
            return false;
        }
        $row = $query->expectResult()->fetchOne();
        return AuthSession::fromOptionalTableRow($row);
    }

    public function createAuthSession(Database $db): AuthSession|false {
        $query = $db->createStatement(<<<sql
            INSERT INTO `AuthSessions`(`userId`) VALUES (?)
            sql);
        
        $ok = $query->bind(SqlValueType::String->createParam($this->id))->execute();
        if (!$ok) {
            return false;
        }
        return $this->getMostRecentAuthSession($db);
    }
}

readonly class LoginSession {
    private const AUTH_KEY_COOKIES_ATTR = "auth_key";
    private const LOGIN_SESSION_ATTR = "login";

    public function __construct(
        public AuthSession $auth,
        public User $user,
    ) {}

    public static function login(Database $db, string $email, string $password): self|false {
        $user = User::fromEmailAndPassword($db, $email, $password);
        if ($user === false) {
            return false;
        }
        $auth = $user->createAuthSession($db);
        if ($auth === false) {
            return false;
        }
        $login = new self(auth: $auth, user: $user);
        $_SESSION[self::LOGIN_SESSION_ATTR] = serialize($login);
        setcookie(
            self::AUTH_KEY_COOKIES_ATTR,
            $login->auth->key,
            time() + AuthSession::SESSION_VALIDITY_SECS,
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
            unset($_COOKIE[self::AUTH_KEY_COOKIES_ATTR]);
            setcookie(
                self::AUTH_KEY_COOKIES_ATTR,
                "",
                time(), // expired
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

    public static function autoLogin(): self|false {
        $serializedLogin = $_SESSION[self::LOGIN_SESSION_ATTR] ?? null;
        if ($serializedLogin !== null) {
            return unserialize($serializedLogin);
        }

        $authSessionKey = $_COOKIE[AUTH_SESSION_KEY_COOKIES_ATTR] ?? null;
        if ($authSessionKey === null) {
            return false;
        }

        $login = self::fromAuthSessionKey($authSessionKey);
        if ($login === false) {
            return false;
        }
        $_SESSION[self::LOGIN_SESSION_ATTR] = serialize($login);
        return $login;
    }
}

?>