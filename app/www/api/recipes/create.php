<?php
require_once "{$_SERVER["DOCUMENT_ROOT"]}/bootstrap.php";
require_once "lib/core/api.php";
require_once "lib/tags.php";
require_once "lib/recipes.php";
require_once "lib/auth.php";
require_once "lib/utils.php";

$server = new ApiServer();

$server->addEndpoint(HTTPMethod::POST, function ($req, $res) {
    $title = $req->expectScalar($res, "title");
    $description = $req->expectScalar($res, "description");
    $image = $req->expectFile($res, "image");
    $tagIds = $req->expectArray($res, "tagIds");
    $difficulty = $req->expectScalar($res, "difficulty");
    $prepTime = $req->expectScalar($res, "prepTime");
    $cost = $req->expectScalar($res, "cost");
    $servings = $req->expectScalar($res, "servings");
    $ingredientsQuantity = $req->expectArray($res, "ingredientsQuantity");
    $ingredientsName = $req->expectArray($res, "ingredientsName");
    $steps = $req->expectArray($res, "steps");

    $db = Database::connectDefault();
    try {
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

        $login = LoginSession::autoLogin($db);
        if ($login === false) {
            $res->dieWithError(HTTPCode::Unauthorized, "Not logged in");
        }

        $image = UploadFile::uploadFileArray($imageFile, FileType::Image, self::IMAGES_UPLOAD_PATH);
        if ($image === null) {
            $res->dieWithError(HTTPCode::InternalServerError, "Failed to upload image");
        }
        $recipeId = Recipe::create(
            db: $db,
            user: $login->user,
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
        if ($recipeId === false) {
            $image->delete();
            $res->dieWithError(HTTPCode::InternalServerError, "Failed to create recipe");
        }
        $res->sendJSON([ "id" => $recipeId ]);
    } catch (InvalidArgumentException $e) {
        $res->dieWithError(HTTPCode::BadRequest, $e);
    }
});

$server->respond();
?>
