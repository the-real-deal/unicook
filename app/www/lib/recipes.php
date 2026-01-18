<?php
require_once "{$_SERVER["DOCUMENT_ROOT"]}/bootstrap.php";
require_once "lib/core/db.php";
require_once "lib/core/files.php";
require_once "lib/core/uuid.php";
require_once "lib/tags.php";
require_once "lib/users.php";

enum RecipeDifficulty: int {
    case Easy = 0;
    case Medium = 1;
    case Hard = 2;
}

enum RecipeCost: int {
    case Cheap = 0;
    case Medium = 1;
    case Expensive = 2;
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
    public const IMAGES_PATH = "recipes";

    protected function __construct(
        public string $id,
        public string $title,
        public string $description,
        public string $photoId,
        public RecipeDifficulty $difficulty,
        public int $prepTime,
        public RecipeCost $cost,
        public int $servings,
        public string $userId,
        public DateTime $createdAt,
    ) {}

    public static function validateId(string $id): string {
        return validateUUID($id, "Recipe id");
    }

    public static function validateTitle(string $title): string {
        if (filter_var_regex($title, '/^.{3,50}$/') === false) {
            throw new InvalidArgumentException(<<<end
            Title must be between 5 and 50 characters
            end);
        }
        return $title;
    }

    public static function validateDescription(string $description): string {
        if (filter_var_regex($description, '/^.{3,250}$/') === false) {
            throw new InvalidArgumentException(<<<end
            Description must be between 3 and 250 characters
            end);
        }
        return $description;
    }

    public static function validateTags(array $tags): array {
        $result = [];
        foreach ($tags as $tag) {
            array_push($result, Tag::validateId($tag));
        }
        return $result;
    }

    public static function validatePrepTime(int $prepTime): int {
        $min = 5;
        $max = 5 * 60;
        if ($prepTime < $min || $prepTime > $max) {
            throw new InvalidArgumentException(<<<end
            Preparation time must be between $min and $max minutes
            end);
        }
        return $prepTime;
    }

    public static function validateServings(int $servings): int {
        $min = 1;
        $max = 10;
        if ($servings < $min || $servings > $max) {
            throw new InvalidArgumentException(<<<end
            Servings must be between $min and $max
            end);
        }
        return $servings;
    }

    public static function validateIngredientQuantity(string $quantity): string {
        if (filter_var_regex($quantity, '/^.{1,20}$/') === false) {
            throw new InvalidArgumentException(<<<end
            Ingredient quantity must be between 1 and 20 characters
            end);
        }
        return $quantity;
    }

    public static function validateIngredientName(string $name): string {
        if (filter_var_regex($name, '/^.{5,30}$/') === false) {
            throw new InvalidArgumentException(<<<end
            Ingredient name must be between 5 and 30 characters
            end);
        }
        return $name;
    }

    public static function validateIngredients(array $ingredients): array {
        $result = [];    
        foreach ($ingredients as $ingredient) {
            [$quantity, $name] = $ingredient;
            array_push($result, [
                self::validateIngredientQuantity($quantity),
                self::validateIngredientName($name),
            ]);
        }
        return $result;
    }
    
    public static function validateStep(string $step): string {
        if (filter_var_regex($step, '/^.{1,250}$/') === false) {
            throw new InvalidArgumentException(<<<end
            Recipe step must be between 1 and 250 characters
            end);
        }
        return $step;
    }

    public static function validateSteps(array $steps): array {
        $result = [];
        foreach ($steps as $step) {
            array_push($result, self::validateStep($step));
        }
        return $result;
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

    public static function create(
        Database $db,
        string $userId,
        string $title,
        string $description,
        array $imageFile, 
        array $tags,
        RecipeDifficulty $difficulty,
        int $prepTime, 
        RecipeCost $cost,
        int $servings,
        array $ingredients,
        array $steps,
    ): string|false {
        $userId = User::validateId($userId);
        $title = self::validateTitle($title);
        $description = self::validateDescription($description);
        $tags = self::validateTags($tags);
        $prepTime = self::validatePrepTime($prepTime);
        $servings = self::validateServings($servings);
        $ingredients = self::validateIngredients($ingredients);
        $steps = self::validateSteps($steps);

        $image = UploadFile::uploadFileArray($imageFile, FileType::Image, self::IMAGES_PATH);
        if ($image === null) {
            return false;
        }

        $ok = $db->beginTransaction();
        if ($ok === false) {
            return false;
        }
        try {
            $query = $db->createStatement(<<<sql
                INSERT INTO `Recipes`(
                    `id`, 
                    `title`, 
                    `description`, 
                    `photoId`, 
                    `difficulty`, 
                    `prepTime`, 
                    `cost`, 
                    `servings`, 
                    `userId`
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
                sql);
            
            $id = uuidv4();
            $ok = $query->bind(
                SqlValueType::String->createParam($id),
                SqlValueType::String->createParam($title),
                SqlValueType::String->createParam($description),
                SqlValueType::String->createParam($image->id),
                SqlValueType::Int->createParam($difficulty->value),
                SqlValueType::Int->createParam($prepTime),
                SqlValueType::Int->createParam($cost->value),
                SqlValueType::Int->createParam($servings),
                SqlValueType::String->createParam($userId),
            )->execute();
            if ($ok === false) {
                throw new RuntimeException("Failed to insert recipe");
            }

            $valueEntries = implode(", ", array_map(
                fn ($tag) => "(?, ?)",
                $tags,
            ));
            $query = $db->createStatement(<<<sql
                INSERT INTO `RecipeTags`(`recipeId`, `tagId`) VALUES
                {$valueEntries}
                sql);
            $ok = $query->bind(...array_merge(...array_map(
                fn ($tag) => [
                    SqlValueType::String->createParam($id),
                    SqlValueType::String->createParam($tag),
                ],
                $tags
            )))->execute();
            if ($ok === false) {
                throw new RuntimeException("Failed to insert recipe tags");
            }

            $valueEntries = implode(", ", array_map(
                fn ($ingredient) => "(?, ?, ?, ?)",
                $ingredients,
            ));
            $query = $db->createStatement(<<<sql
                INSERT INTO `RecipeIngredients`(`recipeId`, `ingredientId`, `name`, `quantity`) VALUES
                {$valueEntries}
                sql);
            $ok = $query->bind(...array_merge(...array_map(
                fn ($ingredient, $i) => [
                    SqlValueType::String->createParam($id),
                    SqlValueType::Int->createParam($i),
                    SqlValueType::String->createParam($ingredient[0]),
                    SqlValueType::String->createParam($ingredient[1]),
                ],
                $ingredients, array_keys($ingredients)
            )))->execute();
            if ($ok === false) {
                throw new RuntimeException("Failed to insert recipe ingredients");
            }

            $valueEntries = implode(", ", array_map(
                fn ($step) => "(?, ?, ?)",
                $steps,
            ));
            $query = $db->createStatement(<<<sql
                INSERT INTO `RecipeSteps`(`recipeId`, `stepNumber`, `instruction`) VALUES
                {$valueEntries}
                sql);
            $ok = $query->bind(...array_merge(...array_map(
                fn ($step, $i) => [
                    SqlValueType::String->createParam($id),
                    SqlValueType::Int->createParam($i),
                    SqlValueType::String->createParam($step),
                ],
                $steps, array_keys($steps)
            )))->execute();
            if ($ok === false) {
                throw new RuntimeException("Failed to insert recipe steps");
            }

            return $db->commit() ? $id : false;

        } catch (\Throwable $th) {
            $db->rollback();
            $image->delete();
            return false;
        }
    }

    public static function getRandom(Database $db): self|false {
        $query = $db->createStatement(<<<sql
            SELECT r.*
            FROM `Recipes` r
            ORDER BY RAND()
            sql);
        $ok = $query->execute();
        if (!$ok) {
            return false;
        }
        $result = $query->expectResult();
        if ($result->totalRows === 0) {
            return false;
        }
        return self::fromTableRow($result->fetchOne());
    }

    public static function getBest(Database $db, int $n): array|false {
        if ($n < 0) {
            throw new InvalidArgumentException("Number of recipes must be non-negative");
        }

        $query = $db->createStatement(<<<sql
            SELECT r.*
            FROM `Reviews` rr
                JOIN `Recipes` r on rr.`recipeId` = r.`id`
            GROUP BY r.`id`
            ORDER BY AVG(rr.`rating`) DESC
            LIMIT ?
            sql);
        $ok = $query->bind(SqlValueType::Int->createParam($n))->execute();
        if (!$ok) {
            return false;
        }
        $result = $query->expectResult();
        return array_map(fn ($row) => self::fromTableRow($row), $result->fetchAll());
    }

    public static function getWithTag(Database $db, string $tagId): array|false {
        $tagId = Tag::validateId($tagId);

        $query = $db->createStatement(<<<sql
            SELECT r.*
            FROM `RecipeTags` rt
                JOIN `Recipes` r on rt.`recipeId` = r.`id`
            WHERE rt.`tagId` = ?
            sql);
        $ok = $query->bind(SqlValueType::String->createParam($tagId))->execute();
        if (!$ok) {
            return false;
        }
        $result = $query->expectResult();
        return array_map(fn ($row) => self::fromTableRow($row), $result->fetchAll());
    }

    public function getRating(Database $db): int|false {
        $query = $db->createStatement(<<<sql
            SELECT AVG(rr.`rating`) AS rating
            FROM `Reviews` rr
                JOIN `Recipes` r on rr.`recipeId` = r.`id`
            WHERE r.`id` = ?
            sql);
        $ok = $query->bind(SqlValueType::String->createParam($this->id))->execute();
        if (!$ok) {
            return false;
        }
        $result = $query->expectResult();
        if ($result->totalRows === 0) {
            return 0; // no reviews
        }
        return $result->fetchOne()["rating"];
    }

    public function getSteps(Database $db): array|false {
        $query = $db->createStatement(<<<sql
            SELECT rs.*
            FROM `RecipeSteps` rs
            WHERE rs.`recipeId` = ?
            ORDER BY rs.`stepNumber` ASC
            sql);
        $ok = $query->bind(SqlValueType::String->createParam($this->id))->execute();
        if (!$ok) {
            return false;
        }
        $result = $query->expectResult();
        return array_map(fn ($row) => RecipeStep::fromTableRow($row), $result->fetchAll());
    }

    public function getIngredients(Database $db): array|false {
        $query = $db->createStatement(<<<sql
            SELECT ri.*
            FROM `RecipeIngredients` ri
            WHERE ri.`recipeId` = ?
            ORDER BY ri.`ingredientId` ASC
            sql);
        $ok = $query->bind(SqlValueType::String->createParam($this->id))->execute();
        if (!$ok) {
            return false;
        }
        $result = $query->expectResult();
        return array_map(fn ($row) => RecipeIngredient::fromTableRow($row), $result->fetchAll());
    }

    public function getTags(Database $db): array|false {
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
        return array_map(fn ($row) => Tags::fromTableRow($row), $result->fetchAll());
    }

    public function getReviews(Database $db): array|false {
        $query = $db->createStatement(<<<sql
            SELECT rr.*
            FROM `Reviews` rr
                JOIN `Recipes` r on rr.`reviewId` = r.`id`
            WHERE r.`id` = ?
            sql);
        $ok = $query->bind(SqlValueType::String->createParam($this->id))->execute();
        if (!$ok) {
            return false;
        }
        $result = $query->expectResult();
        return array_map(fn ($row) => Review::fromTableRow($row), $result->fetchAll());
    }
}

?>