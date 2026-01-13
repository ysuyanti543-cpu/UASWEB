<?php

require_once '../koneksi/connection.php';

$token = $_COOKIE['auth_token'] ?? '';

if ($token === '') {
    http_response_code(401);
    echo json_encode(["success" => false, "message" => "No auth cookie"]);
    exit;
}

try {
    $tokenHash = hash('sha256', $token);

    $stmt = $database_connection->prepare(
        "SELECT id, username, email, nama_depan, nama_belakang
         FROM data_pendaftar
         WHERE token = ? LIMIT 1"
    );
    $stmt->execute([$tokenHash]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        http_response_code(401);
        echo json_encode(["success" => false, "message" => "Invalid token"]);
        exit;
    }

    echo json_encode([
        "success" => true,
        "data" => [
            "id" => $user['id'],
            "username" => $user['username'],
            "email" => $user['email'],
            "nama_depan" => $user['nama_depan'],
            "nama_belakang" => $user['nama_belakang']
        ]
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "success" => false,
        "message" => "Server error",
        "error" => $e->getMessage()
    ]);
}
