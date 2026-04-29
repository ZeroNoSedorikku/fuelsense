<?php
$conn = pg_connect("host=localhost dbname=smartfuel_db user=postgres password=cedric");

if (!$conn) {
    echo "Connection failed!";
}
?>