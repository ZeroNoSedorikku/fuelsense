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

    <!-- ✅ MOBILE FIX -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#000000">

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
            padding: 20px;
            background: rgba(0,0,0,0.6);
            border-bottom: 2px solid #0ff;
            box-shadow: 0 0 20px #0ff;
            position: relative;
        }

        .header h2 {
            color: #0ff;
            text-shadow: 0 0 10px #0ff, 0 0 20px #0ff;
            margin: 0;
            font-size: 20px;
        }

        .header p {
            color: #ff00ff;
            text-shadow: 0 0 10px #ff00ff;
            margin-top: 8px;
            font-size: 14px;
        }

        .logout {
            position: absolute;
            top: 15px;
            right: 15px;
        }

        .logout a {
            padding: 6px 12px;
            border: 1px solid #ff00ff;
            border-radius: 6px;
            color: white;
            text-decoration: none;
            font-size: 12px;
        }

        .logout a:hover {
            background: #ff00ff;
            color: black;
        }

        /* ✅ MOBILE GRID */
        .container {
            padding: 15px;
            display: grid;
            grid-template-columns: 1fr;
            gap: 15px;
        }

        /* DESKTOP */
        @media (min-width: 768px) {
            .container {
                grid-template-columns: repeat(3, 1fr);
                padding: 40px;
            }
        }

        .card {
            background: rgba(10,10,10,0.85);
            border: 1px solid #0ff;
            border-radius: 15px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 0 15px #0ff;
        }

        .card h3 {
            margin-bottom: 15px;
            color: #0ff;
            text-shadow: 0 0 10px #0ff;
            font-size: 16px;
        }

        /* ✅ BIGGER BUTTONS FOR PHONE */
        .card a {
            display: block;
            padding: 12px;
            margin: 8px 0;
            color: #fff;
            text-decoration: none;
            border: 1px solid #ff00ff;
            border-radius: 8px;
            background: transparent;
            font-size: 14px;
            min-height: 45px;
        }

        .card a:hover {
            background: #ff00ff;
            color: #000;
            box-shadow: 0 0 10px #ff00ff;
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