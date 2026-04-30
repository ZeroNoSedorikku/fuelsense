<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$query = "SELECT SUM(distance_km) AS total_km FROM distance_logs WHERE user_id = $1";
$result = pg_query_params($conn, $query, [$user_id]);

$data = pg_fetch_assoc($result);
$total_km = $data['total_km'] ?? 0;

$oil_change_km = 1000;
$remaining_km = $oil_change_km - ($total_km % $oil_change_km);

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
        body {
            margin: 0;
            font-family: 'Orbitron', sans-serif;
            background: radial-gradient(circle at top, #0d0d0d, #000);
            color: white;

            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .card {
            background: rgba(10,10,10,0.9);
            padding: 25px;
            border-radius: 15px;
            border: 1px solid #0ff;
            box-shadow: 0 0 20px #0ff;

            width: 90%;
            max-width: 420px;
            text-align: center;
        }

        h2 {
            color: #0ff;
            text-shadow: 0 0 10px #0ff;
            font-size: 20px;
        }

        .value {
            color: #ff00ff;
            font-size: 18px;
            text-shadow: 0 0 10px #ff00ff;
        }

        .status {
            margin-top: 15px;
            font-size: 16px;
        }

        a {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 15px;
            border: 1px solid #0ff;
            border-radius: 8px;
            color: #0ff;
            text-decoration: none;
        }

        a:hover {
            background: #0ff;
            color: black;
        }
    </style>
</head>
<body>

<div class="card">
    <h2>🔧 Maintenance Prediction</h2>

    <p>Total Distance Traveled:</p>
    <p class="value"><?= number_format($total_km, 2) ?> km</p>

    <p>Next Oil Change:</p>
    <p class="value"><?= $oil_change_km ?> km</p>

    <p>Remaining Distance:</p>
    <p class="value"><?= number_format($remaining_km, 2) ?> km</p>

    <p class="status"><?= $status ?></p>

    <a href="dashboard.php">⬅ Back to Dashboard</a>
</div>

</body>
</html>