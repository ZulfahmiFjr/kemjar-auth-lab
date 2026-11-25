<?php

declare(strict_types=1);

require __DIR__.'/../../src/bootstrap.php';
header('Content-Type: application/json; charset=utf-8');

function respond(int $status, array $data):never{
    http_response_code($status);
    echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
}

if(($_GET['action'] ?? '') !== 'users'){
    respond(404, ['error' => 'Endpoint tidak ditemukan.']);
}
if($_SERVER['REQUEST_METHOD'] !== 'GET'){
    header('Allow: GET');
    respond(405, ['error' => 'Gunakan GET.']);
}

// celah 1, API langsung baca data tanpa ngecek pengguna yg udah login
$users = $db->query('SELECT id, username, email FROM users')->fetchAll(PDO::FETCH_ASSOC);
respond(200, ['users' => $users]);