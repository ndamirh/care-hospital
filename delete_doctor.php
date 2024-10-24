<?php
session_start(); 

include('dbconnect.php');

// Check if the user is logged in
if (!isset($_SESSION["username"])) {
    // If not logged in, redirect to the login page
    header("Location: login.php");
    exit;
}

// Check if a doctor ID is provided via the query parameter
if (isset($_GET['doctor_id'])) {
    $doctor_id = intval($_GET['doctor_id']);

    // SQL query to delete the doctor from the database
    $sql = "DELETE FROM doctors WHERE id = $doctor_id";

    if ($conn->query($sql) === TRUE) {
        // Redirect to the doctor management page after deletion
        header("Location: doctoradmin.php?message=Doctor removed successfully");
    } else {
        echo "Error deleting doctor: " . $conn->error;
    }
} else {
    echo "No doctor ID provided.";
}
?>
