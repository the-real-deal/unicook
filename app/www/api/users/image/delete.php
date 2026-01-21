<?php
require_once "{$_SERVER["DOCUMENT_ROOT"]}/bootstrap.php";
require_once "lib/core/api.php";
require_once "lib/auth.php";
require_once "lib/users.php";

$server = new ApiServer();

$server->addEndpoint(HTTPMethod::POST, function ($req, $res) {
    $userId = $req->getScalar($res, "userId");

    $db = Database::connectDefault();
    try {
        $login = LoginSession::autoLogin($db);
        if ($login === false) {
            $res->dieWithError(HTTPCode::Unauthorized, "Not logged in");
        }
        
        $user = null;
        if ($userId === null) {
            $user = $login->user;
        } else {
            $user = User::fromId($db, $userId);
            if ($user === false) {
                $res->dieWithError(HTTPCode::NotFound, "User not found");
            }
            if ($user->id !== $login->user->id && !$login->user->isAdmin) {
                $res->dieWithError(HTTPCode::Forbidden, "Insufficient permissions");
            }
        }

        $ok = $user->deleteImage($db);
        if (!$ok) {
            $res->dieWithError(HTTPCode::InternalServerError, "Failed to delete image");
        }
        $res->sendJSON([ "ok" => true ]);
    } catch (InvalidArgumentException $e) {
        $res->dieWithError(HTTPCode::BadRequest, $e);
    }
});

$server->respond();
?>
