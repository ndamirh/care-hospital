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

// Fetch unique specialties
$sql_specialties = "SELECT DISTINCT specialty FROM doctors";
$result_specialties = $conn->query($sql_specialties);

// Check if search form is submitted
$where_clauses = [];
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST['specialty'])) {
        $specialty = $conn->real_escape_string($_POST['specialty']);
        $where_clauses[] = "specialty = '$specialty'";
    }
    if (!empty($_POST['keyword'])) {
        $keyword = $conn->real_escape_string($_POST['keyword']);
        $where_clauses[] = "(name LIKE '%$keyword%' OR location LIKE '%$keyword%')";
    }
}

// Build query based on filters
$sql = "SELECT * FROM doctors";
if (count($where_clauses) > 0) {
    $sql .= " WHERE " . implode(" AND ", $where_clauses);
} else {
    // Default: Fetch doctors with IDs 1 to 8 initially if no search is applied
    $sql = "SELECT * FROM doctors WHERE id BETWEEN 1 AND 8";
}

$result = $conn->query($sql);

// Fetch doctors with IDs 9 to 19 for "Load More" functionality (always regardless of search)
$sql_more = "SELECT * FROM doctors WHERE id BETWEEN 9 AND 19";
$result_more = $conn->query($sql_more);

// Debugging: Check if query is correct and returns rows
if (!$result) {
    die("Error in SQL: " . $conn->error); // Display SQL errors
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ward Information</title>
    <link rel="stylesheet" href="findadoctors.css">
	 <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@400;700&family=Roboto:wght@400;700&display=swap" rel="stylesheet">  

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">

</head>
<body>
<form action="findadoctor.php" method="POST">
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
		<!-- Search Start -->
<div class="container-fluid pt-5">
    <div class="container" style="">
        <div class="text-center mx-auto mb-5" style="max-width: 500px;">
            <h5 class="d-inline-block text-black text-uppercase border-bottom border-5">Find A Doctor</h5>
            <h1 class="display-4 mb-4">Find A Doctor Professionals</h1>
            <h5 class="fw-normal">We provide professional doctors with good qualifications</h5>
        </div>
        <div class="mx-auto" style="width: 100%; max-width: 600px;">
            <form action="findadoctor.php" method="POST">
                <div class="input-group">
                    <select name="specialty" class="form-select border-primary w-25" style="height: 60px;">
                        <option value="" selected>Select Specialty</option>
                        <?php if ($result_specialties->num_rows > 0): ?>
                            <?php while ($row_specialty = $result_specialties->fetch_assoc()): ?>
                                <option value="<?php echo htmlspecialchars($row_specialty['specialty']); ?>">
                                    <?php echo htmlspecialchars($row_specialty['specialty']); ?>
                                </option>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </select>
                    <input type="text" name="keyword" class="form-control border-primary w-50" placeholder="Keyword (e.g. doctor's name)">
                    <button class="btn btn-dark border-0 w-100">Search</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Search End -->

<!-- Parallax Image Section -->
    <section class="parallax-container">
        <!-- Image background is managed by CSS. If you want text on the image, action="findadoctor.php" method="POST"include it here -->
    </section>

	
<div class="card-container" id="doctor-list">
    <?php if ($result->num_rows > 0): ?>
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="doctor-card">
                <img src="img/<?php echo htmlspecialchars($row['image']); ?>" alt="<?php echo htmlspecialchars($row['name']); ?>" class="doctor-image">
                <h3 class="doctor-name"><?php echo htmlspecialchars($row['name']); ?></h3>
                <p class="doctor-specialty"><?php echo htmlspecialchars($row['specialty']); ?></p>
                <p class="doctor-location"><?php echo htmlspecialchars($row['location']); ?></p>
                <div class="card-footer">
                    <ul class="list-unstyled">
                        <li class="d-flex">
                           <button type="button" class="btn btn-dark view-profile-btn" data-bs-toggle="modal" data-bs-target="#profileModal" 
                            data-name="<?php echo htmlspecialchars($row['name']); ?>" 
                            data-specialty="<?php echo htmlspecialchars($row['specialty']); ?>" 
                            data-location="<?php echo htmlspecialchars($row['location']); ?>"
                            data-image="img/<?php echo htmlspecialchars($row['image']); ?>"
							data-credentials="<?php echo htmlspecialchars($row['credentials']); ?>"
							data-clinic="<?php echo htmlspecialchars($row['clinic_hours']); ?>"
							>View Profile</button>
                        </li>
                    </ul>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p>No doctors found for the selected specialty.</p>
    <?php endif; ?>
</div>

<!-- "Load More" Button styled like "View Profile" but with the original color -->
<div class="text-center">
    <button id="loadMore" class="btn btn-dark btn-sm mt-4" style="background-color: #007bff; border-color: #007bff;">Load More</button>
</div>


<!-- JavaScript Libraries -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Load More button functionality
    var loadMoreBtn = document.getElementById('loadMore');
    loadMoreBtn.addEventListener('click', function() {
        // Fetch more doctors with IDs 9 to 19
        var doctorList = document.getElementById('doctor-list');
        var moreDoctorsHTML = `<?php
            while ($row_more = $result_more->fetch_assoc()) {
                echo '<div class="doctor-card">
                        <img src="img/' . htmlspecialchars($row_more['image']) . '" alt="' . htmlspecialchars($row_more['name']) . '" class="doctor-image">
                        <h3 class="doctor-name">' . htmlspecialchars($row_more['name']) . '</h3>
                        <p class="doctor-specialty">' . htmlspecialchars($row_more['specialty']) . '</p>
                        <p class="doctor-location">' . htmlspecialchars($row_more['location']) . '</p>
                        <button type="button" class="btn btn-dark view-profile-btn" data-bs-toggle="modal" data-bs-target="#profileModal"
                                data-name="' . htmlspecialchars($row_more['name']) . '"
                                data-specialty="' . htmlspecialchars($row_more['specialty']) . '"
                                data-location="' . htmlspecialchars($row_more['location']) . '"
                                data-image="img/' . htmlspecialchars($row_more['image']) . '"
                                data-credentials="' . htmlspecialchars($row_more['credentials']) . '"
                                data-clinic="' . htmlspecialchars($row_more['clinic_hours']) . '">View Profile</button>
                      </div>';
            }
        ?>`;
        // Append the fetched doctors to the doctor list
        doctorList.innerHTML += moreDoctorsHTML;
        // Disable the Load More button after loading the remaining doctors
        loadMoreBtn.style.display = 'none';
    });
});
</script>

<!-- Modal for Doctor's Profile -->
    <div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="profileModalLabel">Doctor Profile</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <img src="" alt="Doctor Image" class="img-fluid" id="doctorImage">
                    <h3 id="doctorName"></h3>
                    <p id="doctorSpecialty"></p>
                    <p id="doctorLocation"></p>
					<p id="doctorLanguage"></p>
					<p id="doctorCredentials"></p> <!-- Credentials section -->
					<p id="doctorClinicHours"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="location.href='userlogin.html'">Request Appointment</button>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
		var profileModal = document.getElementById('profileModal');
		profileModal.addEventListener('show.bs.modal', function (event) {
        // Button that triggered the modal
        var button = event.relatedTarget;

        // Extract info from data-* attributes
        var name = button.getAttribute('data-name');
        var specialty = button.getAttribute('data-specialty');
        var location = button.getAttribute('data-location');
        var image = button.getAttribute('data-image');
        var credentials = button.getAttribute('data-credentials');
        var clinic = button.getAttribute('data-clinic');
      

        // Update modal content
        var modalName = profileModal.querySelector('#doctorName');
        var modalSpecialty = profileModal.querySelector('#doctorSpecialty');
        var modalLocation = profileModal.querySelector('#doctorLocation');
        var modalImage = profileModal.querySelector('#doctorImage');
        var modalCredentials = profileModal.querySelector('#doctorCredentials');
        var modalClinic = profileModal.querySelector('#doctorClinicHours');
       

        modalName.textContent = name;
        modalSpecialty.textContent = specialty;
        modalLocation.textContent = location;
        modalImage.setAttribute('src', image);
        modalCredentials.textContent = "Credentials: " + credentials;
        modalClinic.textContent = "Clinic Hours: " + clinic;
       
    });
});

    </script>
<br>

  
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

    <p>&copy; 2024 Care Hospital</p>
</div>
	</form>
	
	    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="lib/tempusdominus/js/moment.min.js"></script>
    <script src="lib/tempusdominus/js/moment-timezone.min.js"></script>
    <script src="lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
</body>
</html>
