<?php
session_start();
include 'db.php';

// Protect page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

/* =====================
   FORCE ADD VEHICLE IF NONE
===================== */
$check = pg_query_params(
    $conn,
    "SELECT COUNT(*) FROM vehicles WHERE user_id = $1",
    [$user_id]
);

$count = pg_fetch_result($check, 0, 0);

if ($count == 0) {
    header("Location: add_vehicle.php");
    exit();
}

/* =====================
   GET VEHICLES
===================== */
$vehicles = pg_query_params(
    $conn,
    "SELECT vehicle_id, brand, model FROM vehicles WHERE user_id = $1",
    [$user_id]
);

/* =====================
   CURRENT VEHICLE
===================== */
$current_vehicle_id = $_GET['vehicle_id'] ?? null;

// Auto-select first vehicle if none selected
if (!$current_vehicle_id) {
    $default = pg_query_params(
        $conn,
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
    <title>FuelSense Dashboard</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;600&display=swap" rel="stylesheet">

    <style>
        body {
            margin: 0;
            font-family: 'Orbitron', sans-serif;
            background: radial-gradient(circle at top, #0d0d0d, #000);
            color: white;
        }

        /* ================= HEADER ================= */
        .header {
            text-align: center;
            padding: 20px;
            border-bottom: 2px solid #0ff;
            box-shadow: 0 0 15px #0ff;
        }

        .header h2 {
            margin: 0;
            color: #0ff;
            text-shadow: 0 0 10px #0ff;
        }

        .add-vehicle {
            display: inline-block;
            margin-top: 10px;
            padding: 8px 12px;
            border: 1px solid #ff00ff;
            color: #ff00ff;
            text-decoration: none;
            border-radius: 6px;
            transition: 0.3s;
            font-size: 14px;
        }

        .add-vehicle:hover {
            background: #ff00ff;
            color: black;
            box-shadow: 0 0 15px #ff00ff;
        }

        /* ================= VEHICLE SWITCHER ================= */
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
            font-family: 'Orbitron', sans-serif;
        }

        /* ================= CARDS ================= */
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
            background: rgba(10,10,10,0.7);
            box-shadow: 0 0 10px #0ff;
        }

        .card h3 {
            color: #ff00ff;
            text-shadow: 0 0 10px #ff00ff;
        }

        .card a {
            display: block;
            margin-top: 10px;
            padding: 8px;
            border: 1px solid #0ff;
            color: #0ff;
            text-decoration: none;
            border-radius: 6px;
            transition: 0.3s;
        }

        .card a:hover {
            background: #0ff;
            color: black;
            box-shadow: 0 0 10px #0ff;
        }

    </style>
</head>

<body>

<!-- ================= HEADER ================= -->
<div class="header">
    <h2>🚀 FuelSense Dashboard</h2>
    <a href="add_vehicle.php" class="add-vehicle">➕ Add Vehicle</a>
</div>

<!-- ================= VEHICLE SWITCHER ================= -->
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

<!-- ================= FEATURES ================= -->
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