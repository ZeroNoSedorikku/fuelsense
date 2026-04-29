<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
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

        .tracker-box {
            background: rgba(10,10,10,0.9);
            padding: 30px;
            border-radius: 15px;
            border: 1px solid #0ff;
            box-shadow: 0 0 25px #0ff;
            width: 380px;
            text-align: center;
        }

        h2 {
            color: #0ff;
            text-shadow: 0 0 10px #0ff;
            margin-bottom: 20px;
        }

        .distance-box {
            margin: 20px 0;
            padding: 20px;
            border: 1px solid #0ff;
            border-radius: 10px;
            font-size: 24px;
            box-shadow: 0 0 15px #0ff inset;
        }

        .distance-box span {
            color: #ff00ff;
            text-shadow: 0 0 10px #ff00ff;
        }

        button {
            width: 100%;
            padding: 12px;
            margin: 8px 0;
            font-size: 14px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: 0.3s;
            font-family: 'Orbitron', sans-serif;
        }

        #start {
            background: transparent;
            border: 1px solid #0f0;
            color: #0f0;
        }

        #start:hover {
            background: #0f0;
            color: black;
            box-shadow: 0 0 15px #0f0;
        }

        #stop {
            background: transparent;
            border: 1px solid #ff004c;
            color: #ff004c;
        }

        #stop:hover {
            background: #ff004c;
            color: black;
            box-shadow: 0 0 15px #ff004c;
        }

        .status {
            font-size: 12px;
            margin-top: 10px;
            color: #ff00ff;
        }

        .back {
            margin-top: 15px;
        }

        .back a {
            display: inline-block;
            padding: 10px 15px;
            border: 1px solid #0ff;
            border-radius: 8px;
            color: #0ff;
            text-decoration: none;
            transition: 0.3s;
        }

        .back a:hover {
            background: #0ff;
            color: black;
            box-shadow: 0 0 15px #0ff;
        }

    </style>
</head>
<body>

<div class="tracker-box">
    <h2>🚗 GPS Distance Tracker</h2>

    <button id="start">Start Tracking</button>
    <button id="stop">Stop Tracking</button>

    <div class="distance-box">
        Distance: <span id="distance">0.00</span> km
    </div>

    <div class="status" id="status">Idle...</div>

    <form id="saveForm" method="POST" action="save_distance.php">
        <input type="hidden" name="distance" id="finalDistance">
        <input type="hidden" name="date" id="date">
        <input type="hidden" name="mode" value="GPS">
    </form>

    <div class="back">
        <a href="dashboard.php">⬅ Back to Dashboard</a>
    </div>
</div>

<script>
let watchId;
let lastPosition = null;
let totalDistance = 0;

function toRad(x) {
    return x * Math.PI / 180;
}

function calculateDistance(lat1, lon1, lat2, lon2) {
    const R = 6371;
    const dLat = toRad(lat2 - lat1);
    const dLon = toRad(lon2 - lon1);

    const a =
        Math.sin(dLat/2) * Math.sin(dLat/2) +
        Math.cos(toRad(lat1)) * Math.cos(toRad(lat2)) *
        Math.sin(dLon/2) * Math.sin(dLon/2);

    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
    return R * c;
}

document.getElementById("start").onclick = function () {
    totalDistance = 0;
    lastPosition = null;

    document.getElementById("status").innerText = "Tracking started...";

    watchId = navigator.geolocation.watchPosition(function(position) {
        let lat = position.coords.latitude;
        let lon = position.coords.longitude;

        if (lastPosition) {
            let dist = calculateDistance(
                lastPosition.lat,
                lastPosition.lon,
                lat,
                lon
            );
            totalDistance += dist;
        }

        lastPosition = {lat, lon};

        document.getElementById("distance").innerText = totalDistance.toFixed(2);

    }, function(error) {
        document.getElementById("status").innerText = "GPS Error: " + error.message;
    }, {
        enableHighAccuracy: true
    });
};

document.getElementById("stop").onclick = function () {
    navigator.geolocation.clearWatch(watchId);

    document.getElementById("status").innerText = "Tracking stopped ✔";

    document.getElementById("finalDistance").value = totalDistance.toFixed(2);
    document.getElementById("date").value = new Date().toISOString().split('T')[0];

    document.getElementById("saveForm").submit();
};
</script>

</body>
</html>