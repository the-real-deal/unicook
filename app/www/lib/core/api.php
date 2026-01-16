<?php
require_once "{$_SERVER["DOCUMENT_ROOT"]}/bootstrap.php";
require_once "lib/core/http.php";
require_once "lib/utils.php";

class InvalidHTTPHeaderDataException extends Exception {}

// https://www.php.net/manual/en/filter.constants.php#filter.constants.flags.generic
readonly class ApiRequest {
    private ?array $params;
    private ?array $files;

    public function __construct(
        HTTPMethod $method,
    ) {
        $this->params = $method->paramsArray();
        $this->files = $method->filesArray();
    }

    public function getParam(string $key): array|string|null {
        assert($this->params !== null);
        return $this->params[$key] ?? null;
    }

    public function getFile(string $key): array|null {
        assert($this->files !== null);
        return $this->files[$key] ?? null;
    }

    public function expectParam(ApiResponse $res, string $key): array|string {
        $value = $this->getParam($key);
        if ($value === null) {
            $res->dieWithError(HTTPCode::BadRequest, "Missing param $key");
        } else {
            return $value;
        }
    }

    public function expectFile(ApiResponse $res, string $key): array {
        $value = $this->getFile($key);
        if ($value === null) {
            $res->dieWithError(HTTPCode::BadRequest, "Missing file $key");
        } else {
            return $value;
        }
    }
}

class ApiResponse {
    public function __construct() {}

    public function setCode(HTTPCode $code): self {
        http_response_code($code->value);
        return $this;
    }

    public function setHeader(HTTPHeader $header, mixed $data): self {
        $headerName = $header->value;
        if (!$header->checkData($data)) {
            throw new InvalidHTTPHeaderDataException(
                "Invalid value $data for header $headerName",
            );
        }
        if (isEnum($data)) {
            $data = $data->value;
        }
        header("$headerName: $data");
        return $this;
    }

    public function sendText(string $data): self {
        $this->setHeader(HTTPHeader::ContentType, MimeType::PlainText)
            ->setHeader(HTTPHeader::ContentLength, strlen($data));
        echo $data;
        return $this;
    }

    public function sendJSON(array|object $data): self {
        $jsonString = json_encode($data);
        $this->setHeader(HTTPHeader::ContentType, MimeType::JSON)
            ->setHeader(HTTPHeader::ContentLength, strlen($jsonString));
        echo $jsonString;
        return $this;
    }

    public function sendFile(UploadFile $file): self {
        $this->setHeader(HTTPHeader::ContentType, $file->mime)
            ->setHeader(HTTPHeader::ContentLength, $file->size);
        readfile($file->uploadPath());
        return $this;
    }

    public function dieWithError(
        HTTPCode $code, 
        string|Exception|null $err = null
    ): never {
        $this->setCode($code);
        if (isset($err)) {
            $this->sendJSON([
                "error" => is_string($err) ? $err : $err->getMessage(),
            ]);
        }
        die();
    }

    public function redirect(string $uri): never {
        $this->setHeader(HTTPHeader::Location, $uri);
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
        $methodIndex = searchEnum(
            $allowedHttpMethods,
            strtoupper($_SERVER["REQUEST_METHOD"]),
        );
        if ($methodIndex === false) {
            $res->dieWithError(HTTPCode::MethodNotAllowed);
        }
        
        $method = $allowedHttpMethods[$methodIndex];

        $req = new ApiRequest(method: $method);
        $res = new ApiResponse();
        $callback = $this->callbacks[$method->value] 
            ?? $res->dieWithError(HTTPCode::MethodNotAllowed);
        
        try {
            $callback($req, $res);
        } catch (Exception $e) {
            $res->dieWithError(HTTPCode::InternalServerError, $e);
        }
    }
}

?>
