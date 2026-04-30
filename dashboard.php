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
            margin: 0;
            font-family: 'Orbitron', sans-serif;
            background: radial-gradient(circle at top, #0d0d0d, #000);
            color: white;

            display: flex;
            justify-content: center;   /* horizontal center */
            align-items: flex-start;   /* top align (better for scrolling) */
            min-height: 100vh;
        }

        .header {
            text-align: center;
            padding: 20px 15px 50px;
            background: rgba(0,0,0,0.6);
            border-bottom: 2px solid #0ff;
            box-shadow: 0 0 20px #0ff;
            position: relative;
        }

        .header h2 {
            font-size: 20px;
        }

        .header p {
            font-size: 12px;
        }

        .logout {
            position: absolute;
            top: 10px;
            right: 10px;
        }

        .logout a {
            font-size: 12px;
            padding: 6px 10px;
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
            background: rgba(10,10,10,0.8);
            border: 1px solid #0ff;
            border-radius: 15px;
            padding: 15px;
            width: 100%;
            max-width: 300px;
            text-align: center;
            box-shadow: 0 0 10px #0ff;
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
            padding: 8px;
            margin: 8px 0;
            color: #fff;
            text-decoration: none;
            border: 1px solid #ff00ff;
            border-radius: 8px;
            background: transparent;
            font-size: 13px;
            min-height: 45px;
        }

        .card a:hover {
            background: #ff00ff;
            color: #000;
            box-shadow: 0 0 10px #ff00ff;
        }
        .page-wrapper {
            width: 100%;
            max-width: 1000px;  /* controls centered width */
            padding: 20px;
        }

    </style>
</head>
<body>
<div class="page-wrapper">
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
</div>
</body>
</html>