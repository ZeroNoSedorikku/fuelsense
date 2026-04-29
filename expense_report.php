<?php
session_start();
include 'db.php';

// Protect page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Get current month
$current_month = date('m');
$current_year = date('Y');

// Get total expense and number of days recorded
$query = "
    SELECT 
        SUM(cost) as total_cost,
        COUNT(DISTINCT date) as days_logged
    FROM fuel_logs
    WHERE user_id = $1
    AND EXTRACT(MONTH FROM date) = $2
    AND EXTRACT(YEAR FROM date) = $3
";

$result = pg_query_params($conn, $query, [
    $user_id,
    $current_month,
    $current_year
]);

$data = pg_fetch_assoc($result);

$total_cost = $data['total_cost'] ?? 0;
$days_logged = $data['days_logged'] ?? 0;

// Get total days in current month
$total_days_in_month = date('t');

// Prediction logic
if ($days_logged > 0) {
    $average_per_day = $total_cost / $days_logged;
    $predicted_monthly = $average_per_day * $total_days_in_month;
} else {
    $average_per_day = 0;
    $predicted_monthly = 0;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Expense Report - FuelSense</title>

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
            margin: 0;
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
            width: 350px;
        }

        h2 {
            color: #0ff;
            text-shadow: 0 0 10px #0ff;
        }

        p {
            margin: 10px 0;
            font-size: 16px;
        }

        .highlight {
            color: #ff00ff;
            font-size: 20px;
            text-shadow: 0 0 10px #ff00ff;
        }

        a {
            display: inline-block;
            margin-top: 20px;
            color: #0ff;
            text-decoration: none;
        }

        a:hover {
            text-shadow: 0 0 10px #0ff;
        }
    </style>
</head>
<body>

<div class="card">
    <h2>📊 Monthly Expense Prediction</h2>

    <p>Total Spent This Month:</p>
    <p class="highlight">₱<?php echo number_format($total_cost, 2); ?></p>

    <p>Days with Records:</p>
    <p class="highlight"><?php echo $days_logged; ?> days</p>

    <p>Average Daily Expense:</p>
    <p class="highlight">₱<?php echo number_format($average_per_day, 2); ?></p>

    <hr>

    <p>Predicted Monthly Expense:</p>
    <p class="highlight">₱<?php echo number_format($predicted_monthly, 2); ?></p>

    <a href="dashboard.php">⬅ Back to Dashboard</a>
</div>

</body>
</html>