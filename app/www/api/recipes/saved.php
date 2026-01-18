<?php
require_once "{$_SERVER["DOCUMENT_ROOT"]}/bootstrap.php";
require_once "lib/core/api.php";
require_once "lib/recipes.php";

$server = new ApiServer();

$server->addEndpoint(HTTPMethod::GET, function ($req, $res) {
    $recipeId = $req->expectScalar($res, "recipeId");
    $userId = $req->expectScalar($res, "userId");
    
    $db = Database::connectDefault();
    try {
        $recipe = Recipe::fromId($db, $recipeId);
        if ($recipe === false) {
            $res->dieWithError(HTTPCode::NotFound, "Recipe not found");
        }
        $isSaved = $recipe->isSavedFrom($db, $userId);
        $res->sendJSON([ "saved" => $isSaved ]);
    } catch (InvalidArgumentException $e) {
        $res->dieWithError(HTTPCode::BadRequest, $e);
    }
});

$server->respond();
?>
