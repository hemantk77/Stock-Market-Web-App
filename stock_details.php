<?php
// stock_details.php (Updated)
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

$symbol = isset($_GET['symbol']) ? htmlspecialchars($_GET['symbol']) : null;

// --- In a real app, you would use the symbol to query your API/database for all this data. ---
// For now, we use a more detailed sample data structure.
$allStockData = [
    'GGL' => [
        'logoPath' => 'https://via.placeholder.com/60/EA4335/FFFFFF?text=G',
        'symbol' => 'GGL',
        'companyName' => 'Google Inc.',
        'price' => '2,845.50',
        'changeAmount' => '+15.25',
        'percentage' => '0.54%',
        'isPositive' => true,
        'key_metrics' => [
            ['label' => 'Market Cap', 'value' => '1.9T', 'change' => '+0.5%', 'isPositive' => true],
            ['label' => 'P/E Ratio', 'value' => '26.8', 'change' => '', 'isPositive' => false],
            ['label' => 'Volume', 'value' => '1.2M', 'change' => '', 'isPositive' => false],
            ['label' => 'Dividend Yield', 'value' => 'N/A', 'change' => '', 'isPositive' => false],
        ],
        'related_news' => [
            ['imageUrl' => 'https://via.placeholder.com/80x60/7B90BB/FFFFFF?text=News+1', 'title' => 'Alphabet reports record profits for Q4', 'description' => 'Search giant exceeds expectations...', 'source' => 'Tech Chronicle', 'publishTime' => '4h ago'],
            ['imageUrl' => 'https://via.placeholder.com/80x60/7B90BB/FFFFFF?text=News+2', 'title' => 'New AI developments announced at conference', 'description' => 'The latest advancements could change...', 'source' => 'AI Today', 'publishTime' => '1d ago'],
        ]
    ],
    // You can add more stock details here, e.g., for 'AMZ'
];

$stockDetails = null;
if ($symbol && isset($allStockData[$symbol])) {
    $stockDetails = $allStockData[$symbol];
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Details - <?= htmlspecialchars($symbol) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <div class="container py-3">
        
        <header class="d-flex justify-content-between align-items-center mb-3">
            <a href="dashboard.php" class="btn data-card"><i class="bi bi-arrow-left"></i></a>
            <h3 class="text-white fw-bold mb-0"><?= htmlspecialchars($symbol) ?> Details</h3>
            <button class="btn data-card"><i class="bi bi-bookmark"></i></button>
        </header>

        <?php if ($stockDetails): ?>
            <section class="data-card p-3 mb-4">
                <div class="d-flex align-items-center">
                    <img src="<?= htmlspecialchars($stockDetails['logoPath']) ?>" alt="Logo" class="rounded-circle me-3" style="width: 60px; height: 60px;">
                    <div class="flex-grow-1">
                        <h4 class="fw-bold mb-0"><?= htmlspecialchars($stockDetails['symbol']) ?></h4>
                        <p class="text-muted mb-2"><?= htmlspecialchars($stockDetails['companyName']) ?></p>
                        <h3 class="fw-bold mb-0"><?= htmlspecialchars($stockDetails['price']) ?></h3>
                        <p class="mb-0 <?= $stockDetails['isPositive'] ? 'text-price-up' : 'text-price-down' ?>">
                            <?= htmlspecialchars($stockDetails['changeAmount']) ?> (<?= htmlspecialchars($stockDetails['percentage']) ?>)
                        </p>
                    </div>
                </div>
            </section>

            <section class="data-card p-3 mb-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold">Price Chart</h5>
                    <div class="btn-group btn-group-sm" role="group">
                        <button type="button" class="btn btn-outline-light active">1D</button>
                        <button type="button" class="btn btn-outline-light">1W</button>
                        <button type="button" class="btn btn-outline-light">1M</button>
                        <button type="button" class="btn btn-outline-light">1Y</button>
                    </div>
                </div>
                <canvas id="stockChart"></canvas>
            </section>

            <section class="mb-4">
                <h4 class="fw-bold text-white mb-3">Key Metrics</h4>
                <div class="row g-3">
                    <?php foreach ($stockDetails['key_metrics'] as $metric): ?>
                        <div class="col-6 col-md-3">
                            <div class="data-card p-3 h-100">
                                <p class="text-muted mb-1"><?= htmlspecialchars($metric['label']) ?></p>
                                <h5 class="fw-bold"><?= htmlspecialchars($metric['value']) ?></h5>
                                <?php if (!empty($metric['change'])): ?>
                                    <small class="<?= $metric['isPositive'] ? 'text-price-up' : 'text-price-down' ?>">
                                        <?= htmlspecialchars($metric['change']) ?>
                                    </small>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>

            <section>
                <h4 class="fw-bold text-white mb-3">Related News</h4>
                <?php foreach ($stockDetails['related_news'] as $news): ?>
                    <div class="data-card p-3 mb-2">
                        <div class="d-flex">
                            <img src="<?= htmlspecialchars($news['imageUrl']) ?>" class="rounded me-3" style="width: 80px; height: 60px; object-fit: cover;">
                            <div class="flex-grow-1">
                                <h6 class="fw-bold"><?= htmlspecialchars($news['title']) ?></h6>
                                <p class="small text-muted mb-1"><?= htmlspecialchars($news['description']) ?></p>
                                <small class="text-muted"><?= htmlspecialchars($news['source']) ?> - <?= htmlspecialchars($news['publishTime']) ?></small>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </section>

        <?php else: ?>
            <div class="alert alert-danger">Stock symbol not found. Please go back to the dashboard and select a valid stock.</div>
        <?php endif; ?>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Chart.js implementation (same as before)
        <?php if ($stockDetails): ?>
            const labels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'];
            const data = {
                labels: labels,
                datasets: [{
                    label: 'Stock Price (USD)',
                    backgroundColor: '<?= $stockDetails["isPositive"] ? "rgba(32, 201, 151, 0.2)" : "rgba(220, 53, 69, 0.2)" ?>',
                    borderColor: '<?= $stockDetails["isPositive"] ? "#20c997" : "#dc3545" ?>',
                    data: [2800, 2820, 2810, 2835, 2850, 2845.50],
                    tension: 0.3,
                    fill: true
                }]
            };
            const config = { type: 'line', data: data, options: { scales: { y: { ticks: { color: '#FFF'} }, x: { ticks: { color: '#FFF'} } } } };
            new Chart(document.getElementById('stockChart'), config);
        <?php endif; ?>
    </script>
</body>
</html>