<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require __DIR__ . '/../../../koneksi/connection.php';

/* ===============================
   JIKA REQUEST DARI FETCH (POST)
================================ */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');

    $data = json_decode(file_get_contents("php://input"), true);

    if (!$data) {
        echo json_encode([
            'success' => false,
            'message' => 'Data tidak valid'
        ]);
        exit;
    }

    $nama     = trim($data['nama_depan'] ?? '');
    $email    = trim($data['email'] ?? '');
    $username = trim($data['username'] ?? '');
    $password = $data['password'] ?? '';

    if ($nama === '' || $email === '' || $username === '' || $password === '') {
        echo json_encode([
            'success' => false,
            'message' => 'Semua field wajib diisi'
        ]);
        exit;
    }

    if (strlen($password) < 6) {
        echo json_encode([
            'success' => false,
            'message' => 'Password minimal 6 karakter'
        ]);
        exit;
    }

    $cek = $database_connection->prepare(
        "SELECT id FROM data_pendaftar WHERE username = ? OR email = ?"
    );
    $cek->execute([$username, $email]);

    if ($cek->rowCount() > 0) {
        echo json_encode([
            'success' => false,
            'message' => 'Username atau email sudah terdaftar'
        ]);
        exit;
    }

    $hash = password_hash($password, PASSWORD_DEFAULT);

    $simpan = $database_connection->prepare(
        "INSERT INTO data_pendaftar (nama_depan, email, username, password)
         VALUES (?, ?, ?, ?)"
    );
    $simpan->execute([$nama, $email, $username, $hash]);

    echo json_encode([
        'success' => true,
        'message' => 'Registrasi berhasil, silakan login'
    ]);
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Daftar Akun</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

<style>
body {
    background: linear-gradient(135deg, #667eea, #764ba2);
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: 'Poppins', sans-serif;
}
.card {
    border-radius: 16px;
    max-width: 420px;
    width: 100%;
    box-shadow: 0 15px 30px rgba(0,0,0,.15);
}
.form-control {
    padding: 14px;
    border-radius: 10px;
}
.btn-main {
    background: #667eea;
    color: #fff;
    border-radius: 10px;
    padding: 14px;
    font-weight: 600;
}
.btn-main:hover {
    background: #5a6fdc;
}
.small-text {
    font-size: 14px;
    color: #666;
}
</style>
</head>

<body>

<div class="card p-4">
    <div class="text-center mb-4">
        <i class="fas fa-user-plus fa-3x text-primary mb-2"></i>
        <h4 class="fw-bold">Daftar Akun</h4>
        <p class="small-text">Buat akun baru</p>
    </div>

    <div id="alertBox"></div>

    <form id="registerForm">
        <input type="text" id="nama_depan" class="form-control mb-3" placeholder="Nama Lengkap" required>
        <input type="email" id="email" class="form-control mb-3" placeholder="Email" required>
        <input type="text" id="username" class="form-control mb-3" placeholder="Username" required>
        <input type="password" id="password" class="form-control mb-3" placeholder="Password" required>
        <input type="password" id="confirm_password" class="form-control mb-4" placeholder="Ulangi Password" required>

       <button type="submit" class="btn btn-main w-100">
    <i class="fas fa-user-plus me-2"></i> Daftar
</button>

    </form>

    <div class="text-center mt-3">
        <span class="small-text">Sudah punya akun?</span>
        <a href="login.php" class="fw-semibold text-decoration-none"> Masuk</a>
    </div>
</div>

<script>
const form = document.getElementById('registerForm');
const alertBox = document.getElementById('alertBox');

form.addEventListener('submit', async e => {
    e.preventDefault();

    const password = passwordEl.value;
    if (password !== confirmEl.value) {
        showAlert('Password tidak sama', 'danger');
        return;
    }

    const res = await fetch('registrasi.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({
            nama_depan: namaEl.value,
            email: emailEl.value,
            username: userEl.value,
            password: password
        })
    });

    const json = await res.json();
    showAlert(json.message, json.success ? 'success' : 'danger');

    if (json.success) {
        setTimeout(() => {
            window.location.href = 'login.php';
        }, 1500);
    }
});

const namaEl = document.getElementById('nama_depan');
const emailEl = document.getElementById('email');
const userEl = document.getElementById('username');
const passwordEl = document.getElementById('password');
const confirmEl = document.getElementById('confirm_password');

function showAlert(msg, type) {
    alertBox.innerHTML = `<div class="alert alert-${type}">${msg}</div>`;
}
</script>

</body>
</html>
