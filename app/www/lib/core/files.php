<?php
require_once "{$_SERVER["DOCUMENT_ROOT"]}/bootstrap.php";
require_once "lib/core/uuid.php";
require_once "lib/core/mime.php";
require_once "lib/utils.php";

// $_FILES[<filename>] keys reference:
// https://www.php.net/manual/en/features.file-upload.post-method.php

define("UPLOAD_DIR", "{$_SERVER["DOCUMENT_ROOT"]}/uploads");
if (!file_exists(UPLOAD_DIR) || !is_dir(UPLOAD_DIR)) {
    // upload directory must be created manually to add access permissions (e.g. .htaccess file)
    throw new ErrorException("Upload directory " . UPLOAD_DIR . " does not exist");
}

enum FileType: string {
    case Image = "image";

    public function maxSize(): int {
        return match ($this) {
            self::Image => 500 * 1024, // 500KB
        };
    }

    public function allowedMimes(): array {
        return match ($this) {
            self::Image => [
                MimeType::JPEG,
                MimeType::PNG,
                MimeType::GIF,
                MimeType::WEBP,
            ],
        };
    }
}

class BadFileException extends Exception {}
class UploadErrorException extends Exception {}

readonly class UploadFile {
    public function __construct(
        private string $path,
        public string $id,
        public FileType $type,
        public MimeType $mime,
        public int $size,
    ) {}

    private static function createUploadPath(string $id, MimeType $mime, string $path): string {
        $extension = $mime->preferredExtension();
        return UPLOAD_DIR . "/$path/$id" . ($extension === null ? "" : ".$extension");
    }

    private static function createMetadataPath(string $id, string $path): string {
        return UPLOAD_DIR . "/$path/$id.json";
    }

    public static function fromId(string $id, string $path): self|false {
        $metadataPath = self::createMetadataPath($id, $path);
        if (!file_exists($metadataPath) || !is_file($metadataPath)) {
            return false;
        }
        $jsonString = file_get_contents($metadataPath);
        if ($jsonString === false) {
            throw new RuntimeException("Error reading metadata for $id");
        }
        
        $jsonData = json_decode($jsonString, false);
        assert(is_string($jsonData->id));
        assert(is_string($jsonData->type));
        assert(is_string($jsonData->mime));
        assert(is_int($jsonData->size));

        return new self(
            path: $path,
            id: $jsonData->id,
            type: FileType::from($jsonData->type),
            mime: MimeType::from($jsonData->mime),
            size: $jsonData->size,
        );
    }

    public static function uploadFileArray(array $file, FileType $type, string $path): self {
        // https://www.php.net/manual/en/features.file-upload.php
        $error = $file["error"];

        // Undefined | Multiple Files | $_FILES Corruption Attack
        // If this request falls under any of them, treat it invalid.
        if (!isset($error) || is_array($error)) {
            throw new BadFileException("Invalid file array");
        }

        // check if file has an upload error
        switch ($error) {
            case UPLOAD_ERR_OK:
                break;
            case UPLOAD_ERR_NO_FILE:
                throw new UploadErrorException("No file sent");
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                throw new UploadErrorException("Exceeded filesize limit");
            default:
                throw new UploadErrorException("Unknown file upload error");
        }

        $tmpName = $file["tmp_name"];
        $size = $file["size"];
        $typename = $type->value;

        // check file size
        if ($size > $type->maxSize()) {
            throw new BadFileException(
                "Exceeded size limit for type $typename",
            );
        }
        // check MIME type
        $mimeFinfo = new finfo(FILEINFO_MIME_TYPE);
        $mimeString = $mimeFinfo->file($tmpName);
        if ($mimeString === false) {
            throw new BadFileException("Invalid file format");
        }

        $allowedMimes = $type->allowedMimes();
        $mimeIndex = searchEnum($allowedMimes, $mimeString);
        if ($mimeIndex === false) {
            throw new BadFileException(
                "Invalid mimetype $mimeString for type $typename",
            );
        }
        $mime = $allowedMimes[$mimeIndex];

        $id = uuidv4();
        $uploadPath = self::createUploadPath($id, $mime, $path);
        createMissingDir($uploadPath);
        $moveOk = move_uploaded_file($tmpName, $uploadPath);
        if ($moveOk === false) {
            throw new RuntimeException("Failed to upload file");
        }

        $result = new UploadFile(
            path: $path,
            id: $id,
            type: $type,
            mime: $mime,
            size: $size,
        );

        $metadataPath = self::createMetadataPath($id, $path);
        createMissingDir($metadataPath);
        $metadataFile = fopen($metadataPath, "w");
        $resultJson = json_encode($result);
        if ($resultJson === false) {
            throw new RuntimeException("Failed to upload metadata");
        }
        
        try {
            fwrite($metadataFile, $resultJson);
            return $result;
        } finally {
            fclose($metadataFile);
        }
    }

    public function uploadPath(): string {
        return self::createUploadPath($this->id, $this->mime, $this->path);
    }

    public function metadataPath(): string {
        return self::createMetadataPath($this->id, $this->path);
    }
}

?>
