<?php
require_once "{$_SERVER['DOCUMENT_ROOT']}/bootstrap.php";
require_once "lib/core/db.php";

define("AUTH_SESSION_ID_KEY", "AUTH_SESSION_ID");

$db = Database::connectDefault();

readonly class SessionData {
    public function __construct(
        public AuthSession $session,
        public User $user,
    ) {}
}

readonly class AuthSession extends DBTable {
    private const SESSION_VALIDITY_SECS = 10 * 24 * 60 * 60; // 10 days

    private function __construct(
        public string $id,
        public string $userId,
        public DateTime $createdAt,
        public bool $forceExpired,
    ) {}

    public function expired(): bool {
        $now = new DateTime();
        // https://www.php.net/manual/en/dateinterval.construct.php
        $secondsInterval = new DateInterval("PT{$SESSION_VALIDITY_SECS}S");
        return $this->forceExpired || $this->createdAt->add($secondsInterval) <= $now;
    }

    public static function login(Database $db, string $email, string $password): SessionData|false {
        $user = User::fromLoginInfo($db, $email, $password);
        if ($user === false) {
            return false;
        }
        $session = $user->createAuthSession();
        if ($session === false) {
            return false;
        }
        return new SessionData(session: $session, user: $user);
    }

    public function logout(Database $db): bool {
        $query = $db->createStatement(<<<sql
            UPDATE `AuthSessions`
            SET `forceExpired` = true
            WHERE `id` = ?
            sql);
        return $query->bind(SqlValueType::String->createParam($this->id))->execute();
    }

    public static function validate(Database $db, string $sessionId): SessionData|false {
        $seconds = self::SESSION_VALIDITY_SECS; // needed for interpolation in string
        $query = $db->createStatement(<<<sql
            SELECT s.*
            FROM `AuthSessions` s
            WHERE s.`id` = ?
                AND NOT s.`forceExpired` 
                AND date_add(s.`createdAt`, INTERVAL $seconds SECONDS) > now()
            sql);
        $ok = $query->bind(SqlValueType::String->createParam($sessionId))->execute();
        // TODO: return session (and user from session id? do User::fromSessionId)
        return false;
    }
}

readonly class User extends DBTable {
    private function __construct(
        public string $id,
        public string $username,
        public string $email,
        public string $passwordHash,
        public ?string $avatarId,
        public bool $isAdmin,
        public DateTime $createdAt,
        public bool $deleted,
    ) {}

    public static function fromSessionId(Database $db, string $sessionId): self|false {

    }

    public static function fromLoginInfo(Database $db, string $email, string $password): self|false {
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

    private function getMostRecentSession(Database $db): ?AuthSession {
        $query = $db->createStatement(<<<sql
            SELECT s.* 
            FROM `AuthSessions` s
            WHERE s.`userId` = ? 
            ORDER BY s.`createdAt` DESC
            LIMIT 1
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
        return $this->getMostRecentSession($db);
    }
}

?>