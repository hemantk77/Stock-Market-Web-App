<?php
// markets.php

// Start the session and check if the user is logged in.
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Markets - Stock App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <div class="container py-4">
        <h2 class="fw-bold text-white">Markets</h2>
        <p class="text-white-50">Content for the markets page will be built here.</p>
    </div>

    <nav class="navbar fixed-bottom bottom-nav">
        <div class="container-fluid d-flex justify-content-around">
            <a href="portfolio.php" class="nav-link text-center"><i class="bi bi-wallet-fill"></i><div class="small">Portfolio</div></a>
            <a href="dashboard.php" class="nav-link text-center"><i class="bi bi-newspaper"></i><div class="small">Overview</div></a>
            <a href="markets.php" class="nav-link active text-center"><i class="bi bi-graph-up"></i><div class="small">Markets</div></a>
            <a href="#" class="nav-link text-center"><i class="bi bi-search"></i><div class="small">Search</div></a>
            <a href="profile.php" class="nav-link text-center"><i class="bi bi-person-circle"></i><div class="small">Profile</div></a>
        </div>
    </nav>
    
    <div style="padding-bottom: 80px;"></div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>