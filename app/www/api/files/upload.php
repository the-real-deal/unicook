<?php
require_once "../../bootstrap.php";

$server = new ApiServer();

$server->addEndpoint(HTTPMethod::POST, function () {
    try {
        $file = UploadFile::uploadFileArray(
            $_FILES["fileToUpload"],
            FileType::Image,
            "testupload"
        );
        Response::sendJSON($file);
    } catch (UploadErrorException | BadFileException $e) {
        Response::dieWithError(HTTPCode::BadRequest, $e);
    }
});

$server->respond();
?>
