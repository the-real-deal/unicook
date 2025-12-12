<?php
require_once "../../bootstrap.php";

$server = new ApiServer();

$server->addEndpoint(HTTPMethod::GET, function () {
    $id = Request::parseID($_GET["id"]);
    if ($id === false) {
        Response::dieWithError(HTTPCode::BadRequest, "Invalid or missing id");
    }

    $file = UploadFile::fromId($id, "testupload");
    if ($file === false) {
        Response::dieWithError(HTTPCode::NotFound);
    }
    
    Response::sendJSON($file);
});

$server->respond();
?>
