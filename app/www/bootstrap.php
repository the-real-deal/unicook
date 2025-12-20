<?php
define("PROJECT_ROOT", $_SERVER['DOCUMENT_ROOT']);

// allow includes with paths starting from project root
$includePathSplit = explode(PATH_SEPARATOR, get_include_path());
array_splice($includePathSplit, 1, 0, PROJECT_ROOT); // insert after '.'
set_include_path(implode(PATH_SEPARATOR, $includePathSplit));
?>
