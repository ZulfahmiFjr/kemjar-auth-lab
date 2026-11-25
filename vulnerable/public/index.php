<?php

declare(strict_types=1);

require __DIR__.'/../../src/bootstrap.php';
$user = current_user();
$error = '';
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $token = $_POST['csrf'] ?? '';
    if(!is_string($token) || !hash_equals($_SESSION['csrf'], $token)){
        http_response_code(403);
        exit('Token formulir tidak valid, muat ulang halaman.');
    }
    $action = $_POST['action'] ?? '';
    if($action === 'logout'){
        $_SESSION = [];
        session_destroy();
        header('Location: index.php', true, 303);
        exit;
    }
    if($action !== 'login'){
        http_response_code(400);
        exit('Aksi tidak dikenal.');
    }
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    if(!is_string($username) || !is_string($password)){
        http_response_code(400);
        exit('Username dan kata sandi harus berupa teks.');
    }
    $query = $db->prepare('SELECT * FROM users WHERE username = ?');
    $query->execute([$username]);
    $account = $query->fetch(PDO::FETCH_ASSOC);
    if($account && password_verify($password, $account['password_hash'])){
        session_regenerate_id(true);
        $_SESSION['user_id'] = $account['id'];
        $_SESSION['auth_version'] = $account['auth_version'];
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
        header('Location: index.php', true, 303);
        exit;
    }
    http_response_code(401);
    $error = 'Username atau kata sandi salah.';
}

function e(string $value):string{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Portal Anggota</title>
    <style>
        body { margin: 0; padding: 48px 20px; background: #f0f4fa; color: #172b46; font: 16px/1.6 system-ui, sans-serif; }
        main { max-width: 640px; margin: auto; padding: 28px; background: white; border: 1px solid #d7e0ed; border-radius: 12px; }
        h1 { margin-top: 0; }
        label { display: block; margin-top: 16px; }
        input { box-sizing: border-box; width: 100%; padding: 10px; font: inherit; border: 1px solid #8799b2; border-radius: 6px; }
        button { margin-top: 20px; padding: 10px 18px; background: #2357bf; color: white; border: 0; border-radius: 6px; font: inherit; cursor: pointer; }
        .error { color: #a02222; }
        pre { white-space: pre-wrap; overflow-wrap: anywhere; background: #f0f4fa; padding: 16px; }
    </style>
</head>
<body>
    <main>
        <h1>Portal Anggota</h1>
        <?php if($user): ?>
        <h2>Halo, <?= e($user['username']) ?></h2>
        <p>Email: <?= e($user['email']) ?></p>
        <h2>Daftar anggota</h2>
        <button type="button" id="load-users">Tampilkan anggota</button>
        <pre id="users" hidden></pre>
        <h2>Ubah kata sandi</h2>
        <form id="change-password-form">
            <input type="hidden" name="user_id" value="<?= e((string) $user['id']) ?>">
            <label for="new-password">Kata sandi baru</label>
            <input id="new-password" name="new_password" type="password" autocomplete="new-password" minlength="8" required>
            <button type="submit">Simpan kata sandi</button>
        </form>
        <p id="password-status" role="status" hidden></p>
        <form method="post" action="index.php">
            <input type="hidden" name="csrf" value="<?= e($_SESSION['csrf']) ?>">
            <input type="hidden" name="action" value="logout">
            <button type="submit">Keluar</button>
        </form>
        <?php else: ?>
        <p>Masuk menggunakan akun uji untuk membuka profil.</p>
        <?php if($error !== ''): ?>
        <p class="error" role="alert"><?= e($error) ?></p>
        <?php endif; ?>
        <form method="post" action="index.php">
            <input type="hidden" name="csrf" value="<?= e($_SESSION['csrf']) ?>">
            <input type="hidden" name="action" value="login">
            <label for="username">Username</label>
            <input id="username" name="username" autocomplete="username" required>
            <label for="password">Kata sandi</label>
            <input id="password" name="password" type="password" autocomplete="current-password" required>
            <button type="submit">Masuk</button>
        </form>
        <?php endif; ?>
    </main>
<script>
const loadUsers = document.querySelector('#load-users');
loadUsers?.addEventListener('click', async () => {
    const result = document.querySelector('#users');
    result.hidden = false;
    try {
        const response = await fetch('api.php?action=users');
        const data = await response.json();
        if (!response.ok) throw new Error(data.error || 'Permintaan gagal.');
        result.textContent = JSON.stringify(data.users, null, 2);
    } catch (error) {
        result.textContent = error.message;
    }
});
const passwordForm = document.querySelector('#change-password-form');
passwordForm?.addEventListener('submit', async (event) => {
    event.preventDefault();
    const status = document.querySelector('#password-status');
    const formData = new FormData(passwordForm);
    status.hidden = false;
    status.className = '';
    try {
        const response = await fetch('api.php?action=change-password', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                user_id: formData.get('user_id'),
                new_password: formData.get('new_password')
            })
        });
        const data = await response.json();
        if (!response.ok) throw new Error(data.error || 'Permintaan gagal.');
        status.textContent = data.message;
        passwordForm.reset();
    } catch (error) {
        status.className = 'error';
        status.textContent = error.message;
    }
});
</script>
</body>
</html>