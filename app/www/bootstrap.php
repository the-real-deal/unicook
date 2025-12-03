<?php
session_start();

define('PROJECT_ROOT', __DIR__);

function requireDir(string $dirPath, bool $appendRoot = true): void {
    $dirPath = $appendRoot ? PROJECT_ROOT . DIRECTORY_SEPARATOR . $dirPath : $dirPath;
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