<?php
// stock_details.php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

// Get the stock symbol from the URL.
$symbol = isset($_GET['symbol']) ? htmlspecialchars($_GET['symbol']) : 'Not Found';

// --- In a real app, you would use this symbol to query your API/database ---
// For now, we find the stock from our sample data array.
$stockOverviews = [
    ['logoPath' => 'https://via.placeholder.com/50/20c997/FFFFFF?text=GGL', 'symbol' => 'GGL', 'companyName' => 'Google Inc.', 'price' => '2,845.50', 'changeAmount' => '+15.25', 'percentage' => '0.54%', 'isPositive' => true, 'marketCap' => '1.9T', 'volume' => '1.2M'],
    ['logoPath' => 'https://via.placeholder.com/50/dc3545/FFFFFF?text=AMZ', 'symbol' => 'AMZ', 'companyName' => 'Amazon.com, Inc.', 'price' => '3,321.80', 'changeAmount' => '-8.40', 'percentage' => '0.25%', 'isPositive' => false, 'marketCap' => '1.7T', 'volume' => '2.5M'],
];

$stockDetails = null;
foreach ($stockOverviews as $stock) {
    if ($stock['symbol'] === $symbol) {
        $stockDetails = $stock;
        break;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Details - <?= $symbol ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container py-4">
        <a href="dashboard.php" class="btn btn-outline-light mb-4">&larr; Back to Dashboard</a>

        <?php if ($stockDetails): ?>
            <div class="data-card p-4">
                <div class="d-flex align-items-center mb-3">
                    <img src="<?= htmlspecialchars($stockDetails['logoPath']) ?>" alt="Logo" class="rounded-circle">
                    <div class="ms-3">
                        <h2 class="fw-bold mb-0"><?= htmlspecialchars($stockDetails['companyName']) ?></h2>
                        <h4 class="text-muted"><?= htmlspecialchars($stockDetails['symbol']) ?></h4>
                    </div>
                </div>

                <hr style="border-color: #5870a0;">

                <div class="d-flex justify-content-between align-items-center">
                    <h1 class="display-4 fw-bold"><?= htmlspecialchars($stockDetails['price']) ?></h1>
                    <h3 class="<?= $stockDetails['isPositive'] ? 'text-price-up' : 'text-price-down' ?>">
                        <?= htmlspecialchars($stockDetails['changeAmount']) ?> (<?= htmlspecialchars($stockDetails['percentage']) ?>)
                    </h3>
                </div>

                <div class="mt-4">
                    <canvas id="stockChart"></canvas>
                </div>
            </div>
        <?php else: ?>
            <div class="alert alert-danger">Stock symbol not found.</div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // This is sample data for the chart. In a real app, you would fetch this.
        const labels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'];
        const data = {
            labels: labels,
            datasets: [{
                label: 'Stock Price (USD)',
                backgroundColor: '#7B90BB',
                borderColor: '#FFFFFF',
                data: [2800, 2820, 2810, 2835, 2850, 2845.50],
            }]
        };

        const config = {
            type: 'line',
            data: data,
            options: {
                scales: {
                    y: { ticks: { color: '#FFF'} },
                    x: { ticks: { color: '#FFF'} }
                }
            }
        };

        const myChart = new Chart(
            document.getElementById('stockChart'),
            config
        );
    </script>
</body>
</html>