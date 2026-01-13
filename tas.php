<?php
require_once '../koneksi/connection.php';

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

/* =========================
   GET LIST TAS
========================= */
if ($method === 'GET') {
    $stmt = $database_connection->query("SELECT id, nama_tas, harga, stok FROM tas ORDER BY nama_tas ASC");
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'data' => $data
    ]);
    exit;
}

/* =========================
   TAMBAH STOK TAS (POST)
========================= */
if ($method === 'POST' && isset($_GET['action']) && $_GET['action'] === 'tambah_stok') {
    $token = $_COOKIE['auth_token'] ?? '';
    if ($token === '') {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'No auth cookie']);
        exit;
    }

    $tokenHash = hash('sha256', $token);
    $stmt = $database_connection->prepare(
        "SELECT id FROM data_pendaftar WHERE token = ? LIMIT 1"
    );
    $stmt->execute([$tokenHash]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Invalid token']);
        exit;
    }

    $userId = $user['id'];

    $input = json_decode(file_get_contents("php://input"), true);
    if (
        !$input ||
        !isset($input['tas_id']) ||
        !isset($input['tambah_stok'])
    ) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Input tidak valid']);
        exit;
    }

    $stmt = $database_connection->prepare(
        "UPDATE tas SET stok = stok + ? WHERE id = ?"
    );
    $stmt->execute([
        (int)$input['tambah_stok'],
        (int)$input['tas_id']
    ]);

    echo json_encode([
        'success' => true,
        'message' => 'Stok tas berhasil ditambahkan'
    ]);
    exit;
}
