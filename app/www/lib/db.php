<?php
enum QueryParamType: string {
    case String = "s";
    case Int = "i";
}

class QueryStatement {
    private array $params = [];
            
    private function __construct(private mysqli_stmt $statement) {}

    public function bind(QueryParamType $type, mixed $value): self {
        $this->statement->bind_param($type->value, $value);
        return $this;
    }

    public function execute(): self {
        $this->statement->execute();
        return $this;
    }

    public function getResult(): mysqli_result|false {
        return $this->statement->get_result();
    }
}

class DatabaseHelper {
    private mysqli $conn;

    public function __construct(
        private string $host,
        private string $username,
        private string $password,
        private string $dbname,
        private int $port,
    ) {
        $this->conn = new mysqli($host, $username, $password, $dbname, $port);
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    public static function connectDefault(): self {
        return new self(
            host: envValue('DB_HOST') ?? 'localhost',
            username: envValue('DB_USER') ?? 'unicook_appuser',
            // clear password for demo
            password: envValue('DB_PASSWORD') ?? 'unicook_app_user_passwd!', 
            dbname: envValue('DB_NAME') ?? 'UniCook', 
            port: intval(envValue('DB_PORT') ?? "3306"),
        );
    }

    public function query(string $query): mysqli_result|false {
        return $this->conn->query($query);
    }

    public function createStatement(string $query): QueryStatement {
        $statement = $this->conn->prepare($query);
        return new QueryStatement($statement);
    }
}
?>
