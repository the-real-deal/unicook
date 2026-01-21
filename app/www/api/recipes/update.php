<?php
require_once "{$_SERVER["DOCUMENT_ROOT"]}/bootstrap.php";
require_once "lib/core/api.php";
require_once "lib/tags.php";
require_once "lib/recipes.php";
require_once "lib/auth.php";

$server = new ApiServer();

$server->addEndpoint(HTTPMethod::POST, function ($req, $res) {
    $recipeId = $req->expectScalar($res, "recipeId");
    $title = $req->expectScalar($res, "title", allowEmpty: true);
    $description = $req->expectScalar($res, "description", allowEmpty: true);
    $imageFile = $req->getFile("image");
    $tagIds = $req->getArray($res, "tagIds") ?? [];
    $difficulty = $req->expectScalar($res, "difficulty", allowEmpty: true);
    $prepTime = $req->expectScalar($res, "prepTime", allowEmpty: true);
    $cost = $req->expectScalar($res, "cost", allowEmpty: true);
    $servings = $req->expectScalar($res, "servings", allowEmpty: true);
    $ingredientsQuantity = $req->expectArray($res, "ingredientsQuantity");
    $ingredientsName = $req->expectArray($res, "ingredientsName");
    $steps = $req->expectArray($res, "steps");

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
        if ($login->user->id !== $recipe->userId && !$login->user->isAdmin) {
            $res->dieWithError(HTTPCode::Forbidden, "Insufficient permissions");
        }

        $tags = [];
        foreach ($tagIds as $tagId) {
            $tag = Tag::fromId($db, $tagId);
            if ($tag === false) {
                $res->dieWithError(HTTPCode::NotFound, "Tag $tagId not found");
            }
            array_push($tags, $tag);
        }

        $difficulty = $req->validateEnum($res, $difficulty, RecipeDifficulty::class, "Difficulty");
        $prepTime = $req->validateInt($res, $prepTime, "Prep time");
        $cost = $req->validateEnum($res, $cost, RecipeCost::class, "Cost");
        $servings = $req->validateInt($res, $servings, "Servings");
        
        if (count($ingredientsQuantity) !== count($ingredientsName)) {
            $res->dieWithError(HTTPCode::BadRequest, "Mismatched ingredients quantity and name counts");
        }
        $ingredients = array_map(fn ($name, $quantity) => [ 
            "name" => $name,
            "quantity" => $quantity, 
        ], $ingredientsName, $ingredientsQuantity);

        $image = null;
        if ($imageFile !== null) {
            $image = UploadFile::uploadFileArray($imageFile, FileType::Image, Recipe::IMAGES_UPLOAD_PATH);
            if ($image === false) {
                $res->dieWithError(HTTPCode::InternalServerError, "Failed to upload image");
            }
        }

        $ok = $recipe->update(
            db: $db,
            title: $title,
            description: $description,
            image: $image,
            tags: $tags,
            difficulty: $difficulty,
            prepTime: $prepTime,
            cost: $cost,
            servings: $servings,
            ingredients: $ingredients,
            steps: $steps,
        );
        if (!$ok) {
            if ($image !== null) {
                $image->delete();
            }
            $res->dieWithError(HTTPCode::InternalServerError, "Failed to update recipe");
        }
        $res->sendJSON([ "ok" => true ]);
    } catch (InvalidArgumentException $e) {
        $res->dieWithError(HTTPCode::BadRequest, $e);
    }
});

$server->respond();
?>
