<?php
require_once '../../../koneksi/connection.php';

/* ===== CEK LOGIN ===== */
$token = $_COOKIE['auth_token'] ?? '';
if (!$token) {
    header("Location: login.php");
    exit;
}

$tokenHash = hash('sha256', $token);
$cek = $database_connection->prepare(
    "SELECT id FROM data_pendaftar WHERE token=? LIMIT 1"
);
$cek->execute([$tokenHash]);

if (!$cek->fetch()) {
    header("Location: login.php");
    exit;
}

/* ===== CEK ID ===== */
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: halaman.php");
    exit;
}

$id = (int)$_GET['id'];
$error = "";
$success = "";

/* ===== AMBIL DATA TAS ===== */
$stmt = $database_connection->prepare(
    "SELECT * FROM tas WHERE id=? LIMIT 1"
);
$stmt->execute([$id]);
$tas = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$tas) {
    header("Location: halaman.php");
    exit;
}

/* NOTE: Update is handled by update_tas.php (form below posts to that handler) */
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Tas</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap (opsional, tapi disarankan) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- CSS -->
    <style>
        /* RESET */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* BODY */
        body {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea, #764ba2);
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* CONTAINER */
        .edit-container {
            width: 100%;
            max-width: 600px;
            padding: 15px;
        }

        /* CARD */
        .card {
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.25);
            overflow: hidden;
        }

        /* HEADER */
        .card-header {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: #fff;
            padding: 20px;
            text-align: center;
            font-size: 20px;
            font-weight: bold;
        }

        /* BODY */
        .card-body {
            padding: 25px;
        }

        /* INPUT */
        .form-control {
            width: 100%;
            padding: 10px 14px;
            border-radius: 10px;
            border: 1px solid #ddd;
            margin-top: 5px;
        }

        .form-control:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 2px rgba(102,126,234,0.2);
        }

        /* LABEL */
        label {
            font-weight: 600;
            margin-top: 10px;
            display: block;
        }

        /* BUTTON */
        .btn {
            padding: 10px 22px;
            border-radius: 25px;
            border: none;
            cursor: pointer;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: #fff;
        }

        .btn-primary:hover {
            opacity: 0.9;
        }

        .btn-secondary {
            background: #6c757d;
            color: #fff;
        }

        /* ALERT */
        .alert {
            padding: 12px;
            border-radius: 10px;
            margin-bottom: 15px;
        }

        .alert-success {
            background: #d1e7dd;
            color: #0f5132;
        }

        .alert-danger {
            background: #f8d7da;
            color: #842029;
        }
    </style>
</head>
<body>

<div class="edit-container">
    <div class="card">
        <div class="card-header">
            Edit Data Tas
        </div>

        <div class="card-body">

            <div class="alert alert-success">
                Data siap diedit
            </div>

            <form method="POST" action="update_tas.php" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= (int)$tas['id'] ?>">
                <label>Nama Tas</label>
                <input type="text" class="form-control" name="nama_tas" value="<?= htmlspecialchars($tas['nama_tas']) ?>">

                <label>Harga</label>
                <input type="number" class="form-control" name="harga" value="<?= (int)$tas['harga'] ?>">

                <label>Stok</label>
                <input type="number" class="form-control" name="stok" value="<?= (int)$tas['stok'] ?>">

                <label>Gambar Saat Ini</label>
                <?php if (!empty($tas['gambar'])): ?>
                    <?php $gpath = '../../../uploads/tas/' . htmlspecialchars($tas['gambar']); ?>
                    <div class="mb-2"><img src="<?= $gpath ?>" alt="<?= htmlspecialchars($tas['nama_tas']) ?>" style="max-width:150px;height:auto;border-radius:6px;"></div>
                <?php else: ?>
                    <div class="mb-2 text-muted">(Tidak ada gambar)</div>
                <?php endif; ?>

                <label>Ganti Gambar (jpg/png/gif)</label>
                <input type="file" name="gambar" accept="image/*" class="form-control">

                <label>Deskripsi</label>
                <textarea class="form-control" rows="3" name="deskripsi"><?= htmlspecialchars($tas['deskripsi']) ?></textarea>

                <div class="d-flex justify-content-between mt-4">
                    <a href="halaman.php" class="btn btn-secondary">Kembali</a>
                    <button class="btn btn-primary">Update</button>
                </div>
            </form>

        </div>
    </div>
</div>

</body>
</html>