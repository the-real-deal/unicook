<?php
// $_FILES[<filename>] keys reference:
// https://www.php.net/manual/en/features.file-upload.post-method.php

defineIfMissing('UPLOAD_DIR', rootPath('uploads'));
if (!file_exists(UPLOAD_DIR) || !is_dir(UPLOAD_DIR)) {
    // upload directory must be created manually to add access permissions (e.g. .htaccess file)
    throw new ErrorException('Upload directory '.UPLOAD_DIR.' does not exist');
}

enum MimeType: string {
    case JPEG = 'image/jpeg';
    case PNG = 'image/png';
    case GIF = 'image/gif';
    case WEBP = 'image/webp';
    case JSON = 'application/json';
    case JavaScript = 'text/javascript';
    case PlainText = 'text/plain';

    public function preferredExtension(): string {
        return match ($this) {
            self::JPEG => 'jpeg',
            self::PNG => 'png',
            self::GIF => 'gif',
            self::WEBP => 'webp',
            self::JavaScript => 'js',
            self::PlainText => 'txt',
        };
    }
}

enum FileType: string {
    case Image = 'image';

    public function maxSize(): int {
        return match($this) {
            self::Image => 50 * 1024, // 50KB
        };
    }

    public function allowedMimes(): array {
        return match($this) {
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
    // private constructor to enforce validation method for creation
    // and prevent throwing exceptions in constructor (bad practice)
    public function __construct(
        public string $id,
        public FileType $type,
        public MimeType $mime,
        public int $size,
    ) { }

    private static function createUploadPath(string $id, MimeType $mime): string {
        return joinPath(UPLOAD_DIR, "$id.{$mime->preferredExtension()}");
    }
    
    private static function createMetadataPath(string $id): string {
        return joinPath(UPLOAD_DIR, "$id.json");
    }

    public static function fromArray(array $data): self {
        assert(is_string($data['type']) || $data['type'] instanceof FileType);
        assert(is_string($data['mime']) || $data['mime'] instanceof MimeType);

        return new self(
            id: $data['id'],
            type: is_string($data['type']) ? FileType::from($data['type']) : $data['type'],
            mime: is_string($data['mime']) ? MimeType::from($data['mime']) : $data['mime'],
            size: $data['size'],
        );
    }

    public static function fromId(string $id): self|false {
        $path = self::createMetadataPath($id);
        if (!file_exists($path) || !is_file($path)) {
            return false;
        }
        $jsonString = file_get_contents($path);
        if ($jsonString === false) {
            throw new RuntimeException("Error reading metadata for $id");
        }
        $jsonData = json_decode($jsonString, true);
        return self::fromArray($jsonData);
    }

    public static function fromFileArray(array $file, FileType $type): self {
        // https://www.php.net/manual/en/features.file-upload.php
        $error = $file['error'];

        // Undefined | Multiple Files | $_FILES Corruption Attack
        // If this request falls under any of them, treat it invalid.
        if (!isset($error) || is_array($error)) {
            throw new BadFileException('Invalid file array');
        }

        // check if file has an upload error
        switch ($error) {
            case UPLOAD_ERR_OK:
                break;
            case UPLOAD_ERR_NO_FILE:
                throw new UploadErrorException('No file sent');
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                throw new UploadErrorException('Exceeded filesize limit');
            default:
                throw new UploadErrorException('Unknown file upload error');
        }

        $path = $file['tmp_name'];
        $size = $file['size'];
        $typename = $type->value;

        // check file size
        if ($size > $type->maxSize()) {
            throw new BadFileException("Exceeded filesize limit for type $typename");
        }
        // check MIME type
        $mimeFinfo = new finfo(FILEINFO_MIME_TYPE);
        $mimeString = $mimeFinfo->file($path);
        if ($mimeString === false) {
            throw new BadFileException('Invalid file format');
        }

        $allowedMimes = $type->allowedMimes();
        $mimeIndex = searchEnum($allowedMimes, $mimeString);
        if ($mimeIndex === false) {
            throw new BadFileException("Invalid mimetype $mimeString for type $typename");
        }
        $mime = $allowedMimes[$mimeIndex];

        $id = uuidv4();
        $uploadPath = self::createUploadPath($id, $mime);
        $moveOk = move_uploaded_file($path, $uploadPath);
        if (!$moveOk) {
            throw new RuntimeException('Failed to upload file');
        }

        $result = new UploadFile(
            id: $id,
            type: $type,
            mime: $mime,
            size: $size,
        );
        
        $metadataPath = self::createMetadataPath($id);
        $metadataFile = fopen($metadataPath, "w");
        try {
            fwrite($metadataFile, json_encode($result));
            return $result;
        } finally {
            fclose($metadataFile);
        }
    }

    public function uploadPath(): string {
        return self::createUploadPath($this->id, $this->mime);
    }

    public function metadataPath(): string {
        return self::createMetadataPath($this->id);
    }
}
?>