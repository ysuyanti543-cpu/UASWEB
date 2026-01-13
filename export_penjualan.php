<?php
require_once '../koneksi/connection.php';

$type = $_GET['type'] ?? 'pdf'; // pdf or excel

if ($type === 'pdf') {
    exportPDF();
} elseif ($type === 'excel') {
    exportExcel();
} else {
    http_response_code(400);
    echo 'Invalid export type';
}

function exportPDF() {
    global $database_connection;
    
    try {
        $stmt = $database_connection->query("
            SELECT p.*, t.nama_tas, u.nama_depan, u.nama_belakang
            FROM penjualan p
            JOIN tas t ON p.tas_id = t.id
            JOIN data_pendaftar u ON p.user_id = u.id
            ORDER BY p.tanggal_penjualan DESC
        ");
        $penjualan = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Simple HTML to PDF (you can replace with FPDF if installed)
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="laporan_penjualan.pdf"');
        
        echo "<html><body>";
        echo "<h1>Laporan Penjualan Tas</h1>";
        echo "<table border='1' style='width:100%; border-collapse:collapse;'>";
        echo "<tr><th>ID</th><th>Tanggal</th><th>Pembeli</th><th>Tas</th><th>Jumlah</th><th>Total Harga</th></tr>";
        
        foreach ($penjualan as $p) {
            echo "<tr>";
            echo "<td>{$p['id']}</td>";
            echo "<td>" . date('d/m/Y', strtotime($p['tanggal_penjualan'])) . "</td>";
            echo "<td>{$p['nama_depan']} {$p['nama_belakang']}</td>";
            echo "<td>{$p['nama_tas']}</td>";
            echo "<td>{$p['jumlah']}</td>";
            echo "<td>Rp " . number_format($p['total_harga'], 0, ',', '.') . "</td>";
            echo "</tr>";
        }
        
        echo "</table>";
        echo "</body></html>";
        
    } catch (Exception $e) {
        http_response_code(500);
        echo 'Error generating PDF';
    }
}

function exportExcel() {
    global $database_connection;
    
    try {
        $stmt = $database_connection->query("
            SELECT p.*, t.nama_tas, u.nama_depan, u.nama_belakang
            FROM penjualan p
            JOIN tas t ON p.tas_id = t.id
            JOIN data_pendaftar u ON p.user_id = u.id
            ORDER BY p.tanggal_penjualan DESC
        ");
        $penjualan = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="laporan_penjualan.xls"');
        
        echo "ID\tTanggal\tPembeli\tTas\tJumlah\tTotal Harga\n";
        
        foreach ($penjualan as $p) {
            echo "{$p['id']}\t";
            echo date('d/m/Y', strtotime($p['tanggal_penjualan'])) . "\t";
            echo "{$p['nama_depan']} {$p['nama_belakang']}\t";
            echo "{$p['nama_tas']}\t";
            echo "{$p['jumlah']}\t";
            echo "Rp " . number_format($p['total_harga'], 0, ',', '.') . "\n";
        }
        
    } catch (Exception $e) {
        http_response_code(500);
        echo 'Error generating Excel';
    }
}
?>
