<?php
include('dbconnect.php');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch ward data for IDs 1 to 6
$sql1 = "SELECT ward_name, ward_type, ward_price, ward_description, image_url, available_beds FROM Wards WHERE ward_id BETWEEN 1 AND 6";
$result1 = $conn->query($sql1);

if (!$result1) {
    die("Query failed: " . $conn->error);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ward</title>
    <link rel="stylesheet" href="wardsss.css"> <!-- Link to your separate CSS file -->
</head>
<body>
    <div id="wrapper">
        <!-- Sidebar -->
        <nav class="sidebar">
            <div class="sidebar-brand">
			<img src="img/logos.png" width="50" height="50" alt="Hospital Logo" align="left">
                <span>Care Hospitals</span>
            </div>
            <ul class="sidebar-nav">
                <li><a href="profile.php">Profile</a></li>
                <li><a href="appoinments.php">Appointments</a></li>
                <li><a href="paymenthistory.php">Payment History</a></li>
                <li><a href="wardss.php" class="active">Ward</a></li>
				<li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
		

        <!-- Main content -->
        <main id="content-wrapper">
            <header class="topbar">
                <span>Ward</span>
            </header>

            <!-- Wards 1 to 6 -->
            <div class="card-container">
                <?php if ($result1->num_rows > 0): ?>
                    <?php while ($row = $result1->fetch_assoc()): ?>
                        <div class="card">
                            <h3 class="text-muted card-subtitle" align="center"><?php echo htmlspecialchars($row['ward_name']); ?></h3>
                            <img src="img/<?php echo htmlspecialchars($row['image_url']); ?>" alt="<?php echo htmlspecialchars($row['ward_name']); ?>" class="ward-image">
                            <h4 class="card-title">Price: RM <?php echo number_format($row['ward_price'], 2); ?></h4>
                            <div class="card-footer">
                                <ul class="list-unstyled">
                                    <li class="d-flex">
                                        <span class="icon">&#10003;</span>
                                        <span><?php echo htmlspecialchars($row['ward_description']); ?></span>
                                    </li>
                                    <form action="book.php" method="GET">
                                        <input type="hidden" name="wardType" value="<?php echo htmlspecialchars($row['ward_name']); ?>">
                                        <input type="hidden" name="wardPrice" value="<?php echo htmlspecialchars($row['ward_price']); ?>">
                                        <button type="submit" class="btn">Book Now</button>
                                    </form>
                                </ul>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>No wards available.</p>
                <?php endif; ?>
            </div>

            <footer>
                <span>&copy;  2024</span>
            </footer>
        </main>
	</div>
</body>
</html>
