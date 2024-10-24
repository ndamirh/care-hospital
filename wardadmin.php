<?php
session_start(); 

include('dbconnect.php');

// Check if the user is logged in
if (!isset($_SESSION["username"])) {
    // If not logged in, redirect to the login page
    header("Location: login.php");
    exit;
}

// Retrieve the username from the session
$username = $_SESSION["username"];

// Fetch the user's data from the database
$sql = "SELECT * FROM tbladmin WHERE username='$username'";
$userResult = $conn->query($sql);

if ($userResult->num_rows > 0) {
    $userData = $userResult->fetch_assoc(); // Fetch the user data
} else {
    echo "No user data found.";
    exit;
}

// Check if the form has been submitted to add a new ward
if (isset($_POST['addWard'])) {
    $ward_name = $_POST['ward_name'];
    $ward_type = $_POST['ward_type'];
    $ward_price = $_POST['ward_price'];
    $ward_description = $_POST['ward_description'];
    $image_url = $_POST['image_url'];
    $available_beds = $_POST['available_beds'];

    // Insert the new ward into the wards table
    $insertWardSql = "INSERT INTO wards (ward_name, ward_type, ward_price, ward_description, image_url, available_beds) 
                      VALUES ('$ward_name', '$ward_type', '$ward_price', '$ward_description', '$image_url', '$available_beds')";

    if ($conn->query($insertWardSql) === TRUE) {
        echo "New ward added successfully.";
    } else {
        echo "Error: " . $insertWardSql . "<br>" . $conn->error;
    }
}

// Step 1: Query to retrieve data from ward_bookings table
$sql2 = "SELECT * FROM ward_bookings"; 
$bookingsResult = $conn->query($sql2);

if (!$bookingsResult) {
    die("Error executing query for ward bookings: " . $conn->error);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Hospital Admin Dashboard</title>
    <link rel="stylesheet" href="adminpages.css">
</head>
<body>
    <div class="sidebar">
        <div class="sidebar-header">
            <img src="img/logos.png" width="50" height="50" alt="Hospital Pantai Logo" align="left">
            <h2>Care Hospital Admin</h2>
        </div>
        <ul class="nav">
            <li class="nav-item "><a href="adminpage.php">Dashboard</a></li>
            <li class="nav-item"><a href="appoinmentadmin.php">Appointments</a></li>
            <li class="nav-item "><a href="doctoradmin.php">Doctors</a></li>
            <li class="nav-item active"><a href="wardadmin.php">Ward</a></li>
            <li class="nav-item"><a href="logout.php">Logout</a></li>
        </ul>
    </div>

    <div class="main-content">
        <header>
            <h2>Dashboard</h2>
            <div class="user-wrapper">
                <img src="img/avatar.png" alt="Admin" width="40" height="40">
                <div>
                    <h4><?php echo htmlspecialchars($userData['fullname']); ?></h4>
                    <small><?php echo htmlspecialchars($userData['username']); ?></small>
                </div>
            </div>
        </header>

        <main>
            <div class="cards">
                <!-- Ward Management Card -->
                <div class="card">
                    <div class="card-header">
                        <h3>Add New Ward</h3>
                    </div>
                    <div class="card-content">
                        <!-- Form to add a new ward -->
                        <form action="" method="POST">
                            <label for="ward_name">Ward Name:</label>
                            <input type="text" name="ward_name" required>

                            <label for="ward_type">Ward Type:</label>
                            <input type="text" name="ward_type" required>

                            <label for="ward_price">Ward Price:</label>
                            <input type="number" step="0.01" name="ward_price" required>

                            <label for="ward_description">Description:</label>
                            <textarea name="ward_description" rows="4"></textarea>

                            <label for="image_url">Image URL:</label>
                            <input type="text" name="image_url">

                            <label for="available_beds">Available Beds:</label>
                            <input type="number" name="available_beds" required>

                            <button type="submit" name="addWard">Add Ward</button>
                        </form>
                    </div>
                </div>

                <!-- Ward Booking Management Card -->
                <div class="card">
                    <div class="card-header">
                        <h3>Ward Bookings</h3>
                    </div>
                    <div class="card-content">
                        <table>
                            <thead>
                                <tr>
                                    <th>Booking Id</th>
                                    <th>Patient Name</th>
                                    <th>Patient IC</th>
                                    <th>Patient Phone</th>
                                    <th>Deposit Amount</th>
                                    <th>Payment Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($bookingsResult->num_rows > 0) {
                                    while ($row = $bookingsResult->fetch_assoc()) {
                                        echo "<tr>";
                                        echo "<td>" . htmlspecialchars($row['booking_id']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['patient_name']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['patient_ic']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['patient_phone']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['deposit_amount']) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['payment_status']) . "</td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='6'>No data available.</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
        <footer>
            <span align="center">&copy; Care Hospital 2024</span>
        </footer>
    </div>
</body>
</html>
