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

// ✅ FIXED PREDICTION
$days_in_month = date('t');

$avg_km_per_day = ($days_logged > 0) ? ($total_distance / $days_logged) : 0;
$avg_cost_per_day = ($days_logged > 0) ? ($total_cost / $days_logged) : 0;

$predicted_distance = $avg_km_per_day * $days_in_month;
$predicted_cost = $avg_cost_per_day * $days_in_month;

// Accuracy
$accuracy = ($days_logged / $days_in_month) * 100;
?>

<!DOCTYPE html>
<html>
<head>
<title>Smart Report - FuelSense</title>

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;600&display=swap" rel="stylesheet">

<style>
* {
    box-sizing: border-box;
}

body {
    margin:0;
    font-family:'Orbitron',sans-serif;
    background: radial-gradient(circle at top,#0d0d0d,#000);
    color:white;

    display:flex;
    justify-content:center;
    align-items:flex-start;

    padding:15px;
}

/* CARD */
.card {
    margin-top:20px;
    background:rgba(10,10,10,0.9);
    padding:20px;
    border-radius:15px;
    border:1px solid #0ff;
    box-shadow:0 0 20px #0ff;

    width:100%;
    max-width:420px;

    text-align:center;
}

/* TITLE */
h2 {
    color:#0ff;
    text-shadow:0 0 10px #0ff;
    margin-bottom:10px;
}

/* VALUES */
.value {
    color:#ff00ff;
    text-shadow:0 0 10px #ff00ff;
    font-size:18px;
    margin:5px 0 10px;
}

/* TEXT */
p {
    margin:5px 0;
}

/* LINE */
hr {
    border:0;
    border-top:1px solid rgba(0,255,255,0.3);
    margin:15px 0;
}

/* NOTE */
.note {
    font-size:12px;
    color:#888;
    margin-top:10px;
}

/* BACK BUTTON */
.back {
    margin-top:20px;
}

.back a {
    display:inline-block;
    padding:10px 15px;
    border:1px solid #0ff;
    border-radius:8px;
    color:#0ff;
    text-decoration:none;
    font-size:14px;
}

.back a:hover {
    background:#0ff;
    color:black;
    box-shadow:0 0 10px #0ff;
}

/* 🔥 MOBILE FIX */
@media (max-width: 480px) {

    .card {
        padding:15px;
        margin-top:10px;
    }

    h2 {
        font-size:18px;
    }

    .value {
        font-size:16px;
    }

    .back a {
        width:100%;
        display:block;
    }

}
</style>
</head>

<body>

<div class="card">

<h2>🚀 Smart Fuel Report</h2>

<p><?= htmlspecialchars($v['brand']) ?> <?= htmlspecialchars($v['model']) ?> (<?= $v['cc'] ?>cc)</p>

<p>Total Distance:</p>
<p class="value"><?= number_format($total_distance,2) ?> km</p>

<p>Total Fuel Used:</p>
<p class="value"><?= number_format($total_liters,2) ?> L</p>

<p>Total Cost:</p>
<p class="value">₱<?= number_format($total_cost,2) ?></p>

<hr>

<p>Fuel Efficiency:</p>
<p class="value"><?= number_format($km_per_liter,2) ?> km/L</p>

<p>Cost per km:</p>
<p class="value">₱<?= number_format($cost_per_km,4) ?></p>

<hr>

<p>📊 Predicted Monthly Distance:</p>
<p class="value"><?= number_format($predicted_distance,2) ?> km</p>

<p>💸 Predicted Monthly Expense:</p>
<p class="value">₱<?= number_format($predicted_cost,2) ?></p>

<p class="note">
Accuracy: <?= number_format($accuracy,1) ?>% (<?= $days_logged ?> days logged)
</p>

<div class="back">
    <a href="dashboard.php?vehicle_id=<?= $vehicle_id ?>">⬅ Back to Dashboard</a>
</div>

</div>

</body>
</html>