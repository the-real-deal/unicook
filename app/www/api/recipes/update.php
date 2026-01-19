<?php
require_once "{$_SERVER["DOCUMENT_ROOT"]}/bootstrap.php";
require_once "lib/core/api.php";
require_once "lib/tags.php";
require_once "lib/recipes.php";
require_once "lib/auth.php";

$server = new ApiServer();

$server->addEndpoint(HTTPMethod::POST, function ($req, $res) {
    $recipeId = $req->expectScalar($res, "recipeId");
    $title = $req->getScalar($res, "title");
    $description = $req->getScalar($res, "description");
    $imageFile = $req->getFile($res, "image");
    $tagIds = $req->getArray($res, "tagIds");
    $difficulty = $req->getScalar($res, "difficulty");
    $prepTime = $req->getScalar($res, "prepTime");
    $cost = $req->getScalar($res, "cost");
    $servings = $req->getScalar($res, "servings");
    $ingredientsQuantity = $req->getArray($res, "ingredientsQuantity");
    $ingredientsName = $req->getArray($res, "ingredientsName");
    $steps = $req->getArray($res, "steps");

    $db = Database::connectDefault();
    try {
        $login = LoginSession::autoLogin($db);
        if ($login === false) {
            $res->dieWithError(HTTPCode::Unauthorized, "Not logged in");
        }

        $recipe = Recipe::fromId($db, $recipeId);
        if ($recipe === false) {
            $res->dieWithError(HTTPCode::NotFound, "Recipe not found");
        }
        
        $user = $login->user;
        if ($user->id !== $recipe->userId && !$user->isAdmin) {
            $res->dieWithError(HTTPCode::Forbidden, "Permissions insufficient");
        }

        if ($tagIds !== null) {
            $tags = [];
            foreach ($tagIds as $tagId) {
                $tag = Tag::fromId($db, $tagId);
                if ($tag === false) {
                    $res->dieWithError(HTTPCode::NotFound, "Tag $tagId not found");
                }
                array_push($tags, $tag);
            }
        } else {
            $tags = null;
        }

        $difficulty = $difficulty === null ? null : $req->validateEnum($res, $difficulty, RecipeDifficulty::class, "Difficulty");
        $prepTime = $prepTime === null ? null : $req->validateInt($res, $prepTime, "Prep time");
        $cost = $cost === null ? null : $req->validateEnum($res, $cost, RecipeCost::class, "Cost");
        $servings = $servings === null ? null : $req->validateInt($res, $servings, "Servings");
        if ($ingredientsQuantity !== null || $ingredientsName !== null) {
            if (!is_array($ingredientsQuantity) || !is_array($ingredientsName)) {
                $res->dieWithError(HTTPCode::BadRequest, "Ingredients quantity and name must be both set or unset together");
            }
            if (count($ingredientsQuantity) !== count($ingredientsName)) {
                $res->dieWithError(HTTPCode::BadRequest, "Mismatched ingredients quantity and name counts");
            }
            $ingredients = array_map(fn ($name, $quantity) => [ 
                "name" => $name,
                "quantity" => $quantity, 
            ], $ingredientsName, $ingredientsQuantity);
        } else {
            $ingredients = null;
        }

        if ($imageFile !== null) {
            $image = UploadFile::uploadFileArray($imageFile, FileType::Image, self::IMAGES_UPLOAD_PATH);
            if ($image === false) {
                $res->dieWithError(HTTPCode::InternalServerError, "Failed to upload image");
            }
        } else {
            $image = null;
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
            $res->dieWithError(HTTPCode::InternalServerError, "Failed to create recipe");
        }
        $res->sendJSON([ "ok" => true ]);
    } catch (InvalidArgumentException $e) {
        $res->dieWithError(HTTPCode::BadRequest, $e);
    }
});

$server->respond();
?>
