
<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hospitals";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the request is an AJAX request to fetch diagnosis
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajax']) && $_POST['ajax'] === '1') {
    $symptoms = isset($_POST['symptoms']) ? $_POST['symptoms'] : [];

    if (!empty($symptoms)) {
        $diagnoses = [];
        foreach ($symptoms as $symptom) {
            $symptom = mysqli_real_escape_string($conn, $symptom);
            $query = "SELECT DISTINCT diagnoses FROM organ_symptoms_diagnoses WHERE symptoms LIKE '%$symptom%'";
            $result = $conn->query($query);

            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $diagnoses[] = $row['diagnoses'];
                }
            }
        }

        // Return diagnoses as JSON response
        echo json_encode(['diagnoses' => implode(', ', array_unique($diagnoses))]);
    } else {
        echo json_encode(['diagnoses' => 'No symptoms selected.']);
    }
    exit;
}
?>


<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Appointment</title>
<link rel="stylesheet" href="appoinments.css">
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
	
  <!-- Wards and Card Section -->
    <div class="container">
        <div class="header text-center">
            <h2 align="center">Appointment</h2>
			<p>Before make appointment , choose you simptom </p>
        </div>
	</div>
	
	<!-- Parallax Image Section -->
    <section class="parallax-container">
        <!-- Image background is managed by CSS. If you want text on the image, include it here -->
    </section>
	<br>

<form method="POST">
	  <!-- Appointment Start -->
    <div class="container-fluid py-5">
        <div class="container">
            <div class="row gx-5">
                <div class="col-lg-6 mb-5 mb-lg-0">
                    <div class="mb-4">
                        <h5 class="d-inline-block text-primary text-uppercase border-bottom border-5">Appointment</h5>
                        <h1 class="display-4">Select Your Symptom , it will show u the diagnose </h1>
                    </div>
                    <p class="mb-5">Don't miss your appoinment at Care Hospital</p>
                    <a class="btn btn-primary rounded-pill py-3 px-5 me-3" href="findadoctor.php">Find Doctor</a>
                    <a class="btn btn-outline-primary rounded-pill py-3 px-5" href="">Read More</a>
                </div>
                <div class="col-lg-6">
                    <div class="bg-light text-center rounded p-5">
                        <h1 class="mb-4">Before Book An Appointment</h1>
                        <form>
                            <div class="row g-3">
                                <div class="col-12 col-sm-6">
								 <label for="symptom-select">Select Symptoms:</label>
                                 <select id="symptom-select" name="symptoms[]" class="form-select bg-white border-0" multiple style="height: 120px;">
								<option disabled>Select Symptoms</option>
								<option value="Chest pain or discomfort">Chest pain or discomfort</option>
								<option value="Shortness of breath">Shortness of breath</option>
								<option value="Palpitations">Palpitations</option>
								<option value="Fatigue">Fatigue</option>
								<option value="Swelling in the legs">Swelling in the legs</option>
								<option value="Dizziness">Dizziness</option>
								<option value="Fainting">Fainting</option>
								<option value="Jaundice">Jaundice</option>
								<option value="Dark urine">Dark urine</option>
								<option value="Abdominal pain (upper right side)">Abdominal pain (upper right side)</option>
								<option value="Nausea">Nausea</option>
								<option value="Loss of appetite">Loss of appetite</option>
								<option value="Upper abdominal pain that radiates to the back">Upper abdominal pain that radiates to the back</option>
								<option value="Vomiting">Vomiting</option>
								<option value="Weight loss">Weight loss</option>
								<option value="Elevated blood sugar">Elevated blood sugar</option>
								<option value="Greasy or foul-smelling stools">Greasy or foul-smelling stools</option>
								<option value="Blood in urine">Blood in urine</option>
								<option value="Swelling">Swelling</option>
								<option value="High blood pressure">High blood pressure</option>
								<option value="Frequent urination">Frequent urination</option>
								<option value="Back or flank pain">Back or flank pain</option>
								<option value="Difficulty concentrating">Difficulty concentrating</option>
								<option value="Abdominal pain">Abdominal pain</option>
								<option value="Bloating">Bloating</option>
								<option value="Diarrhea">Diarrhea</option>
								<option value="Constipation">Constipation</option>
								<option value="Blood in stool">Blood in stool</option>
								<option value="Unexplained weight loss">Unexplained weight loss</option>
								<option value="Pain or burning sensation during urination">Pain or burning sensation during urination</option>
								<option value="Frequent urge to urinate">Frequent urge to urinate</option>
								<option value="Pelvic pain">Pelvic pain</option>
								<option value="Incontinence">Incontinence</option>
								<option value="Headaches">Headaches</option>
								<option value="Confusion">Confusion</option>
								<option value="Memory loss">Memory loss</option>
								<option value="Difficulty speaking or understanding">Difficulty speaking or understanding</option>
								<option value="Seizures">Seizures</option>
								<option value="Weakness or numbness in limbs">Weakness or numbness in limbs</option>
								<option value="Mood changes">Mood changes</option>
								<option value="Chronic cough">Chronic cough</option>
								<option value="Wheezing">Wheezing</option>
								<option value="Coughing up blood">Coughing up blood</option>
								<option value="Frequent respiratory infections">Frequent respiratory infections</option>
								<option value="Pain or fullness in the left upper abdomen">Pain or fullness in the left upper abdomen</option>
								<option value="Easy bleeding or bruising">Easy bleeding or bruising</option>
								<option value="Anemia">Anemia</option>
								<option value="Heartburn">Heartburn</option>
								<option value="Indigestion">Indigestion</option>
								<option value="Blood in vomit or stool">Blood in vomit or stool</option>
								<option value="Abnormal or heavy menstrual bleeding">Abnormal or heavy menstrual bleeding</option>
								<option value="Painful intercourse">Painful intercourse</option>
								<option value="Difficulty conceiving">Difficulty conceiving</option>
								<option value="Pain in the lower back or legs">Pain in the lower back or legs</option>
								</select>

                                </div>
                                <div class="col-12 col-sm-6">
                                   <h3>Possible Diagnoses</h3>
								<p id="diagnosis-result">Please select symptoms to see possible diagnoses.</p>
                                </div>
                                <div class="col-12">
                                    <button type="button" class="btn btn-primary w-100 py-3" onclick="location.href='userlogin.html'">Book Appointment</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Appointment End -->
</form>

<form>	
	 <div class="contact-info">
        <ul>
            <li>
                <a href="https://www.waze.com/ms/live-map/directions/pantai-hospital-batu-pahat-jalan-bintang-1?to=place.w.67502099.674693306.666335" target="_blank">
                    <img src="img/waze.png" alt="Location" width="24" height="24"> Location
                </a>
            </li>
            <li>
                <img src="img/cs.png" alt="Customer Service" width="24" height="24">
                <a href="tel:+6074338811">Customer Service: +607 433 8811</a>
            </li>
            <li>
                <img src="img/whatsapp.png" alt="WhatsApp" width="24" height="24">
                <a href="https://wa.me/60106688811" target="_blank">WhatsApp: +6010 668 8811</a>
            </li>
            <li>
                <img src="img/ambulance.png" alt="Ambulance" width="24" height="24">
                <a href="tel:+6074339991">Ambulance: +607 433 9991</a>
            </li>
            <li>
                <img src="img/fax.png" alt="Fax" width="24" height="24">
                <a href="fax:+6074331881">Fax: +607 433 1881</a>
            </li>
            <li>
                <a href="https://www.facebook.com/pantaihospitalbatupahat" target="_blank">
                    <img src="img/fb.png" alt="Facebook" width="24" height="24"> Facebook
                </a>
            </li>
            <li>
                <a href="https://www.instagram.com/pantaihospitalbatupahat/" target="_blank">
                    <img src="img/ig.jpg" alt="Instagram" width="24" height="24"> Instagram
                </a>
            </li>
        </ul>
        <p>&copy; 2024 Care Hospital</p>
    </div>
</form>
</form>

 <script>
        document.getElementById('symptom-select').addEventListener('change', function() {
            const selectedSymptoms = Array.from(this.selectedOptions).map(option => option.value);

            // Make AJAX request to fetch diagnoses
            if (selectedSymptoms.length > 0) {
                const formData = new FormData();
                formData.append('ajax', '1');
                selectedSymptoms.forEach(symptom => formData.append('symptoms[]', symptom));

                fetch('', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    document.getElementById('diagnosis-result').innerHTML = data.diagnoses || 'No matching diagnoses found.';
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById('diagnosis-result').innerHTML = 'Error retrieving diagnosis.';
                });
            } else {
                document.getElementById('diagnosis-result').innerHTML = 'Please select symptoms to see possible diagnoses.';
            }
        });
    </script>

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