<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>FuelSense Dashboard</title>

    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;600&display=swap" rel="stylesheet">

    <style>
        body {
            margin: 0;
            font-family: 'Orbitron', sans-serif;
            background: radial-gradient(circle at top, #0d0d0d, #000000);
            color: #fff;
        }

        .header {
            text-align: center;
            padding: 25px;
            background: rgba(0,0,0,0.6);
            border-bottom: 2px solid #0ff;
            box-shadow: 0 0 20px #0ff;
            position: relative;
        }

        .header h2 {
            color: #0ff;
            text-shadow: 0 0 10px #0ff, 0 0 20px #0ff;
            margin: 0;
        }

        .header p {
            color: #ff00ff;
            text-shadow: 0 0 10px #ff00ff;
            margin-top: 10px;
        }

        .logout {
            position: absolute;
            top: 20px;
            right: 20px;
        }

        .logout a {
            padding: 8px 15px;
            border: 1px solid #ff00ff;
            border-radius: 8px;
            color: white;
            text-decoration: none;
            font-size: 14px;
            transition: 0.3s;
        }

        .logout a:hover {
            background: #ff00ff;
            color: black;
            box-shadow: 0 0 15px #ff00ff;
        }

        .container {
            padding: 40px;
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 25px;
        }

        .card {
            background: rgba(10,10,10,0.8);
            border: 1px solid #0ff;
            border-radius: 15px;
            padding: 25px;
            width: 260px;
            text-align: center;
            box-shadow: 0 0 15px #0ff;
            transition: 0.3s ease;
        }

        .card:hover {
            transform: scale(1.05);
            box-shadow: 0 0 25px #ff00ff, 0 0 40px #0ff;
            border-color: #ff00ff;
        }

        .card h3 {
            margin-bottom: 20px;
            color: #0ff;
            text-shadow: 0 0 10px #0ff;
        }

        .card a {
            display: block;
            padding: 10px;
            margin: 10px 0;
            color: #fff;
            text-decoration: none;
            border: 1px solid #ff00ff;
            border-radius: 8px;
            transition: 0.3s;
            background: transparent;
        }

        .card a:hover {
            background: #ff00ff;
            box-shadow: 0 0 15px #ff00ff;
            color: #000;
        }

    </style>
</head>
<body>

<div class="header">
    <h2>⚡ FuelSense Dashboard ⚡</h2>
    <p>Welcome, <?php echo $_SESSION['email'] ?? 'User'; ?></p>

    <div class="logout">
        <a href="logout.php" onclick="return confirm('Are you sure you want to logout?')">Logout</a>
    </div>
</div>

<div class="container">

    <!-- Track Distance -->
    <div class="card">
        <h3>🚗 Track Distance</h3>
        <a href="add_distance.php">Manual Input</a>
        <a href="gps_track.php">GPS Tracking</a>
        <a href="view_distance.php">View Distance Logs</a>
    </div>

    <!-- Fuel -->
    <div class="card">
        <h3>⛽ Input Fuel</h3>
        <a href="fuel_form.php">Add Fuel</a>
        <a href="view_fuel.php">View Fuel Logs</a>
    </div>

    <!-- Reports -->
    <div class="card">
        <h3>📊 View Reports</h3>
        <a href="smart_report.php">Smart Expense Prediction</a>
        <a href="maintenance_report.php">Maintenance Prediction</a>
    </div>

</div>

</body>
</html>