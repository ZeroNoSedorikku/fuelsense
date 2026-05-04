<?php
session_start();
include 'db.php';

// Protect page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// =====================
// GET VEHICLES
// =====================
$vehicles = pg_query_params($conn,
    "SELECT vehicle_id, brand, model FROM vehicles WHERE user_id = $1",
    [$user_id]
);

// =====================
// CURRENT VEHICLE
// =====================
$current_vehicle_id = $_GET['vehicle_id'] ?? null;

// Auto-select first vehicle if none selected
if (!$current_vehicle_id) {
    $default = pg_query_params($conn,
        "SELECT vehicle_id FROM vehicles WHERE user_id = $1 LIMIT 1",
        [$user_id]
    );

    $v = pg_fetch_assoc($default);

    if ($v) {
        header("Location: dashboard.php?vehicle_id=" . $v['vehicle_id']);
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard - FuelSense</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        body {
            margin: 0;
            font-family: Arial;
            background: #000;
            color: white;
        }

        .header {
            text-align: center;
            padding: 20px;
            border-bottom: 2px solid #0ff;
        }

        .header h2 {
            margin: 0;
            color: #0ff;
        }

        .vehicle-switcher {
            text-align: center;
            margin: 20px;
        }

        select {
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #0ff;
            background: black;
            color: white;
        }

        .container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 15px;
            padding: 20px;
        }

        .card {
            border: 1px solid #0ff;
            padding: 20px;
            border-radius: 10px;
            width: 200px;
            text-align: center;
        }

        .card a {
            display: block;
            margin-top: 10px;
            padding: 8px;
            border: 1px solid #0ff;
            color: #0ff;
            text-decoration: none;
            border-radius: 6px;
        }

        .card a:hover {
            background: #0ff;
            color: black;
        }
    </style>
</head>

<body>

<div class="header">
    <h2>🚀 FuelSense Dashboard</h2>
</div>

<!-- =====================
     VEHICLE SWITCHER
===================== -->
<div class="vehicle-switcher">
<form method="GET" id="vehicleForm">

    <select name="vehicle_id" onchange="document.getElementById('vehicleForm').submit()" required>

        <?php while ($v = pg_fetch_assoc($vehicles)): ?>
            <option value="<?= $v['vehicle_id'] ?>"
                <?= ($current_vehicle_id == $v['vehicle_id']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($v['brand']) ?> <?= htmlspecialchars($v['model']) ?>
            </option>
        <?php endwhile; ?>

    </select>

</form>
</div>

<!-- =====================
     FEATURES
===================== -->
<div class="container">

    <div class="card">
        <h3>Distance</h3>
        <a href="add_distance.php?vehicle_id=<?= $current_vehicle_id ?>">Add Distance</a>
        <a href="gps_track.php?vehicle_id=<?= $current_vehicle_id ?>">GPS Track</a>
        <a href="view_distance.php?vehicle_id=<?= $current_vehicle_id ?>">View Logs</a>
    </div>

    <div class="card">
        <h3>Fuel</h3>
        <a href="fuel_form.php?vehicle_id=<?= $current_vehicle_id ?>">Add Fuel</a>
        <a href="view_fuel.php?vehicle_id=<?= $current_vehicle_id ?>">View Logs</a>
    </div>

    <div class="card">
        <h3>Reports</h3>
        <a href="smart_report.php?vehicle_id=<?= $current_vehicle_id ?>">Smart Report</a>
        <a href="maintenance_report.php?vehicle_id=<?= $current_vehicle_id ?>">Maintenance</a>
    </div>

</div>

</body>
</html>