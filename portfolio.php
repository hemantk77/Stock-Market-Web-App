<?php

session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

$username = explode('@', $_SESSION['email'])[0];

// --- Live API Call Section ---
$apiKey = 88f9b1ad084575c4eb916236b0068dcf; // <--- PASTE YOUR KEY HERE

// Define the symbols for all sections of this page
$portfolioSymbols = ['AAPL', 'GOOGL', 'TSLA', 'MSFT'];
$watchlistSymbols = ['PINS', 'NFLX'];
$trendingSymbols = $portfolioSymbols; // For this example, trending is the same as portfolio

// Combine all unique symbols to make a single API call
$allSymbols = array_unique(array_merge($portfolioSymbols, $watchlistSymbols));
$symbolsString = implode(',', $allSymbols);

// The API endpoint URL
$apiUrl = "http://api.marketstack.com/v1/eod/latest?access_key={$apiKey}&symbols={$symbolsString}";

// Use cURL to make the API request
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$apiResponse = curl_exec($ch);
curl_close($ch);

$stockDataApiResponse = json_decode($apiResponse, true);
$liveStockData = [];

// Process the API response into a structured array we can easily use
if (isset($stockDataApiResponse['data'])) {
    foreach ($stockDataApiResponse['data'] as $stock) {
        $change = $stock['close'] - $stock['open'];
        $percentageChange = ($stock['open'] > 0) ? ($change / $stock['open']) * 100 : 0;
        
        $liveStockData[$stock['symbol']] = [
            'logoPath' => 'https://via.placeholder.com/40/888/FFFFFF?text=' . substr($stock['symbol'], 0, 1),
            'symbol' => $stock['symbol'],
            'companyName' => $stock['symbol'], // Free API does not provide full company names
            'price' => '$' . number_format($stock['close'], 2),
            'percentage' => number_format($percentageChange, 2) . '%',
            'isPositive' => $change >= 0,
        ];
    }
}
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
        <header class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold">Hi, <?= htmlspecialchars($username) ?></h2>
                <p class="text-muted">Welcome back to PulseTrade</p>
            </div>
            <a href="profile.php" class="text-white"><i class="bi bi-person-circle fs-3"></i></a>
        </header>
        <div class="row">
            <div class="col-md-6 mb-4"><div class="data-card p-4"><p class="card-subtitle">Cash Balance</p><h3 class="fw-bold">€2,000.00</h3></div></div>
            <div class="col-md-6 mb-4"><div class="data-card p-4"><p class="card-subtitle">Total Asset Value</p><h3 class="fw-bold">€18,976.00</h3><small class="text-price-up">4.79% (+0.50%) vs last week</small></div></div>
        </div>

        <section class="mb-5">
            <h3 class="fw-bold mb-3">Portfolio</h3>
            <div class="row g-3">
                <?php foreach ($portfolioSymbols as $symbol): if(isset($liveStockData[$symbol])): $item = $liveStockData[$symbol]; ?>
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
                                <p class="<?= $item['isPositive'] ? 'text-price-up' : 'text-price-down' ?> mb-0"><i class="bi <?= $item['isPositive'] ? 'bi-arrow-up-right' : 'bi-arrow-down-left' ?>"></i> <?= htmlspecialchars($item['percentage']) ?></p>
                            </div>
                        </a>
                    </div>
                <?php endif; endforeach; ?>
            </div>
        </section>

        <section class="mb-5">
            <h3 class="fw-bold mb-3">Your watchlist</h3>
            <div class="list-group">
                <?php foreach ($watchlistSymbols as $symbol): if(isset($liveStockData[$symbol])): $item = $liveStockData[$symbol]; ?>
                    <a href="stock_details.php?symbol=<?= htmlspecialchars($item['symbol']) ?>" class="list-group-item list-group-item-action data-card p-3 d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center"><img src="<?= htmlspecialchars($item['logoPath']) ?>" alt="Logo" class="rounded-circle"><div class="ms-3"><p class="fw-bold mb-0"><?= htmlspecialchars($item['symbol']) ?></p><small class="text-muted"><?= htmlspecialchars($item['companyName']) ?></small></div></div>
                        <div><p class="fw-bold mb-0 text-end"><?= htmlspecialchars($item['price']) ?></p><small class="<?= $item['isPositive'] ? 'text-price-up' : 'text-price-down' ?>"><i class="bi <?= $item['isPositive'] ? 'bi-arrow-up-right' : 'bi-arrow-down-left' ?>"></i> <?= htmlspecialchars($item['percentage']) ?></small></div>
                    </a>
                <?php endif; endforeach; ?>
            </div>
        </section>
        
        </div>
    <nav class="navbar fixed-bottom bottom-nav"></nav>
    <div style="padding-bottom: 80px;"></div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>