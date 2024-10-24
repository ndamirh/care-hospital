<?php
include('dbconnect.php');

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $name = $conn->real_escape_string($_POST['name']);
    $credentials = $conn->real_escape_string($_POST['credentials']);
    $languages = $conn->real_escape_string($_POST['languages']);
    $location = $conn->real_escape_string($_POST['location']);
    $specialty = $conn->real_escape_string($_POST['specialty']);
    $clinic_hours = $conn->real_escape_string($_POST['clinic_hours']);

    // Handle file upload
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["image"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);

    // Insert the new doctor into the database
    $sql = "INSERT INTO doctors (name, credentials, languages, location, specialty, clinic_hours, image) 
            VALUES ('$name', '$credentials', '$languages', '$location', '$specialty', '$clinic_hours', '$target_file')";

    if ($conn->query($sql) === TRUE) {
        echo "New doctor added successfully.";
        header("Location: doctoradmin.php"); // Redirect to the doctor admin page
        exit;
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>
