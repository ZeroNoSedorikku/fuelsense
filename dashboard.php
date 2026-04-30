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

    <!-- Mobile -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#000000">

    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;600&display=swap" rel="stylesheet">

    <style>
        body {
            margin: 0;
            font-family: 'Orbitron', sans-serif;
            background: radial-gradient(circle at top, #0d0d0d, #000);
            color: white;

            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
        }

        .page-wrapper {
            width: 100%;
            max-width: 1000px;
            padding: 20px;
        }

        /* HEADER */
        .header {
            text-align: center;
            padding: 20px;
            background: rgba(0,0,0,0.6);
            border-bottom: 2px solid #0ff;
            box-shadow: 0 0 20px #0ff;
            border-radius: 10px;
        }

        .header h2 {
            margin: 0;
            color: #0ff;
            text-shadow: 0 0 10px #0ff;
            font-size: 22px;
        }

        .header p {
            margin-top: 8px;
            font-size: 13px;
            color: #ff00ff;
        }

        .logout {
            margin-top: 10px;
        }

        .logout a {
            display: inline-block;
            padding: 6px 12px;
            border: 1px solid #ff00ff;
            border-radius: 8px;
            color: white;
            text-decoration: none;
            font-size: 12px;
        }

        .logout a:hover {
            background: #ff00ff;
            color: black;
        }

        /* CONTAINER */
        .container {
            margin-top: 25px;
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 20px;
        }

        /* CARDS */
        .card {
            background: rgba(10,10,10,0.85);
            border: 1px solid #0ff;
            border-radius: 15px;
            padding: 20px;
            width: 100%;
            max-width: 280px;
            text-align: center;
            box-shadow: 0 0 15px #0ff;
            flex: 1 1 260px;
            transition: 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 0 25px #ff00ff;
            border-color: #ff00ff;
        }

        .card h3 {
            margin-bottom: 15px;
            color: #0ff;
            text-shadow: 0 0 10px #0ff;
            font-size: 16px;
        }

        .card a {
            display: block;
            padding: 10px;
            margin: 8px 0;
            color: #fff;
            text-decoration: none;
            border: 1px solid #ff00ff;
            border-radius: 8px;
            background: transparent;
            font-size: 13px;
            transition: 0.3s;
        }

        .card a:hover {
            background: #ff00ff;
            color: black;
            box-shadow: 0 0 10px #ff00ff;
        }

        /* MOBILE FIX */
        @media (max-width: 600px) {
            .page-wrapper {
                padding: 15px;
            }

            .header h2 {
                font-size: 18px;
            }

            .card {
                max-width: 95%;
            }
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