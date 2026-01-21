<?php
require_once "{$_SERVER["DOCUMENT_ROOT"]}/bootstrap.php";
require_once "lib/core/api.php";
require_once "lib/users.php";

$server = new ApiServer();

$server->addEndpoint(HTTPMethod::POST, function ($req, $res) {
    $userId = $req->expectScalar($res, "userId");
    
    $db = Database::connectDefault();
    try {
        $user = User::fromId($db, $userId);
        if ($user === false) {
            $res->dieWithError(HTTPCode::NotFound, "User not found");
        }

        $login = LoginSession::autoLogin($db);
        if ($login === false) {
            $res->dieWithError(HTTPCode::Unauthorized, "Not logged in");
        }
        if (!$login->user->isAdmin) {
            $res->dieWithError(HTTPCode::Forbidden, "Insufficient permissions");
        }
            
        $ok = $user->delete($db);
        if (!$ok) {
            $res->dieWithError(HTTPCode::InternalServerError, "Failed to delete user");
        }

        $res->sendJSON([ "ok" => true ]);
    } catch (InvalidArgumentException $e) {
        $res->dieWithError(HTTPCode::BadRequest, $e);
    }
});

$server->respond();
?>
