<?php

declare(strict_types=1);

$appMode = 'patched';
require __DIR__.'/../../src/bootstrap.php';
header('Content-Type: application/json; charset=utf-8');

function respond(int $status, array $data):never{
    http_response_code($status);
    echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
}

$action = $_GET['action'] ?? '';
if($action === 'users'){
    if($_SERVER['REQUEST_METHOD'] !== 'GET'){
        header('Allow: GET');
        respond(405, ['error' => 'Gunakan GET.']);
    }
    // perbaikan 1, wajibin pengguna login dulu sebelum akses daftar anggota
    if(!current_user()){
        respond(401, ['error' => 'Silakan masuk terlebih dahulu.']);
    }
    $users = $db->query('SELECT id, username, email FROM users')->fetchAll(PDO::FETCH_ASSOC);
    respond(200, ['users' => $users]);
}
if($action === 'change-password'){
    if($_SERVER['REQUEST_METHOD'] !== 'POST'){
        header('Allow: POST');
        respond(405, ['error' => 'Gunakan POST.']);
    }
    $user = current_user();
    if(!$user){
        respond(401, ['error' => 'Silakan masuk terlebih dahulu.']);
    }
    $input = json_decode(file_get_contents('php://input') ?: '', true);
    if(!is_array($input)){
        $input = $_POST;
    }
    // perbaikan 2, wajibin token csrf, verifikasi sandi lama, pake id sesi server, sama rotasi sesi
    $token = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? $input['csrf'] ?? '';
    if(!is_string($token) || !hash_equals($_SESSION['csrf'], $token)){
        respond(403, ['error' => 'Token CSRF tidak valid, muat ulang halaman.']);
    }
    $currentPassword = $input['current_password'] ?? '';
    $newPassword = $input['new_password'] ?? '';
    if(!is_string($currentPassword) || $currentPassword === '' || !is_string($newPassword)){
        respond(400, ['error' => 'Kata sandi saat ini dan kata sandi baru wajib diisi.']);
    }
    if(!password_verify($currentPassword, $user['password_hash'])){
        respond(403, ['error' => 'Kata sandi saat ini salah.']);
    }
    if(strlen($newPassword) < 8){
        respond(400, ['error' => 'Kata sandi baru minimal 8 karakter.']);
    }
    $newHash = password_hash($newPassword, PASSWORD_DEFAULT);
    $authVersion = bin2hex(random_bytes(16));
    $query = $db->prepare('UPDATE users SET password_hash = ?, auth_version = ? WHERE id = ?');
    $query->execute([$newHash, $authVersion, $user['id']]);
    session_regenerate_id(true);
    $_SESSION['auth_version'] = $authVersion;
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
    respond(200, ['message' => 'Kata sandi berhasil diperbarui.', 'csrf' => $_SESSION['csrf']]);
}
respond(404, ['error' => 'Endpoint tidak ditemukan.']);