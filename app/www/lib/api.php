<?php
require_once "files.php";

enum HTTPHeader: string {
    case ContentType = "Content-Type";
    case ContentLength = "Content-Length";
    case Location = "Location";

    public function checkData(mixed $data): bool {
        return match ($this) {
            self::ContentType => $data instanceof MimeType,
            self::ContentLength => is_int($data) && $data >= 0,
            default => true,
        };
    }
}

enum HTTPCode: int {
    case NotFound = 404;
    case BadRequest = 400;
    case MethodNotAllowed = 405;
    case InternalServerError = 500;
    case OK = 200;
}

enum HTTPMethod: string {
    case GET = "GET";
    case POST = "POST";
}

class InvalidHTTPHeaderDataException extends Exception {}

// https://www.php.net/manual/en/filter.constants.php#filter.constants.flags.generic
final class Request {
    private function __construct() {}

    public static function parseID(string $value): string|false {
        $value = htmlspecialchars($value);
        return filter_var(
            $value, 
            options: FILTER_REQUIRE_SCALAR | FILTER_FLAG_EMPTY_STRING_NULL
        );
    }
}

final class Response {
    private function __construct() {}

    public static function setHeader(HTTPHeader $header, mixed $data) {
        $headerName = $header->value;
        if (!$header->checkData($data)) {
            throw new InvalidHTTPHeaderDataException(
                "Invalid value $data for header $headerName",
            );
        }
        if ($data instanceof \UnitEnum) {
            $data = $data->value;
        }
        header("$headerName: $data");
    }

    public static function sendText(string $data) {
        self::setHeader(HTTPHeader::ContentType, MimeType::PlainText);
        self::setHeader(HTTPHeader::ContentLength, strlen($data));
        echo $data;
    }

    public static function sendJSON(array|object $data) {
        $jsonString = json_encode($data);
        self::setHeader(HTTPHeader::ContentType, MimeType::JSON);
        self::setHeader(HTTPHeader::ContentLength, strlen($jsonString));
        echo $jsonString;
    }

    public static function sendFile(UploadFile $file) {
        self::setHeader(HTTPHeader::ContentType, $file->mime);
        self::setHeader(HTTPHeader::ContentLength, $file->size);
        readfile($file->uploadPath());
    }

    public static function dieWithError(
        HTTPCode $code, 
        string|Exception|null $err = null
    ): never {
        self::setCode($code);
        if (isset($err)) {
            self::sendJSON([
                "error" => is_string($err) ? $err : $err->getMessage(),
            ]);
        }
        die();
    }

    public static function redirect(string $uri): never {
        self::setHeader(HTTPHeader::Location, $uri);
        die();
    }

    public static function setCode(HTTPCode $code) {
        http_response_code($code->value);
    }
}

class ApiServer {
    private array $callbacks = [];

    public function addEndpoint(HTTPMethod $method, callable $callback) {
        assert(!isset($this->callbacks[$method->value]));
        $this->callbacks[$method->value] = $callback;
    }

    public function respond() {
        $allowedHttpMethods = HTTPMethod::cases();
        $methodIndex = searchEnum(
            $allowedHttpMethods,
            strtoupper($_SERVER["REQUEST_METHOD"]),
        );
        if ($methodIndex === false) {
            Response::dieWithError(HTTPCode::MethodNotAllowed);
        }
        
        $method = $allowedHttpMethods[$methodIndex];
        $callback = $this->callbacks[$method->value] 
            ?? Response::dieWithError(HTTPCode::MethodNotAllowed);
        
        try {
            $callback();
        } catch (Exception $e) {
            Response::dieWithError(HTTPCode::InternalServerError, $e);
        }
    }
}

?>
