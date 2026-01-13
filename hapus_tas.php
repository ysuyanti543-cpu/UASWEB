<?php
require '../../../koneksi/connection.php';

if (!isset($_COOKIE['auth_token'])) {
    header("Location: login.php");
    exit;
}

$tokenHash = hash('sha256', $_COOKIE['auth_token']);
$stmt = $database_connection->prepare(
    "SELECT * FROM data_pendaftar WHERE token = ?"
);
$stmt->execute([$tokenHash]);
$user = $stmt->fetch();

if (!$user) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: halaman.php");
    exit;
}

$id = $_GET['id'];

// Ambil nama file gambar untuk dihapus juga
$imgStmt = $database_connection->prepare("SELECT gambar FROM tas WHERE id = ? LIMIT 1");
$imgStmt->execute([$id]);
$imgRow = $imgStmt->fetch(PDO::FETCH_ASSOC);
$imgFile = $imgRow['gambar'] ?? null;

$stmt = $database_connection->prepare("DELETE FROM tas WHERE id = ?");
$stmt->execute([$id]);

// Hapus file gambar dari filesystem jika ada dan bukan default
if (!empty($imgFile) && strtolower($imgFile) !== 'default.jpg') {
    $path = __DIR__ . '/../../../uploads/tas/' . $imgFile;
    if (is_file($path)) {
        @unlink($path);
    }
}

header("Location: halaman.php");
exit;
?>
