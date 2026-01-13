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

$error = "";
$success = "";

/* Ambil data kategori */
$stmt = $database_connection->query("SELECT * FROM kategori_tas ORDER BY nama_kategori");
$dataKategori = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* Proses tambah tas dengan upload gambar */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once __DIR__ . '/../../helpers/image_upload.php';

    $nama_tas = $_POST['nama_tas'] ?? '';
    $harga = $_POST['harga'] ?? 0;
    $stok = $_POST['stok'] ?? 0;
    $kategori_id = $_POST['kategori_id'] ?? null;
    $deskripsi = $_POST['deskripsi'] ?? '';

    $gambarFile = null;
    if (!empty($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
        $saved = save_uploaded_image(
            $_FILES['gambar'],
            __DIR__ . '/../../../uploads/tas',
            1200,
            1200
        );
        if ($saved) {
            $gambarFile = $saved;
        }
    }

    $insert = $database_connection->prepare(
        "INSERT INTO tas (nama_tas, harga, stok, kategori_id, gambar, deskripsi) VALUES (?, ?, ?, ?, ?, ?)"
    );

    if ($insert->execute([$nama_tas, $harga, $stok, $kategori_id, $gambarFile, $deskripsi])) {
        $success = "Data tas berhasil ditambahkan";
    } else {
        $error = "Gagal menambahkan data tas";
    }
}
?>

<?php include '../header.php'; ?>

<style>
.add-container {
    max-width: 600px;
    margin: 60px auto;
}
.card {
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}
.card-header {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: #fff;
    border-radius: 15px 15px 0 0;
}
.btn-add {
    background: linear-gradient(135deg, #667eea, #764ba2);
    border: none;
}
.btn-add:hover {
    opacity: 0.9;
}
</style>

<div class="container add-container">
    <div class="card">
        <div class="card-header text-center">
            <h4><i class="fas fa-plus me-2"></i>Tambah Data Tas</h4>
        </div>

        <div class="card-body p-4">
            <?php if ($success): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle me-2"></i><?= $success ?>
                </div>
            <?php endif; ?>

            <?php if ($error): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i><?= $error ?>
                </div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label class="form-label">Nama Tas</label>
                    <input type="text" name="nama_tas" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Harga</label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" name="harga" class="form-control" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Stok</label>
                    <input type="number" name="stok" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Kategori</label>
                    <select name="kategori_id" class="form-control" required>
                        <option value="">Pilih Kategori</option>
                        <?php foreach ($dataKategori as $kategori): ?>
                            <option value="<?= $kategori['id'] ?>"><?= htmlspecialchars($kategori['nama_kategori']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Upload Gambar (jpg/png/gif)</label>
                    <input type="file" name="gambar" accept="image/*" class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="deskripsi" class="form-control" rows="3"></textarea>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="halaman.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Kembali
                    </a>
                    <button class="btn btn-primary btn-add">
                        <i class="fas fa-save me-2"></i>Tambah
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include '../footer.php'; ?>
