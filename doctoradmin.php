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
            <li class="nav-item active"><a href="doctoradmin.php">Doctors</a></li>
            <li class="nav-item "><a href="wardadmin.php">Ward</a></li>
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

                
                <!-- Doctors Management Card -->
                <div class="card">
                    <div class="card-header">
                        <h3>Manage Doctors</h3>
                        <button onclick="openModal()">Add New</button>
                    </div>
					
					<div class="card">
                    <div class="card-header"><!-- Add Doctor Modal -->
						<div id="addDoctorModal" class="modal">
							<div class="modal-content">
								<span class="close" onclick="closeModal()">&times;</span>
								<h2>Add New Doctor</h2>
								<form action="add_doctor.php" method="POST" enctype="multipart/form-data">
									<label for="name">Doctor Name:</label>
									<input type="text" name="name" required>

									<label for="credentials">Credentials:</label>
									<input type="text" name="credentials" required>

									<label for="languages">Languages:</label>
									<input type="text" name="languages" required>

									<label for="location">Location:</label>
									<input type="text" name="location" required>

									<label for="specialty">Specialty:</label>
									<input type="text" name="specialty" required>

									<label for="clinic_hours">Clinic Hours:</label>
									<textarea name="clinic_hours" required></textarea>

									<label for="image">Doctor's Image:</label>
									<input type="file" name="image" accept="image/*" required>

									<button type="submit">Add Doctor</button>
					</form>
    </div>
</div>
 </div>
</div>

<!-- JavaScript to handle modal visibility -->
<script>
    function openModal() {
        document.getElementById('addDoctorModal').style.display = 'block';
    }

    function closeModal() {
        document.getElementById('addDoctorModal').style.display = 'none';
    }
</script>

					
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
										echo "<td>
												<form action='delete_doctor.php' method='GET' onsubmit='return confirm(\"Are you sure you want to delete this doctor?\")'>
													<input type='hidden' name='doctor_id' value='" . htmlspecialchars($doctorsRow['id']) . "'>
													<button type='submit'>Remove</button>
												</form>
											  </td>";

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
