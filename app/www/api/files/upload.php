<?php
require_once "{$_SERVER['DOCUMENT_ROOT']}/bootstrap.php";
require_once "lib/core/api.php";
require_once "lib/core/files.php";

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
