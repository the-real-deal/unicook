<?php 
require_once 'bootstrap.php';
requireComponent(rootPath('layouts', 'PageLayout'));
requireComponent(rootPath('components', 'HTMLHeader'));
requireComponent(rootPath('components', 'TestForm'));

echo PageLayout(
    title: "Home", 
    children: [
        HTMLHeader('Test title', HeaderLevel::H2),
        TestForm(),
    ],
);
?>

