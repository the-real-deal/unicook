<?php
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

    public static function checkSession(
        DatabaseHelper $db, 
        string $sessionKey
    ): self|false {
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
}

?>