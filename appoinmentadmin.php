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


// Step 2: Query to retrieve payment method from payments table
$sql1 = "SELECT * FROM appointments"; 
$appointmentsResult = $conn->query($sql1);

if (!$appointmentsResult) {
    die("Error executing query for appointments: " . $conn->error);
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
            <li class="nav-item active"><a href="appointmentadmin.php">Appointments</a></li>
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
                        <h3>Appoinments</h3>
                        
                    </div>
                    <div class="card-content">
                        <table>
                            <thead>
                                <tr>
                                    <th>Id</th>
									<th>Appoinment Date</th>
                                    <th>Time Booking</th>
									<th>Name</th>
									<th>Date Of Birth User</th>
									<th>Email</th>
									<th>No Ic User</th>
									
                                    
                                </tr>
                            </thead>
                            <tbody>
    <?php
    if ($appointmentsResult->num_rows > 0) {
        while ($appointmentRow = $appointmentsResult->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($appointmentRow['id']) . "</td>";
            echo "<td>" . htmlspecialchars($appointmentRow['appointment_date']) . "</td>";
            echo "<td>" . htmlspecialchars($appointmentRow['time']) . "</td>";
            echo "<td>" . htmlspecialchars($appointmentRow['name']) . "</td>";
            echo "<td>" . htmlspecialchars($appointmentRow['dob']) . "</td>";
            echo "<td>" . htmlspecialchars($appointmentRow['email']) . "</td>";
            echo "<td>" . htmlspecialchars($appointmentRow['nric']) . "</td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='7'>No data available.</td></tr>";
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
