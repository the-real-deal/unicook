<?php
require_once "{$_SERVER["DOCUMENT_ROOT"]}/bootstrap.php";
require_once "lib/core/api.php";
require_once "lib/auth.php";

$server = new ApiServer();

$server->addEndpoint(HTTPMethod::POST, function ($req, $res) {
    $db = Database::connectDefault();
    $login = LoginSession::autoLogin($db);
    if ($login === false) {
        $res->dieWithError(HTTPCode::Unauthorized, "Not logged in");
    }
    $ok = $login->user->deleteImage($db);
    if ($ok === false) {
        $res->dieWithError(HTTPCode::InternalServerError, "Delete failed");
    }
    $res->sendJSON([ "ok" => true ]);
});

$server->respond();
?>
