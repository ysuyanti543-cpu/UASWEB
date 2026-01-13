<?php
require '../../../koneksi/connection.php';

/* ===== CEK LOGIN ===== */
if (!isset($_COOKIE['auth_token'])) {
    header("Location: login.php");
    exit;
}

$tokenHash = hash('sha256', $_COOKIE['auth_token']);
$stmt = $database_connection->prepare(
    "SELECT id FROM data_pendaftar WHERE token = ?"
);
$stmt->execute([$tokenHash]);

if (!$stmt->fetch()) {
    header("Location: login.php");
    exit;
}

/* ===== BATASI JUMLAH TAS ===== */
$limit = 4; // tampilkan 8 tas saja
$stmt = $database_connection->prepare(
    "SELECT id, nama_tas, harga, stok, gambar 
     FROM tas 
     ORDER BY id DESC 
     LIMIT :limit"
);
$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
$stmt->execute();
$dataTas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Data Tas</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

<style>
body {
    background:#f4f6f9;
}
.navbar {
    background:linear-gradient(135deg,#667eea,#764ba2);
}
.card {
    border-radius:14px;
}
img.tas-img {
    width:60px;
    height:60px;
    object-fit:cover;
    border-radius:8px;
}
</style>
</head>

<body>

<!-- NAVBAR -->
<nav class="navbar navbar-dark mb-4">
    <div class="container">
        <span class="navbar-brand fw-bold">
            <i class="fas fa-bag-shopping me-2"></i>Penjualan Tas
        </span>
        <a href="logout.php" class="btn btn-light btn-sm">
            <i class="fas fa-sign-out-alt"></i> Logout
        </a>
    </div>
</nav>

<div class="container">

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="fw-bold">Data Tas</h4>
    <a href="tambah_tas.php" class="btn btn-primary btn-sm">
        <i class="fas fa-plus"></i> Tambah Tas
    </a>
</div>

<div class="card shadow-sm">
<div class="card-body">

<table class="table table-bordered table-striped align-middle text-center">
<thead class="table-dark">
<tr>
    <th>No</th>
    <th>Nama Tas</th>
    <th>Harga</th>
    <th>Stok</th>
    <th>Gambar</th>
    <th>Aksi</th>
</tr>
</thead>

<tbody>
<?php if (!$dataTas): ?>
<tr>
    <td colspan="6" class="text-muted">Data tas belum tersedia</td>
</tr>
<?php endif; ?>

<?php $no=1; foreach ($dataTas as $tas): ?>
<tr>
    <td><?= $no++ ?></td>
    <td class="text-start"><?= htmlspecialchars($tas['nama_tas']) ?></td>
    <td>Rp <?= number_format($tas['harga'],0,',','.') ?></td>
    <td><?= $tas['stok'] ?></td>
    <td>
        <?php if ($tas['gambar']): ?>
            <img src="<?= $tas['gambar'] ?>" class="tas-img">
        <?php else: ?>
            -
        <?php endif; ?>
    </td>
    <td>
        <a href="edit_tas.php?id=<?= $tas['id'] ?>" class="btn btn-warning btn-sm">
            <i class="fas fa-edit"></i>
        </a>
        <a href="hapus_tas.php?id=<?= $tas['id'] ?>"
           class="btn btn-danger btn-sm"
           onclick="return confirm('Hapus data tas ini?')">
            <i class="fas fa-trash"></i>
        </a>
    </td>
</tr>
<?php endforeach; ?>
</tbody>
</table>

</div>
</div>

</div>

</body>
</html>
