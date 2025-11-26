<?php
defineIfMissing('UPLOAD_DIR', rootPath('uploads'));
if (!file_exists(UPLOAD_DIR)) {
    // upload directory must be created manually to add access permissions (e.g. .htaccess file)
    throw new Exception('Upload directory '.UPLOAD_DIR.' does not exist');
}

// $_FILES[<filename>] keys reference:
// https://www.php.net/manual/en/features.file-upload.post-method.php

enum MimeType: string {
    case JPEG = 'image/jpeg';
    case PNG = 'image/png';
    case GIF = 'image/gif';
    case WEBP = 'image/webp';

    public function preferredExtension(): string {
        return match ($this) {
            self::JPEG => 'jpeg',
            self::PNG => 'png',
            self::GIF => 'gif',
            self::WEBP => 'webp',
        };
    }
}

enum UploadType: string {
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

readonly class UploadFile {
    private ?string $uploadPath;
    
    // private constructor to enforce validation method for creation
    // and prevent throwing exceptions in constructor (bad practice)
    private function __construct(
        public string $path,
        public MimeType $mime,
        public int $size,
        public UploadType $type,
    ) { }

    public static function fromFileArray(array $file, UploadType $type): self {
        // https://www.php.net/manual/en/features.file-upload.php
        $error = $file['error'] ?? null;

        // Undefined | Multiple Files | $_FILES Corruption Attack
        // If this request falls under any of them, treat it invalid.
        if (!isset($error) || is_array($error)) {
            throw new RuntimeException('Invalid parameters');
        }

        // check if file has an upload error
        switch ($error) {
            case UPLOAD_ERR_OK:
                break;
            case UPLOAD_ERR_NO_FILE:
                throw new RuntimeException('No file sent');
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                throw new RuntimeException('Exceeded filesize limit');
            default:
                throw new RuntimeException('Unknown file upload error');
        }

        $path = $file['tmp_name'];
        $size = $file['size'];
        $typename = $type->value;

        // check file size
        if ($size > $type->maxSize()) {
            throw new RuntimeException("Exceeded filesize limit for type $typename");
        }
        // check MIME type
        $mimeFinfo = new finfo(FILEINFO_MIME_TYPE);
        $mime = $mimeFinfo->file($path);
        if ($mime === false) {
            throw new RuntimeException('Invalid file format');
        }

        $allowedMimes = $type->allowedMimes();
        $allowedMimeStrings = array_map(fn($m) => $m->value, $allowedMimes);
        $mimeIndex = array_search($mime, $allowedMimeStrings, true);
        if ($mimeIndex === false) {
            throw new RuntimeException("Invalid mimetype $mime for type $typename");
        }

        return new self(
            path: $path,
            mime: $allowedMimes[$mimeIndex],
            size: $size,
            type: $type,
        );
    }

    public function getUploadPath(): string|false {
        if ($this->uploadPath === null) {
            return false;
        }
        return $this->uploadPath;
    }

    public function isUploaded(): bool {
        return $this->getUploadPath() !== false;
    }

    public function upload(): string {
        if ($this->isUploaded()) {
            throw new RuntimeException('File already uploaded');
        }
        $uuid = uuidv4();
        $extension = $this->mime->preferredExtension();
        $uploadPath = joinPaths(UPLOAD_DIR, "$uuid.$extension");
        $moveOk = move_uploaded_file($this->path, $uploadPath);
        if (!$moveOk) {
            throw new RuntimeException('Failed to upload file');
        }
        $this->uploadPath = $uploadPath;
        return $uploadPath;
    }
}

?>