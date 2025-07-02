<?php
// profile.php

// Start the session and check if the user is logged in.
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

// Get user email from the session to display it.
$user_email = $_SESSION['email'];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Profile - Stock App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <div class="container py-4">
        
        <!-- Header Section -->
        <header class="d-flex align-items-center mb-4 text-white">
            <h2 class="fw-bold mb-0">My Profile</h2>
        </header>

        <!-- Profile Info Card -->
        <div class="data-card p-4">
            <div class="mb-4 text-center">
                <i class="bi bi-person-circle" style="font-size: 80px;"></i>
                <h4 class="fw-bold mt-2"><?= htmlspecialchars($user_email) ?></h4>
                <p class="text-muted">Standard Member</p>
            </div>
            
            <hr style="border-color: #5870a0;">

            <!-- Menu Options -->
            <div class="list-group list-group-flush">
                <a href="#" class="list-group-item list-group-item-action bg-transparent text-white border-0">Account Settings</a>
                <a href="#" class="list-group-item list-group-item-action bg-transparent text-white border-0">Notification Preferences</a>
                <a href="premium.php" class="list-group-item list-group-item-action bg-transparent text-white border-0">Go Premium</a>
            </div>

            <hr style="border-color: #5870a0;">

            <!-- Logout Button -->
            <div class="mt-4">
                <a href="logout.php" class="btn btn-danger w-100 py-2">
                    <i class="bi bi-box-arrow-right me-2"></i>Logout
                </a>
            </div>
        </div>
    </div>

    <!-- Bottom Navigation Bar -->
    <nav class="navbar fixed-bottom bottom-nav">
        <div class="container-fluid d-flex justify-content-around">
            <a href="portfolio.php" class="nav-link text-center">
            <i class="bi bi-wallet-fill"></i>
            <div class="small">Portfolio</div>
            </a>
            <a href="dashboard.php" class="nav-link text-center">
            <i class="bi bi-newspaper"></i>
            <div class="small">Overview</div>
            </a>
            <a href="markets.php" class="nav-link text-center">
            <i class="bi bi-graph-up"></i>
            <div class="small">Markets</div>
            </a>
            <a href="profile.php" class="nav-link text-center">
            <i class="bi bi-person-circle"></i>
            <div class="small">Profile</div>
            </a>
        </div>
    </nav>
    
    <div style="padding-bottom: 80px;"></div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>