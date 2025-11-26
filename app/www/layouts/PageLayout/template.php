<?php 
requireComponent(rootPath('layouts', 'MainLayout'));
requireComponent(rootPath('components', 'HTMLHeader'));
requireComponent(rootPath('components', 'ListView'));

echo MainLayout(
    title: $props->title,
    children: [
        HTMLHeader('Navbar: '.$props->title),
        ListView($props->children),
        HTMLHeader('Footer'),
    ]
);
?>