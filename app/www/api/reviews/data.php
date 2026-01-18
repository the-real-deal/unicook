<?php
require_once "{$_SERVER["DOCUMENT_ROOT"]}/bootstrap.php";
require_once "lib/core/api.php";
require_once "lib/reviews.php";

$server = new ApiServer();

$server->addEndpoint(HTTPMethod::GET, function ($req, $res) {
    $reviewId = $req->expectScalar($res, "reviewId");
    
    $db = Database::connectDefault();
    try {
        $review = Review::fromId($db, $reviewId);
        if ($review === false) {
            $res->dieWithError(HTTPCode::NotFound, "Review not found");
        }
        $res->sendJSON($review);
    } catch (InvalidArgumentException $e) {
        $res->dieWithError(HTTPCode::BadRequest, $e);
    }
});

$server->respond();
?>
