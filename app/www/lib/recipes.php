<?php
require_once "{$_SERVER["DOCUMENT_ROOT"]}/bootstrap.php";
require_once "lib/core/db.php";
require_once "lib/core/uuid.php";
require_once "lib/tags.php";

enum RecipeDifficulty: int {
    case Easy = 0;
    case Medium = 1;
    case Hard = 2;
}

readonly class RecipeStep extends DBTable {
    protected function __construct(
        public string $recipeId,
        public int $stepNumber,
        public string $instruction,
    ) {}
}

readonly class RecipeIngredient extends DBTable {
    protected function __construct(
        public string $recipeId,
        public int $ingredientId,
        public string $name,
        public string $quantity,
        public ?string $barcode,
    ) {}
}

readonly class Recipe extends DBTable {
    protected function __construct(
        public string $id,
        public string $title,
        public ?string $description,
        public ?string $photoId,
        public RecipeDifficulty $difficulty,
        public int $cookingTime,
        public int $servings,
        public string $userId,
        public DateTime $createdAt,
        public bool $deleted,
    ) {}

    public static function validateId(string $id): string {
        return validateUUID($id);
    }

    public static function fromId(Database $db, string $id): self|false {
        $id = self::validateId($id);
        
        $query = $db->createStatement(<<<sql
            SELECT r.*
            FROM `Recipes` r
            WHERE r.`id` = ?
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

    public function getSteps(Database $db): Generator|false {
        $query = $db->createStatement(<<<sql
            SELECT rs.*
            FROM `RecipeSteps` rs
            WHERE rs.`recipeId` = ?
            sql);
        $ok = $query->bind(SqlValueType::String->createParam($this->id))->execute();
        if (!$ok) {
            return false;
        }
        $result = $query->expectResult();
        foreach ($result->iterate() as $row) {
            yield RecipeStep::fromTableRow($row);
        }
    }

    public function getIngredients(Database $db): Generator|false {
        $query = $db->createStatement(<<<sql
            SELECT ri.*
            FROM `RecipeIngredients` ri
            WHERE ri.`recipeId` = ?
            sql);
        $ok = $query->bind(SqlValueType::String->createParam($this->id))->execute();
        if (!$ok) {
            return false;
        }
        $result = $query->expectResult();
        foreach ($result->iterate() as $row) {
            yield RecipeIngredient::fromTableRow($row);
        }
    }

    public function getTags(Database $db): Generator|false {
        $query = $db->createStatement(<<<sql
            SELECT t.*
            FROM `RecipeTags` rt
                JOIN `Tags` t on rt.`tagId` = t.`id`
            WHERE rt.`recipeId` = ?
            sql);
        $ok = $query->bind(SqlValueType::String->createParam($this->id))->execute();
        if (!$ok) {
            return false;
        }
        $result = $query->expectResult();
        foreach ($result->iterate() as $row) {
            yield Tags::fromTableRow($row);
        }
    }

    public function getReviews(Database $db): Generator|false {
        $query = $db->createStatement(<<<sql
            SELECT r.*
            FROM `Reviews` r
                JOIN `Recipes` rr on r.`reviewId` = rr.`id`
            WHERE rr.`id` = ?
                AND rr.`deleted` = 0
            sql);
        $ok = $query->bind(SqlValueType::String->createParam($this->id))->execute();
        if (!$ok) {
            return false;
        }
        $result = $query->expectResult();
        foreach ($result->iterate() as $row) {
            yield Review::fromTableRow($row);
        }
    }
}

?>