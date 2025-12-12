<?php
require_once "../../bootstrap.php";

$server = new ApiServer();

$server->addEndpoint(HTTPMethod::POST, function ($req, $res) {
    try {
        $file = UploadFile::uploadFileArray(
            $_FILES["fileToUpload"],
            FileType::Image,
            "testupload"
        );
        $res->sendJSON($file);
    } catch (UploadErrorException | BadFileException $e) {
        $res->dieWithError(HTTPCode::BadRequest, $e);
    }
});

$server->respond();
?>
