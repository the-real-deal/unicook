<?php
require_once "{$_SERVER["DOCUMENT_ROOT"]}/bootstrap.php";
require_once "lib/core/db.php";
require_once "lib/core/uuid.php";
require_once "lib/users.php";
require_once "lib/recipes.php";

readonly class Review extends DBTable {
    protected function __construct(
        public string $id,
        public string $userId,
        public string $recipeId,
        public int $rating,
        public string $body,
        public DateTime $createdAt,
    ) {}

    public static function validateId(string $id): string {
        return validateUUID($id, "Review id");
    }

    public static function validateRating(int $rating): int {
        $min = 1;
        $max = 5;
        if ($rating < $min || $rating > $max) {
            throw new InvalidArgumentException(<<<end
            Review rating must be between $min and $max
            end);
        }
        return $rating;
    }

    public static function validateBody(string $body): string {
        if (filter_var_regex($body, '/^.{1,250}$/') === false) {
            throw new InvalidArgumentException(<<<end
            Review body must be between 1 and 250 characters
            end);
        }
        return $body;
    }

    public static function fromId(Database $db, string $id): self|false {
        $id = self::validateId($id);

        $query = $db->createStatement(<<<sql
            SELECT r.*
            FROM `Reviews` r
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
        Recipe $recipe,
        int $rating,
        string $body,
    ): string|false {
        $rating = self::validateRating($rating);
        $body = self::validateBody($body);

        $query = $db->createStatement(<<<sql
            INSERT INTO `Reviews`(
                `id`, 
                `userId`, 
                `recipeId`, 
                `rating`, 
                `body`
            ) VALUES (?, ?, ?, ?, ?)
            sql);
        
        $id = uuidv4();
        $ok = $query->bind(
            SqlValueType::String->createParam($id),
            SqlValueType::String->createParam($user->id),
            SqlValueType::String->createParam($recipe->id),
            SqlValueType::Int->createParam($rating),
            SqlValueType::String->createParam($body),
        )->execute();
        if (!$ok) {
            return false;
        }
        return $id;
    }
}
?>