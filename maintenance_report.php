<?php
session_start();
include 'db.php';

// Protect page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$vehicle_id = $_GET['vehicle_id'] ?? null;

if (!$vehicle_id) {
    exit("Select vehicle first");
}

/* =====================
   GET VEHICLE INFO
===================== */
$vehicle_query = "
    SELECT brand, model, cc
    FROM vehicles
    WHERE vehicle_id = $1 AND user_id = $2
";

$vehicle_result = pg_query_params($conn, $vehicle_query, [$vehicle_id, $user_id]);
$vehicle = pg_fetch_assoc($vehicle_result);

if (!$vehicle) {
    exit("Invalid vehicle");
}

/* =====================
   AUTO OIL CHANGE BASED ON CC
===================== */
$cc = (int)$vehicle['cc'];

if ($cc <= 125) {
    $oil_change_km = 1000;
} elseif ($cc <= 200) {
    $oil_change_km = 1500;
} elseif ($cc <= 400) {
    $oil_change_km = 2000;
} else {
    $oil_change_km = 3000;
}

/* =====================
   TOTAL DISTANCE (PER VEHICLE)
===================== */
$query = "
    SELECT COALESCE(SUM(distance_km),0) AS total_km
    FROM distance_logs
    WHERE user_id = $1 AND vehicle_id = $2
";

$result = pg_query_params($conn, $query, [$user_id, $vehicle_id]);
$data = pg_fetch_assoc($result);

$total_km = (float)$data['total_km'];

/* =====================
   MAINTENANCE LOGIC
===================== */
$used_km = fmod($total_km, $oil_change_km);
$remaining_km = $oil_change_km - $used_km;

// exact hit fix
if ($used_km == 0 && $total_km > 0) {
    $remaining_km = 0;
}

// STATUS
if ($remaining_km == 0 && $total_km > 0) {
    $status = "⚠️ Maintenance Required!";
} elseif ($remaining_km <= ($oil_change_km * 0.2)) {
    $status = "⚠️ Maintenance Soon";
} else {
    $status = "✅ Good Condition";
}

/* =====================
   PROGRESS %
===================== */
$progress = ($oil_change_km > 0) 
    ? ($used_km / $oil_change_km) * 100 
    : 0;
?>

<!DOCTYPE html>
<html>
<head>
<title>Maintenance Report - FuelSense</title>

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;600&display=swap" rel="stylesheet">

<style>
body {
    margin:0;
    font-family:'Orbitron',sans-serif;
    background: radial-gradient(circle,#0d0d0d,#000);
    color:white;
    display:flex;
    justify-content:center;
    align-items:center;
    min-height:100vh;
}

.card {
    background: rgba(10,10,10,0.9);
    padding:25px;
    border-radius:15px;
    border:1px solid #0ff;
    box-shadow:0 0 20px #0ff;
    width:90%;
    max-width:400px;
    text-align:center;
}

h2 {
    color:#0ff;
    text-shadow:0 0 10px #0ff;
}

.vehicle {
    color:#ff00ff;
    margin-bottom:10px;
}

.value {
    color:#ff00ff;
    text-shadow:0 0 10px #ff00ff;
    font-size:18px;
}

.status {
    margin-top:10px;
    font-size:16px;
}

/* PROGRESS BAR */
.bar {
    width:100%;
    height:10px;
    background:#111;
    border:1px solid #0ff;
    border-radius:10px;
    margin:10px 0;
    overflow:hidden;
}

.fill {
    height:100%;
    background:#0ff;
    box-shadow:0 0 10px #0ff;
}

.back {
    margin-top:20px;
}

.back a {
    padding:10px 15px;
    border:1px solid #0ff;
    border-radius:8px;
    color:#0ff;
    text-decoration:none;
}

.back a:hover {
    background:#0ff;
    color:black;
}
</style>
</head>

<body>

<div class="card">

<h2>🔧 Maintenance Prediction</h2>

<div class="vehicle">
<?= htmlspecialchars($vehicle['brand']) ?>
<?= htmlspecialchars($vehicle['model']) ?>
(<?= $cc ?>cc)
</div>

<p>Total Distance</p>
<p class="value"><?= number_format($total_km,2) ?> km</p>

<p>Oil Change Interval</p>
<p class="value"><?= $oil_change_km ?> km</p>

<p>Remaining Distance</p>
<p class="value"><?= number_format($remaining_km,2) ?> km</p>

<div class="bar">
    <div class="fill" style="width: <?= min(100, $progress) ?>%;"></div>
</div>

<p class="status"><?= $status ?></p>

<div class="back">
<a href="dashboard.php?vehicle_id=<?= $vehicle_id ?>">⬅ Back</a>
</div>

</div>

</body>
</html>