<?php
// register_process.php

// This script will process the registration form data.

// Step 1: Check if the form was submitted using the POST method.
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Step 2: Retrieve the data from the form.
    // Use htmlspecialchars to prevent XSS attacks.
    $email = htmlspecialchars($_POST['email']);
    $password = htmlspecialchars($_POST['password']);
    $confirm_password = htmlspecialchars($_POST['confirm_password']);

    // Step 3: Perform server-side validation.
    $errors = [];

    // Check if email is empty or invalid.
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "A valid email is required.";
    }

    // Check if password is empty or too short.
    if (empty($password) || strlen($password) < 8) {
        $errors[] = "Password must be at least 8 characters long.";
    }

    // Check if passwords match.
    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    }

    // Step 4: If there are errors, redirect back to the form with an error message.
    if (!empty($errors)) {
        // We can pass the error message back to the registration page via a URL parameter.
        // In a real app, using sessions would be a better way to handle this.
        $error_message = urlencode(implode("<br>", $errors));
        header("Location: register.php?error=" . $error_message);
        exit(); // Stop script execution
    }

    // --- If validation passes, proceed to database interaction ---

    // Step 5: Hash the password for security before storing it.
    // NEVER store plain text passwords.
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Step 6: Connect to the database and insert the new user.
    // (Database connection code would go here)
    /*
    Example (pseudo-code):
    $db_connection = new mysqli("server", "username", "password", "database");

    // Check for existing email to prevent duplicates
    $stmt = $db_connection->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Email already exists
        header("Location: register.php?error=Email+already+exists.");
        exit();
    }

    // Insert new user
    $stmt = $db_connection->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $email, $hashed_password);

    if ($stmt->execute()) {
        // Success!
    } else {
        // Database error
        header("Location: register.php?error=An+error+occurred.");
        exit();
    }
    */


    // Step 7: Redirect to the login page with a success message.
    header("Location: login.php?success=Registration+successful.+Please+login.");
    exit();

} else {
    // If someone tries to access this script directly, redirect them.
    header("Location: register.php");
    exit();
}
?>