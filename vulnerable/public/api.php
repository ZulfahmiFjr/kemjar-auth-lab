<?php

declare(strict_types=1);

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
    // celah 1, API langsung baca data tanpa ngecek pengguna yg udah login
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
    $rawUserId = $input['user_id'] ?? null;
    $userId = is_int($rawUserId) || is_string($rawUserId) ? filter_var($rawUserId, FILTER_VALIDATE_INT) : false;
    $newPassword = $input['new_password'] ?? '';
    if($userId === false || $userId <= 0){
        respond(400, ['error' => 'ID pengguna tidak valid.']);
    }
    if(!is_string($newPassword) || strlen($newPassword) < 8){
        respond(400, ['error' => 'Kata sandi baru minimal 8 karakter.']);
    }
    // celah 2, ubah sandi langsung pake user_id dari input tanpa cek sandi lama sama otorisasi
    $query = $db->prepare('UPDATE users SET password_hash = ? WHERE id = ?');
    $query->execute([password_hash($newPassword, PASSWORD_DEFAULT), $userId]);
    if($query->rowCount() === 0){
        respond(404, ['error' => 'Pengguna tidak ditemukan.']);
    }
    respond(200, ['message' => 'Kata sandi berhasil diperbarui.']);
}
respond(404, ['error' => 'Endpoint tidak ditemukan.']);