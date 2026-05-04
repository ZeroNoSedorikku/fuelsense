<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) exit();

$user_id = $_SESSION['user_id'];
$vehicle_id = $_GET['vehicle_id'] ?? null;

if (!$vehicle_id) exit("Select vehicle");

// VEHICLE INFO
$v = pg_fetch_assoc(pg_query_params($conn,
"SELECT * FROM vehicles WHERE vehicle_id=$1 AND user_id=$2",
[$vehicle_id, $user_id]));

// LAST 30 DAYS DATA
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

$total_distance = (float)$dist['d'];
$total_liters = (float)$fuel['l'];
$total_cost = (float)$fuel['c'];
$days_logged = (int)$dist['days'];

$km_per_liter = $total_liters ? $total_distance/$total_liters : 0;
$cost_per_km = $total_distance ? $total_cost/$total_distance : 0;

$days_in_month = date('t');

$avg_cost = $days_logged ? $total_cost/$days_logged : 0;
$avg_km = $days_logged ? $total_distance/$days_logged : 0;

$predicted_cost = $avg_cost * $days_in_month;
$predicted_distance = $avg_km * $days_in_month;
?>

<h2>🚀 Smart Report</h2>

<p><?= $v['brand'] ?> <?= $v['model'] ?> (<?= $v['cc'] ?>cc)</p>

<p>Total Distance: <?= $total_distance ?> km</p>
<p>Total Fuel: <?= $total_liters ?> L</p>
<p>Total Cost: ₱<?= $total_cost ?></p>

<p>Efficiency: <?= number_format($km_per_liter,2) ?> km/L</p>
<p>Cost/km: ₱<?= number_format($cost_per_km,2) ?></p>

<hr>

<p>Predicted Distance: <?= number_format($predicted_distance,2) ?> km</p>
<p>Predicted Cost: ₱<?= number_format($predicted_cost,2) ?></p>