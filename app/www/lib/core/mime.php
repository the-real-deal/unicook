<?php
require_once "{$_SERVER["DOCUMENT_ROOT"]}/bootstrap.php";

enum MimeType: string {
    case JPEG = "image/jpeg";
    case PNG = "image/png";
    case GIF = "image/gif";
    case WEBP = "image/webp";
    case JSON = "application/json";
    case JavaScript = "text/javascript";
    case PlainText = "text/plain";
    case TextEventStream = "text/event-stream";

    public function preferredExtension(): ?string {
        return match ($this) {
            self::JPEG => "jpeg",
            self::PNG => "png",
            self::GIF => "gif",
            self::WEBP => "webp",
            self::JavaScript => "js",
            self::PlainText => "txt",
            default => null,
        };
    }
}

?>