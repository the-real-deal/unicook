<?php
require_once "{$_SERVER["DOCUMENT_ROOT"]}/bootstrap.php";
require_once "lib/core/api.php";
require_once "lib/recipes.php";

$server = new ApiServer();

$server->addEndpoint(HTTPMethod::POST, function ($req, $res) {
    $recipeId = $req->expectScalar($res, "recipeId");
    
    $db = Database::connectDefault();
    try {
        $recipe = Recipe::fromId($db, $recipeId);
        if ($recipe === false) {
            $res->dieWithError(HTTPCode::NotFound, "Recipe not found");
        }

        $login = LoginSession::autoLogin($db);
        if ($login === false) {
            $res->dieWithError(HTTPCode::Unauthorized, "Not logged in");
        }
        $user = $login->user;
        if ($user->id !== $recipe->userId && !$user->isAdmin) {
            $res->dieWithError(HTTPCode::Forbidden, "Permissions insufficient");
        }
            
        $ok = $recipe->delete($db);
        if (!$ok) {
            $res->dieWithError(HTTPCode::InternalServerError, "Failed to delete recipe");
        }

        $res->sendJSON([ "ok" => true ]);
    } catch (InvalidArgumentException $e) {
        $res->dieWithError(HTTPCode::BadRequest, $e);
    }
});

$server->respond();
?>
