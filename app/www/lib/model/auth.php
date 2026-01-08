<?php
require_once "{$_SERVER['DOCUMENT_ROOT']}/bootstrap.php";
require_once "lib/core/db.php";

define("AUTH_SESSION_ID_KEY", "AUTH_SESSION_ID");

$db = Database::connectDefault();

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
}

readonly class AuthSession extends DBTable {
    private const SESSION_VALIDITY_SECS = 10 * 24 * 60 * 60; // 10 days

    protected function __construct(
        public string $id,
        public string $userId,
        public DateTime $createdAt,
        public bool $forceExpired,
    ) {}

    private static function createUserSession(Database $db, string $userId): self|false {
        $query = $db->createStatement(<<<sql
            INSERT INTO `AuthSessions`(`userId`) VALUES (?)
            sql);
        // TODO: create session and check login
    }

    private static function sqlExpiredCheck(?string $tableAlias = null): string {
        $tablePrefix = self::sqlTableAliasPrefix($tableAlias);
        $seconds = self::SESSION_VALIDITY_SECS;
        return <<<sql
        (NOT $tablePrefix`forceExpired` 
        AND $tablePrefix`createdAt` > DATE_ADD(now(), INTERVAL $seconds SECONDS))
        sql;
    }

    private static function matchUserPassword(Database $db, string $email, string $password): string|false {
        $query = $db->createStatement(<<<sql
            SELECT u.`id`, u.`passwordHash`
            FROM `Users` u
            WHERE u.`email` = ?
            sql);
        $query->bind(SqlValueType::String->createParam($email))->execute();
        $result = $query->expectResult();
        if ($result->totalRows === 0) {
            return false;
        }
        
        ["passwordHash" => $passwordHash, "id" => $id ] = $result->fetchOne();
        $passwordMatches = password_verify($password, $passwordHash);
        if (!$passwordMatches) {
            return false;
        }
        return $id;
    }

    private function expire(Database $db): bool {
        $query = $db->createStatement(<<<sql
            UPDATE `AuthSessions`
            SET `forceExpired` = true
            WHERE `id` = ?
            sql);
        return $query->bind(SqlValueType::String->createParam($this->id))->execute();
    }

    private function expired(): bool {
        $now = new DateTime();
        // https://www.php.net/manual/en/dateinterval.construct.php
        $secondsInterval = new DateInterval("PT{$SESSION_VALIDITY_SECS}S");
        return $this->forceExpired || $this->expiresAt > $now->add($secondsInterval);
    }

    public static function login(Database $db, string $email, string $password): QueryResult|false {
        $userId = self::matchUserPassword($db, $email, $password);
        if ($userId === false) {
            return false;
        }

        
    }

    public static function check(Database $db, string $key): QueryResult|false {
        return false;
    }
}

?>