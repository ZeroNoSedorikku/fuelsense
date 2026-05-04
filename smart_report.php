<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) exit();

$user_id = $_SESSION['user_id'];
$vehicle_id = $_GET['vehicle_id'] ?? null;

if (!$vehicle_id) exit("Select vehicle");

// VEHICLE
$v = pg_fetch_assoc(pg_query_params($conn,
"SELECT * FROM vehicles WHERE vehicle_id=$1 AND user_id=$2",
[$vehicle_id, $user_id]));

// DATA (30 DAYS)
$fuel = pg_fetch_assoc(pg_query_params($conn,
"SELECT COALESCE(SUM(liters),0) l, COALESCE(SUM(cost),0) c
 FROM fuel_logs
 WHERE user_id=$1 AND vehicle_id=$2
 AND date >= CURRENT_DATE - INTERVAL '30 days'",
[$user_id,$vehicle_id]));

$dist = pg_fetch_assoc(pg_query_params($conn,
"SELECT COALESCE(SUM(distance_km),0) d,
 COUNT(DISTINCT date) days
 FROM distance_logs
 WHERE user_id=$1 AND vehicle_id=$2
 AND date >= CURRENT_DATE - INTERVAL '30 days'",
[$user_id,$vehicle_id]));

// VALUES
$total_distance = (float)$dist['d'];
$total_liters = (float)$fuel['l'];
$total_cost = (float)$fuel['c'];
$days_logged = (int)$dist['days'];

$km_per_liter = $total_liters ? $total_distance/$total_liters : 0;
$cost_per_km = $total_distance ? $total_cost/$total_distance : 0;

// SMART PREDICTION
$days_in_month = date('t');

$usage_ratio = ($days_logged > 0) ? ($days_logged / $days_in_month) : 0;
$predicted_active_days = $days_in_month * $usage_ratio;

$avg_km_per_day = ($days_logged > 0) ? ($total_distance / $days_logged) : 0;

$predicted_distance = $avg_km_per_day * $predicted_active_days;
$predicted_cost = $cost_per_km * $predicted_distance;

$accuracy = ($days_logged / $days_in_month) * 100;
?>

<!DOCTYPE html>
<html>
<head>
<title>Smart Report</title>

<style>
body {
    margin:0;
    font-family: 'Orbitron', sans-serif;
    background: radial-gradient(circle,#0d0d0d,#000);
    color:white;
    display:flex;
    justify-content:center;
    align-items:center;
    min-height:100vh;
}

.card {
    background:#111;
    padding:25px;
    border-radius:15px;
    border:1px solid #0ff;
    box-shadow:0 0 20px #0ff;
    text-align:center;
    width:90%;
    max-width:400px;
}

h2 {
    color:#0ff;
    text-shadow:0 0 10px #0ff;
}

.value {
    color:#ff00ff;
    text-shadow:0 0 10px #ff00ff;
}

hr {
    border:0;
    border-top:1px solid #0ff;
    margin:15px 0;
}

.note {
    font-size:12px;
    color:#888;
}
</style>
</head>

<body>

<div class="card">

<h2>🚀 Smart Report</h2>

<p><?= $v['brand'] ?> <?= $v['model'] ?> (<?= $v['cc'] ?>cc)</p>

<p>Total Distance:</p>
<p class="value"><?= number_format($total_distance,2) ?> km</p>

<p>Total Fuel:</p>
<p class="value"><?= number_format($total_liters,2) ?> L</p>

<p>Total Cost:</p>
<p class="value">₱<?= number_format($total_cost,2) ?></p>

<hr>

<p>Fuel Efficiency:</p>
<p class="value"><?= number_format($km_per_liter,2) ?> km/L</p>

<p>Cost per km:</p>
<p class="value">₱<?= number_format($cost_per_km,2) ?></p>

<hr>

<p>Predicted Distance:</p>
<p class="value"><?= number_format($predicted_distance,2) ?> km</p>

<p>Predicted Cost:</p>
<p class="value">₱<?= number_format($predicted_cost,2) ?></p>

<p class="note">
Accuracy: <?= number_format($accuracy,1) ?>%
(<?= $days_logged ?> active days)
</p>

</div>

</body>
</html>