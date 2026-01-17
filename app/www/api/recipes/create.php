<?php
require_once "{$_SERVER["DOCUMENT_ROOT"]}/bootstrap.php";
require_once "lib/core/api.php";
require_once "lib/recipes.php";
require_once "lib/auth.php";
require_once "lib/utils.php";

$server = new ApiServer();

$server->addEndpoint(HTTPMethod::POST, function ($req, $res) {
    $title = $req->expectScalar($res, "title");
    $description = $req->expectScalar($res, "description");
    $image = $req->expectFile($res, "image");
    $tags = $req->expectArray($res, "tags");
    $difficulty = $req->expectScalar($res, "difficulty");
    $prepTime = $req->expectScalar($res, "prepTime");
    $cost = $req->expectScalar($res, "cost");
    $servings = $req->expectScalar($res, "servings");
    $ingredientsQuantity = $req->expectArray($res, "ingredientsQuantity");
    $ingredientsName = $req->expectArray($res, "ingredientsName");
    $steps = $req->expectArray($res, "steps");

    $difficulty = $req->validateEnum($res, $difficulty, RecipeDifficulty::class, "Difficulty");
    $prepTime = $req->validateInt($res, $prepTime, "Prep time");
    $cost = $req->validateEnum($res, $cost, RecipeCost::class, "Cost");
    $servings = $req->validateInt($res, $servings, "Servings");
    if (count($ingredientsQuantity) !== count($ingredientsName)) {
        $res->dieWithError(HTTPCode::BadRequest, "Mismatched ingredients quantity and name counts");
    }
    $ingredients = array_map(fn ($quantity, $name) => [ $quantity, $name ], $ingredientsQuantity, $ingredientsName);

    $db = Database::connectDefault();
    try {
        $login = LoginSession::autoLogin($db);
        if ($login === false) {
            $res->dieWithError(HTTPCode::Unauthorized, "Not logged in");
        }
        $recipeId = Recipe::create(
            db: $db,
            userId: $login->user->id,
            title: $title,
            description: $description,
            imageFile: $image,
            tags: $tags,
            difficulty: $difficulty,
            prepTime: $prepTime,
            cost: $cost,
            servings: $servings,
            ingredients: $ingredients,
            steps: $steps,
        );
        if ($recipeId === false) {
            $res->dieWithError(HTTPCode::InternalServerError, "Failed to create recipe");
        }
        $res->sendJSON([ "id" => $recipeId ]);
    } catch (InvalidArgumentException $e) {
        $res->dieWithError(HTTPCode::BadRequest, $e);
    }
});

$server->respond();
?>
