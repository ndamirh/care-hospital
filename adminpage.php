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

// Step 1: Query to retrieve data from ward_bookings table
$sql3 = "SELECT * FROM doctors"; 
$doctorsResult = $conn->query($sql3);

if (!$doctorsResult) {
    die("Error executing query for doctors: " . $conn->error);
}

// Step 1: Query to retrieve data from ward_bookings table
$sql2 = "SELECT * FROM ward_bookings"; 
$bookingsResult = $conn->query($sql2);

if (!$bookingsResult) {
    die("Error executing query for ward bookings: " . $conn->error);
}

// Step 2: Query to retrieve payment method from payments table
$sql1 = "SELECT payment_method FROM payments"; 
$paymentsResult = $conn->query($sql1);

if (!$paymentsResult) {
    die("Error executing query for payments: " . $conn->error);
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
            <li class="nav-item active"><a href="">Dashboard</a></li>
            <li class="nav-item"><a href="appoinmentadmin.php">Appointments</a></li>
            <li class="nav-item"><a href="doctoradmin.php">Doctors</a></li>
            <li class="nav-item"><a href="wardadmin.php">Ward</a></li>
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

                <!-- Payments Card -->
                <div class="card">
                    <div class="card-header">
                        <h3>Payments</h3>
                        
                    </div>
                    <div class="card-content">
                        <table>
                            <thead>
                                <tr>
                                    <th>Patient Name</th>
                                    <th>Amount</th>
                                    <th>Date</th>
                                    <th>Method</th>
                                    
                                </tr>
                            </thead>
                            <tbody>
                                <?php
								if ($bookingsResult->num_rows > 0 && $paymentsResult->num_rows > 0) {
									while ($bookingRow = $bookingsResult->fetch_assoc() and $paymentRow = $paymentsResult->fetch_assoc()) {
										echo "<tr>";
										echo "<td>" . htmlspecialchars($bookingRow['patient_name']) . "</td>";
										echo "<td>" . htmlspecialchars($bookingRow['deposit_amount']) . "</td>";
										echo "<td>" . htmlspecialchars($bookingRow['booking_date']) . "</td>";
										echo "<td>" . htmlspecialchars($paymentRow['payment_method']) . "</td>";
										
										echo "</tr>";
									}
								} else {
									echo "<tr><td colspan='5'>No data available.</td></tr>";
								}
								?>

                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Doctors Management Card -->
                <div class="card">
                    <div class="card-header">
                        <h3>Manage Doctors</h3>
                        <button onclick="location.href='#'">Add New</button>
                    </div>
                    <div class="card-content">
                        <!-- Table of doctors -->
                        <table>
                            <thead>
                                <tr>
                                    <th>Doctor Name</th>
                                    <th>Specialization</th>
                                    <th>Contact</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
								<?php
								if ($doctorsResult->num_rows > 0) {
									while ($doctorsRow = $doctorsResult->fetch_assoc()) {
										echo "<tr>";
										echo "<td>" . htmlspecialchars($doctorsRow['name']) . "</td>";
										echo "<td>" . htmlspecialchars($doctorsRow['specialty']) . "</td>";
										echo "<td>" . htmlspecialchars($doctorsRow['credentials']) . "</td>";
										echo "<td><button>Edit</button> <button>Remove</button></td>";
										echo "</tr>";
									}
								} else {
									echo "<tr><td colspan='5'>No data available.</td></tr>";
								}
								?>
                            </tbody>
                        </table>
                    </div>
                </div>

       
        </main>
        <footer>
            <span align="center">&copy; Care Hospital 2024</span>
        </footer>
    </div>
</body>
</html>
