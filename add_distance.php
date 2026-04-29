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
    <title>Add Distance - FuelSense</title>

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
            text-shadow: 0 0 10px #0ff;
        }

        label {
            color: #ff00ff;
        }

        input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            background: transparent;
            border: 1px solid #0ff;
            border-radius: 8px;
            color: white;
        }

        button {
            width: 100%;
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
            text-align: center;
            margin-top: 15px;
        }
    </style>
</head>
<body>

<div class="form-container">
    <h2>🚗 Add Distance</h2>

    <?php if (isset($_GET['success'])): ?>
        <p style="color:#0f0; text-align:center;">✔ Distance added!</p>
    <?php endif; ?>

    <form method="POST" action="save_distance.php">
        <label>Date</label>
        <input type="date" name="date" value="<?php echo date('Y-m-d'); ?>" required>

        <label>Distance (km)</label>
        <input type="number" name="distance" step="0.01" min="0" required>

        <button type="submit">Save Distance</button>
    </form>

    <div class="back">
        <a href="dashboard.php" style="color:#0ff;">⬅ Back</a>
    </div>
</div>

</body>
</html>