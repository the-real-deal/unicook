<?php
require_once "{$_SERVER["DOCUMENT_ROOT"]}/bootstrap.php";
require_once "lib/core/db.php";
require_once "lib/core/uuid.php";

readonly class Review extends DBTable {
    public const MAX_RATING = 5;

    protected function __construct(
        public string $id,
        public string $userId,
        public string $recipeId,
        public int $rating,
        public ?string $body,
        public DateTime $createdAt,
        public bool $deleted,
    ) {}

    public static function validateId(string $id): string {
        return validateUUID($id, "Review id");
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
}
?>