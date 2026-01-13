<?php
// Web-friendly logout: clear DB token, expire cookie, and redirect to login page
require __DIR__ . '/../../../koneksi/connection.php';

// If no cookie, just redirect
if (!isset($_COOKIE['auth_token'])) {
    header('Location: login.php');
    exit;
}

$token = $_COOKIE['auth_token'];
$tokenHash = hash('sha256', $token);

try {
    // Find user and clear token
    $stmt = $database_connection->prepare("SELECT id FROM data_pendaftar WHERE token = ? LIMIT 1");
    $stmt->execute([$tokenHash]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $upd = $database_connection->prepare("UPDATE data_pendaftar SET token = NULL WHERE id = ?");
        $upd->execute([(int)$user['id']]);
    }
} catch (Throwable $e) {
    // ignore DB errors for logout - still attempt to clear cookie
}

// Expire cookie
$is_https = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on';
setcookie('auth_token', '', [
    'expires' => time() - 3600,
    'path' => '/',
    'httponly' => true,
    'secure' => $is_https,
    'samesite' => 'Lax'
]);

// Redirect to login page
header('Location: login.php');
exit;

?>
