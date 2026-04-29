<?php
session_start();

// MUST check existence first
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    header("Location: login.php");
    exit();
}

// THEN check role
if ($_SESSION['role'] !== 'admin') {
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard - FuelSense</title>

    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;600&display=swap" rel="stylesheet">

    <style>
        body {
            margin: 0;
            font-family: 'Orbitron', sans-serif;
            background: radial-gradient(circle at top, #0d0d0d, #000);
            color: white;
        }

        .header {
            text-align: center;
            padding: 25px;
            border-bottom: 2px solid red;
            box-shadow: 0 0 20px red;
        }

        .header h2 {
            color: red;
            text-shadow: 0 0 10px red;
        }

        .container {
            padding: 40px;
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 25px;
        }

        .card {
            background: rgba(10,10,10,0.9);
            padding: 25px;
            border-radius: 15px;
            border: 1px solid red;
            box-shadow: 0 0 15px red;
            width: 260px;
            text-align: center;
        }

        .card h3 {
            color: red;
        }

        .card a {
            display: block;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid red;
            color: white;
            text-decoration: none;
            border-radius: 8px;
        }

        .card a:hover {
            background: red;
            color: black;
        }

        .logout {
            text-align: center;
            margin-top: 20px;
        }

        .logout a {
            color: white;
            border: 1px solid red;
            padding: 10px;
            text-decoration: none;
        }

        .logout a:hover {
            background: red;
            color: black;
        }
    </style>
</head>
<body>

<div class="header">
    <h2>🛠 Admin Dashboard</h2>
    <p>Welcome, <?php echo $_SESSION['email']; ?></p>
</div>

<div class="container">

    <div class="card">
        <h3>👥 Users</h3>
        <a href="view_users.php">View Users</a>
    </div>

    <div class="card">
        <h3>⛽ Fuel Logs</h3>
        <a href="admin_fuel_logs.php">View All Fuel Logs</a>
    </div>

    <div class="card">
        <h3>🚗 Distance Logs</h3>
        <a href="admin_distance_logs.php">View All Distance Logs</a>
    </div>

</div>

<div class="logout">
    <a href="logout.php">Logout</a>
</div>

</body>
</html>