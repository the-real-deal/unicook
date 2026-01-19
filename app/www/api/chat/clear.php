<?php
require_once "{$_SERVER["DOCUMENT_ROOT"]}/bootstrap.php";
require_once "lib/core/api.php";
require_once "lib/chat.php";

$server = new ApiServer();

$server->addEndpoint(HTTPMethod::POST, function ($req, $res) {
    $chat = Chat::restoreFromSession();
    $chat->clear();
    $res->sendJson([ "ok" => true ]);
});

$server->respond();
?>
