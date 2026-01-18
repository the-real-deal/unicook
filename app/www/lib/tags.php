<?php
require_once "{$_SERVER["DOCUMENT_ROOT"]}/bootstrap.php";
require_once "lib/core/db.php";
require_once "lib/core/uuid.php";
require_once "lib/recipes.php";

readonly class Tag extends DBTable {
    protected function __construct(
        public string $id,
        public string $name,
    ) {}

    public static function validateId(string $id): string {
        return validateUUID($id, "Tag id");
    }

    public static function fromId(Database $db, string $id): self|false {
        $id = self::validateId($id);

        $query = $db->createStatement(<<<sql
            SELECT t.*
            FROM `Tags` t
            WHERE t.`id` = ?
            sql);
        $ok = $query->bind(SqlValueType::String->createParam($id))->execute();
        if (!$ok) {
            return false;
        }
        $result = $query->expectResult();
        if ($result->totalRows === 0) {
            return false;
        }
        return self::fromTableRow($result->fetchOne());
    }

    public static function getAllTags(Database $db): array|false {
        $query = $db->createStatement(<<<sql
            SELECT t.*
            FROM `Tags` t
            sql);
        $ok = $query->execute();
        if (!$ok) {
            return false;
        }
        $result = $query->expectResult();
        return array_map(fn ($row) => self::fromTableRow($row), $result->fetchAll());
    }

    public function getRecipes(Database $db): array|false {
        $query = $db->createStatement(<<<sql
            SELECT r.*
            FROM `RecipeTags` rt
                JOIN `Recipes` r on rt.`recipeId` = r.`id`
            WHERE rt.`tagId` = ?
            sql);
        $ok = $query->bind(SqlValueType::String->createParam($this->id))->execute();
        if (!$ok) {
            return false;
        }
        $result = $query->expectResult();
        return array_map(fn ($row) => Recipe::fromTableRow($row), $result->fetchAll());
    }
}
?>