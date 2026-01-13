<?php
require_once "{$_SERVER["DOCUMENT_ROOT"]}/bootstrap.php";
require_once "lib/core/db.php";
require_once "lib/recipes.php";

readonly class User extends DBTable {
    protected function __construct(
        public string $id,
        public string $username,
        public string $email,
        public string $passwordHash,
        public ?string $avatarId,
        public bool $isAdmin,
        public DateTime $createdAt,
        public bool $deleted,
    ) {}

    public static function fromAuthSessionId(Database $db, string $sessionId): self|false {
        $query = $db->createStatement(<<<sql
            SELECT u.*
            FROM `Users` u
                JOIN `AuthSessions` s ON u.`id` = s.`userId`
            WHERE s.`id` = ?
            sql);
        $ok = $query->bind(SqlValueType::String->createParam($sessionId))->execute();
        if (!$ok) {
            return false;
        }

        $result = $query->expectResult();
        if ($result->totalRows === 0) {
            return false;
        }
        return self::fromTableRow($result->fetchOne());
    }

    public static function fromEmailAndPassword(Database $db, string $email, string $password): self|false {
        $query = $db->createStatement(<<<sql
            SELECT u.*
            FROM `Users` u
            WHERE u.`email` = ?
            sql);
        $ok = $query->bind(SqlValueType::String->createParam($email))->execute();
        if (!$ok) {
            return false;
        }
        $result = $query->expectResult();
        if ($result->totalRows === 0) {
            return false;
        }
        
        $user = self::fromTableRow($result->fetchOne());
        $passwordMatches = password_verify($password, $user->passwordHash);
        if ($passwordMatches) {
            return $user;
        } else {
            return false;
        }
    }

    public function createAuthSession(Database $db): string|false {
        $query = $db->createStatement(<<<sql
            INSERT INTO `AuthSessions`(`keyHash`, `userId`) VALUES (?, ?)
            sql);
        
        $key = uuidv4();
        $keyHash = password_hash($key, PASSWORD_DEFAULT);
        
        $ok = $query->bind(
            SqlValueType::String->createParam($keyHash),
            SqlValueType::String->createParam($this->id),
        )->execute();
        if ($ok) {
            return $key;
        } else {
            return false;
        }
    }

    public function getSavedRecipes(Database $db): Generator|false {
        $query = $db->createStatement(<<<sql
            SELECT r.*
            FROM `RecipeSaves` rs
                JOIN `Recipes` r on rs.`recipeId` = r.`id`
            WHERE rs.`userId` = ?
            sql);
        $ok = $query->bind(SqlValueType::String->createParam($this->id))->execute();
        if (!$ok) {
            return false;
        }
        $result = $query->expectResult();
        foreach ($result->iterate() as $row) {
            yield Recipe::fromTableRow($row);
        }
    }
}
?>