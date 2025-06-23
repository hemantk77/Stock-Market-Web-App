<?php
$conn = new mysqli("localhost", "root", "password123", "stock_app_db", 3307);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully!";
?>
