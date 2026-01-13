<?php
require_once "{$_SERVER["DOCUMENT_ROOT"]}/bootstrap.php";
require_once "lib/core/api.php";

$server = new ApiServer();

$server->addEndpoint(HTTPMethod::GET, function ($req, $res) {
    $res->redirect("/home/");
});

$server->respond();
?>
