<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

define("PROJECT_ROOT", __DIR__);

function listFilesRec(
    string $dirPath,
    ?string $extension,
    ?string $root = PROJECT_ROOT,
): array {
    $dirPath = ($root === null ? "" : $root . DIRECTORY_SEPARATOR) . $dirPath;
    // require_once recursively all PHP files in the given directory
    $files = scandir($dirPath);
    $result = [];
    foreach ($files as $file) {
        if ($file === "." || $file === "..") {
            continue;
        }
        $fullPath = $dirPath . DIRECTORY_SEPARATOR . $file;
        if (is_dir($fullPath)) {
            $result = array_merge(
                $result,
                listFilesRec($fullPath, $extension, null),
            );
        } elseif (is_file($fullPath)) {
            if (
                $extension === null ||
                pathinfo($fullPath, PATHINFO_EXTENSION) === $extension
            ) {
                array_push($result, $fullPath);
            }
        }
    }
    return $result;
}

function requireDir(string $dirPath, string $root = PROJECT_ROOT)
{
    $files = listFilesRec($dirPath, "php", $root);
    foreach ($files as $file) {
        require_once $file;
    }
}

requireDir("core"); // core utilities
requireDir("lib"); // library code
?>
