<?php
// premium.php

// Start the session and check if the user is logged in.
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Go Premium - Stock App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <div class="container py-4">
        
        <header class="d-flex align-items-center mb-4 text-white">
            <a href="dashboard.php" class="btn data-card me-3"><i class="bi bi-arrow-left"></i></a>
            <h2 class="fw-bold mb-0">Go Premium</h2>
        </header>

        <div class="data-card p-4 p-md-5">
            <h3 class="fw-bold mb-3">Gain the edge you need to make well-informed decisions and invest like a pro.</h3>
            
            <ul class="list-unstyled mt-4">
                <li class="d-flex align-items-center mb-3">
                    <i class="bi bi-check-circle-fill text-price-up me-3 fs-4"></i>
                    <span>Exclusive articles with stock picks and investment ideas.</span>
                </li>
                <li class="d-flex align-items-center mb-3">
                    <i class="bi bi-check-circle-fill text-price-up me-3 fs-4"></i>
                    <span>Market moving calls from Wall Street analysts and strategists.</span>
                </li>
                <li class="d-flex align-items-center mb-3">
                    <i class="bi bi-check-circle-fill text-price-up me-3 fs-4"></i>
                    <span>Full-length, on demand interviews with CEOs & the biggest name in investing.</span>
                </li>
            </ul>

            <div class="mt-5">
                <a href="subscribe.php?plan=annual" class="btn btn-custom w-100 py-3 mb-3 fs-5">
                    Annual Plan - 299.99€
                </a>
                
                <a href="subscribe.php?plan=monthly" class="btn btn-custom w-100 py-3 fs-5">
                    Monthly Plan - 34.99€
                </a>
            </div>
        </div>

    </div>

    <nav class="navbar fixed-bottom bottom-nav">
        <div class="container-fluid d-flex justify-content-around">
            <a href="portfolio.php" class="nav-link text-center"><i class="bi bi-wallet-fill"></i><div class="small">Portfolio</div></a>
            <a href="dashboard.php" class="nav-link text-center"><i class="bi bi-newspaper"></i><div class="small">Overview</div></a>
            <a href="#" class="nav-link text-center"><i class="bi bi-graph-up"></i><div class="small">Markets</div></a>
            <a href="#" class="nav-link text-center"><i class="bi bi-search"></i><div class="small">Search</div></a>
            <a href="profile.php" class="nav-link text-center"><i class="bi bi-person-circle"></i><div class="small">Profile</div></a>
        </div>
    </nav>
    
    <div style="padding-bottom: 80px;"></div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>