<?php
require_once '../../bootstrap.php';

$server = new ApiServer();

$server->addEndpoint(HTTPMethod::GET, function() {
    $id = Request::requireID('id');

    $file = UploadFile::fromId($id);
    if ($file === false) {
        Response::dieWithCode(HTTPCode::NotFound);
    }
    Response::sendFile($file);
});

$server->respond();
?>