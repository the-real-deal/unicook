<?php
require_once "lib/core/db.php";

define("SESSION_KEY_ATTRIBUTE", "sessionKey");

readonly class Session {
    private function __construct(
        public string $id,
        public string $userID,
        public string $key,
        public string $expiresAt,
        public bool $forceExpired,
    ) {}

    public static function check(DatabaseHelper $db): mysqli_result|false {
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