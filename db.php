<?php
$conn = pg_connect("postgresql://fuelsense_db_user:QY6ZkIZT1WdTJP2U6NW9a1T01zIx1ZJN@dpg-d7obpthj2pic73digsk0-a.singapore-postgres.render.com/fuelsense_db?sslmode=require");

if (!$conn) {
    die("Database connection failed!");
}
?>
