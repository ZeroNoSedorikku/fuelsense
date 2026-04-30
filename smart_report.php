<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Get current month
$month = date('m');
$year = date('Y');

// Get total fuel data
$fuel_query = "
    SELECT 
        SUM(liters) AS total_liters,
        SUM(cost) AS total_cost
    FROM fuel_logs
    WHERE user_id = $1
    AND EXTRACT(MONTH FROM date) = $2
    AND EXTRACT(YEAR FROM date) = $3
";

$fuel_result = pg_query_params($conn, $fuel_query, [$user_id, $month, $year]);
$fuel_data = pg_fetch_assoc($fuel_result);

$total_liters = $fuel_data['total_liters'] ?? 0;
$total_cost = $fuel_data['total_cost'] ?? 0;

// Get total distance
$distance_query = "
    SELECT 
        SUM(distance_km) AS total_distance,
        COUNT(DISTINCT date) AS days_logged
    FROM distance_logs
    WHERE user_id = $1
    AND EXTRACT(MONTH FROM date) = $2
    AND EXTRACT(YEAR FROM date) = $3
";

$distance_result = pg_query_params($conn, $distance_query, [$user_id, $month, $year]);
$distance_data = pg_fetch_assoc($distance_result);

$total_distance = $distance_data['total_distance'] ?? 0;
$days_logged = $distance_data['days_logged'] ?? 0;

// Calculations
$km_per_liter = ($total_liters > 0) ? ($total_distance / $total_liters) : 0;

// safer cost per km calculation
$cost_per_km = ($total_distance > 0) ? ($total_cost / $total_distance) : 0;

// estimate daily average based on full month (not logged days)
$days_in_month = date('t');

$avg_km_per_day = $total_distance / $days_in_month;
$predicted_cost = $total_cost;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Smart Report - FuelSense</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;600&display=swap" rel="stylesheet">

    <style>
        @media (max-width: 600px) {

    h2 {
        font-size: 18px;
    }

    .card {
        width: 100%;
    }

    .header h2 {
        font-size: 18px;
    }

    .logout {
        top: 5px;
        right: 5px;
    }

}
        body {
            margin: 0;
            font-family: 'Orbitron', sans-serif;
            background: radial-gradient(circle at top, #0d0d0d, #000);
            color: white;

            display: flex;
            justify-content: center;   /* horizontal center */
            align-items: flex-start;   /* top align (better for scrolling) */
            min-height: 100vh;
        }

        .card {
            background: rgba(10,10,10,0.9);
            padding: 30px;
            border-radius: 15px;
            border: 1px solid #0ff;
            box-shadow: 0 0 20px #0ff;
            display: inline-block;
            width: 400px;
        }

        h2 {
            color: #0ff;
            text-shadow: 0 0 10px #0ff;
        }

        .value {
            color: #ff00ff;
            font-size: 20px;
            text-shadow: 0 0 10px #ff00ff;
        }

        a {
            color: #0ff;
            display: block;
            margin-top: 20px;
        }
        .page-wrapper {
            width: 100%;
            max-width: 1000px;  /* controls centered width */
            padding: 20px;
        }
        .container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;   /* THIS centers it */
        }
    </style>
</head>
<body>
<div class="page-wrapper">
<div class="card">
    <h2>🚀 Smart Fuel Report</h2>

    <p>Total Distance:</p>
    <p class="value"><?= number_format($total_distance, 2) ?> km</p>

    <p>Total Fuel Used:</p>
    <p class="value"><?= number_format($total_liters, 2) ?> L</p>

    <p>Total Cost:</p>
    <p class="value">₱<?= number_format($total_cost, 2) ?></p>

    <hr>

    <p>Fuel Efficiency (km/L):</p>
    <p class="value"><?= number_format($km_per_liter, 2) ?></p>

    <p>Cost per km:</p>
    <p class="value">₱<?= number_format($cost_per_km, 4) ?></p>

    <hr>

    <p>Predicted Monthly Expense:</p>
    <p class="value">₱<?= number_format($predicted_cost, 2) ?></p>

    <a href="dashboard.php">⬅ Back to Dashboard</a>
</div>
</div>
</body>
</html>