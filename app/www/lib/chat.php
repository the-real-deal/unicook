<?php
require_once "{$_SERVER["DOCUMENT_ROOT"]}/bootstrap.php";
require_once "lib/core/db.php";
require_once "lib/core/http.php";
require_once "lib/core/mime.php";

define("MISTRAL_API_URL", "https://api.mistral.ai/v1/agents/completions");
// clear api key for demo purposes
define("MISTRAL_API_KEY", envValue("MISTRAL_API_KEY") ?? "nh5cxczbW9RHViDA6WhFnM8w8YCkQStc");
define("MISTRAL_AGENT_ID", envValue("MISTRAL_AGENT_ID") ?? "ag_019b12d4995071a98c64db6fd041d99e");

enum ChatMessageRole: string {
    case User = "user";
    case Assistant = "assistant";
}

readonly class ChatMessage {
    private function __construct(
        public ChatMessageRole $role,
        public string $content,
    ) {}

    public static function validateContent(string $content, ChatMessageRole $role): string {
        if ($role === ChatMessageRole::User && filter_var_regex($content, '/^.{1,250}$/') === false) {
            throw new InvalidArgumentException(<<<end
            Chat message content must be between 1 and 250 characters
            end);
        }
        return $content;
    }

    public static function create(ChatMessageRole $role, string $content): self {
        $content = self::validateContent($content, $role);
    
        return new self(
            role: $role,
            content: $content,
        );
    }
}

class Chat {
    private const MESSAGES_SESSION_KEY = "chat";

    private function __construct(
        public array $messages = [],
    ) {}

    public static function restoreFromSession(): self {
        $serialized = $_SESSION[self::MESSAGES_SESSION_KEY] ?? null;
        return $serialized === null ? new self() : unserialize($serialized);
    }

    private function saveToSession() {
        $_SESSION[self::MESSAGES_SESSION_KEY] = serialize($this);
    }

    private function addMessage(ChatMessage $message) {
        array_push($this->messages, $message);
        $this->saveToSession();
    }

    public function clear() {
        $this->messages = [];
        unset($_SESSION[self::MESSAGES_SESSION_KEY]);
    }

    public function sendMessage(string $content): Generator|false {
        $this->addMessage(ChatMessage::create(ChatMessageRole::User, $content));

        // https://www.php.net/manual/en/function.stream-context-create.php#refsect1-function.stream-context-create-examples
        $payload = [
            "agent_id" => MISTRAL_AGENT_ID,
            "stream" => true,
            "messages" => $this->messages,
        ];
        $options = [
            "http" => [
                "method" => HTTPMethod::POST->value,
                "header" => implode("\r\n", [
                    HTTPHeader::ContentType->createString(MimeType::JSON),
                    HTTPHeader::Authorization->createString("Bearer " . MISTRAL_API_KEY),
                ]),
                "content" => json_encode($payload),
            ]
        ];
        $context = stream_context_create($options);

        $stream = false;
        try {
            $stream = fopen(MISTRAL_API_URL, "r", context: $context);
            if ($stream === false) {
                return false;
            }
            $responseMessage = "";
            while (!feof($stream)) {
                $line = fgets($stream);
                if ($line === false) {
                    break;
                }
                $line = trim($line);
                if (empty($line)) {
                    continue;
                }
                
                // https://developer.mozilla.org/en-US/docs/Web/API/Server-sent_events/Using_server-sent_events#fields
                $prefix = "data";

                if (str_starts_with($line, $prefix)) {
                    $data = substr($line, strlen("$prefix: "));
                    $response = json_decode($data, false);
                    if ($response === null) {
                        break;
                    }
                    $content = "";
                    $end = false;
                    foreach ($response->choices as $choice) {
                        $end = $choice->finish_reason !== null;
                        if ($end) {
                            break;
                        }
                        $content .= $choice->delta->content;
                    }
                    yield $content;
                    $responseMessage += $content;

                    if ($end) {
                        break;
                    }
                }
            }
            $this->addMessage(ChatMessage::create(ChatMessageRole::Assistant, $responseMessage));
        } catch (\Throwable $th) {
            return false;
        } finally {
            if ($stream !== false) {
                fclose($stream);
            }
        }
    }
}

?>