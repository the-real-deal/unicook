<?php
require_once "{$_SERVER['DOCUMENT_ROOT']}/bootstrap.php";
require_once "lib/core/api.php";
require_once "lib/core/files.php";

$server = new ApiServer();

$server->addEndpoint(HTTPMethod::GET, function ($req, $res) {
    $id = $req->parseID($_GET["id"]);
    if ($id === false) {
        $res->dieWithError(HTTPCode::BadRequest, "Invalid or missing id");
    }

    $file = UploadFile::fromId($id, "testupload");
    if ($file === false) {
        $res->dieWithError(HTTPCode::NotFound);
    }
    
    $res->sendFile($file);
});

$server->respond();
?>
