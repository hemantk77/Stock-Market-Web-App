<?php
// login_process.php

// Step 1: Start a session.
// This must be the very first thing in your script to use sessions.
session_start();

// Check if the form was submitted using the POST method.
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Step 2: Database connection variables
        $db_server = "localhost";
        $db_username = "root";
        $db_password = ""; // The default password for XAMPP is an empty string
        $db_name = "stock_app_db"; // The database name we created earlier

    // Create a new database connection
    $conn = new mysqli($db_server, $db_username, $db_password, $db_name);

    // Check the connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Step 3: Retrieve and validate form data
    $email = htmlspecialchars($_POST['email']);
    $password = htmlspecialchars($_POST['password']);

    if (empty($email) || empty($password)) {
        header("Location: login.php?error=" . urlencode("Email and password are required."));
        exit();
    }

    // Step 4: Prepare a statement to find the user by email
    $stmt = $conn->prepare("SELECT id, email, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    
    // Get the result
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        // User with that email exists, now verify the password.
        $user = $result->fetch_assoc();

        // Step 5: Verify the submitted password against the hashed password from the database.
        if (password_verify($password, $user['password'])) {
            // Password is correct!

            // Step 6: Regenerate session ID for security and store user data in the session.
            session_regenerate_id(true);
            $_SESSION['loggedin'] = true;
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];

            // Redirect the user to a protected dashboard or main page
            header("Location: dashboard.php"); // You will create this page next
            exit();

        } else {
            // Incorrect password
            header("Location: login.php?error=" . urlencode("Invalid email or password."));
            exit();
        }
    } else {
        // No user found with that email
        header("Location: login.php?error=" . urlencode("Invalid email or password."));
        exit();
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();

} else {
    // Redirect if accessed directly
    header("Location: login.php");
    exit();
}
?>