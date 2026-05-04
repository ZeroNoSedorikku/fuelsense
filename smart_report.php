<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    exit("Access denied");
}

$user_id = $_SESSION['user_id'];
$vehicle_id = $_GET['vehicle_id'] ?? null;

if (!$vehicle_id) {
    exit("No vehicle selected");
}

// =====================
// VEHICLE INFO
// =====================
$vehicle_query = "
    SELECT brand, model
    FROM vehicles
    WHERE vehicle_id = $1 AND user_id = $2
";

$vehicle_result = pg_query_params($conn, $vehicle_query, [$vehicle_id, $user_id]);
$vehicle = pg_fetch_assoc($vehicle_result);

if (!$vehicle) {
    exit("Invalid vehicle");
}

// =====================
// FUEL DATA (30 DAYS)
// =====================
$fuel_query = "
    SELECT 
        COALESCE(SUM(liters),0) AS total_liters,
        COALESCE(SUM(cost),0) AS total_cost,
        COUNT(DISTINCT date) AS days_logged
    FROM fuel_logs
    WHERE user_id = $1
    AND vehicle_id = $2
    AND date >= CURRENT_DATE - INTERVAL '30 days'
";

$fuel_result = pg_query_params($conn, $fuel_query, [$user_id, $vehicle_id]);
$fuel = pg_fetch_assoc($fuel_result);

// =====================
// DISTANCE DATA
// =====================
$distance_query = "
    SELECT 
        COALESCE(SUM(distance_km),0) AS total_distance
    FROM distance_logs
    WHERE user_id = $1
    AND vehicle_id = $2
    AND date >= CURRENT_DATE - INTERVAL '30 days'
";

$distance_result = pg_query_params($conn, $distance_query, [$user_id, $vehicle_id]);
$distance = pg_fetch_assoc($distance_result);

// =====================
// CALCULATIONS
// =====================
$total_liters = (float)$fuel['total_liters'];
$total_cost = (float)$fuel['total_cost'];
$total_distance = (float)$distance['total_distance'];
$days_logged = (int)$fuel['days_logged'];

$km_per_liter = $total_liters > 0 ? $total_distance / $total_liters : 0;
$cost_per_km = $total_distance > 0 ? $total_cost / $total_distance : 0;

// prediction
$days_in_month = date('t');

$avg_cost_per_day = $days_logged > 0 ? $total_cost / $days_logged : 0;
$avg_km_per_day = $days_logged > 0 ? $total_distance / $days_logged : 0;

$predicted_cost = $avg_cost_per_day * $days_in_month;
$predicted_distance = $avg_km_per_day * $days_in_month;

$accuracy = $days_logged > 0 ? ($days_logged / $days_in_month) * 100 : 0;
?>

<!DOCTYPE html>
<html>
<head>
<title>Smart Report</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
body {
    margin:0;
    font-family: Arial;
    background:#000;
    color:#fff;
    display:flex;
    justify-content:center;
}

.card {
    margin:20px;
    padding:20px;
    max-width:400px;
    width:100%;
    border:1px solid #0ff;
    text-align:center;
}

.value {
    color:#ff00ff;
    margin-bottom:10px;
}
</style>
</head>

<body>

<div class="card">

<h2>🚀 Smart Report</h2>
<p><?= $vehicle['brand'] ?> <?= $vehicle['model'] ?></p>

<p>Total Distance</p>
<p class="value"><?= number_format($total_distance,2) ?> km</p>

<p>Total Fuel</p>
<p class="value"><?= number_format($total_liters,2) ?> L</p>

<p>Total Cost</p>
<p class="value">₱<?= number_format($total_cost,2) ?></p>

<hr>

<p>Fuel Efficiency</p>
<p class="value"><?= number_format($km_per_liter,2) ?> km/L</p>

<p>Cost per km</p>
<p class="value">₱<?= number_format($cost_per_km,4) ?></p>

<hr>

<p>📊 Predicted Distance</p>
<p class="value"><?= number_format($predicted_distance,2) ?> km</p>

<p>💸 Predicted Expense</p>
<p class="value">₱<?= number_format($predicted_cost,2) ?></p>

<p style="font-size:12px;color:#888;">
Accuracy: <?= number_format($accuracy,1) ?>% (<?= $days_logged ?> days logged)
</p>

<a href="dashboard.php?vehicle_id=<?= $vehicle_id ?>">⬅ Back</a>

</div>

</body>
</html>