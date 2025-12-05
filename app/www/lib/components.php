<?php

function requireComponent(string ...$path): void {
    require_once joinPath($path, 'index.php');
}

function renderComponent(string $templateDir, array $props, string $templatePath = 'template.php') {
    $templatePath = joinPath($templateDir, $templatePath);
    $props = (object)$props; // make props accessible with arrow notation
    require $templatePath;
}

?>