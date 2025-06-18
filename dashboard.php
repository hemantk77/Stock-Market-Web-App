<?php
// dashboard.php

// Step 1: Start the session and check if the user is logged in.
session_start();

// If the user is not logged in, redirect them to the login page.
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

// Step 2: Sample data to simulate fetching from a database or API.
$newsItems = [
    [
        'imageUrl' => 'https://via.placeholder.com/600x400.png/7B90BB/FFFFFF?text=Market+News',
        'category' => 'Business',
        'publishTime' => '2 hours ago',
        'title' => 'Global Markets Rally on Positive Economic Outlook',
        'description' => 'Major indices across the globe saw significant gains today as new data suggests a stronger-than-expected economic recovery, boosting investor confidence.',
        'source' => 'Global News Network'
    ],
    // Add more news items here...
];

$stockOverviews = [
    [
        'logoPath' => 'https://via.placeholder.com/50/20c997/FFFFFF?text=GGL',
        'symbol' => 'GGL',
        'companyName' => 'Google Inc.',
        'price' => '2,845.50',
        'changeAmount' => '+15.25',
        'percentage' => '0.54%',
        'isPositive' => true,
        'marketCap' => '1.9T',
        'volume' => '1.2M'
    ],
    [
        'logoPath' => 'https://via.placeholder.com/50/dc3545/FFFFFF?text=AMZ',
        'symbol' => 'AMZ',
        'companyName' => 'Amazon.com, Inc.',
        'price' => '3,321.80',
        'changeAmount' => '-8.40',
        'percentage' => '0.25%',
        'isPositive' => false,
        'marketCap' => '1.7T',
        'volume' => '2.5M'
    ],
    // Add more stock items here...
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
                <button class="nav-link active" id="pills-news-tab" data-bs-toggle="pill" data-bs-target="#pills-news" type="button" role="tab">Latest News</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="pills-stocks-tab" data-bs-toggle="pill" data-bs-target="#pills-stocks" type="button" role="tab">Stock Prices</button>
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
                <p class="text-muted mb-4">Tap on stocks for detailed analysis.</p>

                <?php foreach ($stockOverviews as $stock): ?>
                    <div class="data-card p-3">
                        <div class="d-flex align-items-center">
                            <img src="<?= htmlspecialchars($stock['logoPath']) ?>" alt="Logo" class="rounded-circle">
                            <div class="flex-grow-1 ms-3">
                                <div class="d-flex justify-content-between">
                                    <span class="fw-bold"><?= htmlspecialchars($stock['symbol']) ?></span>
                                    <span class="fw-bold"><?= htmlspecialchars($stock['price']) ?></span>
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
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <nav class="navbar fixed-bottom bottom-nav">
        <div class="container-fluid d-flex justify-content-around">
            <a href="#" class="nav-link text-center"><i class="bi bi-wallet-fill"></i><div class="small">Portfolio</div></a>
            <a href="#" class="nav-link active text-center"><i class="bi bi-newspaper"></i><div class="small">Overview</div></a>
            <a href="#" class="nav-link text-center"><i class="bi bi-graph-up"></i><div class="small">Markets</div></a>
            <a href="#" class="nav-link text-center"><i class="bi bi-search"></i><div class="small">Search</div></a>
            <a href="profile.php" class="nav-link text-center"><i class="bi bi-person-circle"></i><div class="small">Profile</div></a>
        </div>
    </nav>
    
    <div style="padding-bottom: 80px;"></div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>