<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['specialty'])) {
    $specialty = $_POST['specialty'];

    // Database connection details
    $servername = "localhost";
    $username = "root"; // Adjust if needed
    $password = ""; // Adjust if needed
    $dbname = "hospitals"; // Adjust if needed

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Fetch doctors based on the selected specialty
    $stmt = $conn->prepare("SELECT name FROM doctors WHERE specialty = ?");
    $stmt->bind_param("s", $specialty);
    $stmt->execute();
    $result = $stmt->get_result();

    // Generate doctor dropdown options
    $options = '<option selected>Select Doctor</option>';
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $options .= '<option value="' . htmlspecialchars($row['name']) . '">' . htmlspecialchars($row['name']) . '</option>';
        }
    } else {
        $options .= '<option disabled>No doctors available</option>';
    }

    // Close the connection
    $stmt->close();
    $conn->close();

    // Return the options for the doctor dropdown
    echo $options;
}
?>
