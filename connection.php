<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = 'localhost';
$dbname = 'penjualantas';
$username = 'root';
$password = '';

try {
    $database_connection = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
} catch (PDOException $e) {

    if (strpos($e->getMessage(), 'Unknown database') !== false) {

        try {
            $pdo = new PDO("mysql:host=$host;charset=utf8mb4", $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Buat database
            $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname`");
            $pdo->exec("USE `$dbname`");

            // ================= TABEL =================
            $pdo->exec("\n                CREATE TABLE IF NOT EXISTS data_pendaftar (\n                    id INT AUTO_INCREMENT PRIMARY KEY,\n                    nama_depan VARCHAR(50) NOT NULL,\n                    nama_belakang VARCHAR(50),\n                    email VARCHAR(100) UNIQUE NOT NULL,\n                    username VARCHAR(50) UNIQUE NOT NULL,\n                    password VARCHAR(255) NOT NULL,\n                    token VARCHAR(255) DEFAULT NULL,\n                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP\n                )\n            ");

            $pdo->exec("\n                CREATE TABLE IF NOT EXISTS kategori_tas (\n                    id INT AUTO_INCREMENT PRIMARY KEY,\n                    nama_kategori VARCHAR(100) NOT NULL\n                )\n            ");

            $pdo->exec("\n                CREATE TABLE IF NOT EXISTS tas (\n                    id INT AUTO_INCREMENT PRIMARY KEY,\n                    nama_tas VARCHAR(100),\n                    deskripsi TEXT,\n                    harga DECIMAL(10,2),\n                    stok INT,\n                    kategori_id INT\n                )\n            ");

            // ================= DATA CONTOH =================
            $passwordAdmin = password_hash('admin123', PASSWORD_DEFAULT);

            $pdo->prepare("\n                INSERT INTO data_pendaftar (nama_depan,nama_belakang,email,username,password)\n                VALUES (?,?,?,?,?)\n            ")->execute([
                'Admin',
                'User',
                'admin@example.com',
                'admin',
                $passwordAdmin
            ]);

            $pdo->exec("\n                INSERT IGNORE INTO kategori_tas (nama_kategori)\n                VALUES ('Tas Sekolah'),('Tas Kerja'),('Tas Travel')\n            ");

            $pdo->exec("\n                INSERT IGNORE INTO tas (nama_tas,deskripsi,harga,stok,kategori_id)\n                VALUES\n                ('Tas Sekolah Hitam','Kuat & awet',150000,20,1),\n                ('Tas Kerja Kulit','Elegan',300000,10,2)\n            ");

            // Koneksi ulang
            $database_connection = new PDO(
                "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
                $username,
                $password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]
            );

        } catch (PDOException $err) {
            die("Setup database gagal: " . $err->getMessage());
        }

    } else {
        die("Koneksi gagal: " . $e->getMessage());
    }
}

?>
