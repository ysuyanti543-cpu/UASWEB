<?php
require '../../../koneksi/connection.php';

$mode = $_GET['mode'] ?? 'view';

/* ======================
   AMBIL DATA RIWAYAT (WAJIB DI ATAS)
====================== */
$data = $database_connection->query("
    SELECT 
        p.id,
        t.nama_tas,
        t.harga,
        p.jumlah,
        p.total_harga,
        p.tanggal_penjualan
    FROM penjualan p
    JOIN tas t ON p.tas_id = t.id
    ORDER BY p.tanggal_penjualan DESC
")->fetchAll(PDO::FETCH_ASSOC);

/* ======================
   EXPORT EXCEL
====================== */
if ($mode === 'excel') {
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=riwayat_penjualan.xls");

    echo "<table border='1'>
        <tr>
            <th>No</th>
            <th>Nama Tas</th>
            <th>Harga</th>
            <th>Jumlah</th>
            <th>Total</th>
            <th>Tanggal</th>
        </tr>";

    $no = 1;
    foreach ($data as $d) {
        echo "<tr>
            <td>{$no}</td>
            <td>{$d['nama_tas']}</td>
            <td>{$d['harga']}</td>
            <td>{$d['jumlah']}</td>
            <td>{$d['total_harga']}</td>
            <td>{$d['tanggal_penjualan']}</td>
        </tr>";
        $no++;
    }

    echo "</table>";
    exit;
}

/* ======================
   CETAK PDF
====================== */
if ($mode === 'pdf') {
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Cetak PDF</title>
<style>
table{width:100%;border-collapse:collapse}
th,td{border:1px solid #000;padding:6px;font-size:12px}
</style>
</head>
<body onload="window.print()">

<h3>Riwayat Penjualan Tas</h3>

<table>
<tr>
    <th>No</th>
    <th>Nama Tas</th>
    <th>Harga</th>
    <th>Jumlah</th>
    <th>Total</th>
    <th>Tanggal</th>
</tr>

<?php $no=1; foreach($data as $d): ?>
<tr>
    <td><?= $no++ ?></td>
    <td><?= $d['nama_tas'] ?></td>
    <td>Rp <?= number_format($d['harga'],0,',','.') ?></td>
    <td><?= $d['jumlah'] ?></td>
    <td>Rp <?= number_format($d['total_harga'],0,',','.') ?></td>
    <td><?= date('d-m-Y H:i', strtotime($d['tanggal_penjualan'])) ?></td>
</tr>
<?php endforeach; ?>

</table>

</body>
</html>
<?php
exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Riwayat Penjualan</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="container mt-4">

<h4>Riwayat Penjualan Tas</h4>

<div class="mb-3">
    <a href="?mode=pdf" class="btn btn-danger btn-sm">Cetak PDF</a>
    <a href="?mode=excel" class="btn btn-success btn-sm">Export Excel</a>
    <a href="katalog.php" class="btn btn-secondary btn-sm">← Kembali</a>
</div>

<table class="table table-bordered table-sm bg-white">
<thead class="table-light">
<tr>
    <th>No</th>
    <th>Nama Tas</th>
    <th>Harga</th>
    <th>Jumlah</th>
    <th>Total</th>
    <th>Tanggal</th>
</tr>
</thead>
<tbody>

<?php if ($data): $no=1; foreach($data as $d): ?>
<tr>
    <td><?= $no++ ?></td>
    <td><?= $d['nama_tas'] ?></td>
    <td>Rp <?= number_format($d['harga'],0,',','.') ?></td>
    <td><?= $d['jumlah'] ?></td>
    <td class="text-danger">Rp <?= number_format($d['total_harga'],0,',','.') ?></td>
    <td><?= date('d-m-Y H:i', strtotime($d['tanggal_penjualan'])) ?></td>
</tr>
<?php endforeach; else: ?>
<tr>
    <td colspan="6" class="text-center">Belum ada data</td>
</tr>
<?php endif; ?>

</tbody>
</table>

</body>
</html>
