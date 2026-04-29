<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Get total distance of user
$query = "SELECT SUM(distance_km) AS total_km FROM distance_logs WHERE user_id = $1";
$result = pg_query_params($conn, $query, [$user_id]);

$data = pg_fetch_assoc($result);
$total_km = $data['total_km'] ?? 0;

// Maintenance threshold
$oil_change_km = 1000;

// Compute remaining km
$remaining_km = $oil_change_km - ($total_km % $oil_change_km);

// Status
if ($remaining_km <= 0 || $remaining_km == $oil_change_km) {
    $status = "⚠️ Maintenance Required!";
} elseif ($remaining_km <= 200) {
    $status = "⚠️ Maintenance Soon";
} else {
    $status = "✅ Good Condition";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Maintenance Report - FuelSense</title>

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
            font-family: 'Orbitron', sans-serif;
            background: radial-gradient(circle at top, #0d0d0d, #000);
            color: white;
            text-align: center;
            padding: 30px;
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

        .status {
            margin-top: 15px;
            font-size: 18px;
        }

        a {
            display: block;
            margin-top: 20px;
            color: #0ff;
        }
    </style>
</head>
<body>

<div class="card">
    <h2>🔧 Maintenance Prediction</h2>

    <p>Total Distance Traveled:</p>
    <p class="value"><?= number_format($total_km, 2) ?> km</p>

    <p>Next Oil Change At:</p>
    <p class="value"><?= $oil_change_km ?> km intervals</p>

    <p>Remaining Distance Before Maintenance:</p>
    <p class="value"><?= number_format($remaining_km, 2) ?> km</p>

    <p class="status"><?= $status ?></p>

    <a href="dashboard.php">⬅ Back to Dashboard</a>
</div>

</body>
</html>