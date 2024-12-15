<?php

date_default_timezone_set('Asia/Jakarta'); // Ganti dengan zona waktu Anda
ini_set('session.use_only_cookies', 1);
ini_set('session.use_strict_mode', 1);

session_set_cookie_params([
    'lifetime' => 1800, // 3 minutes
    'path' => '/',
    'domain' => 'e-commerce.test',
    'secure' => true,
    'httponly' => true,
    'samesite' => 'Strict' // 'Strict' or 'Lax'
]);

session_start();
if (isset($_SESSION['user_id'])) {
    if (!isset($_SESSION['last_regeneration_time'])) {
        regenerate_session_id_loggedIn();
    } else {
        $last_regeneration_time = $_SESSION['last_regeneration_time'];
        $current_time = time();
        if ($current_time - $last_regeneration_time >= 1800) {
            regenerate_session_id_loggedIn();
        }
    }
} else {
    if (!isset($_SESSION['last_regeneration_time'])) {
        regenerate_session_id();
    } else {
        $last_regeneration_time = $_SESSION['last_regeneration_time'];
        $current_time = time();
        if ($current_time - $last_regeneration_time >= 1800) {
            regenerate_session_id();
        }
    }
}

function regenerate_session_id_loggedIn()
{
    session_regenerate_id(true);
    $userId = $_SESSION['user_id'];
    $newSessionId = session_create_id(true);
    $sessionId = "{$newSessionId}-{$userId}";
    session_id($sessionId);
    $_SESSION['last_regeneration_time'] = time();
    ;
}

function regenerate_session_id()
{
    session_regenerate_id(true);
    $_SESSION['last_regeneration_time'] = time();
    ;
}