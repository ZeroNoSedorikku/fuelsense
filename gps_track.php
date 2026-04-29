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

    <style>
        body {
            font-family: Arial;
            background: #111;
            color: white;
            text-align: center;
            padding-top: 50px;
        }

        button {
            padding: 15px 25px;
            margin: 10px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        #start {
            background: #27ae60;
            color: white;
        }

        #stop {
            background: #c0392b;
            color: white;
        }

        .box {
            margin-top: 30px;
            font-size: 22px;
        }
    </style>
</head>
<body>

<h2>🚗 GPS Distance Tracker</h2>

<button id="start">Start Tracking</button>
<button id="stop">Stop Tracking</button>

<div class="box">
    Distance: <span id="distance">0</span> km
</div>

<form id="saveForm" method="POST" action="save_distance.php">
    <input type="hidden" name="distance" id="finalDistance">
    <input type="hidden" name="date" id="date">
    <input type="hidden" name="mode" value="GPS">
</form>

<script>
let watchId;
let lastPosition = null;
let totalDistance = 0;

function toRad(x) {
    return x * Math.PI / 180;
}

// Haversine Formula
function calculateDistance(lat1, lon1, lat2, lon2) {
    const R = 6371; // km
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
        alert("GPS Error: " + error.message);
    }, {
        enableHighAccuracy: true
    });
};

document.getElementById("stop").onclick = function () {
    navigator.geolocation.clearWatch(watchId);

    document.getElementById("finalDistance").value = totalDistance.toFixed(2);
    document.getElementById("date").value = new Date().toISOString().split('T')[0];

    document.getElementById("saveForm").submit();
};
</script>

</body>
</html>