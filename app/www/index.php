<?php
// require_once "bootstrap.php";

$server = new ApiServer();

$server->addEndpoint(HTTPMethod::GET, function () {
    Response::redirect("/home/");
});

$server->respond();
?>
