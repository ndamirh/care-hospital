<?php
// Database connection
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

// Get doctor ID from URL
$doctor_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch doctor details based on ID
$sql = "SELECT * FROM doctors WHERE id = $doctor_id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $doctor = $result->fetch_assoc();
} else {
    echo "Doctor not found.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Profile</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <h1>Doctor Profile: <?php echo htmlspecialchars($doctor['name']); ?></h1>
    </header>

    <div class="profile-container">
        <img src="img/<?php echo htmlspecialchars($doctor['image']); ?>" alt="<?php echo htmlspecialchars($doctor['name']); ?>" class="doctor-profile-image">
        <h2><?php echo htmlspecialchars($doctor['name']); ?></h2>
        <p><strong>Specialty:</strong> <?php echo htmlspecialchars($doctor['specialty']); ?></p>
        <p><strong>Location:</strong> <?php echo htmlspecialchars($doctor['location']); ?></p>
        <p><strong>Experience:</strong> <?php echo htmlspecialchars($doctor['experience']); ?> years</p>
        <p><strong>Bio:</strong> <?php echo htmlspecialchars($doctor['bio']); ?></p>
        <button class="btn-request-appointment">Request Appointment</button>
    </div>
</body>
</html>
