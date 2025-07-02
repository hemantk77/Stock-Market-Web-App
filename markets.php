<?php
// markets.php (Updated with content)

session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

// Sample data for major market indices. A real app would get this from an API.
$marketIndices = [
    ['name' => 'S&P 500', 'fullName' => 'Standard & Poor\'s 500', 'value' => '5,433.74', 'change' => '+21.43 (0.39%)', 'isPositive' => true],
    ['name' => 'NASDAQ', 'fullName' => 'NASDAQ Composite', 'value' => '17,688.88', 'change' => '+168.14 (0.95%)', 'isPositive' => true],
    ['name' => 'DAX', 'fullName' => 'Deutscher Aktienindex', 'value' => '18,557.27', 'change' => '-45.89 (0.25%)', 'isPositive' => false],
    ['name' => 'FTSE 100', 'fullName' => 'Financial Times Stock Exchange 100', 'value' => '8,281.55', 'change' => '+60.55 (0.73%)', 'isPositive' => true],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Markets - PulseTrade</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <div class="container py-4">
        <h2 class="fw-bold text-white mb-4">Global Markets</h2>

        <?php foreach ($marketIndices as $index): ?>
            <div class="data-card p-3 mb-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="fw-bold mb-0"><?= htmlspecialchars($index['name']) ?></h4>
                        <p class="text-secondary mb-0"><?= htmlspecialchars($index['fullName']) ?></p>
                    </div>
                    <div class="text-end">
                        <h5 class="fw-bold mb-0"><?= htmlspecialchars($index['value']) ?></h5>
                        <p class="mb-0 <?= $index['isPositive'] ? 'text-price-up' : 'text-price-down' ?>">
                            <?= htmlspecialchars($index['change']) ?>
                        </p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <nav class="navbar fixed-bottom bottom-nav">
        <div class="container-fluid d-flex justify-content-around">
            <a href="portfolio.php" class="nav-link text-center"><i class="bi bi-wallet-fill"></i><div class="small">Portfolio</div></a>
            <a href="dashboard.php" class="nav-link text-center"><i class="bi bi-newspaper"></i><div class="small">Overview</div></a>
            <a href="markets.php" class="nav-link active text-center"><i class="bi bi-graph-up"></i><div class="small">Markets</div></a>
            <a href="profile.php" class="nav-link text-center"><i class="bi bi-person-circle"></i><div class="small">Profile</div></a>
        </div>
    </nav>
    
    <div style="padding-bottom: 80px;"></div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>