<?php
require_once "{$_SERVER["DOCUMENT_ROOT"]}/bootstrap.php";
require_once "lib/core/api.php";
require_once "lib/auth.php";
require_once "lib/users.php";

$server = new ApiServer();

$server->addEndpoint(HTTPMethod::POST, function ($req, $res) {
    $recipeId = $req->expectScalar($res, "recipeId");
    
    $db = Database::connectDefault();
    try {
        $login = LoginSession::autoLogin($db);
        if ($login === false) {
            $res->dieWithError(HTTPCode::Unauthorized, "Not logged in");
        }
        $ok = $login->user->unsaveRecipe($db, $recipeId);
        if (!$ok) {
            $res->dieWithError(HTTPCode::InternalServerError, "Cannot unsave recipe");
        }
        $res->sendJSON([ "ok" => true ]);
    } catch (InvalidArgumentException $e) {
        $res->dieWithError(HTTPCode::BadRequest, $e);
    }
});

$server->respond();
?>
