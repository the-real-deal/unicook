<?php
require_once "{$_SERVER['DOCUMENT_ROOT']}/bootstrap.php";
require_once "lib/core/db.php";

$db = Database::connectDefault();

enum RecipeDifficulty: int {
    case Easy = 0;
    case Medium = 1;
    case Hard = 2;
}

readonly class Recipe extends DBTable {
    protected function __construct(
        public string $id,
        public string $title,
        public ?string $description,
        public string $photoId,
        public RecipeDifficulty $difficulty,
        public int $cookingTime,
        public int $servings,
        public string $userId,
        public DateTime $createdAt,
        public bool $deleted,
    ) {}
}

// TODO: test recipe enum
// TODO: about: total recipes, total users, total tags, avg rating

?>