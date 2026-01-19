<?php
require_once "{$_SERVER["DOCUMENT_ROOT"]}/bootstrap.php";
require_once "lib/core/db.php";
require_once "lib/core/uuid.php";
require_once "lib/core/files.php";
require_once "lib/recipes.php";
require_once "lib/utils.php";
require_once "lib/auth.php";

readonly class User extends DBTable {
    private const IMAGES_UPLOAD_PATH = "users";

    protected function __construct(
        public string $id,
        public string $username,
        public string $email,
        private string $passwordHash,
        public ?string $avatarId,
        public bool $isAdmin,
        public DateTime $createdAt,
    ) {}

    public static function validateId(string $id): string {
        return validateUUID($id, "User id");
    }

    public static function validateUsername(string $username): string {
        if (filter_var_regex($username, '/^.{3,50}$/') === false) {
            throw new InvalidArgumentException(<<<end
            Username must be between 3 and 50 characters
            end);
        }
        return $username;
    }

    public static function validateEmail(string $email): string {
        if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            throw new InvalidArgumentException("User email must be a valid email address");
        }
        return $email;
    }

    public static function validatePassword(string $password): string {
        if (filter_var_regex($password, '/^(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,50}$/') === false) {
            throw new InvalidArgumentException(<<<end
            User password must be between 8 and 50 characters, with at least 1 symbol and 1 number
            end);
        }
        return $password;
    }

    public static function create(
        Database $db, 
        string $username, 
        string $email, 
        string $password,
    ): string|false {
        $username = self::validateUsername($username);
        $email = self::validateEmail($email);
        $password = self::validatePassword($password);

        $query = $db->createStatement(<<<sql
            INSERT INTO `Users`(`id`, `username`, `email`, `passwordHash`, `avatarId`) 
            VALUES (?, ?, ?, ?, ?)
            sql);
        $id = uuidv4();
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $ok = $query->bind(
            SqlValueType::String->createParam($id),
            SqlValueType::String->createParam($username),
            SqlValueType::String->createParam($email),
            SqlValueType::String->createParam($passwordHash),
            SqlValueType::String->createParam(null),
        )->execute();
        if (!$ok) {
            return false;
        }
        return $id;
    }

    public function update(
        Database $db,
        ?bool $admin,
    ): bool {
        $admin ??= $this->isAdmin;

        $query = $db->createStatement(<<<sql
            UPDATE `Users` u SET 
                u.`isAdmin` = ?
            WHERE u.`id` = ?
            sql);
        $ok = $query->bind(
            SqlValueType::Bool->createParam($admin),
            SqlValueType::String->createParam($this->id),
        )->execute();
        return $ok;
    }

    public static function fromId(Database $db, string $id): self|false {
        $id = self::validateId($id);

        $query = $db->createStatement(<<<sql
            SELECT u.*
            FROM `Users` u
            WHERE u.`id` = ?
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

    public static function fromAuthSession(Database $db, AuthSession $auth): self|false {
        $query = $db->createStatement(<<<sql
            SELECT u.*
            FROM `Users` u
                JOIN `AuthSessions` s ON u.`id` = s.`userId`
            WHERE s.`id` = ?
            sql);
        $ok = $query->bind(SqlValueType::String->createParam($auth->id))->execute();
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
        self::validateEmail($email);
        self::validatePassword($password);

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
        if (!$passwordMatches) {
            return false;
        }
        return $user;
    }

    public static function emailTaken(Database $db, string $email): bool {
        self::validateEmail($email);

        $query = $db->createStatement(<<<sql
            SELECT u.`email`
            FROM `Users` u
            WHERE u.`email` = ?
            sql);
        $ok = $query->bind(SqlValueType::String->createParam($email))->execute();
        if (!$ok) {
            return false;
        }
        $result = $query->expectResult();
        return $result->totalRows > 0;
    }

    public function uploadImage(Database $db, array $fileArray): UploadFile|false {
        $image = UploadFile::uploadFileArray($fileArray, FileType::Image, self::IMAGES_UPLOAD_PATH);
        if ($image === false) {
            return false;
        }

        $query = $db->createStatement(<<<sql
            UPDATE `Users` u
            SET u.`avatarId` = ?
            WHERE u.`id` = ?
            sql);
        $ok = $query->bind(
            SqlValueType::String->createParam($image->id),
            SqlValueType::String->createParam($this->id),
        )->execute();
        if (!$ok) {
            return false;
        }

        $prevImage = $this->getImage();
        if ($this->avatarId !== null) {
            $this->deleteImageUpload();
        }
        return $image;
    }

    public function getImage(): UploadFile|false {
        if ($this->avatarId === null) {
            return false;
        }
        return UploadFile::fromId($this->avatarId, self::IMAGES_UPLOAD_PATH);
    }

    private function deleteImageUpload(): bool {
        $image = $this->getImage();
        if ($image === false) {
            return false;
        }
        return $image->delete();
    }

    public function deleteImage(Database $db): bool {
        $query = $db->createStatement(<<<sql
            UPDATE `Users` u
            SET u.`avatarId` = ?
            WHERE u.`id` = ?
            sql);
        $ok = $query->bind(
            SqlValueType::String->createParam(null),
            SqlValueType::String->createParam($this->id),
        )->execute();
        if (!$ok) {
            return false;
        }
        return $this->deleteImageUpload();
    }

    public function getPublishedRecipes(Database $db): array|false {
        $query = $db->createStatement(<<<sql
            SELECT r.*
            FROM `Recipes` r
                JOIN `Users` u on r.`userId` = u.`id`
            WHERE u.`id` = ?
            sql);
        $ok = $query->bind(SqlValueType::String->createParam($this->id))->execute();
        if (!$ok) {
            return false;
        }
        $result = $query->expectResult();
        return array_map(fn ($row) => Recipe::fromTableRow($row), $result->fetchAll());
    }

    public function getSavedRecipes(Database $db): array|false {
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
        return array_map(fn ($row) => Recipe::fromTableRow($row), $result->fetchAll());
    }

    private function getSavedRecipe(Database $db, Recipe $recipe): RecipeSave|null|false {
        $query = $db->createStatement(<<<sql
            SELECT rs.*
            FROM `RecipeSaves` rs
            WHERE rs.`recipeId` = ?
                AND rs.`userId` = ?
            sql);
        $ok = $query->bind(
            SqlValueType::String->createParam($recipe->id),
            SqlValueType::String->createParam($this->id),
            )->execute();
        if (!$ok) {
            return false;
        }
        $result = $query->expectResult();
        return RecipeSave::fromOptionalTableRow($result->fetchOne());
    }

    public function saveRecipe(Database $db, Recipe $recipe): bool {
        $recipeSave = $this->getSavedRecipe($db, $recipe);
        if ($recipeSave === false || $recipeSave !== null) {
            return false;
        }
        
        $query = $db->createStatement(<<<sql
            INSERT INTO `RecipeSaves`(`recipeId`, `userId`)
            VALUES (?, ?)
            sql);
        $ok = $query->bind(
            SqlValueType::String->createParam($recipe->id),
            SqlValueType::String->createParam($this->id),
        )->execute();
        return $ok;
    }

    public function unsaveRecipe(Database $db, Recipe $recipe): bool {
        $recipeSave = $this->getSavedRecipe($db, $recipe);
        if ($recipeSave === false || $recipeSave === null) {
            return false;
        }
        
        $query = $db->createStatement(<<<sql
            DELETE FROM `RecipeSaves` rs
            WHERE rs.`recipeId` = ?
                AND rs.`userId` = ?
            sql);

        $ok = $query->bind(
            SqlValueType::String->createParam($recipe->id),
            SqlValueType::String->createParam($this->id),
        )->execute();
        return $ok;
    }
}
?>