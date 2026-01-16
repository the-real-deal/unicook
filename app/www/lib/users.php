<?php
require_once "{$_SERVER["DOCUMENT_ROOT"]}/bootstrap.php";
require_once "lib/core/db.php";
require_once "lib/core/uuid.php";
require_once "lib/recipes.php";
require_once "lib/utils.php";

readonly class User extends DBTable {
    public const PASSWORD_REGEX = <<<regex
    /^(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,50}$/
    regex;
    public const USERNAME_REGEX = <<<regex
    /^.{3,50}$/
    regex;

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

    private static function validateUsername(string $username): string {
        if (filter_var_regex($username, self::USERNAME_REGEX) === false) {
            throw new InvalidArgumentException(<<<end
            Username must be between 5 and 50 characters
            end);
        }
        return $username;
    }

    private static function validateEmail(string $email): string {
        if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            throw new InvalidArgumentException("Invalid email");
        }
        return $email;
    }

    private static function validatePassword(string $password): string {
        if (filter_var_regex($password, self::PASSWORD_REGEX) === false) {
            throw new InvalidArgumentException(<<<end
            Password must be between 8 and 50 characters, with at least 1 symbol and 1 number
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
        if ($ok) {
            return $id;
        } else {
            return false;
        }
    }

    public static function fromAuthSessionId(Database $db, string $sessionId): self|false {
        $sessionId = validateUUID($sessionId);
        
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
        if ($passwordMatches) {
            return $user;
        } else {
            return false;
        }
    }

    public static function searchEmail(Database $db, string $email): bool {
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
        if ($result->totalRows === 0) {
            return false;
        } else {
            return true;
        }
    }

    public function createAuthSession(Database $db): string|false {
        $query = $db->createStatement(<<<sql
            INSERT INTO `AuthSessions`(`id`, `keyHash`, `userId`) VALUES (?, ?, ?)
            sql);
        $sessionId = uuidv4();
        $key = uuidv4();
        $keyHash = hash(AuthSession::KEY_HASH_ALGO, $key);
        $ok = $query->bind(
            SqlValueType::String->createParam($sessionId),
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