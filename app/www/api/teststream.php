<?php
require_once "{$_SERVER['DOCUMENT_ROOT']}/bootstrap.php";
require_once "lib/core/api.php";
require_once "lib/utils.php";

$server = new ApiServer();

define("MISTRAL_API_URL", "https://api.mistral.ai/v1/chat/completions");
// clear api key for demo purposes
define("MISTRAL_API_KEY", envValue("MISTRAL_API_KEY") || "nh5cxczbW9RHViDA6WhFnM8w8YCkQStc");
define("MISTRAL_AGENT_ID", envValue("MISTRAL_AGENT_ID") || "ag_019b12d4995071a98c64db6fd041d99e");

$server->addEndpoint(HTTPMethod::POST, function ($req, $res) {
    $res->setHeader(HTTPHeader::ContentType, MimeType::PlainText);
    // https://itsrav.dev/articles/streaming-http-response-in-php-to-turn-long-running-process-into-realtime-experience
    set_time_limit(0); // max execution time unlimited
    ob_implicit_flush(1); // automatically flush every output

    $prompt = $_POST["prompt"];
    
    for ($i=0; $i < 10; $i++) { 
        sleep(1);
        echo "#$i $prompt\n";
    }
});

$server->respond();

?>