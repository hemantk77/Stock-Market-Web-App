<?php
// dashboard.php (Updated to use .png logos)
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

$apiKey = "88f9b1ad084575c4eb916236b0068dcf"; // <--- PASTE YOUR KEY HERE
$symbols = "AAPL,MSFT,TSLA,GOOGL";
$apiUrl = "http://api.marketstack.com/v1/eod/latest?access_key={$apiKey}&symbols={$symbols}";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$apiResponse = curl_exec($ch);
curl_close($ch);

$stockData = json_decode($apiResponse, true);
$stockOverviews = [];

if (isset($stockData['data'])) {
    foreach ($stockData['data'] as $stock) {
        $change = $stock['close'] - $stock['open'];
        $percentageChange = ($stock['open'] > 0) ? ($change / $stock['open']) * 100 : 0;
        
        $stockOverviews[] = [
            // --- THIS IS THE UPDATED LINE for .png files ---
            'logoPath' => 'img/logos/' . strtolower($stock['symbol']) . '.png',
            'symbol' => $stock['symbol'],
            'companyName' => $stock['symbol'],
            'price' => '$' . number_format($stock['close'], 2),
            'changeAmount' => number_format($change, 2),
            'percentage' => number_format($percentageChange, 2) . '%',
            'isPositive' => $change >= 0,
        ];
    }
}

$newsItems = [
    ['imageUrl' => 'https://images.unsplash.com/photo-1504711434969-e33886168f5c?q=80&w=2070', 'category' => 'Markets', 'publishTime' => '2 hours ago', 'title' => 'Stock Market Hits Record High as Tech Shares Surge', 'description' => 'Major indices saw significant gains as new data suggests a stronger-than-expected economic recovery.', 'source' => 'Financial Times'],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - PulseTrade</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container py-4">
        <header class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold">Market Overview</h2>
                <p class="text-secondary">Stay updated with latest news & stock prices</p>
            </div>
            <a href="#" class="text-white"><i class="bi bi-bell fs-3"></i></a>
        </header>

        <ul class="nav nav-pills nav-fill mb-4 custom-tabs" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="pills-news-tab" data-bs-toggle="pill" data-bs-target="#pills-news" type="button" role="tab">Latest News</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="pills-stocks-tab" data-bs-toggle="pill" data-bs-target="#pills-stocks" type="button" role="tab">Stock Prices</button>
            </li>
        </ul>

        <div class="tab-content" id="pills-tabContent">
            <div class="tab-pane fade show active" id="pills-news" role="tabpanel">
                <?php foreach ($newsItems as $item): ?>
                    <a href="#" class="card-link">
                        <div class="card data-card border-0 mb-3">
                            <img src="<?= htmlspecialchars($item['imageUrl']) ?>" class="card-img-top" alt="News Image" style="max-height: 180px; object-fit: cover;">
                            <div class="card-body">
                                <span class="badge bg-primary mb-2"><?= htmlspecialchars($item['category']) ?></span>
                                <h5 class="card-title"><?= htmlspecialchars($item['title']) ?></h5>
                                <p class="card-text text-secondary"><?= htmlspecialchars($item['description']) ?></p>
                                <small class="text-secondary">Source: <?= htmlspecialchars($item['source']) ?></small>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>

            <div class="tab-pane fade" id="pills-stocks" role="tabpanel">
                <h4 class="fw-bold">Top Stocks</h4>
                <p class="text-secondary mb-3">Live data from marketstack API.</p>
                <?php if (!empty($stockOverviews)): ?>
                    <?php foreach ($stockOverviews as $stock): ?>
                        <a href="stock_details.php?symbol=<?= htmlspecialchars($stock['symbol']) ?>" class="card-link">
                            <div class="data-card p-3 mb-2 d-flex align-items-center">
                                <img src="<?= htmlspecialchars($stock['logoPath']) ?>" alt="Logo" class="rounded-circle" style="width: 50px; height: 50px;">
                                <div class="flex-grow-1 mx-3">
                                    <span class="fw-bold fs-5"><?= htmlspecialchars($stock['symbol']) ?></span>
                                    <p class="text-secondary mb-0 text-truncate"><?= htmlspecialchars($stock['companyName']) ?></p>
                                </div>
                                <div class="text-end">
                                    <span class="fw-bold fs-5"><?= htmlspecialchars($stock['price']) ?></span>
                                    <p class="mb-0 <?= $stock['isPositive'] ? 'text-price-up' : 'text-price-down' ?>">
                                        <?= htmlspecialchars($stock['changeAmount']) ?> (<?= htmlspecialchars($stock['percentage']) ?>)
                                    </p>
                                </div>
                                <i class="bi bi-chevron-right text-secondary ms-3"></i>
                            </div>
                        </a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="alert alert-warning">Could not fetch live stock data.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <nav class="navbar fixed-bottom bottom-nav">
        <div class="container-fluid d-flex justify-content-around">
            <a href="portfolio.php" class="nav-link text-center"><i class="bi bi-wallet-fill"></i><div class="small">Portfolio</div></a>
            <a href="dashboard.php" class="nav-link active text-center"><i class="bi bi-newspaper"></i><div class="small">Overview</div></a>
            <a href="markets.php" class="nav-link text-center"><i class="bi bi-graph-up"></i><div class="small">Markets</div></a>
            <a href="#" class="nav-link text-center"><i class="bi bi-search"></i><div class="small">Search</div></a>
            <a href="profile.php" class="nav-link text-center"><i class="bi bi-person-circle"></i><div class="small">Profile</div></a>
        </div>
    </nav>
    <div style="padding-bottom: 80px;"></div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>