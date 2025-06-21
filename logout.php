<?php
// logout.php

// Step 1: Start the session.
session_start();

// Step 2: Unset all session variables.
// This clears all data stored in the session, like 'loggedin', 'user_id', etc.
$_SESSION = array();

// Step 3: Destroy the session.
// This removes the session from the server.
session_destroy();

// Step 4: Redirect the user to the login page.
// The user is now logged out and sent to the login screen.
header("location: login.php");
exit;
?>