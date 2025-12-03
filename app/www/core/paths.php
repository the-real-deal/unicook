<?php

function joinPath(array|string ...$args): string {
    $items = [];
    foreach ($args as $arg) {
        if (is_array($arg)) {
            array_push($items, joinPath(...$arg));
        } else {
            assert(is_string($arg));
            $arg = rtrim($arg, DIRECTORY_SEPARATOR);
            array_push($items, $arg);
        }
    }
    return implode(DIRECTORY_SEPARATOR, $items);
}

function rootPath(array|string ...$args): string {
    return joinPath(PROJECT_ROOT, ...$args);
}

?>