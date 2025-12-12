<?php
define("SESSION_KEY_ATTRIBUTE", "sessionKey");

readonly class User {
    public function __construct(
        public string $id,
        public string $username,
        public string $email,
        public string $passwordHash,
        public string $avatarID,
        public bool $admin,
        public DateTime $createdAt,
        public bool $deleted,
    ) {}
}

function checkSession(DatabaseHelper $db, string $sessionKey): User|false {
    if (!isset($sessionKey)) {
        return false;
    }
    $result = $db->createStatement(<<<sql
        SELECT u.*
        FROM `LoginSessions` ls
        JOIN `Users` u ON ls.`UserID` = u.`ID`
        WHERE u.`ID` = ?
            AND NOT ls.`ForceExpired`
            AND ls.`ExpiresAt` < CURRENT_TIMESTAMP()
        LIMIT 1
        sql)
        ->bind(QueryParamType::String, $sessionKey)
        ->execute()
        ->getResult();
    if ($result === false) {
        return $result;
    }
}

?>