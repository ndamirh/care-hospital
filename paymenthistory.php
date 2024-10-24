<?php
include('dbconnect.php');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql1 = "SELECT * FROM ward_bookings ";
$result1 = $conn->query($sql1);

$sql2 = "SELECT * FROM payments ";
$result1 = $conn->query($sql2);

if (!$result1) {
    die("Query failed: " . $conn->error);
}
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ward</title>
    <link rel="stylesheet" href="historypayment.css"> <!-- Link to your separate CSS file -->
</head>
<body>
    <div id="wrapper">
        <!-- Sidebar -->
        <nav class="sidebar">
            <div class="sidebar-brand">
			<img src="img/logos.png" width="50" height="50" alt="Hospital Logo" align="left">
                <span align='center'> Care Hospitals</span>
				
            </div>
			<br>
            <ul class="sidebar-nav">
                <li><a href="profiles.php">Profile</a></li>
                <li><a href="appoinmentpatient.php">Appointments</a></li>
                <li><a href="paymenthistory.php" class="active"> History</a></li>
                <li><a href="wardss.php" >Ward</a></li>
				<li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
		
        <!-- Main content -->
        <main id="content-wrapper">
            <header class="topbar">
                <span>History</span>
            </header>

       <div class="card-container">
                <?php if ($result1->num_rows > 0): ?>
                    <?php while ($row = $result1->fetch_assoc()): ?>
                        <div class="card">
                            <h3 class="text-muted card-subtitle" align="center"><?php echo htmlspecialchars($row['booking_id']); ?></h3>
                            <h3 class="card-title" align="center"><?php echo htmlspecialchars($row['amount_paid']); ?></h3>
                            <div class="card-footer">
                                <ul class="list-unstyled">
                                    <li class="d-flex">
                                        <span class="icon">&#10003;</span>
                                        <span><?php echo htmlspecialchars($row['payment_method']); ?></span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>No history available.</p>
                <?php endif; ?>
            </div>

            <footer>
                <span>&copy; Care Hospitals 2024</span>
            </footer>
        </main>
	</div>
</body>
</html>
