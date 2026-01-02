<?php
require_once "{$_SERVER['DOCUMENT_ROOT']}/bootstrap.php";
require_once "lib/core/db.php";

define("SESSION_KEY_ATTRIBUTE", "sessionKey");

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

readonly class LoginSession extends DBTable {
    protected function __construct(
        public string $id,
        public string $userId,
        public string $key,
        public string $expiresAt,
        public bool $forceExpired,
    ) {}

    public static function check(Database $db): mysqli_result|false {
        // $result = $db->createStatement(<<<sql
        //     SELECT u.*
        //     FROM `LoginSessions` ls
        //     JOIN `Users` u ON ls.`UserID` = u.`ID`
        //     WHERE ls.`Key` = ?
        //         AND NOT ls.`ForceExpired`
        //     LIMIT 1
        //     sql)
        //     ->bind(QueryParamType::String, $sessionKey)
        //     ->execute()
        //     ->getResult();
        $result = $db->query(<<<sql
        SELECT *
        FROM `LoginSessions`
        sql);
        return $result;
    }
}

?>