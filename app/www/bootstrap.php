<?php

define("SESSION_NAME", "unicook_session");

// start or resume session
session_name(SESSION_NAME);
// https://www.php.net/manual/en/session.security.ini.php
session_start([
    "cookie_lifetime" => 0,
    "use_cookies" => true,
    "use_only_cookies" => true,
    "use_strict_mode" => true,
    "cookie_httponly" => true,
    "cookie_secure" => false,
    "cookie_samesite" => "Strict",
    "use_trans_sid" => false,
    "cache_limiter" => "nocache",
]);

// allow includes with paths starting from project root
$includePathSplit = explode(PATH_SEPARATOR, get_include_path());
array_splice($includePathSplit, 1, 0, $_SERVER["DOCUMENT_ROOT"]); // insert after '.'
set_include_path(implode(PATH_SEPARATOR, $includePathSplit));

// set UTC as default timezone
date_default_timezone_set("UTC");

// debug function
function debug(mixed $value): mixed {
    echo "<pre>";
    var_dump($value);
    echo "</pre>";
    return $value;
}

?>
