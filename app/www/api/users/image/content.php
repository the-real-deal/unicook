<?php
require_once "{$_SERVER["DOCUMENT_ROOT"]}/bootstrap.php";
require_once "lib/core/api.php";
require_once "lib/core/files.php";
require_once "lib/users.php";

$server = new ApiServer();

$server->addEndpoint(HTTPMethod::GET, function ($req, $res) {
    $userId = $req->expectParam($res, "userId");
    
    $db = Database::connectDefault();
    try {
        $user = User::fromId($db, $userId);
        if ($user === false) {
            $res->dieWithError(HTTPCode::NotFound, "User not found");
        }
        $image = $user->getImage();
        if ($image === false) {
            $res->dieWithError(HTTPCode::NotFound, "Image not found");
        }
        $res->sendUpload($image);
    } catch (InvalidArgumentException $e) {
        $res->dieWithError(HTTPCode::BadRequest, $e);
    }
});

$server->respond();
?>
