<?php 
requireComponent('layouts', 'MainLayout');
requireComponent('components', 'HTMLHeader');
?>

<?= MainLayout(
    pageTitle: $props->pageTitle,
    children: [
        HTMLHeader('Navbar: '.$props->pageTitle),
        $props->page,
        HTMLHeader('Footer'),
    ] 
) ?>