<?php
require_once "{$_SERVER["DOCUMENT_ROOT"]}/bootstrap.php";
require_once "lib/core/api.php";
require_once "lib/tags.php";
require_once "lib/recipes.php";
require_once "lib/auth.php";

$server = new ApiServer();

$server->addEndpoint(HTTPMethod::GET, function ($req, $res) {
    $query = $req->getScalar($res, "query");
    $minPrepTime = $req->getScalar($res, "minPrepTime");
    $maxPrepTime = $req->getScalar($res, "maxPrepTime");
    $difficulty = $req->getScalar($res, "difficulty");
    $cost = $req->getScalar($res, "cost");
    $tagIds = $req->getArray($res, "tagIds");
    $from = $req->getScalar($res, "from");
    $n = $req->getScalar($res, "n");

    $db = Database::connectDefault();
    try {
        $minPrepTime = $minPrepTime === null ? null : $req->validateInt($res, $minPrepTime, "Min prep time");
        $maxPrepTime = $maxPrepTime === null ? null : $req->validateInt($res, $maxPrepTime, "Max prep time");
        $difficulty = $difficulty === null ? null : $req->validateEnum($res, $difficulty, RecipeDifficulty::class, "Difficulty");
        $cost = $cost === null ? null : $req->validateEnum($res, $cost, RecipeCost::class, "Cost");
        $tags = [];
        foreach (($tagIds ?? []) as $tagId) {
            $tag = Tag::fromId($db, $tagId);
            if ($tag === false) {
                $res->dieWithError(HTTPCode::NotFound, "Tag $tagId not found");
            }
            array_push($tags, $tag);
        }
        $from = $from === null ? null : $req->validateInt($res, $from, "From");
        $n = $n === null ? null : $req->validateInt($res, $n, "N");

        $recipes = Recipe::search(
            db: $db,
            queryString: $query,
            minPrepTime: $minPrepTime,
            maxPrepTime: $maxPrepTime,
            difficulty: $difficulty,
            cost: $cost,
            tags: $tags,
            from: $from,
            n: $n
        );
        if ($recipes === false) {
            $res->dieWithError(HTTPCode::InternalServerError, "Failed to search recipes");
        }
        $res->sendJSON($recipes);
    } catch (InvalidArgumentException $e) {
        $res->dieWithError(HTTPCode::BadRequest, $e);
    }
});

$server->respond();
?>
