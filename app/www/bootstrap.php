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

define("SESSION_NAME", "unicook_session");
// https://www.php.net/manual/en/session.security.ini.php
session_start([
    "session_name" => SESSION_NAME,
    "session.cookie_lifetime" => 0,
    "session.use_cookies" => true,
    "session.use_only_cookies" => true,
    "session.use_strict_mode" => true,
    "session.cookie_httponly" => true,
    "session.cookie_secure" => false,
    "session.cookie_samesite" => "Strict",
    "session.use_trans_sid" => false,
    "session.cache_limiter" => "nocache",
]);

?>
