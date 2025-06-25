<?php
// stock_details.php (Updated with Interactive Chart.js)
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

$symbol = isset($_GET['symbol']) ? htmlspecialchars($_GET['symbol']) : null;
$stockDetails = null;

if ($symbol) {
    $apiKey = "88f9b1ad084575c4eb916236b0068dcf"; // <-- Don't forget your API Key
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
            'logoPath' => 'img/logos/' . strtolower($stock['symbol']) . '.png',
            'symbol' => $stock['symbol'], 'companyName' => $stock['symbol'],
            'price' => '$' . number_format($stock['close'], 2),
            'changeAmount' => number_format($change, 2),
            'percentage' => number_format($percentageChange, 2) . '%',
            'isPositive' => $change >= 0,
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
                        <p class="text-secondary mb-2"><?= htmlspecialchars($stockDetails['companyName']) ?></p>
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

            <?php else: ?>
            <div class="alert alert-danger">Could not fetch details for the stock symbol '<?= htmlspecialchars($symbol) ?>'.</div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Check if we have stock data before trying to create a chart
        <?php if ($stockDetails): ?>
            
            // --- NEW INTERACTIVE CHART.JS CODE ---

            // 1. Get the canvas element from the HTML
            const ctx = document.getElementById('stockChart');

            // 2. Define colors based on the stock's performance (passed from PHP)
            const isPositive = <?= json_encode($stockDetails['isPositive']) ?>;
            const accentColor = isPositive ? 'rgba(45, 249, 12, 1)' : 'rgba(255, 169, 50, 1)';
            const gradientColor = isPositive ? 'rgba(45, 249, 12, 0.2)' : 'rgba(255, 169, 50, 0.2)';

            // 3. Sample data for the chart. A real app would fetch this via another API call.
            const labels = ['9:30am', '10am', '11am', '12pm', '1pm', '2pm', '3pm', '4pm'];
            const dataPoints = isPositive 
                ? [170.1, 171.5, 171.2, 172.8, 172.5, 173.1, 172.9, 172.28]
                : [173.1, 172.5, 172.8, 171.2, 171.5, 170.1, 170.5, 169.90];

            // 4. Create the chart with a much more professional and interactive configuration
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Price (USD)',
                        data: dataPoints,
                        borderColor: accentColor,
                        backgroundColor: gradientColor,
                        fill: true, // This creates the area fill below the line
                        tension: 0.4, // This makes the line curved
                        borderWidth: 2,
                        pointRadius: 0 // Hides the dots on the line
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    interaction: {
                        intersect: false,
                        mode: 'index', // Tooltips will show for the nearest data point
                    },
                    plugins: {
                        legend: {
                            display: false // Hides the "Price (USD)" legend
                        },
                        tooltip: {
                            backgroundColor: '#000',
                            titleFont: { size: 14, weight: 'bold' },
                            bodyFont: { size: 12 },
                            padding: 10,
                            cornerRadius: 4
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false // Hides vertical grid lines
                            },
                            ticks: {
                                color: 'rgba(255, 255, 255, 0.7)'
                            }
                        },
                        y: {
                            grid: {
                                color: 'rgba(255, 255, 255, 0.1)' // Makes horizontal grid lines subtle
                            },
                            ticks: {
                                color: 'rgba(255, 255, 255, 0.7)'
                            }
                        }
                    }
                }
            });
        <?php endif; ?>
    </script>
</body>
</html>