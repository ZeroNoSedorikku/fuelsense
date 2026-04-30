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
    <title>Add Fuel - FuelSense</title>

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
            margin-bottom: 25px;
        }

        label {
            display: block;
            margin: 10px 0 5px;
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
            box-sizing: border-box; 
        }

        input:focus {
            box-shadow: 0 0 10px #0ff;
        }

        button {
            width: 100%;
            margin-top: 20px;
            padding: 12px;
            background: transparent;
            border: 1px solid #ff00ff;
            color: white;
            border-radius: 8px;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            background: #ff00ff;
            color: black;
            box-shadow: 0 0 15px #ff00ff;
        }

        .back {
            margin-top: 15px;
            text-align: center;
        }

        .back a {
            color: #0ff;
            text-decoration: none;
            font-size: 12px;
        }

        .back a:hover {
            text-shadow: 0 0 10px #0ff;
        }

    </style>
</head>
<body>

<div class="form-container">
    <h2>⛽ Add Fuel</h2>

    <form method="POST" action="save_fuel.php">
        <label>Date</label>
        <input type="date" name="date" value="<?php echo date('Y-m-d'); ?>" required>

        <label>Liters</label>
        <input type="number" name="liters" step="0.01" min="0" required>

        <label>Cost</label>
        <input type="number" name="cost" step="0.01" required>

        <button type="submit">Add Fuel</button>
    </form>

    <div class="back">
        <a href="dashboard.php">⬅ Back to Dashboard</a>
    </div>
</div>

</body>
</html>