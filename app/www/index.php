<?php 
require_once 'bootstrap.php';
requireComponent('layouts', 'PageLayout');
requireComponent('components', 'HTMLHeader');
?>

<?= PageLayout("Home", HTMLHeader('Test title', HeaderLevel::H2)) ?>
