<?php 
require_once 'MainLayout.php';
require_once rootPath('components', 'HTMLHeader.php');
?>

<?= MainLayout(
    pageTitle: $props->pageTitle,
    children: [
        HTMLHeader('Navbar: '.$props->pageTitle),
        $props->page,
        HTMLHeader('Footer'),
    ] 
) ?>