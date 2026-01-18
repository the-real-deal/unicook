<?php
require_once "{$_SERVER["DOCUMENT_ROOT"]}/bootstrap.php";
require_once "lib/core/api.php";
require_once "lib/recipes.php";
require_once "lib/reviews.php";

$server = new ApiServer();

$server->addEndpoint(HTTPMethod::POST, function ($req, $res) {
    $recipeId = $req->expectScalar($res, "recipeId");
    $rating = $req->expectScalar($res, "rating");
    $body = $req->expectScalar($res, "body");

    $db = Database::connectDefault();
    try {
        $rating = $req->validateInt($res, $rating, "Rating");

        $login = LoginSession::autoLogin($db);
        if ($login === false) {
            $res->dieWithError(HTTPCode::Unauthorized, "Not logged in");
        }
        $recipe = Recipe::fromId($db, $recipeId);
        if ($recipe === false) {
            $res->dieWithError(HTTPCode::NotFound, "Recipe not found");
        }

        $reviewId = Review::create(
            db: $db,
            user: $login->user,
            recipe: $recipe,
            rating: $rating,
            body: $body,
        );
        if ($reviewId === false) {
            $res->dieWithError(HTTPCode::InternalServerError, "Failed to create review");
        }
        $res->sendJSON([ "id" => $reviewId ]);
    } catch (InvalidArgumentException $e) {
        $res->dieWithError(HTTPCode::BadRequest, $e);
    }
});

$server->respond();
?>
