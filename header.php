<?php include 'security_headers.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Penjualan Tas</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body { background: #f5f6fa; }
        .product-card {
            border-radius: 12px;
            transition: .3s;
        }
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0,0,0,.1);
        }
        .product-img {
            height: 180px;
            object-fit: cover;
            border-radius: 12px 12px 0 0;
        }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand fw-bold" href="dashboard.php">👜 Penjualan Tas</a>
        <div class="collapse navbar-collapse">
            <?php
            // Compute base path up to (but not including) /api/
            $script = $_SERVER['SCRIPT_NAME'] ?? '';
            $pos = strpos($script, '/api/');
            if ($pos !== false) {
                $base = substr($script, 0, $pos);
            } else {
                // fallback to current directory's parent
                $base = rtrim(dirname($script), '\/');
            }

            // Build absolute URLs for main pages (works from any include path)
            $dashboardUrl = $base . '/api/dashboard.php';
            $katalogUrl   = $base . '/api/web/katalog.php';
            $logoutUrl    = $base . '/api/logout.php';
           

            // Determine current page for 'active' highlighting
            $current = strtolower($script);
            $activedashboard = (strpos($current, 'dashboard.php') !== false) ? 'active' : '';
            $activeKatalog   = (strpos($current, 'katalog.php') !== false) ? 'active' : '';
            ?>

            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link <?= $activedashboard ?>" href="<?= htmlspecialchars($dashboardUrl) ?>">dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $activeKatalog ?>" href="<?= htmlspecialchars($katalogUrl) ?>">Katalog</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-danger" href="<?= htmlspecialchars($logoutUrl) ?>">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
