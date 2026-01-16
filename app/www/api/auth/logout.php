<?php
require_once "{$_SERVER["DOCUMENT_ROOT"]}/bootstrap.php";
require_once "lib/core/api.php";
require_once "lib/auth.php";

$server = new ApiServer();

$server->addEndpoint(HTTPMethod::POST, function ($req, $res) {
    $db = Database::connectDefault();
    $login = LoginSession::autoLogin($db);
    if ($login === false) {
        $res->dieWithError(HTTPCode::BadRequest, "Failed to verify login");
    }
    $ok = $login->logout($db);
    if (!$ok) {
        $res->dieWithError(HTTPCode::InternalServerError, "Failed to logout");
    }
    $res->sendJson([ "ok" => true ]);
});

$server->respond();
?>