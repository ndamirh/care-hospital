<?php
session_start();

// Display errors for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Check if the form has been submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ensure all fields are set
    if (isset($_POST["fullname"], $_POST["username"], $_POST["password"], $_POST["email"])) {
        $fullnames = trim($_POST["fullname"]);
        $username = trim($_POST["username"]);
        $password = trim($_POST["password"]);
        $email = trim($_POST["email"]);

        // Database connection details
        $servername = "localhost";
        $db_username = "root";
        $db_password = "";
        $dbname = "hospitals";

        // Create a new mysqli connection
        $conn = new mysqli($servername, $db_username, $db_password, $dbname);

        // Check the connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Check if username already exists
        $check_sql = "SELECT * FROM tbllogin WHERE username = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("s", $username);
        $check_stmt->execute();
        $result = $check_stmt->get_result();

        if ($result->num_rows > 0) {
            echo "<p>Username already exists. Please choose a different one.</p>";
            exit; // Stop further execution
        }

        // Hash the password before storing it in the database
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insert user data into the database
        $sql = "INSERT INTO tbllogin (fullname, username, password, email) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $fullnames, $username, $hashed_password, $email);

        if ($stmt->execute()) {
            $new_user_id = $stmt->insert_id; // Get the new user ID
            echo "<p>Registration successful. Your user ID is: $new_user_id. You can now <a href='userlogin.html'>login</a>.</p>";
        } else {
            echo "<p>Error during registration. Please try again later.</p>";
        }

        // Close the statement and database connection
        $stmt->close();
        $conn->close();
    } else {
        echo "<p>All fields are required.</p>";
    }
}
?>
