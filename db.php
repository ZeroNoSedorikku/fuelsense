<?php
$conn = pg_connect("
    host=dpg-d7obpthj2pic73digsk0-a.singapore-postgres.render.com
    port=5432
    dbname=fuelsense_db
    user=fuelsense_db_user
    password=QY6ZkIZT1WdTJP2U6NW9a1T01zIx1ZJN
    sslmode=require
");

if (!$conn) {
    die("Database connection failed!");
}
?>