<?php

define("SESSION_NAME", "unicook_session_id");

function secureSessionStart(bool $https = false) {
    // https://www.php.net/manual/en/session.security.ini.php
    session_start([
        "session_name" => SESSION_NAME,
        "session.cookie_lifetime" => 0,
        "session.use_cookies" => true,
        "session.use_only_cookies" => true,
        "session.use_strict_mode" => true,
        "session.cookie_httponly" => true,
        "session.cookie_secure" => $https,
        "session.cookie_samesite" => "Strict",
        "session.use_trans_sid" => false,
        "session.cache_limiter" => "nocache",
    ]);
}

?>