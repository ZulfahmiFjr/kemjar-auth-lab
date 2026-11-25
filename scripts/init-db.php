<?php

declare(strict_types=1);

if(PHP_SAPI !== 'cli'){
    http_response_code(404);
    exit;
}

try{
    require __DIR__.'/../src/database.php';
    $db = open_database('vulnerable');
    $users = $db->query('SELECT id, username, email FROM users')->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE).PHP_EOL;
}catch(Throwable $e){
    error_log((string) $e);
    fwrite(STDERR, 'Database gagal disiapkan, periksa pesan error di terminal.'.PHP_EOL);
    exit(1);
}