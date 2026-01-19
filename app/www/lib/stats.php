<?php
require_once "{$_SERVER["DOCUMENT_ROOT"]}/bootstrap.php";
require_once "lib/core/db.php";

readonly class Stats {
    private function __construct() {}

    public static function getTotalUsers(Database $db): int|false {
        $query = $db->createStatement(<<<sql
            SELECT COUNT(*) AS users
            FROM `Users` u
            sql);
        $ok = $query->execute();
        if (!$ok) {
            return false;
        }
        $row = $query->expectResult()->fetchOne();
        return SqlValueType::Int->valueFromField($row["users"]);
    }

    public static function getTotalUniversities(Database $db): int|false {
        $query = $db->createStatement(<<<sql
            SELECT 1 AS universities
            sql);
        $ok = $query->execute();
        if (!$ok) {
            return false;
        }
        $row = $query->expectResult()->fetchOne();
        return SqlValueType::Int->valueFromField($row["universities"]);
    }

    public static function getTotalRecipes(Database $db): int|false {
        $query = $db->createStatement(<<<sql
            SELECT COUNT(*) AS recipes
            FROM `Recipes` r
            sql);
        $ok = $query->execute();
        if (!$ok) {
            return false;
        }
        $row = $query->expectResult()->fetchOne();
        return SqlValueType::Int->valueFromField($row["recipes"]);
    }

    public static function getAverageRating(Database $db): float|false {
        $query = $db->createStatement(<<<sql
            SELECT COALESCE(AVG(r.`rating`), 0) AS rating
            FROM `Reviews` r
            sql);
        $ok = $query->execute();
        if (!$ok) {
            return false;
        }
        $row = $query->expectResult()->fetchOne();
        return SqlValueType::Float->valueFromField($row["rating"]);
    }
}
?>