<?php
// register_process.php 

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$db_server = "sql100.infinityfree.com"; 
$db_username = "if0_39306569"; 
$db_password = "Group2frontend"; 
$db_name = "if0_39306569_stockapp"; 

$conn = new mysqli($db_server, $db_username, $db_password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = htmlspecialchars($_POST['email']);
    $password = htmlspecialchars($_POST['password']);
    $confirm_password = htmlspecialchars($_POST['confirm_password']);
    $errors = [];

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "A valid email is required.";
    }
    if (empty($password) || strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters long.";
    }
    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    }

    if (!empty($errors)) {
        $error_message = urlencode(implode("<br>", $errors));
        header("Location: register.php?error=" . $error_message);
        exit();
    }
    
    // Check if email already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        header("Location: register.php?error=" . urlencode("This email address is already in use."));
        exit();
    }
    $stmt->close();

    // Hash the password and insert the new user
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $email, $hashed_password);

    if ($stmt->execute()) {
        header("Location: login.php?success=" . urlencode("Registration successful! Please login."));
        exit();
    } else {
        header("Location: register.php?error=" . urlencode("An error occurred during registration."));
        exit();
    }

    $stmt->close();
    $conn->close();
}
?>