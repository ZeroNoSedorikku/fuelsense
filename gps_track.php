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

        .tracker-box {
            background: rgba(10,10,10,0.9);
            padding: 20px;
            border-radius: 15px;
            border: 1px solid #0ff;
            box-shadow: 0 0 20px #0ff;
            width: 90%;
            max-width: 400px;
        }

        h2 {
            color: #0ff;
            text-shadow: 0 0 10px #0ff;
            font-size: 20px;
        }

        .distance-box {
            margin: 20px 0;
            padding: 15px;
            border: 1px solid #0ff;
            border-radius: 10px;
            font-size: 22px;
            box-shadow: 0 0 10px #0ff inset;
        }

        .distance-box span {
            color: #ff00ff;
        }

        button {
            width: 100%;
            padding: 10px;
            margin: 5px 0;
        }

        #start {
            border: 1px solid #0f0;
            color: #0f0;
            background: transparent;
        }

        #start:hover {
            background: #0f0;
            color: black;
        }

        #stop {
            border: 1px solid #ff004c;
            color: #ff004c;
            background: transparent;
        }

        #stop:hover {
            background: #ff004c;
            color: black;
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
            padding: 8px 12px;
            border: 1px solid #0ff;
            border-radius: 8px;
            color: #0ff;
            text-decoration: none;
        }

        .back a:hover {
            background: #0ff;
            color: black;
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
        <a href="dashboard.php">⬅ Back</a>
    </div>
</div>

<script>
let watchId = null;
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
        Math.sin(dLat/2) ** 2 +
        Math.cos(toRad(lat1)) * Math.cos(toRad(lat2)) *
        Math.sin(dLon/2) ** 2;

    const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
    return R * c;
}

document.getElementById("start").onclick = function () {
    if (!navigator.geolocation) {
        alert("GPS not supported on this device.");
        return;
    }

    totalDistance = 0;
    lastPosition = null;

    document.getElementById("status").innerText = "Tracking started...";

    watchId = navigator.geolocation.watchPosition(function(position) {

        let lat = position.coords.latitude;
        let lon = position.coords.longitude;

        // ignore bad accuracy readings
        if (position.coords.accuracy > 30) return;

        if (lastPosition) {
            let dist = calculateDistance(
                lastPosition.lat,
                lastPosition.lon,
                lat,
                lon
            );

            // ignore tiny movements (noise)
            if (dist > 0.01) {
                totalDistance += dist;
            }
        }

        lastPosition = {lat, lon};

        document.getElementById("distance").innerText = totalDistance.toFixed(2);

    }, function(error) {
        document.getElementById("status").innerText = "GPS Error: " + error.message;
    }, {
        enableHighAccuracy: true,
        maximumAge: 0
    });
};

document.getElementById("stop").onclick = function () {
    if (watchId === null) {
        alert("Start tracking first!");
        return;
    }

    navigator.geolocation.clearWatch(watchId);

    document.getElementById("status").innerText = "Tracking stopped ✔";

    if (confirm("Save this distance?")) {
        document.getElementById("finalDistance").value = totalDistance.toFixed(2);
        document.getElementById("date").value = new Date().toISOString().split('T')[0];
        document.getElementById("saveForm").submit();
    }
};
</script>

</body>
</html>