<?php
require_once "{$_SERVER["DOCUMENT_ROOT"]}/bootstrap.php";
require_once "lib/core/mime.php";

enum HTTPHeader: string {
    case ContentType = "Content-Type";
    case ContentLength = "Content-Length";
    case Location = "Location";
    case Authorization = "Authorization";

    public function checkData(mixed $data): bool {
        return match ($this) {
            self::ContentType => $data instanceof MimeType,
            self::ContentLength => is_int($data) && $data >= 0,
            default => is_string($data),
        };
    }

    public function createString(mixed $data): string|false {
        $headerName = $this->value;
        if (!$this->checkData($data)) {
            return false;
        }
        if (isEnum($data)) {
            $data = $data->value;
        }
        return "$headerName: $data";
    }
}

enum HTTPCode: int {
    case NotFound = 404;
    case BadRequest = 400;
    case Unauthorized = 401;
    case MethodNotAllowed = 405;
    case InternalServerError = 500;
    case OK = 200;
}

enum HTTPMethod: string {
    case GET = "GET";
    case POST = "POST";

    public function paramsArray(): ?array {
        return match ($this) {
            self::GET => $_GET,
            self::POST => $_POST,
            default => null,
        };
    }

    public function filesArray(): ?array {
        return match ($this) {
            self::POST => $_FILES,
            default => null,
        };
    }
}

?>