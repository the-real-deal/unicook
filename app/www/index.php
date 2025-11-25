<?php 
require_once 'bootstrap.php';
require_once rootPath('layouts', 'PageLayout.php');
require_once rootPath('components', 'HTMLHeader.php');
?>

<?= PageLayout("Home", HTMLHeader('Test title', HeaderLevel::H2)) ?>
