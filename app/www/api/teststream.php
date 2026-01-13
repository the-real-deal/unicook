<?php
require_once "{$_SERVER['DOCUMENT_ROOT']}/bootstrap.php";
require_once "lib/core/api.php";
require_once "lib/utils.php";

$server = new ApiServer();

define("MISTRAL_API_URL", "https://api.mistral.ai/v1/agents/completions");
// clear api key for demo purposes
define("MISTRAL_API_KEY", envValue("MISTRAL_API_KEY") ?? "nh5cxczbW9RHViDA6WhFnM8w8YCkQStc");
define("MISTRAL_AGENT_ID", envValue("MISTRAL_AGENT_ID") ?? "ag_019b12d4995071a98c64db6fd041d99e");

$server->addEndpoint(HTTPMethod::POST, function ($req, $res) {
    ini_set("output_buffering", false);
    ob_implicit_flush(true);

    $res->setHeader(HTTPHeader::ContentType, MimeType::PlainText);
    $prompt = $_POST["prompt"];

    // https://www.php.net/manual/en/function.stream-context-create.php#refsect1-function.stream-context-create-examples
    $payload = [
        "agent_id" => MISTRAL_AGENT_ID,
        "stream" => true,
        "messages" => [
            [
                "role" => "user",
                "content" => $prompt
            ]
        ]
    ];
    $options = [
        "http" => [
            "method" => "POST",
            "header" => "Content-Type: application/json\r\n" .
                        "Authorization: Bearer " . MISTRAL_API_KEY,
            "content" => json_encode($payload),
        ]
    ];
    $context = stream_context_create($options);

    $stream = false;
    try {
        $stream = fopen(MISTRAL_API_URL, "r", context: $context);
        if ($stream === false) {
            echo "ERROR: Could not open stream\n";
            echo "Last error: " . print_r(error_get_last(), true) . "\n";
            return;    
        // throw new RuntimeException("Error opening stream");
        }
        while (!feof($stream)) {
            $line = fgets($stream);
            
            if ($line === false) {
                break;
            }
            
            // Process SSE format
            $line = trim($line);
            
            if (empty($line)) {
                // Empty line indicates end of event
                continue;
            }
            
            // https://developer.mozilla.org/en-US/docs/Web/API/Server-sent_events/Using_server-sent_events#fields
            $prefix = "data";

            if (str_starts_with($line, $prefix)) {
                $data = substr($line, strlen("$prefix: ")); // Remove 'data:' prefix
                $response = json_decode($data, false);
                if ($response === null) {
                    throw new RuntimeException("Error parsing response");
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
                echo $content;

                if ($end) {
                    return;
                }
            }
        }
    } catch (\Throwable $th) {
        // just stop streaming the response
        return;
    } finally {
        if ($stream !== false) {
            fclose($stream);
        }
    }
});

$server->respond();

?>