<?php
require_once '../../../koneksi/connection.php';

/* =========================
   ARRAY FOTO TAS (MANUAL)
   Tanpa ubah database
========================= */
$fotoTas = [
    'Tas Kerja Kulit'    => 'kulit.png',
    'Tas Olahraga Nike' => 'nike.png',
    'tas sekolah'       => 'tas.png',
    'Tas Sekolah Hitam' => '100.png',
    'Tas Travel Besar'  => 'travel.png',
];

/* =========================
   PROSES BELI
========================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['beli'])) {

    $tas_id = (int) $_POST['tas_id'];
    $harga  = (int) $_POST['harga'];
    $jumlah = (int) $_POST['jumlah'];

    if ($jumlah < 1) {
        header("Location: menu.php");
        exit;
    }

    // Pastikan user sudah login: ambil auth_token dari cookie dan cari user_id
    $token = $_COOKIE['auth_token'] ?? '';
    if (!$token) {
        header("Location: ../login.php");
        exit;
    }

    $tokenHash = hash('sha256', $token);
    $ustmt = $database_connection->prepare("SELECT id FROM data_pendaftar WHERE token = ? LIMIT 1");
    $ustmt->execute([$tokenHash]);
    $user = $ustmt->fetch(PDO::FETCH_ASSOC);
    if (!$user) {
        header("Location: ../login.php");
        exit;
    }
    $userId = (int) $user['id'];

    $total = $harga * $jumlah;

    $stmt = $database_connection->prepare(
        "INSERT INTO penjualan (tas_id, user_id, jumlah, total_harga)
         VALUES (?, ?, ?, ?)"
    );
    $stmt->execute([$tas_id, $userId, $jumlah, $total]);

    header("Location: riwayat.php");
    exit;
}

/* =========================
   DATA TAS
========================= */
$tas = $database_connection->query("
    SELECT 
        t1.id,
        t1.nama_tas,
        t1.harga,
        SUM(t2.stok) AS stok
    FROM tas t1
    JOIN tas t2 ON t1.nama_tas = t2.nama_tas
    WHERE t1.id = (
        SELECT MIN(id) 
        FROM tas 
        WHERE nama_tas = t1.nama_tas
    )
    GROUP BY t1.id, t1.nama_tas, t1.harga
    ORDER BY t1.nama_tas ASC
")->fetchAll(PDO::FETCH_ASSOC);
?>

<?php include '../header.php'; ?>

<style>
.product-card {
    border: 1px solid #dee2e6;
    border-radius: 12px;
    padding: 15px;
    text-align: center;
    height: 100%;
}
.product-card img {
    height: 140px;
    object-fit: cover;
    border-radius: 8px;
}
img.loading {
    filter: blur(8px);
    transition: filter .35s ease;
}
.product-name {
    font-weight: 600;
    font-size: 14px;
    min-height: 40px;
}
</style>

<div class="container mt-4">

    <div class="text-end mb-3">
        <a href="riwayat.php" class="btn btn-outline-primary btn-sm">
            📄 Lihat Riwayat Penjualan
        </a>
    </div>

    <div class="text-center mb-4">
        <img src="logo.png" width="120" class="mb-2" alt="Katalog">
        <h4>Katalog Tas</h4>
    </div>

    <div class="row">
        <?php foreach ($tas as $t): ?>

            <?php
            /* =========================
               LOGIKA GAMBAR DARI ARRAY
            ========================= */
            $namaTas = $t['nama_tas'];
            $imgFile = $fotoTas[$namaTas] ?? null;
            $fullPath = $imgFile ? __DIR__ . '/' . $imgFile : null;

            if ($fullPath && file_exists($fullPath)) {
                $gambar = $imgFile;
            } else {
                $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="400" height="300">
                            <rect width="100%" height="100%" fill="#eee"/>
                            <text x="50%" y="50%" dominant-baseline="middle"
                                  text-anchor="middle" fill="#aaa"
                                  font-size="20">No Image</text>
                        </svg>';
                $gambar = 'data:image/svg+xml;utf8,' . rawurlencode($svg);
            }
            ?>

            <div class="col-md-3 mb-3">
                <div class="product-card bg-white shadow-sm">

                    <img src="<?= $gambar ?>"
                         class="img-fluid mb-2 loading"
                         loading="lazy"
                         onload="this.classList.remove('loading')"
                         alt="<?= htmlspecialchars($namaTas) ?>">

                    <div class="product-name">
                        <?= htmlspecialchars($namaTas) ?>
                    </div>

                    <div class="text-danger fw-bold mt-1">
                        Rp <?= number_format($t['harga'], 0, ',', '.') ?>
                    </div>

                    <small>Stok: <?= (int) $t['stok'] ?></small>

                    <form method="POST" class="mt-2">
                        <input type="hidden" name="tas_id" value="<?= $t['id'] ?>">
                        <input type="hidden" name="harga" value="<?= $t['harga'] ?>">

                        <input type="number"
                               name="jumlah"
                               class="form-control form-control-sm mb-2"
                               min="1"
                               max="<?= (int) $t['stok'] ?>"
                               value="1"
                               required>

                        <button name="beli" class="btn btn-primary btn-sm w-100">
                            Beli
                        </button>
                    </form>

                </div>
            </div>

        <?php endforeach; ?>
    </div>
</div>

<?php include '../footer.php'; ?>
