<?php
$conn = new mysqli('localhost', 'root', '', 'stock_app_db');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully!";
?>
