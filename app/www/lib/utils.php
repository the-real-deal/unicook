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
    return joinPath($_SERVER['DOCUMENT_ROOT'], $args);
}

// https://www.uuidgenerator.net/dev-corner/php
function uuidv4($data = null): string {
    // Generate 16 bytes (128 bits) of random data or use the data passed into the function.
    $data = $data ?? random_bytes(16);
    assert(strlen($data) == 16);

    // Set version to 0100
    $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
    // Set bits 6-7 to 10
    $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

    // Output the 36 character UUID.
    return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
}

?>