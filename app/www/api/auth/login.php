<?php
require_once "{$_SERVER['DOCUMENT_ROOT']}/bootstrap.php";
require_once "lib/auth.php";

$server = new ApiServer();

$server->addEndpoint(HTTPMethod::POST, function ($req, $res) {
    $email = $req->parseStringNonEmpty($_POST["email"] ?? null);
    if ($email === false) {
        $res->dieWithError(HTTPCode::BadRequest, "Invalid or missing email");
    }
    $password = $req->parseStringNonEmpty($_POST["password"] ?? null);
    if ($password === false) {
        $res->dieWithError(HTTPCode::BadRequest, "Invalid or missing password");
    }
    
    $db = Database::connectDefault();
    $login = LoginSession::login($db, $email, $password);
    if ($login === false) {
        $res->dieWithError(HTTPCode::Unauthorized, "Login failed");
    }

    $res->sendJSON([ "ok" => true ]);
});

$server->respond();
?>
