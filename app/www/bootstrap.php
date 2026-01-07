<?php

// allow includes with paths starting from project root
$includePathSplit = explode(PATH_SEPARATOR, get_include_path());
array_splice($includePathSplit, 1, 0, $_SERVER['DOCUMENT_ROOT']); // insert after '.'
set_include_path(implode(PATH_SEPARATOR, $includePathSplit));

// set UTC as default timezone
date_default_timezone_set('UTC');

function debug(mixed ...$values) {
    echo "<pre>";
    foreach ($values as $val) {
        var_dump($val);
    }
    echo "</pre>";
}

?>
