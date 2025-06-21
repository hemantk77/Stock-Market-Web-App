<?php
// dashboard.php (Updated with Live API Call)
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

// --- Live API Call Section ---

// Your secret API key from marketstack.com
$apiKey = 88f9b1ad084575c4eb916236b0068dcf; // <--- PASTE YOUR KEY HERE

// The stock symbols we want to fetch
$symbols = "AAPL,MSFT,TSLA,GOOGL";

// The API endpoint URL
$apiUrl = "http://api.marketstack.com/v1/eod/latest?access_key={$apiKey}&symbols={$symbols}";

// Use cURL to make the API request
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$apiResponse = curl_exec($ch);
curl_close($ch);

// Decode the JSON response into a PHP array
$stockData = json_decode($apiResponse, true);

// Initialize an empty array for our stock overviews
$stockOverviews = [];

// Check if the API call was successful and data exists
if (isset($stockData['data'])) {
    foreach ($stockData['data'] as $stock) {
        // We need to calculate the change and percentage ourselves
        $change = $stock['close'] - $stock['open'];
        $percentageChange = ($stock['open'] > 0) ? ($change / $stock['open']) * 100 : 0;
        
        // Format the data to match what our HTML expects
        $stockOverviews[] = [
            'logoPath' => 'https://via.placeholder.com/50/888/FFFFFF?text=' . substr($stock['symbol'], 0, 1),
            'symbol' => $stock['symbol'],
            'companyName' => $stock['symbol'], // The free API doesn't provide company names, so we reuse the symbol.
            'price' => number_format($stock['close'], 2),
            'changeAmount' => number_format($change, 2),
            'percentage' => number_format($percentageChange, 2) . '%',
            'isPositive' => $change >= 0,
        ];
    }
} else {
    // Handle cases where the API call might fail
    // For now, we'll just show an empty list or a message.
}

// --- End of API Call Section ---


// Sample data for News (we'll keep this static for now)
$newsItems = [
    ['imageUrl' => 'https://via.placeholder.com/600x400.png/7B90BB/FFFFFF?text=Market+News', 'category' => 'Business', 'publishTime' => '2 hours ago', 'title' => 'Global Markets Rally on Positive Economic Outlook', 'description' => 'Major indices across the globe saw significant gains today...', 'source' => 'Global News Network'],
];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Stock App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container py-4">
        <header class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold">Market Overview</h2>
                <p class="text-muted">Stay updated with latest news & stock prices</p>
            </div>
            <i class="bi bi-bell fs-3"></i>
        </header>

        <ul class="nav nav-pills nav-fill mb-4" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="pills-stocks-tab" data-bs-toggle="pill" data-bs-target="#pills-stocks" type="button" role="tab">Stock Prices</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="pills-news-tab" data-bs-toggle="pill" data-bs-target="#pills-news" type="button" role="tab">Latest News</button>
            </li>
        </ul>

        <div class="tab-content" id="pills-tabContent">
            
            <div class="tab-pane fade show active" id="pills-news" role="tabpanel">
                <?php foreach ($newsItems as $item): ?>
                    <div class="card data-card">
                        <img src="<?= htmlspecialchars($item['imageUrl']) ?>" class="card-img-top" alt="News Image">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="news-category-tag"><?= htmlspecialchars($item['category']) ?></span>
                                <small class="text-muted"><?= htmlspecialchars($item['publishTime']) ?></small>
                            </div>
                            <h5 class="card-title"><?= htmlspecialchars($item['title']) ?></h5>
                            <p class="card-text card-subtitle"><?= htmlspecialchars($item['description']) ?></p>
                            <small class="text-muted">Source: <?= htmlspecialchars($item['source']) ?></small>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="tab-pane fade" id="pills-stocks" role="tabpanel">
                <h4 class="fw-bold">Top Stocks</h4>
                <p class="text-muted mb-4">Live data from marketstack API.</p>

                <?php if (!empty($stockOverviews)): ?>
                    <?php foreach ($stockOverviews as $stock): ?>
                        <a href="stock_details.php?symbol=<?= htmlspecialchars($stock['symbol']) ?>" class="card-link">
                            <div class="data-card p-3 mb-2">
                                <div class="d-flex align-items-center">
                                    <img src="<?= htmlspecialchars($stock['logoPath']) ?>" alt="Logo" class="rounded-circle">
                                    <div class="flex-grow-1 ms-3">
                                        <div class="d-flex justify-content-between">
                                            <span class="fw-bold"><?= htmlspecialchars($stock['symbol']) ?></span>
                                            <span class="fw-bold">$<?= htmlspecialchars($stock['price']) ?></span>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <small class="text-muted"><?= htmlspecialchars($stock['companyName']) ?></small>
                                            <small class="<?= $stock['isPositive'] ? 'text-price-up' : 'text-price-down' ?>">
                                                <?= htmlspecialchars($stock['changeAmount']) ?> (<?= htmlspecialchars($stock['percentage']) ?>)
                                            </small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="alert alert-warning">Could not fetch live stock data. Please check your API key or try again later.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <nav class="navbar fixed-bottom bottom-nav"></nav>
    <div style="padding-bottom: 80px;"></div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>