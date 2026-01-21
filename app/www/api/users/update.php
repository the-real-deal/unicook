<?php
require_once "{$_SERVER["DOCUMENT_ROOT"]}/bootstrap.php";
require_once "lib/core/api.php";
require_once "lib/users.php";

$server = new ApiServer();

$server->addEndpoint(HTTPMethod::POST, function ($req, $res) {
    $userId = $req->expectScalar($res, "userId");
    $admin = $req->getScalar($res, "admin");
    
    $db = Database::connectDefault();
    try {
        $admin = $admin === null ? null : $req->validateBool($res, $admin, "Admin");

        $user = User::fromId($db, $userId);
        if ($user === false) {
            $res->dieWithError(HTTPCode::NotFound, "User not found");
        }

        $login = LoginSession::autoLogin($db);
        if ($login === false) {
            $res->dieWithError(HTTPCode::Unauthorized, "Not logged in");
        }
        if (
            ($admin !== null && !$login->user->isAdmin) ||
            ($login->user->id !== $userId && !$login->user->isAdmin)
        ) {
            $res->dieWithError(HTTPCode::Forbidden, "Insufficient permissions");
        }

        $ok = $user->update(
            db: $db,
            admin: $admin,
        );
        if (!$ok) {
            $res->dieWithError(HTTPCode::InternalServerError, "Failed to update user");
        }

        $res->sendJSON([ "ok" => true ]);
    } catch (InvalidArgumentException $e) {
        $res->dieWithError(HTTPCode::BadRequest, $e);
    }
});

$server->respond();
?>
