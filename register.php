<?php
require_once '../koneksi/connection.php';
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

$method = $_SERVER['REQUEST_METHOD'];

if ($method !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

// Validasi input
if (!isset($data['nama_depan']) || !isset($data['email']) || !isset($data['username']) || !isset($data['password'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Semua field wajib diisi']);
    exit;
}

$nama_depan = trim($data['nama_depan']);
$nama_belakang = trim($data['nama_belakang'] ?? '');
$email = trim($data['email']);
$username = trim($data['username']);
$password = $data['password'];

if (empty($nama_depan) || empty($email) || empty($username) || empty($password)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Field tidak boleh kosong']);
    exit;
}

if (strlen($password) < 6) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Password minimal 6 karakter']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Format email tidak valid']);
    exit;
}

try {
    // Cek apakah username atau email sudah ada
    $stmt = $database_connection->prepare("SELECT id FROM data_pendaftar WHERE username = ? OR email = ? LIMIT 1");
    $stmt->execute([$username, $email]);
    $existing = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($existing) {
        http_response_code(409);
        echo json_encode(['success' => false, 'message' => 'Username atau email sudah terdaftar']);
        exit;
    }

    // Hash password (use password_hash for secure storage)
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert user baru
    $stmt = $database_connection->prepare("
        INSERT INTO data_pendaftar (nama_depan, nama_belakang, email, username, password, created_at)
        VALUES (?, ?, ?, ?, ?, NOW())
    ");

    $result = $stmt->execute([$nama_depan, $nama_belakang, $email, $username, $hashedPassword]);

    if ($result) {
        echo json_encode([
            'success' => true,
            'message' => 'Registrasi berhasil! Silakan login dengan akun Anda.'
        ]);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Gagal menyimpan data']);
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Terjadi kesalahan server',
        'error' => $e->getMessage()
    ]);
}
?>
