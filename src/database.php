<?php

declare(strict_types=1);

function open_database(string $mode):PDO{
    if(!in_array($mode, ['vulnerable', 'patched'], true)){
        throw new InvalidArgumentException('Mode aplikasi tidak dikenal.');
    }
    $dir = dirname(__DIR__).'/data';
    if(!is_dir($dir)){
        mkdir($dir, 0700, true);
    }
    $db = new PDO('sqlite:'.$dir.'/'.$mode.'.sqlite');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->exec('PRAGMA busy_timeout = 3000');
    $db->exec('CREATE TABLE IF NOT EXISTS users (id INTEGER PRIMARY KEY, username TEXT UNIQUE NOT NULL, email TEXT NOT NULL, password_hash TEXT NOT NULL, auth_version TEXT NOT NULL)');
    if((int) $db->query('SELECT COUNT(*) FROM users')->fetchColumn() === 0){
        $query = $db->prepare('INSERT INTO users (id, username, email, password_hash, auth_version) VALUES (?, ?, ?, ?, ?)');
        $db->beginTransaction();
        $query->execute([1, 'akun_a', 'a@lab.test', password_hash('BelajarA!2026', PASSWORD_DEFAULT), bin2hex(random_bytes(16))]);
        $query->execute([2, 'akun_b', 'b@lab.test', password_hash('BelajarB!2026', PASSWORD_DEFAULT), bin2hex(random_bytes(16))]);
        $db->commit();
    }
    return $db;
}