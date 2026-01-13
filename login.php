<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

require __DIR__ . '/../../../koneksi/connection.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = strtolower(trim($_POST['username']));
    $password = $_POST['password'];

    if ($username === '' || $password === '') {
        $error = 'Username dan password wajib diisi!';
    } else {
        $stmt = $database_connection->prepare(
            "SELECT id, password 
             FROM data_pendaftar 
             WHERE LOWER(username)=? OR LOWER(email)=?
             LIMIT 1"
        );
        $stmt->execute([$username, $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            // Buat token untuk cookie
            $token = bin2hex(random_bytes(32));
            $tokenHash = hash('sha256', $token);

            $upd = $database_connection->prepare(
                "UPDATE data_pendaftar SET token = ? WHERE id = ?"
            );
            $upd->execute([$tokenHash, (int)$user['id']]);

            // Set cookie
            $is_https = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on';
            setcookie('auth_token', $token, [
                'expires'  => time() + (60 * 60 * 24 * 14),
                'path'     => '/',
                'httponly' => true,
                'secure'   => $is_https,
                'samesite' => 'Lax'
            ]);

            $_SESSION['login'] = true;
            $_SESSION['user_id'] = $user['id'];

            header('Location: menu.php');
            exit;
        } else {
            $error = 'Username / Email atau password salah!';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Login | Penjualan Tas</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body{
    background:linear-gradient(135deg,#667eea,#764ba2);
    min-height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
}
.card{
    border-radius:15px;
    box-shadow:0 10px 30px rgba(0,0,0,.2);
}
</style>
</head>
<body>

<div class="container">
<div class="row justify-content-center">
<div class="col-md-4">
<div class="card">
<div class="card-body p-4">

<h4 class="text-center mb-4">Login Penjualan Tas</h4>

<?php if($error): ?>
<div class="alert alert-danger text-center"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<form method="post">
    <div class="mb-3">
        <label class="form-label">Username / Email</label>
        <input type="text" name="username" class="form-control" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control" required>
    </div>
    <button class="btn btn-primary w-100">Login</button>
</form>

<div class="text-center mt-3">
    <span class="text-muted">Belum punya akun?</span><br>
    <a href="registrasi.php" class="btn btn-outline-secondary btn-sm mt-2 w-100">
        Daftar Akun
    </a>
</div>

</div>
</div>
</div>
</div>
</div>

</body>
</html>
