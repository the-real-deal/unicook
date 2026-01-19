<?php
require_once "{$_SERVER["DOCUMENT_ROOT"]}/bootstrap.php";
require_once "lib/core/db.php";
require_once "lib/core/files.php";
require_once "lib/core/uuid.php";
require_once "lib/tags.php";
require_once "lib/users.php";
require_once "lib/reviews.php";

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
    public const IMAGES_UPLOAD_PATH = "recipes";

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
        if (filter_var_regex($title, '/^.{1,50}$/') === false) {
            throw new InvalidArgumentException(<<<end
            Recipe title must be between 1 and 50 characters
            end);
        }
        return $title;
    }

    public static function validateDescription(string $description): string {
        if (filter_var_regex($description, '/^.{1,250}$/') === false) {
            throw new InvalidArgumentException(<<<end
            Recipe description must be between 1 and 250 characters
            end);
        }
        return $description;
    }

    public static function validatePrepTime(int $prepTime): int {
        $min = 5;
        $max = 5 * 60;
        if ($prepTime < $min || $prepTime > $max) {
            throw new InvalidArgumentException(<<<end
            Recipe preparation time must be between $min and $max minutes
            end);
        }
        return $prepTime;
    }

    public static function validateServings(int $servings): int {
        $min = 1;
        $max = 10;
        if ($servings < $min || $servings > $max) {
            throw new InvalidArgumentException(<<<end
            Recipe servings must be between $min and $max
            end);
        }
        return $servings;
    }

    public static function validateIngredientName(string $name): string {
        if (filter_var_regex($name, '/^.{1,30}$/') === false) {
            throw new InvalidArgumentException(<<<end
            Recipe ingredient name must be between 1 and 30 characters
            end);
        }
        return $name;
    }
    
    public static function validateIngredientQuantity(string $quantity): string {
        if (filter_var_regex($quantity, '/^.{1,20}$/') === false) {
            throw new InvalidArgumentException(<<<end
            Recipe ingredient quantity must be between 1 and 20 characters
            end);
        }
        return $quantity;
    }

    public static function validateTags(array $tags): array {
        $result = [];
        $tags = array_values(array_unique($tags, SORT_REGULAR));
        return $tags;
    }

    public static function validateIngredients(array $ingredients): array {
        $result = [];
        $ingredients = array_values(array_unique($ingredients, SORT_REGULAR));
        foreach ($ingredients as $ingredient) {
            [ "name" => $name, "quantity" => $quantity ] = $ingredient;
            array_push($result, [
                "name" => self::validateIngredientName($name),
                "quantity" => self::validateIngredientQuantity($quantity),
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
        $steps = array_values(array_unique($steps, SORT_REGULAR));
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
        User $user,
        string $title,
        string $description,
        UploadFile $image, 
        array $tags,
        RecipeDifficulty $difficulty,
        int $prepTime, 
        RecipeCost $cost,
        int $servings,
        array $ingredients,
        array $steps,
    ): string|false {
        $title = self::validateTitle($title);
        $description = self::validateDescription($description);
        $tags = self::validateTags($tags);
        $prepTime = self::validatePrepTime($prepTime);
        $servings = self::validateServings($servings);
        $ingredients = self::validateIngredients($ingredients);
        $steps = self::validateSteps($steps);

        $ok = $db->beginTransaction();
        if (!$ok) {
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
                SqlValueType::String->createParam($user->id),
            )->execute();
            if (!$ok) {
                throw new RuntimeException("Failed to insert recipe");
            }

            if (count($tags) > 0) {
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
                        SqlValueType::String->createParam($tag->id),
                    ],
                    $tags
                )))->execute();
                if (!$ok) {
                    throw new RuntimeException("Failed to insert recipe tags");
                }
            }

            if (count($ingredients) > 0) {
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
                        SqlValueType::String->createParam($ingredient["name"]),
                        SqlValueType::String->createParam($ingredient["quantity"]),
                    ],
                    $ingredients, array_keys($ingredients)
                )))->execute();
                if (!$ok) {
                    throw new RuntimeException("Failed to insert recipe ingredients");
                }
            }

            if (count($steps) > 0) {
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
                if (!$ok) {
                    throw new RuntimeException("Failed to insert recipe steps");
                }
            }

            return $db->commit() ? $id : false;

        } catch (\Throwable $th) {
            $db->rollback();
            return false;
        }
    }

    public function update(
        Database $db,
        string $title,
        string $description,
        ?UploadFile $image, 
        array $tags,
        RecipeDifficulty $difficulty,
        int $prepTime, 
        RecipeCost $cost,
        int $servings,
        array $ingredients,
        array $steps,
    ): bool {
        $title = self::validateTitle($title);
        $description = self::validateDescription($description);
        $tags = self::validateTags($tags);
        $prepTime = self::validatePrepTime($prepTime);
        $servings = self::validateServings($servings);
        $ingredients = self::validateIngredients($ingredients);
        $steps = self::validateSteps($steps);

        $ok = $db->beginTransaction();
        if (!$ok) {
            return false;
        }
        try {
            $query = $db->createStatement(<<<sql
                UPDATE `Recipes` r SET
                    r.`title` = ?,
                    r.`description` = ?,
                    r.`photoId` = ?,
                    r.`difficulty` = ?,
                    r.`prepTime` = ?,
                    r.`cost` = ?,
                    r.`servings` = ?
                WHERE r.`id` = ?
                sql);
            
            $imageId = $image === null ? $this->photoId : $image->id;
            $ok = $query->bind(
                SqlValueType::String->createParam($title),
                SqlValueType::String->createParam($description),
                SqlValueType::String->createParam($imageId),
                SqlValueType::Int->createParam($difficulty->value),
                SqlValueType::Int->createParam($prepTime),
                SqlValueType::Int->createParam($cost->value),
                SqlValueType::Int->createParam($servings),
                SqlValueType::String->createParam($this->id),
            )->execute();
            if (!$ok) {
                throw new RuntimeException("Failed to update recipe");
            }

            $query = $db->createStatement(<<<sql
                DELETE FROM `RecipeTags` WHERE `recipeId` = ?
                sql);
            $ok = $query->bind(SqlValueType::String->createParam($this->id))->execute();
            if (!$ok) {
                throw new RuntimeException("Failed to delete old recipe tags");
            }

            if (count($tags) > 0) {
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
                        SqlValueType::String->createParam($this->id),
                        SqlValueType::String->createParam($tag->id),
                    ],
                    $tags
                )))->execute();
                if (!$ok) {
                    throw new RuntimeException("Failed to insert new recipe tags");
                }
            }

            $query = $db->createStatement(<<<sql
                DELETE FROM `RecipeIngredients` WHERE `recipeId` = ?
                sql);
            $ok = $query->bind(SqlValueType::String->createParam($this->id))->execute();
            if (!$ok) {
                throw new RuntimeException("Failed to delete old recipe ingredients");
            }

            if (count($ingredients) > 0) {
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
                        SqlValueType::String->createParam($this->id),
                        SqlValueType::Int->createParam($i),
                        SqlValueType::String->createParam($ingredient["name"]),
                        SqlValueType::String->createParam($ingredient["quantity"]),
                    ],
                    $ingredients, array_keys($ingredients)
                )))->execute();
                if (!$ok) {
                    throw new RuntimeException("Failed to insert new recipe ingredients");
                }
            }

            $query = $db->createStatement(<<<sql
                DELETE FROM `RecipeSteps` WHERE `recipeId` = ?
                sql);
            $ok = $query->bind(SqlValueType::String->createParam($this->id))->execute();
            if (!$ok) {
                throw new RuntimeException("Failed to delete old recipe steps");
            }

            if (count($steps) > 0) {
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
                        SqlValueType::String->createParam($this->id),
                        SqlValueType::Int->createParam($i),
                        SqlValueType::String->createParam($step),
                    ],
                    $steps, array_keys($steps)
                )))->execute();
                if (!$ok) {
                    throw new RuntimeException("Failed to insert new recipe steps");
                }
            }

            return $db->commit();

        } catch (\Throwable $th) {
            $db->rollback();
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

    private static function validateSearchQuery(string $query): string {
        if (filter_var_regex($query, '/^.{0,100}$/') === false) {
            throw new InvalidArgumentException(<<<end
            Recipe title must be between 0 and 100 characters
            end);
        }
        return $query;
    }

    public static function search(
        Database $db,
        ?string $queryString,
        ?int $minPrepTime,
        ?int $maxPrepTime,
        ?RecipeDifficulty $difficulty,
        ?RecipeCost $cost,
        ?array $tags,
        ?int $from,
        ?int $n,
    ): array|false {
        // I don't care anymore about magic numbers at this point
        $queryString = self::validateSearchQuery($queryString ?? "");
        $minPrepTime ??= 0;
        $maxPrepTime ??= 10 * 60;
        if (
            $minPrepTime < 0 || 
            $minPrepTime > 10 * 60 || 
            $maxPrepTime < 0 || 
            $maxPrepTime > 10 * 60 || 
            $minPrepTime > $maxPrepTime
        ) {
            throw new InvalidArgumentException("Invalid preparation time range");
        }
        $tags = $tags === null ? [] : array_values(array_unique($tags, SORT_REGULAR));
        $from ??= 0;
        $n ??= 100;
        if (
            $from < 0 || 
            $n < 0 ||
            $n > 100
        ) {
            throw new InvalidArgumentException("Invalid search results range");
        }

        $difficultyCheck = ($difficulty === null) ? "1" : "r.`difficulty` = ?";
        $costCheck = ($cost === null) ? "1" : "r.`cost` = ?";
        $tagsCheck = (count($tags) === 0) ? "1" : "rt.`tagId` IN (" . implode(", ", array_map(fn ($tag) => "?", $tags)) . ")";
        $query = $db->createStatement(<<<sql
            SELECT DISTINCT r.*
            FROM `Recipes` r
                LEFT JOIN `RecipeTags` rt on r.`id` = rt.`recipeId`
            WHERE lower(r.`title`) LIKE ?
                AND r.`prepTime` BETWEEN ? AND ?
                AND $difficultyCheck
                AND $costCheck
                AND $tagsCheck
            ORDER BY r.`createdAt` DESC
            LIMIT ? OFFSET ?
            sql);
        $ok = $query->bind(...array_merge(
            [
                SqlValueType::String->createParam("%$queryString%"),
                SqlValueType::Int->createParam($minPrepTime),
                SqlValueType::Int->createParam($maxPrepTime),
            ],
            $difficulty === null ? [] : [SqlValueType::Int->createParam($difficulty->value)],
            $cost === null ? [] : [SqlValueType::Int->createParam($cost->value)],
            array_map(fn ($tag) => SqlValueType::String->createParam($tag->id), $tags),
            [
                SqlValueType::Int->createParam($n),
                SqlValueType::Int->createParam($from),
            ],
        ))->execute();
        if (!$ok) {
            return false;
        }
        $result = $query->expectResult();
        $recipes = array_map(fn ($row) => self::fromTableRow($row), $result->fetchAll());
        if (strlen($queryString) > 0) {
            usort($recipes, fn ($r1, $r2) => 
                levenshtein(strtolower($r1->title), strtolower($queryString)) -
                levenshtein(strtolower($r2->title), strtolower($queryString))
            );
        }
        return $recipes;
    }

    public function getImage(): UploadFile|false {
        return UploadFile::fromId($this->photoId, self::IMAGES_UPLOAD_PATH);
    }

    public function getRating(Database $db): int|false {
        $query = $db->createStatement(<<<sql
            SELECT COALESCE(AVG(rr.`rating`), 0) AS rating
            FROM `Reviews` rr
                JOIN `Recipes` r on rr.`recipeId` = r.`id`
            WHERE r.`id` = ?
            sql);
        $ok = $query->bind(SqlValueType::String->createParam($this->id))->execute();
        if (!$ok) {
            return false;
        }
        $row = $query->expectResult()->fetchOne();
        return SqlValueType::Int->valueFromField($row["rating"]);
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
        return array_map(fn ($row) => Tag::fromTableRow($row), $result->fetchAll());
    }

    public function getReviews(Database $db): array|false {
        $query = $db->createStatement(<<<sql
            SELECT rr.*
            FROM `Reviews` rr
                JOIN `Recipes` r on rr.`recipeId` = r.`id`
            WHERE r.`id` = ?
            sql);
        $ok = $query->bind(SqlValueType::String->createParam($this->id))->execute();
        if (!$ok) {
            return false;
        }
        $result = $query->expectResult();
        return array_map(fn ($row) => Review::fromTableRow($row), $result->fetchAll());
    }

    public function isSavedFrom(Database $db, User $user): bool {
        $query = $db->createStatement(<<<sql
            SELECT rs.*
            FROM `RecipeSaves` rs
            WHERE rs.`recipeId` = ?
                AND rs.`userId` = ?
            sql);
        $ok = $query->bind(
            SqlValueType::String->createParam($this->id),
            SqlValueType::String->createParam($user->id)
        )->execute();    
        if (!$ok) {
            return false;
        }
        $result = $query->expectResult();
        return $result->totalRows > 0;
    }
}

?>