<?php
session_start();

function requireDir(string $dirPath): void {
    // require_once recursively all PHP files in the given directory
    $files = scandir($dirPath);
    foreach ($files as $file) {
        if ($file === '.' || $file === '..') {
            continue;
        }
        $fullPath = $dirPath . DIRECTORY_SEPARATOR . $file;
        if (is_dir($fullPath)) {
            requireDir($fullPath);
        } elseif (is_file($fullPath) && pathinfo($fullPath, PATHINFO_EXTENSION) === 'php') {
            require_once $fullPath;
        }
    }
}

requireDir('core'); // core utilities
requireDir('lib'); // library code

?>