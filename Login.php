<?php
// Error reporting - disable in production
if ($_SERVER['SERVER_NAME'] !== 'localhost' && $_SERVER['SERVER_NAME'] !== '127.0.0.1') {
    error_reporting(0);
    ini_set('display_errors', 0);
} else {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}

header('Content-Type: application/json');

require __DIR__ . '/../koneksi/connection.php'; // SESUAIKAN PATH

// Terima JSON atau form-data
$in = json_decode(file_get_contents("php://input"), true) ?? $_POST;

$u = trim($in['username'] ?? '');
$p = trim($in['password'] ?? '');

if ($u === '' || $p === '') {
    http_response_code(400);
    echo json_encode([
        "success" => false,
        "message" => "username & password wajib"
    ]);
    exit;
}

try {
    $stmt = $database_connection->prepare(
        "SELECT id, username, nama_depan, nama_belakang
         FROM data_pendaftar
         WHERE username = ? AND password = ?
         LIMIT 1"
    );

    $stmt = $database_connection->prepare(
        "SELECT id, username, nama_depan, nama_belakang, password
         FROM data_pendaftar
         WHERE username = ? LIMIT 1"
    );
    $stmt->execute([$u]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user || !password_verify($p, $user['password'])) {
        http_response_code(401);
        echo json_encode([
            "success" => false,
            "message" => "Username atau password salah"
        ]);
        exit;
    }

    if (!$user) {
        http_response_code(401);
        echo json_encode([
            "success" => false,
            "message" => "Username atau password salah"
        ]);
        exit;
    }

    // Buat token
    $token = bin2hex(random_bytes(32));
    $tokenHash = hash('sha256', $token);

    $upd = $database_connection->prepare(
        "UPDATE data_pendaftar SET token = ? WHERE id = ?"
    );
    $upd->execute([$tokenHash, (int)$user['id']]);

    // Secure cookie settings
    $is_https = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on';
    setcookie('auth_token', $token, [
        'expires'  => time() + (60 * 60 * 24 * 14),
        'path'     => '/',
        'httponly' => true,
        'secure'   => $is_https,  // Secure only on HTTPS
        'samesite' => 'Lax'
    ]);

    echo json_encode([
        "success" => true,
        "message" => "Login berhasil",
        "data" => [
            "id" => (int)$user['id'],
            "username" => $user['username'],
            "nama_depan" => $user['nama_depan'],
            "nama_belakang" => $user['nama_belakang']
        ]
    ]);

} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        "success" => false,
        "message" => "Server error",
        "error" => $e->getMessage() // boleh dihapus saat produksi
    ]);
}
