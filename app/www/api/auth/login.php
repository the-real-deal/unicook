<?php
require_once "{$_SERVER["DOCUMENT_ROOT"]}/bootstrap.php";
require_once "lib/auth.php";

$server = new ApiServer();

$server->addEndpoint(HTTPMethod::POST, function ($req, $res) {
    $email = $req->expectParam($res, "email");
    $password = $req->expectParam($res, "password");
    
    $db = Database::connectDefault();
    $login = LoginSession::login($db, $email, $password);
    if ($login === false) {
        $res->dieWithError(HTTPCode::Unauthorized, "Login failed");
    }

    $res->sendJSON([ "ok" => true ]);
});

$server->respond();
?>
