<?php
require_once "{$_SERVER["DOCUMENT_ROOT"]}/bootstrap.php";
require_once "lib/core/api.php";
require_once "lib/users.php";

$server = new ApiServer();

$server->addEndpoint(HTTPMethod::GET, function ($req, $res) {
    $userId = $req->expectParam($res, "userId");
    
    $db = Database::connectDefault();
    $user = false;
    try {
        $user = User::fromId($db, $userId);
    } catch (InvalidArgumentException $e) {
        $res->dieWithError(HTTPCode::BadRequest, $e);
    }
    if ($user === false) {
        $res->dieWithError(HTTPCode::NotFound, "User not found");
    }
    $res->sendJSON($user);
});

$server->respond();
?>
