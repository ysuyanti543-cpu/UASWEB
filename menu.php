<?php
require_once '../../../koneksi/connection.php';

$token = $_COOKIE['auth_token'] ?? '';
$tokenHash = hash('sha256', $token);

$stmt = $database_connection->prepare(
    "SELECT * FROM data_pendaftar WHERE token=?"
);
$stmt->execute([$tokenHash]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Penjualan Tas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: #f8f9fa;
        }
        .navbar-brand {
            font-weight: 700;
        }
        .dashboard-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        .dashboard-card:hover {
            transform: translateY(-5px);
        }
        .welcome-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
        }
        .btn-dashboard {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 25px;
            padding: 12px 30px;
            font-weight: 600;
        }
        .btn-dashboard:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-shopping-bag me-2 text-primary"></i>Penjualan Tas
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="menu.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="katalog.php">Katalog Tas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <!-- Welcome Section -->
        <div class="welcome-section">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1>Selamat Datang, <?= $user['nama_depan'] ?>!</h1>
                    <p class="mb-0">Selamat berbelanja di sistem penjualan tas kami. Temukan tas impian Anda dengan mudah.</p>
                </div>
                <div class="col-md-4 text-center">
                    <i class="fas fa-shopping-bag fa-5x opacity-50"></i>
                </div>
            </div>
        </div>

        <!-- Dashboard Cards -->
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card dashboard-card h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-store fa-3x text-primary mb-3"></i>
                        <h5 class="card-title">Katalog Tas</h5>
                        <p class="card-text">Jelajahi berbagai koleksi tas kami dengan gambar dan detail lengkap.</p>
                        <a href="katalog.php" class="btn btn-dashboard">
                            <i class="fas fa-eye me-2"></i>Lihat Katalog
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card dashboard-card h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-user-edit fa-3x text-success mb-3"></i>
                        <h5 class="card-title">Edit Profil</h5>
                        <p class="card-text">Perbarui informasi pribadi dan pengaturan akun Anda.</p>
                        <a href="edit.php" class="btn btn-dashboard">
                            <i class="fas fa-edit me-2"></i>Edit Profil
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card dashboard-card h-100">
                    <div class="card-body text-center">
                        <i class="fas fa-shopping-cart fa-3x text-info mb-3"></i>
                        <h5 class="card-title">Riwayat Pembelian</h5>
                        <p class="card-text">Lihat riwayat pembelian dan status pesanan Anda.</p>
                        <a href="Riwayat.php" class="btn btn-dashboard">
                        <i class="fas fa-history me-2"></i>Lihat Riwayat
                                </a>
                    </div>
                </div>
                
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="row mt-4">
            <div class="col-md-3">
                <div class="card dashboard-card text-center">
                    <div class="card-body">
                        <h3 class="text-primary">50+</h3>
                        <p class="mb-0">Tas Tersedia</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card dashboard-card text-center">
                    <div class="card-body">
                        <h3 class="text-success">100+</h3>
                        <p class="mb-0">Pelanggan</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card dashboard-card text-center">
                    <div class="card-body">
                        <h3 class="text-info">500+</h3>
                        <p class="mb-0">Transaksi</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card dashboard-card text-center">
                    <div class="card-body">
                        <h3 class="text-warning">4.8</h3>
                        <p class="mb-0">Rating</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
