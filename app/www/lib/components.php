<?php

function renderComponent(string $__FILE__, array $props, string $templateExtension = 'template'): string {
    $extension = pathinfo($__FILE__, PATHINFO_EXTENSION);
    $filename = pathinfo($__FILE__, PATHINFO_FILENAME);
    $templateFile = $filename . '.' . $templateExtension . '.' . $extension;
    $templatePath = joinPath(dirname($__FILE__), $templateFile);
    
    // store page rendering in buffer
    // https://www.php.net/manual/en/function.ob-start.php
    ob_start();
    // create scope to prevent variables pollution
    (function() use($props, $templatePath) {
        // include template
        $props = (object)$props; // make props accessible with arrow notation
        require $templatePath;
    })();
    // get buffer and clean it
    // https://www.php.net/manual/en/function.ob-get-clean.php
    $template = ob_get_clean();
    assert($template !== false); // assert there are no errors
    return $template;
}

?>