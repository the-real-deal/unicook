<?php

function includeComponent(string $path, string $filename = 'index.php'): callable  {
    $componentFile = joinPath(rootPath($path), $filename);
    return require $componentFile;
}

function renderComponent(string $__DIR__, array $props, string $templateFile = 'template.php'): string {
    $templatePath = joinPath($__DIR__, $templateFile);
    
    // store page rendering in buffer
    // https://www.php.net/manual/en/function.ob-start.php
    ob_start();
    // create scope to prevent variables pollution
    (function() use($props, $templatePath) {
        // make props visible to the template by converting them to variables
        // https://www.php.net/manual/en/function.extract.php
        extract($props);
        // include template
        require $templatePath;
    })();
    // get buffer and clean it
    // https://www.php.net/manual/en/function.ob-get-clean.php
    $template = ob_get_clean();
    assert($template !== false); // assert there are no errors
    return $template;
}
?>