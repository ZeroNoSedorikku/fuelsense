<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <title>GPS Tracking - FuelSense</title>

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
            height: 100vh;
        }

        .box {
            background: rgba(10,10,10,0.9);
            padding: 30px;
            border-radius: 15px;
            border: 1px solid #0ff;
            box-shadow: 0 0 20px #0ff;
            width: 360px;
            text-align: center;
        }

        h2 {
            color: #0ff;
            text-shadow: 0 0 10px #0ff;
            margin-bottom: 20px;
        }

        button {
            width: 100%;
            padding: 12px;
            background: transparent;
            border: 1px solid #ff00ff;
            color: white;
            border-radius: 8px;
            cursor: pointer;
            transition: 0.3s;
            margin-bottom: 15px;
        }

        button:hover {
            background: #ff00ff;
            color: black;
            box-shadow: 0 0 15px #ff00ff;
        }

        #output {
            margin-top: 15px;
            padding: 10px;
            border: 1px solid #0ff;
            border-radius: 8px;
            min-height: 50px;
            box-shadow: 0 0 10px #0ff inset;
        }

        .status {
            margin-top: 10px;
            font-size: 12px;
            color: #ff00ff;
        }

        .back {
            margin-top: 20px;
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

<div class="box">
    <h2>📍 GPS Tracking</h2>

    <button onclick="getLocation()">Start GPS</button>

    <div id="output">Waiting for signal...</div>
    <div class="status" id="status"></div>

    <div class="back">
        <a href="dashboard.php">⬅ Back to Dashboard</a>
    </div>
</div>

<script>
function getLocation() {
    document.getElementById("status").innerHTML = "Locating...";
    
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition, showError);
    } else {
        document.getElementById("output").innerHTML = "GPS not supported";
    }
}

function showPosition(position) {
    let lat = position.coords.latitude;
    let lon = position.coords.longitude;

    document.getElementById("output").innerHTML =
        "Latitude: " + lat + "<br>Longitude: " + lon;

    document.getElementById("status").innerHTML = "Signal acquired ✔";

    // Send to PHP
    fetch("save_gps.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: "lat=" + lat + "&lon=" + lon
    });
}

function showError(error) {
    let message = "";
    switch(error.code) {
        case error.PERMISSION_DENIED:
            message = "Permission denied ❌";
            break;
        case error.POSITION_UNAVAILABLE:
            message = "Location unavailable ⚠";
            break;
        case error.TIMEOUT:
            message = "Request timeout ⏳";
            break;
        default:
            message = "Unknown error";
    }

    document.getElementById("status").innerHTML = message;
}
</script>

</body>
</html>