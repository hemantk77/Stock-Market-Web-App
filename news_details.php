<?php
// news_details.php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

// Get the news ID (array index) from the URL.
$id = isset($_GET['id']) ? (int)$_GET['id'] : -1;

// --- In a real app, you would use this ID to query your database ---
// For now, we find the news item from our sample data array.
$newsItems = [
    ['imageUrl' => 'https://via.placeholder.com/1200x600.png/7B90BB/FFFFFF?text=Market+News', 'category' => 'Business', 'publishTime' => '2 hours ago', 'title' => 'Global Markets Rally on Positive Economic Outlook', 'description' => 'Major indices across the globe saw significant gains today as new data suggests a stronger-than-expected economic recovery, boosting investor confidence.', 'source' => 'Global News Network', 'full_article' => 'The rally was widespread, with technology and financial sectors leading the charge. Analysts point to recent government stimulus packages and a successful vaccination rollout as key drivers for the renewed optimism. However, some experts caution that inflation risks remain on the horizon, potentially leading to market volatility in the coming months. (This is where the full, longer article text would be displayed).'],
];

$newsDetails = null;
if ($id >= 0 && isset($newsItems[$id])) {
    $newsDetails = $newsItems[$id];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>News Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container py-4">
        <a href="dashboard.php" class="btn btn-outline-light mb-4">&larr; Back to Dashboard</a>

        <?php if ($newsDetails): ?>
            <div class="data-card p-4">
                <h1 class="fw-bold mb-3"><?= htmlspecialchars($newsDetails['title']) ?></h1>
                <div class="d-flex justify-content-between align-items-center text-muted mb-3">
                    <div>
                        <span class="news-category-tag"><?= htmlspecialchars($newsDetails['category']) ?></span>
                        <span class="ms-3">Source: <?= htmlspecialchars($newsDetails['source']) ?></span>
                    </div>
                    <span><?= htmlspecialchars($newsDetails['publishTime']) ?></span>
                </div>
                <img src="<?= htmlspecialchars($newsDetails['imageUrl']) ?>" class="img-fluid rounded mb-4" alt="News Article Image">
                <p style="line-height: 1.8; font-size: 1.1rem;">
                    <?= nl2br(htmlspecialchars($newsDetails['full_article'])) ?>
                </p>
            </div>
        <?php else: ?>
            <div class="alert alert-danger">News article not found.</div>
        <?php endif; ?>
    </div>
</body>
</html>