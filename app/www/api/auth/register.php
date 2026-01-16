<?php
require_once "{$_SERVER["DOCUMENT_ROOT"]}/bootstrap.php";
require_once "lib/core/api.php";
require_once "lib/auth.php";

$server = new ApiServer();

$server->addEndpoint(HTTPMethod::POST, function ($req, $res) {
    $username = $req->expectParam($res, "username");
    $email = $req->expectParam($res, "email");
    $password = $req->expectParam($res, "password");
    
    $db = Database::connectDefault();
    try {
        $login = LoginSession::register($db, $username, $email, $password);
        if ($login === false) {
            $res->dieWithError(HTTPCode::Unauthorized, "Registration failed");
        }
        $res->sendJSON([ "ok" => true ]);
    } catch (InvalidArgumentException $e) {
        $res->dieWithError(HTTPCode::BadRequest, $e);
    }
});

$server->respond();
?>
