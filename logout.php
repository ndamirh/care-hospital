<?php
session_start(); // Start the session

include('dbconnect.php');


// Destroy the session to log out the user
session_unset(); // Remove all session variables
session_destroy(); // Destroy the session

// Redirect to the main page
header("Location: mainpage.html");
exit;
?>
