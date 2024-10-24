<?php
session_start(); 

include('dbconnect.php');

// Check if the user is logged in
if (!isset($_SESSION["username"])) {
    // If not logged in, redirect to the login page
    header("Location: userlogin.php");
    exit;
}

// Retrieve the username from the session
$username = $_SESSION["username"];

// Fetch the user's data from the database
$sql = "SELECT * FROM tbllogin WHERE username='$username'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $rows = $result->fetch_assoc(); // Fetch the user data
} else {
    echo "No user data found.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Profile</title>
    <link rel="stylesheet" href="patientpages.css"> <!-- Link to your CSS file -->
</head>
<body>
    <div id="wrapper">
        <!-- Sidebar -->
        <nav class="sidebar">
            <div class="sidebar-brand">
			<img src="img/logos.png" width="50" height="50" alt="Hospital  Logo" align="left">
                <span> Care Hospitals</span>
				<span> Patient </span>
            </div>
            <ul class="sidebar-nav">
                <li><a href="profile.php" class="active">Profile</a></li>
                <li><a href="appoinmentpatient.php">Appointments</a></li>
                <li><a href="paymenthistory.php">Payment History</a></li>
                <li><a href="wardss.php">Ward</a></li>
				<li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>

        <!-- Main content -->
        <div id="content-wrapper">
            <header class="topbar">
                <span>Patient Profile</span>
            </header>

		<form action="" method="post" enctype="multipart/form-data">

            <div class="container">
                <!-- Edit Profile Form -->
                <div class="edit-profile">
                    <h2>Edit Profile</h2>
                    <form action="" method="post" enctype="multipart/form-data">
                       
                        <!-- Profile Information Fields -->
                        <div class="form-group">
                            <label for="fullname">Full Name</label>
                            <input type="text" id="fullname" name="fullname" value="<?php echo $rows['fullname']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" id="username" name="username" value="<?php echo $rows['username']; ?>" readonly>
                        </div>
						<div class="form-group">
						<label for="password">Password</label>
						<input type="text" id="password" name="password" value="<?php echo htmlspecialchars($rows['password']); ?>" readonly>
						<span class="toggle-password" onclick="togglePasswordVisibility()">👁️</span>
						</div>

                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <input type="email" id="email" name="email" value="<?php echo $rows['email']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="facebook">Facebook Username</label>
                            <input type="text" id="facebook" name="facebook" value="<?php echo $rows['fbname']; ?>">
                        </div>
                        <div class="form-group">
                            <label for="twitter">Twitter Username</label>
                            <input type="text" id="twitter" name="twitter" value="<?php echo $rows['igname']; ?>">
                        </div>
                        <button type="submit" name="Submit" class="update-btn">Update Info</button>
                    </form>
                </div>
            </div>

            <?php 
			// Handle form submission
			if (isset($_POST['Submit'])) {
				// Retrieve values from the form and sanitize them
				$fullname = $conn->real_escape_string($_POST['fullname']);
				$email = $conn->real_escape_string($_POST['email']);
				$facebookname = $conn->real_escape_string($_POST['facebook']);
				$instagramname = $conn->real_escape_string($_POST['twitter']);

				// Update the user data in the database
				$sql = "UPDATE tbllogin SET  fullname='$fullname', email='$email', fbname='$facebookname', igname='$instagramname' WHERE username='$username'";
				if ($conn->query($sql) === TRUE) {
					echo '<script type="text/javascript">
					alert("User information updated successfully.");
					window.location = "profiles.php";
					</script>';
				} else {
					echo "Error updating record: " . $conn->error;
				}
			}

			$conn->close();
			?>

        </div>
    </div>
    <footer>
        <span>&copy; Care Hospital 2024</span>
    </footer>
	
<script>
 function togglePasswordVisibility() {
        const passwordField = document.getElementById('password');
        if (passwordField.type === 'password') {
            passwordField.type = 'text';
        } else {
            passwordField.type = 'password';
        }
    }
  </script>
</body>
</html>
