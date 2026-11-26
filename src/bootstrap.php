<?php

declare(strict_types=1);

ini_set('display_errors', '0');
set_exception_handler(static function(Throwable $e):void{
    error_log((string) $e);
    http_response_code(500);
    exit('Terjadi kesalahan server, periksa log PHP.');
});
require __DIR__.'/database.php';
$appMode = $appMode ?? 'vulnerable';
$db = open_database($appMode);
session_name('kemjar_'.$appMode);
session_start(['use_strict_mode' => true, 'cookie_httponly' => true, 'cookie_samesite' => 'Strict']);
header('Cache-Control: no-store');
$_SESSION['csrf'] ??= bin2hex(random_bytes(32));

function current_user():?array{
    global $db;
    $query = $db->prepare('SELECT * FROM users WHERE id = ?');
    $query->execute([$_SESSION['user_id'] ?? 0]);
    $user = $query->fetch(PDO::FETCH_ASSOC);
    if(!$user || !hash_equals($user['auth_version'], $_SESSION['auth_version'] ?? '')){
        unset($_SESSION['user_id'], $_SESSION['auth_version']);
        return null;
    }
    return $user;
}