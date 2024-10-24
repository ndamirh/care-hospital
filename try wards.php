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

// Fetch ward data for IDs 1 to 6
$sql1 = "SELECT ward_name, ward_type, ward_price, ward_description, image_url, available_beds FROM Wards WHERE ward_id BETWEEN 1 AND 6";
$result1 = $conn->query($sql1);

// Fetch ward data for IDs 7 to 14
$sql2 = "SELECT ward_name, ward_type, ward_price, ward_description, image_url, available_beds FROM Wards WHERE ward_id BETWEEN 7 AND 14";
$result2 = $conn->query($sql2);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ward Information</title>
    <link rel="stylesheet" href="wards.css">
</head>
<body>
<form action="book.php" method="POST">
<header>
<div class="header-container">
    <a href="mainpage.html">
        <img src="img/logos.png" width="50" height="50" alt="Hospital Pantai Logo">
    </a>
    <div class="header-text">
        <p class="main-title">CARE HOSPITALS</p>
        <p class="sub-title">Caring From the Heart</p>
    </div>
    <nav class="nav-links">
		<img src="img/ambulance.png" alt="Ambulance" width="24" height="24" align="center"> <a href="tel:+606 231 3610">+606 231 3610</a>
        <a href="findadoctor.php">Find a Doctor</a>
        <div class="dropdown">
            <a href="#">Patient & Visitors</a>
            <div class="dropdown-content">
                <a href="appoinment.php">Appointments</a>
                <a href="try wards.php">Ward Rates</a>
            </div>
        </div>
        <a href="userlogin.html">Login</a>
    </nav>
</div>
</header>
	
<br>
	
<!-- Wards and Card Section -->
    <div class="container">
        <div class="header text-center">
            <h2 align="center">Wards and Care Units</h2>
			<p>Discover our ward options and find the best fit for your needs at Hospital Pantai.</p>
        </div>
	
<!-- Parallax Image Section -->
    <section class="parallax-container">
        <!-- Image background is managed by CSS. If you want text on the image, include it here -->
    </section>

		
		<br>
		
    <!-- Wards 1 to 6 -->
    <div class="card-container">
        <?php if ($result1->num_rows > 0): ?>
            <?php while ($row = $result1->fetch_assoc()): ?>
                <div class="card">
                    <h3 class="text-muted card-subtitle" align="center"><?php echo $row['ward_name']; ?></h3>
                    <img src="img/<?php echo $row['image_url']; ?>" alt="<?php echo $row['ward_name']; ?>" class="ward-image">
                    <h4 class="card-title">Price: RM <?php echo number_format($row['ward_price'], 2); ?></h4>
                    <div class="card-footer">
                        <ul class="list-unstyled">
                            <li class="d-flex">
                                <span class="icon">&#10003;</span>
                                <span><?php echo $row['ward_description']; ?></span>
                            </li>
                        </ul>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No wards available.</p>
        <?php endif; ?>
    </div>

    <br>
    
    <!-- Wards 7 to 14 -->
    <div class="card-container">
        <?php if ($result2->num_rows > 0): ?>
            <?php while ($row = $result2->fetch_assoc()): ?>
                <div class="card">
                    <h3 class="text-muted card-subtitle" align="center"><?php echo $row['ward_name']; ?></h3>
                    <h4 class="card-title">Price: RM <?php echo number_format($row['ward_price'], 2); ?></h4>
                    <div class="card-footer">
                        <ul class="list-unstyled">
                            <li class="d-flex">
                                <span class="icon">&#10003;</span>
                                <span><?php echo $row['ward_description']; ?></span>
                            </li>
                        </ul>
                    </div>
                </div>
                <br>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No wards available.</p>
        <?php endif; ?>
    </div>
</div>
<div class="contact-info">
    <ul class="contact-list" align="left">
        <li> <img src="img/waze.png" alt="Location" width="24" height="24"> <a href="https://maps.app.goo.gl/jxiWjUAmwiMTpd9JA" target="_blank" >9S, Jalan Bintang Satu, Taman Koperasi Bahagia, 83000 Batu Pahat, Malaysia</a></li>
        <li><img src="img/cs.png" alt="Customer Service" width="24" height="24"> <a href="tel:+606-231 9999">+606-231 9999</a></li>
        <li><a href="https://wa.me/60163012999" target="_blank"><img src="img/whatsapp.png" alt="WhatsApp" width="24" height="24">+6016-301 2999</a></li>
        <li><img src="img/ambulance.png" alt="Ambulance" width="24" height="24"> <a href="tel:+606 231 3610">+606 231 3610</a></li>
        <li><img src="img/fax.png" alt="Fax" width="24" height="24"><a href="tel:+606-231 3299">+606-231 3299</a></li>
    </ul>
    
    <p align="left">Follow us on:</p>
    <a href="https://www.facebook.com" target="_blank"><img src="img/fb.png" alt="Facebook" width="24" height="24" align="left"></a>
    <a href="https://www.instagram.com" target="_blank"><img src="img/ig.jpg" alt="Instagram" width="24" height="24" align="left" ></a>

    <p>&copy; 2024 Pantai Hospital</p>
</div>
	</form>
</body>
</html>
