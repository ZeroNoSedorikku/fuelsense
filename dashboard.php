<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

/* CHECK VEHICLES */
$check = pg_query_params($conn,
    "SELECT COUNT(*) FROM vehicles WHERE user_id = $1",
    [$user_id]
);
$count = pg_fetch_result($check, 0, 0);

if ($count == 0) {
    header("Location: add_vehicle.php");
    exit();
}

/* GET VEHICLES */
$vehicles = pg_query_params($conn,
    "SELECT vehicle_id, brand, model FROM vehicles WHERE user_id = $1",
    [$user_id]
);

/* CURRENT VEHICLE */
$current_vehicle_id = $_GET['vehicle_id'] ?? null;

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

/* HEADER */
.header {
    text-align: center;
    padding: 20px;
    border-bottom: 2px solid #0ff;
    box-shadow: 0 0 15px #0ff;
    position: relative;
}

.header h2 {
    color: #0ff;
    text-shadow: 0 0 10px #0ff;
}

.logout {
    position: absolute;
    right: 20px;
    top: 20px;
}

.logout a {
    border: 1px solid red;
    padding: 6px 10px;
    color: red;
    text-decoration: none;
    border-radius: 6px;
}

.logout a:hover {
    background: red;
    color: black;
}

/* ADD VEHICLE */
.add-vehicle {
    margin-top: 10px;
    display: inline-block;
    padding: 8px 12px;
    border: 1px solid #ff00ff;
    color: #ff00ff;
    border-radius: 6px;
    text-decoration: none;
}

.add-vehicle:hover {
    background: #ff00ff;
    color: black;
}

/* SWITCHER */
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

/* ✅ NEW CLEAN VEHICLE LIST */
.vehicle-list {
    max-width: 320px;
    margin: 15px auto;
    border: 1px solid #0ff;
    border-radius: 10px;
    box-shadow: 0 0 10px #0ff;
    padding: 10px;
    background: rgba(10,10,10,0.8);

    max-height: 150px;   /* scroll limit */
    overflow-y: auto;
}

.vehicle-item {
    display: flex;
    justify-content: space-between;
    align-items: center;

    padding: 6px 10px;
    margin: 5px 0;

    border-bottom: 1px solid rgba(255,255,255,0.1);
}

.vehicle-item:last-child {
    border-bottom: none;
}

.vehicle-name {
    color: #0ff;
    font-size: 13px;
}

.delete-btn {
    color: red;
    text-decoration: none;
    font-size: 12px;
    border: 1px solid red;
    padding: 2px 6px;
    border-radius: 5px;
}

.delete-btn:hover {
    background: red;
    color: black;
}

/* CARDS */
.container {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 20px;
    padding: 20px;
}

.card {
    border: 1px solid #0ff;
    padding: 20px;
    border-radius: 12px;
    width: 220px;
    text-align: center;
    background: rgba(10,10,10,0.8);
    box-shadow: 0 0 15px #0ff;
}

.card h3 {
    color: #ff00ff;
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

    <div class="logout">
        <a href="logout.php">Logout</a>
    </div>

    <a href="add_vehicle.php" class="add-vehicle">➕ Add Vehicle</a>
</div>

<!-- SWITCHER -->
<div class="vehicle-switcher">
<form method="GET">
    <select name="vehicle_id" onchange="this.form.submit()">
        <?php 
        pg_result_seek($vehicles, 0);
        while ($v = pg_fetch_assoc($vehicles)): ?>
            <option value="<?= $v['vehicle_id'] ?>"
                <?= ($current_vehicle_id == $v['vehicle_id']) ? 'selected' : '' ?>>
                <?= htmlspecialchars($v['brand']) ?> <?= htmlspecialchars($v['model']) ?>
            </option>
        <?php endwhile; ?>
    </select>
</form>

<!-- ✅ CLEAN VEHICLE LIST -->
<div class="vehicle-list">
<?php 
pg_result_seek($vehicles, 0);
while ($v = pg_fetch_assoc($vehicles)): ?>
    <div class="vehicle-item">

        <span class="vehicle-name">
            <?= htmlspecialchars($v['brand']) ?> <?= htmlspecialchars($v['model']) ?>
        </span>

        <a class="delete-btn"
           href="delete_vehicle.php?vehicle_id=<?= $v['vehicle_id'] ?>"
           onclick="return confirm('Delete this vehicle and ALL its data?')">
           ❌
        </a>

    </div>
<?php endwhile; ?>
</div>

</div>

<!-- FEATURES -->
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