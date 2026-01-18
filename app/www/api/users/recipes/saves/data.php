<?php
require_once "{$_SERVER["DOCUMENT_ROOT"]}/bootstrap.php";
require_once "lib/core/api.php";
require_once "lib/users.php";

$server = new ApiServer();

$server->addEndpoint(HTTPMethod::GET, function ($req, $res) {
    $userId = $req->expectScalar($res, "userId");
    
    $db = Database::connectDefault();
    try {
        $user = User::fromId($db, $userId);
        if ($user === false) {
            $res->dieWithError(HTTPCode::NotFound, "User not found");
        }
        $recipes = $user->getSavedRecipes($db);
        if ($recipes === false) {
            $res->dieWithError(HTTPCode::InternalServerError, "Failed get user recipes");
        }
        $res->sendJSON($recipes);
    } catch (InvalidArgumentException $e) {
        $res->dieWithError(HTTPCode::BadRequest, $e);
    }
});

$server->respond();
?>
