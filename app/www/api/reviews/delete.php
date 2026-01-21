<?php
require_once "{$_SERVER["DOCUMENT_ROOT"]}/bootstrap.php";
require_once "lib/core/api.php";
require_once "lib/reviews.php";

$server = new ApiServer();

$server->addEndpoint(HTTPMethod::POST, function ($req, $res) {
    $reviewId = $req->expectScalar($res, "reviewId");
    
    $db = Database::connectDefault();
    try {
        $review = Review::fromId($db, $reviewId);
        if ($review === false) {
            $res->dieWithError(HTTPCode::NotFound, "Review not found");
        }

        $login = LoginSession::autoLogin($db);
        if ($login === false) {
            $res->dieWithError(HTTPCode::Unauthorized, "Not logged in");
        }
        if ($login->user->id !== $review->userId && !$login->user->isAdmin) {
            $res->dieWithError(HTTPCode::Forbidden, "Insufficient permissions");
        }
            
        $ok = $review->delete($db);
        if (!$ok) {
            $res->dieWithError(HTTPCode::InternalServerError, "Failed to delete review");
        }

        $res->sendJSON([ "ok" => true ]);
    } catch (InvalidArgumentException $e) {
        $res->dieWithError(HTTPCode::BadRequest, $e);
    }
});

$server->respond();
?>
