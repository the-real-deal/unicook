<?php
require_once "../../bootstrap.php";

$server = new ApiServer();

$server->addEndpoint(HTTPMethod::POST, function () {
    try {
        $file = UploadFile::fromFileArray(
            $_FILES["fileToUpload"],
            FileType::Image,
        );
        Response::sendJSON($file);
    } catch (UploadErrorException | BadFileException $e) {
        Response::dieWithException($e, HTTPCode::BadRequest);
    }
});

$server->respond();
?>
