<?php
require_once "{$_SERVER["DOCUMENT_ROOT"]}/bootstrap.php";
require_once "lib/core/api.php";
require_once "lib/recipes.php";

$server = new ApiServer();

$server->addEndpoint(HTTPMethod::GET, function ($req, $res) {
    $tagId = $req->expectScalar($res, "tagId");
    
    $db = Database::connectDefault();
    try {
        $recipes = Recipe::getWithTag($db, $tagId);
        if ($recipes === false) {
            $res->dieWithError(HTTPCode::InternalServerError, "Failed to get recipes");
        }
        $res->sendJSON($recipes);
    } catch (InvalidArgumentException $e) {
        $res->dieWithError(HTTPCode::BadRequest, $e);
    }
});

$server->respond();
?>
