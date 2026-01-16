<?php
require_once "{$_SERVER["DOCUMENT_ROOT"]}/bootstrap.php";
require_once "lib/core/api.php";
require_once "lib/core/files.php";
require_once "lib/auth.php";

$server = new ApiServer();

$server->addEndpoint(HTTPMethod::POST, function ($req, $res) {
    $image = $req->expectFile($res, "image");
    
    $db = Database::connectDefault();
    try {
        $login = LoginSession::autoLogin($db);
        if ($login === false) {
            $res->dieWithError(HTTPCode::Unauthorized, "Not logged in");
        }
        $image = $login->user->uploadImage($db, $image);
        if ($image === false) {
            $res->dieWithError(HTTPCode::InternalServerError, "Upload failed");
        }
        $res->sendJSON($image);
    } catch (InvalidArgumentException | UploadErrorException | BadFileException $e) {
        $res->dieWithError(HTTPCode::BadRequest, $e);
    }
});

$server->respond();
?>
