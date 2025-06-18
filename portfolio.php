<?php
// portfolio.php

// Step 1: Start the session and check if the user is logged in.
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

// Step 2: Sample data to simulate fetching from a database or API.
$username = explode('@', $_SESSION['email'])[0]; // Get username from email

$portfolioItems = [
    ['logoPath' => 'https://via.placeholder.com/40/000000/FFFFFF?text=A', 'symbol' => 'AAPL', 'companyName' => 'Apple Inc.', 'price' => '172.28', 'percentage' => '+1.29%', 'isPositive' => true],
    ['logoPath' => 'https://via.placeholder.com/40/EA4335/FFFFFF?text=G', 'symbol' => 'GOOGL', 'companyName' => 'Alphabet Inc.', 'price' => '139.74', 'percentage' => '+0.56%', 'isPositive' => true],
    ['logoPath' => 'https://via.placeholder.com/40/55ACEE/FFFFFF?text=T', 'symbol' => 'TSLA', 'companyName' => 'Tesla, Inc.', 'price' => '244.12', 'percentage' => '-0.89%', 'isPositive' => false],
    ['logoPath' => 'https://via.placeholder.com/40/3B5998/FFFFFF?text=M', 'symbol' => 'MSFT', 'companyName' => 'Microsoft Corp.', 'price' => '337.52', 'percentage' => '+0.21%', 'isPositive' => true],
];

$watchlistItems = [
    ['logoPath' => 'https://via.placeholder.com/50/E60023/FFFFFF?text=P', 'symbol' => 'PINS', 'companyName' => 'Pinterest, Inc.', 'price' => '34.50', 'percentage' => '+2.15%', 'isPositive' => true],
    ['logoPath' => 'https://via.placeholder.com/50/FF0000/FFFFFF?text=N', 'symbol' => 'NFLX', 'companyName' => 'Netflix, Inc.', 'price' => '410.17', 'percentage' => '-1.50%', 'isPositive' => false],
];

$trendingStocks = $portfolioItems; // For this example, trending stocks are the same as portfolio items.

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portfolio - Stock App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <div class="container py-4">

        <!-- Header Section -->
        <header class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold">Hi, <?= htmlspecialchars($username) ?></h2>
                <p class="text-muted">Welcome back to PulseTrade</p>
            </div>
            <a href="profile.php" class="text-white"><i class="bi bi-person-circle fs-3"></i></a>
        </header>

        <!-- Main Stat Cards -->
        <div class="row">
            <div class="col-md-6 mb-4">
                <div class="data-card p-4">
                    <p class="card-subtitle">Cash Balance</p>
                    <h3 class="fw-bold">€2,000.00</h3>
                </div>
            </div>
            <div class="col-md-6 mb-4">
                <div class="data-card p-4">
                    <p class="card-subtitle">Total Asset Value</p>
                    <h3 class="fw-bold">€18,976.00</h3>
                    <small class="text-price-up">4.79% (+0.50%) vs last week</small>
                </div>
            </div>
        </div>

        <!-- Portfolio Section -->
        <section class="mb-5">
            <h3 class="fw-bold mb-3">Portfolio</h3>
            <div class="row g-3">
                <?php foreach ($portfolioItems as $item): ?>
                    <div class="col-6 col-md-4 col-lg-3">
                        <a href="stock_details.php?symbol=<?= htmlspecialchars($item['symbol']) ?>" class="card-link">
                            <div class="data-card p-3 h-100">
                                <div class="d-flex align-items-center mb-2">
                                    <img src="<?= htmlspecialchars($item['logoPath']) ?>" alt="Logo" class="rounded-circle">
                                    <div class="ms-2">
                                        <span class="fw-bold"><?= htmlspecialchars($item['symbol']) ?></span>
                                        <small class="d-block text-muted text-truncate"><?= htmlspecialchars($item['companyName']) ?></small>
                                    </div>
                                </div>
                                <h5 class="fw-bold"><?= htmlspecialchars($item['price']) ?></h5>
                                <p class="<?= $item['isPositive'] ? 'text-price-up' : 'text-price-down' ?> mb-0">
                                    <i class="bi <?= $item['isPositive'] ? 'bi-arrow-up-right' : 'bi-arrow-down-left' ?>"></i>
                                    <?= htmlspecialchars($item['percentage']) ?>
                                </p>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

        <!-- Watchlist Section -->
        <section class="mb-5">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3 class="fw-bold">Your watchlist</h3>
                <a href="#" class="text-white">Edit Watchlist</a>
            </div>
            <div class="list-group">
                <?php foreach ($watchlistItems as $item): ?>
                    <a href="stock_details.php?symbol=<?= htmlspecialchars($item['symbol']) ?>" class="list-group-item list-group-item-action data-card p-3 d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <img src="<?= htmlspecialchars($item['logoPath']) ?>" alt="Logo" class="rounded-circle">
                            <div class="ms-3">
                                <p class="fw-bold mb-0"><?= htmlspecialchars($item['symbol']) ?></p>
                                <small class="text-muted"><?= htmlspecialchars($item['companyName']) ?></small>
                            </div>
                        </div>
                        <div>
                            <p class="fw-bold mb-0 text-end"><?= htmlspecialchars($item['price']) ?></p>
                            <small class="<?= $item['isPositive'] ? 'text-price-up' : 'text-price-down' ?>">
                                <i class="bi <?= $item['isPositive'] ? 'bi-arrow-up-right' : 'bi-arrow-down-left' ?>"></i>
                                <?= htmlspecialchars($item['percentage']) ?>
                            </small>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        </section>

        <!-- Trending Stocks Section -->
        <section>
            <h3 class="fw-bold mb-3">Trending Stocks</h3>
            <div class="row g-3">
                <!-- Re-using the same loop structure as portfolio -->
                 <?php foreach ($trendingStocks as $item): ?>
                    <div class="col-6 col-md-4 col-lg-3">
                        <a href="stock_details.php?symbol=<?= htmlspecialchars($item['symbol']) ?>" class="card-link">
                            <div class="data-card p-3 h-100">
                                <div class="d-flex align-items-center mb-2">
                                    <img src="<?= htmlspecialchars($item['logoPath']) ?>" alt="Logo" class="rounded-circle">
                                    <div class="ms-2">
                                        <span class="fw-bold"><?= htmlspecialchars($item['symbol']) ?></span>
                                        <small class="d-block text-muted text-truncate"><?= htmlspecialchars($item['companyName']) ?></small>
                                    </div>
                                </div>
                                <h5 class="fw-bold"><?= htmlspecialchars($item['price']) ?></h5>
                                <p class="<?= $item['isPositive'] ? 'text-price-up' : 'text-price-down' ?> mb-0">
                                    <i class="bi <?= $item['isPositive'] ? 'bi-arrow-up-right' : 'bi-arrow-down-left' ?>"></i>
                                    <?= htmlspecialchars($item['percentage']) ?>
                                </p>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

    </div>

    <!-- Bottom Navigation Bar -->
    <nav class="navbar fixed-bottom bottom-nav">
        <div class="container-fluid d-flex justify-content-around">
            <a href="portfolio.php" class="nav-link active text-center"><i class="bi bi-wallet-fill"></i><div class="small">Portfolio</div></a>
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