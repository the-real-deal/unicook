<?php
require_once "{$_SERVER["DOCUMENT_ROOT"]}/bootstrap.php";
require_once "lib/core/api.php";
require_once "lib/chat.php";

$server = new ApiServer();

$server->addEndpoint(HTTPMethod::GET, function ($req, $res) {
    $chat = Chat::restoreFromSession();
    $res->sendJson($chat->messages);
});

$server->addEndpoint(HTTPMethod::POST, function ($req, $res) {
    $content = $req->expectScalar($res, "content");

    $chat = Chat::restoreFromSession();
    try {
        $response = $chat->sendMessage($content);
        if ($response === false) {
            $res->dieWithError(HTTPCode::InternalServerError, "Failed to send message");
        }
        $res->sendStream(MimeType::PlainText, $response);
    } catch (InvalidArgumentException $e) {
        $res->dieWithError(HTTPCode::BadRequest, $e);
    }
});

$server->respond();
?>
