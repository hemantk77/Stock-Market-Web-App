<?php
// register_process.php (Updated with local database credentials)

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // --- Validation (Same as before) ---
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

    // --- Database Interaction (Updated with correct local credentials) ---

    // Step 1: Database connection variables for a default XAMPP setup
    $db_server = "localhost";
    $db_username = "root";
    $db_password = "password123";
    $db_name = "stock_app_db"; // The database name we created

    // Create a new database connection
    $conn = new mysqli($db_server, $db_username, $db_password, $db_name, 3307);

    // Check the connection
    if ($conn->connect_error) {
        // In a real app, you would log this error, not show it to the user
        die("Connection failed: " . $conn->connect_error);
    }

    // Step 2: Check if email already exists to prevent duplicates
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Email already exists
        header("Location: register.php?error=" . urlencode("This email address is already in use."));
        exit();
    }
    $stmt->close();

    // Step 3: Hash the password and insert the new user
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
    $stmt->bind_param("ss", $email, $hashed_password);

    if ($stmt->execute()) {
        // If registration is successful, redirect to the login page
        header("Location: login.php?success=" . urlencode("Registration successful! Please login."));
        exit();
    } else {
        // If there was a database error
        header("Location: register.php?error=" . urlencode("An error occurred during registration."));
        exit();
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();

} else {
    // Redirect if accessed directly
    header("Location: register.php");
    exit();
}
?>