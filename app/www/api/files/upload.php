<?php
require_once "{$_SERVER["DOCUMENT_ROOT"]}/bootstrap.php";
require_once "lib/core/api.php";
require_once "lib/core/files.php";

$server = new ApiServer();

$server->addEndpoint(HTTPMethod::POST, function ($req, $res) {
    $file = $req->expectFile($res, "file");
    $type = $req->expectParam($res, "type");
    $path = $req->expectParam($res, "path");

    try {
        $file = UploadFile::uploadFileArray(
            $file,
            FileType::from($type),
            $path
        );
        $res->sendJSON($file);
    } catch (UploadErrorException | BadFileException $e) {
        $res->dieWithError(HTTPCode::BadRequest, $e);
    }
});

$server->respond();
?>
