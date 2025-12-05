<?php
require_once 'files.php';

enum HTTPHeader: string {
    case ContentType = 'Content-Type';
    case ContentLength = 'Content-Length';
    case Location = 'Location';

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
    case GET = 'GET';
    case POST = 'POST';
}

class InvalidHTTPHeaderDataException extends Exception {}

final class Request {
    private function __construct() { }

    public static function requireID(string $param, int $input = INPUT_GET): string {
        $id = filter_input(
            $input, 
            $param, 
            options: FILTER_REQUIRE_SCALAR | FILTER_FLAG_EMPTY_STRING_NULL
        );
        if (!is_string($id)) {
            Response::dieWithException(new RuntimeException("Invalid or missing $param parameter"), HTTPCode::BadRequest);
        }
        return htmlspecialchars($id);
    }
}

final class Response {
    private function __construct() {}

    public static function setHeader(HTTPHeader $header, mixed $data) {
        $headerName = $header->value;
        if (!$header->checkData($data)) {
            throw new InvalidHTTPHeaderDataException("Invalid value $data for header $headerName");
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

    public static function dieWithException(\Throwable $e, HTTPCode $code): never {
        self::setCode(HTTPCode::InternalServerError);
        self::sendJSON([
            'error' => $e->getMessage()
        ]);
        die();
    }

    public static function redirect(string $uri): never {
        self::setHeader(HTTPHeader::Location, $uri);
        die();
    }

    public static function setCode(HTTPCode $code) {
        http_response_code($code->value);
    }

    public static function dieWithCode(HTTPCode $code): never {
        self::setCode($code);
        die();
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
        $methodIndex = searchEnum($allowedHttpMethods, strtoupper($_SERVER['REQUEST_METHOD']));
        if ($methodIndex === false) {
            Response::dieWithCode(HTTPCode::MethodNotAllowed);
        }
        $method = $allowedHttpMethods[$methodIndex];
        $callback = $this->callbacks[$method->value];
        if (!isset($callback)) {
            Response::dieWithCode(HTTPCode::MethodNotAllowed);
        }
        try {
            $callback();
        } catch (\Throwable $th) {
            Response::dieWithException($th, HTTPCode::InternalServerError);
        }
    }
}

?>