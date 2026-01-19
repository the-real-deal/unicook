<?php
require_once "{$_SERVER["DOCUMENT_ROOT"]}/bootstrap.php";
require_once "lib/core/api.php";
require_once "lib/recipes.php";

$server = new ApiServer();

$server->addEndpoint(HTTPMethod::GET, function ($req, $res) {
    $recipeId = $req->expectScalar($res, "recipeId");
    $userId = $req->getScalar($res, "userId");
    
    $db = Database::connectDefault();
    try {
        $user = null;
        if ($userId === null) {
            $login = LoginSession::autoLogin($db);
            if ($login === false) {
                $res->dieWithError(HTTPCode::Unauthorized, "Not logged in");
            }
            $user = $login->user;
        } else {
            $user = User::fromId($db, $userId);
            if ($user === false) {
                $res->dieWithError(HTTPCode::NotFound, "User not found");
            }
        }
    
        $recipe = Recipe::fromId($db, $recipeId);
        if ($recipe === false) {
            $res->dieWithError(HTTPCode::NotFound, "Recipe not found");
        }

        $isSaved = $recipe->isSavedFrom($db, $user);
        $res->sendJSON([ "saved" => $isSaved ]);
    } catch (InvalidArgumentException $e) {
        $res->dieWithError(HTTPCode::BadRequest, $e);
    }
});

$server->respond();
?>
