<?php
require_once "{$_SERVER["DOCUMENT_ROOT"]}/bootstrap.php";
require_once "lib/core/api.php";
require_once "lib/stats.php";

$server = new ApiServer();

$server->addEndpoint(HTTPMethod::GET, function ($req, $res) {
    $db = Database::connectDefault();
    try {
        $users = Stats::getTotalUsers($db);
        if ($users === false) {
            $res->dieWithError(HTTPCode::InternalServerError, "Failed to get total users");
        }
        $rating = Stats::getAverageRating($db);
        if ($rating === false) {
            $res->dieWithError(HTTPCode::InternalServerError, "Failed to get rating");
        }
        $recipes = Stats::getTotalRecipes($db);
        if ($recipes === false) {
            $res->dieWithError(HTTPCode::InternalServerError, "Failed to get total recipes");
        }
        $res->sendJSON([ 
            "users" => $users, 
            "rating" => $rating, 
            "recipes" => $recipes,
            "universities" => 1,
        ]);
    } catch (InvalidArgumentException $e) {
        $res->dieWithError(HTTPCode::BadRequest, $e);
    }
});

$server->respond();
?>
