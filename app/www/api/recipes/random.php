<?php
require_once "{$_SERVER["DOCUMENT_ROOT"]}/bootstrap.php";
require_once "lib/core/api.php";
require_once "lib/recipes.php";

$server = new ApiServer();

$server->addEndpoint(HTTPMethod::GET, function ($req, $res) {
    $db = Database::connectDefault();
    
    $recipe = Recipe::getRandom($db);
    if ($recipe === false) {
        $res->dieWithError(HTTPCode::NotFound, "Recipe not found");
    }
    $res->sendJSON($recipe);
});

$server->respond();
?>
