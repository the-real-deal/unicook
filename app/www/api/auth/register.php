<?php
require_once "{$_SERVER["DOCUMENT_ROOT"]}/bootstrap.php";
require_once "lib/core/api.php";
require_once "lib/auth.php";

$server = new ApiServer();

$server->addEndpoint(HTTPMethod::POST, function ($req, $res) {
    $username = $req->expectScalar($res, "username");
    $email = $req->expectScalar($res, "email");
    $password = $req->expectScalar($res, "password");

    $db = Database::connectDefault();
    try {
        $authKey = LoginSession::register($db, $username, $email, $password);
        if ($authKey === false) {
            $res->dieWithError(HTTPCode::Unauthorized, "Registration failed");
        }
        $res->sendJSON([ LoginSession::AUTH_KEY_COOKIE_ATTR => $authKey ]);
    } catch (InvalidArgumentException $e) {
        $res->dieWithError(HTTPCode::BadRequest, $e);
    }
});

$server->respond();
?>
