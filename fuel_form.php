<?php
session_start();
include 'db.php';

// Protect page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Get vehicles of user
$vehicle_query = "SELECT * FROM vehicles WHERE user_id = $1";
$vehicle_result = pg_query_params($conn, $vehicle_query, [$user_id]);

// Check if user has vehicles
if (pg_num_rows($vehicle_result) == 0) {
    echo "<p style='color:white;text-align:center;'>No vehicles found. <a href='add_vehicle.php'>Add one first</a></p>";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Fuel - FuelSense</title>

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

        .form-container {
            background: rgba(10,10,10,0.9);
            padding: 20px;
            border-radius: 15px;
            border: 1px solid #0ff;
            box-shadow: 0 0 20px #0ff;
            width: 90%;
            max-width: 400px;
        }

        h2 {
            text-align: center;
            color: #0ff;
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-top: 10px;
            color: #ff00ff;
        }

        input, select {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            background: transparent;
            border: 1px solid #0ff;
            border-radius: 8px;
            color: white;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            margin-top: 20px;
            padding: 12px;
            border: 1px solid #ff00ff;
            background: transparent;
            color: white;
            border-radius: 8px;
            cursor: pointer;
        }

        button:hover {
            background: #ff00ff;
            color: black;
        }

        .back {
            margin-top: 15px;
            text-align: center;
        }

        .back a {
            color: #0ff;
            text-decoration: none;
        }

        .back a:hover {
            text-shadow: 0 0 10px #0ff;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>⛽ Add Fuel</h2>

    <form method="POST" action="save_fuel.php" 
          onsubmit="return confirm('Save this fuel record?');">

        <!-- VEHICLE SELECT -->
        <label>Select Vehicle</label>
        <select name="vehicle_id" required>
            <option value="">Select Vehicle</option>

            <?php while ($v = pg_fetch_assoc($vehicle_result)): ?>
                <option value="<?= $v['vehicle_id'] ?>">
                    <?= htmlspecialchars($v['brand'] . ' ' . $v['model'] . ' (' . $v['cc'] . 'cc ' . $v['type'] . ')') ?>
                </option>
            <?php endwhile; ?>

        </select>

        <!-- DATE -->
        <label>Date</label>
        <input type="date" name="date" value="<?= date('Y-m-d') ?>" required>

        <!-- LITERS -->
        <label>Liters</label>
        <input type="number" name="liters" step="0.01" min="0" required>

        <!-- COST -->
        <label>Cost (₱)</label>
        <input type="number" name="cost" step="0.01" min="0" required>

        <button type="submit">Add Fuel</button>
    </form>

    <div class="back">
        <a href="dashboard.php">⬅ Back to Dashboard</a>
    </div>
</div>

</body>
</html>