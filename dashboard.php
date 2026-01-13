<?php
/* =========================
   STATIC DATA (NO DATABASE)
========================= */
$totalTas = 5;
$totalPenjualan = 10;
$totalPendapatan = 1250000;
$stokRendah = 2;

/* =========================
   DATA TAS (STATIC)
========================= */
$dataTas = [
    ['id' => 1, 'nama_tas' => 'Tas Kulit Hitam', 'harga' => 150000, 'stok' => 10],
    ['id' => 2, 'nama_tas' => 'Tas Travel Biru', 'harga' => 200000, 'stok' => 5],
    ['id' => 3, 'nama_tas' => 'Tas Nike Merah', 'harga' => 100000, 'stok' => 8],
    ['id' => 4, 'nama_tas' => 'Tas Kulit Coklat', 'harga' => 180000, 'stok' => 3],
    ['id' => 5, 'nama_tas' => 'Tas Travel Hijau', 'harga' => 220000, 'stok' => 2],
];

/* =========================
   DATA PENJUALAN (STATIC)
========================= */
$dataPenjualan = [
    ['id' => 1, 'tanggal_penjualan' => '2023-10-01', 'pembeli' => 'John Doe', 'nama_tas' => 'Tas Kulit Hitam', 'jumlah' => 1, 'total_harga' => 150000],
    ['id' => 2, 'tanggal_penjualan' => '2023-10-02', 'pembeli' => 'Jane Smith', 'nama_tas' => 'Tas Travel Biru', 'jumlah' => 2, 'total_harga' => 400000],
    ['id' => 3, 'tanggal_penjualan' => '2023-10-03', 'pembeli' => 'Bob Johnson', 'nama_tas' => 'Tas Nike Merah', 'jumlah' => 1, 'total_harga' => 100000],
    ['id' => 4, 'tanggal_penjualan' => '2023-10-04', 'pembeli' => 'Alice Brown', 'nama_tas' => 'Tas Kulit Coklat', 'jumlah' => 1, 'total_harga' => 180000],
    ['id' => 5, 'tanggal_penjualan' => '2023-10-05', 'pembeli' => 'Charlie Wilson', 'nama_tas' => 'Tas Travel Hijau', 'jumlah' => 1, 'total_harga' => 220000],
    ['id' => 6, 'tanggal_penjualan' => '2023-10-06', 'pembeli' => 'David Lee', 'nama_tas' => 'Tas Kulit Hitam', 'jumlah' => 1, 'total_harga' => 150000],
    ['id' => 7, 'tanggal_penjualan' => '2023-10-07', 'pembeli' => 'Eva Garcia', 'nama_tas' => 'Tas Travel Biru', 'jumlah' => 1, 'total_harga' => 200000],
    ['id' => 8, 'tanggal_penjualan' => '2023-10-08', 'pembeli' => 'Frank Miller', 'nama_tas' => 'Tas Nike Merah', 'jumlah' => 2, 'total_harga' => 200000],
    ['id' => 9, 'tanggal_penjualan' => '2023-10-09', 'pembeli' => 'Grace Taylor', 'nama_tas' => 'Tas Kulit Coklat', 'jumlah' => 1, 'total_harga' => 180000],
    ['id' => 10, 'tanggal_penjualan' => '2023-10-10', 'pembeli' => 'Henry Davis', 'nama_tas' => 'Tas Travel Hijau', 'jumlah' => 1, 'total_harga' => 220000],
];

include 'header.php';
?>

<div class="container mt-4">
    <h4 class="mb-3">Dashboard Penjualan Tas</h4>

    <!-- STATISTIK -->
    <table class="table table-bordered table-sm w-50 mb-4">
        <tr>
            <th>Total Tas</th>
            <td><?= $totalTas ?></td>
        </tr>
        <tr>
            <th>Total Penjualan</th>
            <td><?= $totalPenjualan ?></td>
        </tr>
        <tr>
            <th>Total Pendapatan</th>
            <td>Rp <?= number_format($totalPendapatan, 0, ',', '.') ?></td>
        </tr>
        <tr>
            <th>Stok Rendah</th>
            <td><?= $stokRendah ?></td>
        </tr>
    </table>

    <!-- DATA TAS -->
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h5>Data Tas</h5>
        <a href="create_tas.php" class="btn btn-sm btn-primary">Tambah Tas</a>
    </div>

    <table class="table table-bordered table-sm mb-4">
        <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>Nama Tas</th>
                <th>Harga</th>
                <th>Stok</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($dataTas)): ?>
                <tr>
                    <td colspan="4" class="text-center">Data tas kosong</td>
                </tr>
            <?php else: ?>
                <?php foreach ($dataTas as $t): ?>
                <tr>
                    <td><?= $t['id'] ?></td>
                    <td><?= htmlspecialchars($t['nama_tas']) ?></td>
                    <td>Rp <?= number_format($t['harga'], 0, ',', '.') ?></td>
                    <td><?= $t['stok'] ?></td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- DATA PENJUALAN -->
    <h5>Data Penjualan</h5>

    <table class="table table-bordered table-sm">
        <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>Tanggal</th>
                <th>Pembeli</th>
                <th>Tas</th>
                <th>Jumlah</th>
                <th>Total Harga</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($dataPenjualan)): ?>
                <tr>
                    <td colspan="6" class="text-center">Belum ada penjualan</td>
                </tr>
            <?php else: ?>
                <?php foreach ($dataPenjualan as $p): ?>
                <tr>
                    <td><?= $p['id'] ?></td>
                    <td><?= date('d-m-Y', strtotime($p['tanggal_penjualan'])) ?></td>
                    <td><?= htmlspecialchars($p['pembeli'] ?? 'Pengguna') ?></td>
                    <td><?= htmlspecialchars($p['nama_tas']) ?></td>
                    <td><?= $p['jumlah'] ?></td>
                    <td>Rp <?= number_format($p['total_harga'], 0, ',', '.') ?></td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include 'footer.php'; ?>
