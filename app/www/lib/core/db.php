<?php
require_once "{$_SERVER["DOCUMENT_ROOT"]}/bootstrap.php";
require_once "lib/utils.php";

define("MYSQL_DATETIME_FORMAT", "Y-m-d H:i:s");

enum SqlValueType {
    case Int;
    case Bool;
    case Float;
    case String;
    case Datetime;

    public static function fromField(object $field): self {
        // https://www.php.net/manual/en/mysqli.constants.php
        return match ($field->type) {
            MYSQLI_TYPE_TINY,
            MYSQLI_TYPE_SHORT,
            MYSQLI_TYPE_LONG,
            MYSQLI_TYPE_LONGLONG,
            MYSQLI_TYPE_INT24,
            MYSQLI_TYPE_CHAR => self::Int,
            
            MYSQLI_TYPE_BIT => self::Bool,

            MYSQLI_TYPE_DECIMAL,
            MYSQLI_TYPE_NEWDECIMAL,
            MYSQLI_TYPE_FLOAT,
            MYSQLI_TYPE_DOUBLE => self::Float,
            
            MYSQLI_TYPE_VAR_STRING,
            MYSQLI_TYPE_STRING,
            MYSQLI_TYPE_TINY_BLOB,
            MYSQLI_TYPE_MEDIUM_BLOB,
            MYSQLI_TYPE_LONG_BLOB,
            MYSQLI_TYPE_BLOB => self::String,

            MYSQLI_TYPE_TIMESTAMP,
            MYSQLI_TYPE_DATETIME => self::Datetime,

            default => throw new RuntimeException("Invalid field type {$field->type}")
        };
    }

    // https://www.php.net/manual/en/mysqli-stmt.bind-param.php
    public function typeString(): string  {
        return match ($this) {
            self::Int, self::Bool => "i",
            self::Float => "d",
            self::String,
            self::Datetime => "s",
        };
    }

    public function valueFromField(null|int|float|string|false $value): mixed {
        if ($value === null) {
            return null;
        }
        switch ($this) {
            case self::Int:
                assert(is_int($value));
                return $value;
            case self::Bool:
                assert(is_int($value));
                return boolval($value);
            case self::Float:
                assert(is_float($value));
                return $value;
            case self::String:
                assert(is_string($value));
                return $value;
            case self::Datetime:
                assert(is_string($value));
                return DateTime::createFromFormat(MYSQL_DATETIME_FORMAT, $value);
        }
    }
    
    public function valueIntoField(mixed $value): null|int|float|string {
        if ($value === null) {
            return null;
        }
        switch ($this) {
            case self::Int:
                assert(is_int($value));
                return $value;
            case self::Bool:
                assert(is_bool($value));
                return (int)$value;
            case self::Float:
                assert(is_float($value));
                return $value;
            case self::String:
                assert(is_string($value));
                return $value;
            case self::Datetime:
                assert(get_class($value) === DateTime::class);
                return $value->format(MYSQL_DATETIME_FORMAT);
        }
    }

    public function createParam(mixed $data): SqlParam {
        return new SqlParam($this, $data);
    }
}

readonly class SqlParam {
    public function __construct(public SqlValueType $type, public mixed $data) {}
}

class QueryRow extends ArrayObject {
    private function __construct(array $data) {
        parent::__construct($data);
    }
    
    public static function fromArray(array $row, array $fields): self {
        $data = [];
        foreach ($row as $key => $value) {
            assert($fields[$key] instanceof SqlValueType);
            $value = $fields[$key]->valueFromField($value);
            $data[$key] = $value;
        }
        return new self($data);
    }
}

class QueryResult implements Closeable {
    use AutoCloseable;

    private array $fields;
    readonly public int $totalRows;

    public function __construct(private mysqli_result $result) {
        $this->fields = [];
        foreach ($result->fetch_fields() as $field) {
            $this->fields[$field->name] = SqlValueType::fromField($field);
        }
        $this->totalRows = $result->num_rows;
    }

    public static function fromResult(mysqli_result|false $result): self|false {
        return $result === false ? false : new self($result);
    }

    public function close() {
        $this->result->close();
    }

    public function fetchOne(): ?QueryRow {
        $row = $this->result->fetch_assoc();
        if ($row === null) {
            return null;
        } else if ($row === false) {
            throw new RuntimeException("Error fetching row from result");
        }
        return QueryRow::fromArray($row, $this->fields);
    }
    
    public function fetchAll(): array {
        $rows = $this->result->fetch_all(MYSQLI_ASSOC);
        return array_map(
            fn ($row) => QueryRow::fromArray($row, $this->fields),
            $rows,
        );
    }
}

class QueryStatement {
    private array $params = [];
            
    public function __construct(private mysqli_stmt $statement) {}

    public function bind(SqlParam ...$params): self {
        $typeStrings = array_map(fn ($v) => $v->type->typeString(), $params);
        $fields = array_map(fn ($v) => $v->type->valueIntoField($v->data), $params);
        $this->statement->bind_param(implode("", $typeStrings), ...$fields);
        return $this;
    }

    public function execute(): bool {
        return $this->statement->execute();
    }

    public function getResult(): QueryResult|false {
        return QueryResult::fromResult($this->statement->get_result());
    }

    public function expectResult(): QueryResult {
        $result = $this->getResult();
        if ($result === false) {
            throw new RuntimeException("Expected query result");
        }
        return $result;
    }
}

class Database implements Closeable {
    use AutoCloseable;

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
            host: envValue("DB_HOST") ?? "localhost",
            username: envValue("DB_USER") ?? "unicook_appuser",
            // clear password for demo
            password: envValue("DB_PASSWORD") ?? "unicook_app_user_passwd!", 
            dbname: envValue("DB_NAME") ?? "UniCook", 
            port: intval(envValue("DB_PORT") ?? "3306"),
        );
    }

    public function beginTransaction(int $flags = MYSQLI_TRANS_START_READ_WRITE): bool {
        return $this->conn->begin_transaction($flags);
    }

    public function createStatement(string $query): QueryStatement {
        $statement = $this->conn->prepare($query);
        return new QueryStatement($statement);
    }

    public function commit(): bool {
        return $this->conn->commit();
    }
    
    public function rollback(): bool {
        return $this->conn->rollback();
    }

    public function close() {
        $this->conn->close();
    }
}

readonly abstract class DBTable {
    // return static: instance of child class
    public static function fromTableRow(QueryRow $row): static {
        $reflection = new ReflectionClass(static::class);
        $params = [];
        foreach ($reflection->getConstructor()->getParameters() as $param) {
            $value = $row[$param->getName()];
            $type = $param->getType();
            if ($type !== null && $type instanceof ReflectionNamedType && !$type->isBuiltin()) {
                $enumClass = new ReflectionClass($type->getName());
                if ($enumClass->isEnum()) {
                    $enumClassName = $enumClass->getName();
                    $value = $enumClassName::from($value);
                }
            }
            array_push($params, $value);
        }
        return new static(...$params);
    }

    public static function fromOptionalTableRow(?QueryRow $row): ?static {
        return $row === null ? null : static::fromTableRow($row);
    }

    protected static function tableAliasPrefix(?string $alias): string {
        return $alias === null ? "" : "$alias.";
    }
}

?>
