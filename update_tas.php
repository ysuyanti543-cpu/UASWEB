<?php
require '../../../koneksi/connection.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: halaman.php");
    exit;
}

require_once __DIR__ . '/../../helpers/image_upload.php';

$id        = (int) $_POST['id'];
$nama_tas  = $_POST['nama_tas'];
$harga     = $_POST['harga'];
$stok      = $_POST['stok'];

// Ambil gambar lama (jika ada) sebelum update
$oldStmt = $database_connection->prepare("SELECT gambar FROM tas WHERE id = ? LIMIT 1");
$oldStmt->execute([$id]);
$oldRow = $oldStmt->fetch(PDO::FETCH_ASSOC);
$oldGambar = $oldRow['gambar'] ?? null;

// Handle optional image upload
$gambarFile = $_POST['gambar'] ?? $oldGambar;
if (!empty($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
    $saved = save_uploaded_image($_FILES['gambar'], __DIR__ . '/../../../uploads/tas', 1200, 1200);
    if ($saved) {
        $gambarFile = $saved;
    }
}

$stmt = $database_connection->prepare(
    "UPDATE tas 
    SET nama_tas = ?, harga = ?, stok = ?, gambar = ?
    WHERE id = ?"
);

$stmt->execute([
    $nama_tas,
    $harga,
    $stok,
    $gambarFile,
    $id
]);

// Jika ada gambar baru yang berbeda dari gambar lama, hapus file lama (kecuali default.jpg/null)
if (!empty($gambarFile) && !empty($oldGambar) && $gambarFile !== $oldGambar) {
    $oldPath = __DIR__ . '/../../../uploads/tas/' . $oldGambar;
    if (is_file($oldPath) && strtolower($oldGambar) !== 'default.jpg') {
        @unlink($oldPath);
    }
}

header("Location: halaman.php?status=update");
exit;
