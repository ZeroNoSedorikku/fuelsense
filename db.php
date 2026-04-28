<?php
$conn = pg_connect("
    host=dpg-d7obpthj2pic73digsk0-a.singapore-postgres.render.com
    dbname=fuelsense_db
    user=fuelsense_db_user
    password=YOUR_PASSWORD
");

if (!$conn) {
    die("Database connection failed!");
}
?>
