<?php
include 'db.php';

pg_query($conn, "
INSERT INTO users (name, email, password, role)
VALUES ('Admin', 'admin@fuelsense.com', 'admin123', 'admin')
");

echo "Admin created!";
?>
