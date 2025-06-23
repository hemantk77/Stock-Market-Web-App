<?php

session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

$symbol = isset($_GET['symbol']) ? htmlspecialchars($_GET['symbol']) : null;
$stockDetails = null;

if ($symbol) {
    // --- Live API Call Section ---
    $apiKey = "88f9b1ad084575c4eb916236b0068dcf"; // <--- PASTE YOUR KEY HERE
    $apiUrl = "http://api.marketstack.com/v1/eod/latest?access_key={$apiKey}&symbols={$symbol}";
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $apiResponse = curl_exec($ch);
    curl_close($ch);
    
    $apiData = json_decode($apiResponse, true);

    if (isset($apiData['data']) && !empty($apiData['data'])) {
        $stock = $apiData['data'][0];
        $change = $stock['close'] - $stock['open'];
        $percentageChange = ($stock['open'] > 0) ? ($change / $stock['open']) * 100 : 0;
        
        $stockDetails = [
            'logoPath' => 'https://via.placeholder.com/60/888/FFFFFF?text=' . substr($stock['symbol'], 0, 1),
            'symbol' => $stock['symbol'],
            'companyName' => $stock['symbol'],
            'price' => '$' . number_format($stock['close'], 2),
            'changeAmount' => number_format($change, 2),
            'percentage' => number_format($percentageChange, 2) . '%',
            'isPositive' => $change >= 0,
            // These remain static as the free 'eod' endpoint doesn't provide them.
            'key_metrics' => [
                ['label' => 'Market Cap', 'value' => 'N/A'], ['label' => 'P/E Ratio', 'value' => 'N/A'],
                ['label' => 'Volume', 'value' => number_format($stock['volume'], 0)], ['label' => 'Dividend Yield', 'value' => 'N/A'],
            ],
            'related_news' => [
                ['imageUrl' => 'https://via.placeholder.com/80x60/7B90BB/FFFFFF?text=News', 'title' => "News about {$stock['symbol']}", 'description' => 'Latest updates and market analysis...', 'source' => 'News Network', 'publishTime' => '1h ago'],
            ]
        ];
    }
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
                <h5 class="fw-bold">Price Chart</h5>
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
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
            
            <section><h4 class="fw-bold text-white mb-3">Related News</h4>
                <?php foreach ($stockDetails['related_news'] as $news): ?>
                    <div class="data-card p-3 mb-2"><div class="d-flex"><img src="<?= htmlspecialchars($news['imageUrl']) ?>" class="rounded me-3" style="width: 80px; height: 60px; object-fit: cover;"><div class="flex-grow-1"><h6 class="fw-bold"><?= htmlspecialchars($news['title']) ?></h6><p class="small text-muted mb-1"><?= htmlspecialchars($news['description']) ?></p><small class="text-muted"><?= htmlspecialchars($news['source']) ?> - <?= htmlspecialchars($news['publishTime']) ?></small></div></div></div>
                <?php endforeach; ?>
            </section>

        <?php else: ?>
            <div class="alert alert-danger">Could not fetch details for the stock symbol '<?= htmlspecialchars($symbol) ?>'. Please try again.</div>
        <?php endif; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script> /* ... Chart.js code from previous step ... */ </script>
</body>
</html>