<?php
session_start(); // Start the session

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Database connection details
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

    // Check if the user is logged in
    if (!isset($_SESSION["username"])) {
        // If not logged in, redirect to the login page
        header("Location: appoinmentpatient.php");
        exit;
    }

    // Retrieve the username from the session
    $username = $_SESSION["username"];

    // Fetch the user's data from the database
    $stmt = $conn->prepare("SELECT * FROM tbllogin WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $rows = $result->fetch_assoc(); // Fetch the user data
    } else {
        echo "No user data found.";
        exit;
    }

    // Get form data
    $name = $_POST['name'];
    $dob = $_POST['dob'];
    $time = $_POST['time'];
    $nric = $_POST['nric'];
    $contact = $_POST['contact'];
    $email = $_POST['email'];
    $appointment_date = $_POST['appointment_date'];
    $consent = isset($_POST['consent']) ? 1 : 0;
    $marketing_agree = isset($_POST['marketing_agree']) ? 1 : 0;
    $marketing_disagree = isset($_POST['marketing_disagree']) ? 1 : 0;

    // Validate inputs
   if (!empty($name) && !empty($dob) && !empty($nric) && !empty($contact) && !empty($email) && !empty($appointment_date) && !empty($time)) {
    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO appointments (appointment_date, name, dob, time, nric, contact, email, consent, marketing_agree, marketing_disagree) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssssiii", $appointment_date, $name, $dob, $time, $nric, $contact, $email, $consent, $marketing_agree, $marketing_disagree);


        // Execute the statement
        if ($stmt->execute()) {
            // Get the last inserted ID
            $last_id = $stmt->insert_id;
            // Generate formatted ID
            $formatted_id = "MRH" . str_pad($last_id, 5, "0", STR_PAD_LEFT);

            // Update the record with the formatted ID
            $update_stmt = $conn->prepare("UPDATE appointments SET formatted_id = ? WHERE id = ?");
            $update_stmt->bind_param("si", $formatted_id, $last_id);
            $update_stmt->execute();

            echo "<script>
                alert('Appointment successfully set! Your ID is: $formatted_id');
                document.getElementById('successModal').style.display = 'block';
            </script>";
        } else {
            echo "Error: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    } else {
        echo "<script>alert('All fields are required!');</script>";
    }

    // Close the connection
    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Custom Calendar Picker</title>
    <!-- Include Flatpickr CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="appointment.css">
</head>
<body>
	<div id="wrapper">
        <!-- Sidebar -->
        <nav class="sidebar">
            <div class="sidebar-brand">
			<img src="img/logos.png" width="50" height="50" alt="Hospital Logo" align="left">
                <span> Care Hospitals </span>
            </div>
            <ul class="sidebar-nav">
                <li><a href="profiles.php">Profile</a></li>
                <li><a href="appoinmentpatient.php" class="active">Appointments</a></li>
                <li><a href="paymenthistory.php">Payment History</a></li>
                <li><a href="wardss.php" >Ward</a></li>
				<li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>
		
        <!-- Main content -->
        <main id="content-wrapper">
            <header class="topbar">
                <span>Appoinment</span>
            </header>
			
    <div class="form-container">
        <form action="" method="post">
            <h2 align="center">- SELECT YOUR PREFERRED DATE AND TIME -</h2>

            <!-- Custom Calendar -->
            <div class="date-time-section">
                <label for="appointment-date">Select your preferred date*</label>
                <input type="hidden" id="appointment-date" name="appointment_date" class="calendar-input" placeholder="dd/mm/yyyy" required>

                <label for="appointment-time" required>Select your preferred time*</label>
				<input type="hidden" id="selected-time" name="time" required>
                <div class="time-slots">
                    <button type="button" class="time-btn">08:00 AM</button>
                    <button type="button" class="time-btn">09:00 AM</button>
                    <button type="button"class="time-btn">09:30 AM</button>
                    <button type="button" class="time-btn">10:00 AM</button>
                    <button type="button"class="time-btn">10:30 AM</button>
                    <button type="button"class="time-btn">11:00 AM</button>
                    <button type="button"class="time-btn">11:30 AM</button>
                    <button type="button" class="time-btn">12:00 PM</button>
                    <button type="button" class="time-btn">12:30 PM</button>
                    <button type="button" class="time-btn">02:00 PM</button>
                    <button type="button" class="time-btn">02:30 PM</button>
                    <button type="button" class="time-btn">03:00 PM</button>
                    <button type="button" class="time-btn">03:30 PM</button>
                    <button type="button" class="time-btn">04:00 PM</button>
                    <button type="button"class="time-btn">04:30 PM</button>
                </div>
            </div>

            <h2>Patient's Details</h2>
            <div class="patient-details">
               
                <input type="text" id="name" name="name" placeholder="Patient's Name*" required>

                
               
				<input type="date" id="dob" name="dob" class="calendar-input" placeholder="Date Of Birth" required>



                <input type="text" id="nric" name="nric" placeholder="NRIC / Passport No*" required>

                
                <input type="tel" id="contact" name="contact" placeholder="Contact Number*" required>

                
                <input type="email" id="email" name="email" placeholder="Email*" required>
            </div>

            <div class="consent">
				<label>Note: Fields marked with an asterisk '*' are mandatory.</label>
                <label><input type="checkbox" name="consent" required> I have read, understood and consent to <a id="openModal">Personal Data Protection Notice</a></label>
                <label><input type="checkbox" name="marketing_agree"> I agree to receive marketing-related messages or collaterals</label>
                <label><input type="checkbox" name="marketing_disagree"> I do not agree to receive marketing-related messages or collaterals</label>
            </div>

            <div class="submit-section">
                <button type="submit" class="btn">Submit</button>
            </div>
        </form>
    </div>
	
	<div id="successModal" class="modal" style="display:none;">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Appointment Confirmed</h2>
        <p>Your appointment has been successfully set!</p>
    </div>
	</div>

			
	<!-- The Modal -->
	<div id="myModal" class="modal">

		<!-- Modal content -->
	<div class="modal-content">
		<span class="close">&times;</span>
		<h2>IHH MY Personal Data Protection Notice</h2>
		<p>Revised on 15th November 2023 (“Effective Date”)</p>
		<p>This IHH MY Personal Data Protection Notice (“Notice”) belongs to and is adopted collectively by IHH Healthcare Malaysia (“IHH MY”, “we”, “us”, “our”, or “company”).</p>
		<p>We are committed to protecting the personal data of individuals or Data Subjects (“your”, “you” and “yours”) responsibly and in compliance with relevant data protection laws. This Notice addresses how we Process your personal data when you interact with us.</p>
		<p>This Notice may be updated or supplemented to meet local requirements or to provide you additional information. We strongly encourage that you carefully review this Notice.</p>
		<p>This Notice may also be found on the following webpages:</p>
		<ul>
			<li><a href="https://www.pantai.com.my/pdpnotice" target="_blank">Pantai Medical Centre</a></li>
			<li><a href="https://gleneagles.com.my/legal/pdpnotice" target="_blank">Gleneagles Hospital</a></li>
			<li><a href="https://princecourt.com/pdpnotice" target="_blank">Prince Court Medical Centre</a></li>
		</ul>

		<h3>Your Personal Data</h3>
		<p>For purposes of this Notice, Personal Data means any information or combination of information, relating, directly or indirectly to an identified or identifiable natural person. Depending on the nature of your interaction with us, Personal Data may include:</p>
		<ul>
			<li>Name</li>
			<li>Identification number</li>
			<li>Passport number</li>
			<li>Telephone number(s)</li>
			<li>Mailing address</li>
			<li>Email address</li>
			<li>Network traffic data</li>
			<li>Online identifiers</li>
			<li>Any other information provided to us or accessed during your interaction</li>
		</ul>
		<p>We may Process certain Personal Data about your Relatives, but only when there is a legitimate business purpose related to your relationship with us.</p>
		<p>In some cases, it may be necessary for us to Process special categories of Personal Data (including sensitive Personal Data). We only Process Sensitive Personal Data where it is required or authorized under the law, or in the case of legal claims.</p>

		<h3>How Do We Collect Your Personal Data?</h3>
		<p>We collect Personal Data from you in the following ways:</p>
		<ul>
			<li>Directly, when you create an account, register with us, or submit forms.</li>
			<li>When you disclose Personal Data in meetings, emails, or telephone conversations.</li>
			<li>When you participate in research conducted by us.</li>
			<li>When you sign up for marketing communications.</li>
			<li>When you provide feedback on our website or social media.</li>
			<li>When you visit our premises, and your images are captured by CCTV or photographs.</li>
			<li>When you submit an employment application.</li>
			<li>When you provide Personal Data for any other reason.</li>
		</ul>

		<h3>Purposes for Collecting Personal Data</h3>
		<p>Personal Data may be collected and Processed for the following purposes:</p>
		<ul>
			<li>Business purposes, including conclusion and execution of agreements, marketing, customer service, finance, and research.</li>
			<li>Human resources and personnel management, including recruitment and administration of benefits.</li>
			<li>Health, safety, and security management.</li>
			<li>Compliance with legal obligations.</li>
			<li>Vital interests, such as protecting your life or health.</li>
			<li>Direct marketing communications.</li>
			<li>Secondary purposes such as maintaining security, conducting audits, and preparing for dispute resolution.</li>
		</ul>

		<h3>Sharing Your Personal Data</h3>
		<p>Your Personal Data may be shared with our Affiliates and healthcare professionals. We may also share your data with:</p>
		<ul>
			<li>Service providers and vendors.</li>
			<li>Public and governmental authorities.</li>
			<li>Professional advisors, such as banks and auditors.</li>
			<li>Other parties during corporate transactions.</li>
		</ul>

		<h3>Cross-Border Transfer of Personal Data</h3>
		<p>Your Personal Data may be transferred to or accessed by our Affiliates and authorized external parties located internationally, which may have different data protection laws.</p>

		<h3>Retention of Personal Data</h3>
		<p>We retain your Personal Data as long as necessary to fulfill the purposes for which it was collected, or as required by applicable law.</p>

		<h3>Protection of Personal Data</h3>
		<p>We are committed to maintaining the security of your Personal Data through appropriate physical, technical, and organizational measures.</p>

		<h3>Your Rights</h3>
		<p>You may have the right to:</p>
		<ul>
			<li>Obtain information on the Processing of your Personal Data.</li>
			<li>Request access, correction, or deletion of your Personal Data.</li>
			<li>Withdraw your consent or object to the Processing of your data.</li>
			<li>Request an electronic copy of your Personal Data.</li>
		</ul>

		<h3>Contact Us</h3>
		<p>If you have any inquiries, requests, or comments regarding this Notice, please contact the Data Protection Office via:</p>
		<p>Email: <a href="mailto:my.ihh.dpo@ihhhealthcare.com">my.ihh.dpo@ihhhealthcare.com</a></p>
		<p>Written communication mailed to:</p>
		<p>Data Protection Officer,<br>
			IHH Healthcare Malaysia<br>
			Pantai Medical Centre Sdn Bhd,<br>
			Level 6, Block A,<br>
			Pantai Hospital Kuala Lumpur,<br>
			8, Jalan Bukit Pantai,<br>
			59100, Kuala Lumpur.</p>

		<h3>Updates to Notice</h3>
		<p>We may revise this Notice from time to time. Any changes will become effective as of the Effective Date when posted on our website. Please review this Notice periodically for updates.</p>

		<p>The English language version of this notice shall prevail in the event of any inconsistencies with translated versions.</p>
	</div>


	</div>

    <!-- Include Flatpickr JS -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
       // Initialize Flatpickr for appointment date
flatpickr("#appointment-date", {
    enableTime: false,
    dateFormat: "Y-m-d", // Use ISO format for consistency with the database
    minDate: "today",
    onChange: function(selectedDates, dateStr, instance) {
        document.getElementById('appointment-date').value = dateStr; // Set the value correctly
    }
});

// Initialize Flatpickr for date of birth (DOB)
flatpickr("#dob", {
    enableTime: false,
    dateFormat: "Y-m-d", // Use ISO format for consistency with the database
    maxDate: "today", // Users cannot select a future date for DOB
    onChange: function(selectedDates, dateStr, instance) {
        document.getElementById('dob').value = dateStr; // Set the value correctly
    }
});
		// Select all time buttons and the hidden input for selected time
		const timeButtons = document.querySelectorAll('.time-btn');
		const selectedTimeInput = document.getElementById('selected-time');

		// Add click event listener to each button
		timeButtons.forEach(button => {
			button.addEventListener('click', function () {
				// Remove highlight from all buttons
				timeButtons.forEach(btn => btn.classList.remove('selected'));

				// Highlight the clicked button
				this.classList.add('selected');

				// Update the hidden input value with the selected time
				selectedTimeInput.value = this.innerText; // Correctly set the value of the hidden input
			});
		});


		// Get the modal
		var modal = document.getElementById("myModal");

		// Get the link that opens the modal
		var btn = document.getElementById("openModal");

		// Get the <span> element that closes the modal
		var span = document.getElementsByClassName("close")[0];

		// When the user clicks on the link, open the modal
		btn.onclick = function() {
			modal.style.display = "block";
		}

		// When the user clicks on <span> (x), close the modal
		span.onclick = function() {
			modal.style.display = "none";
		}

		// When the user clicks anywhere outside of the modal, close it
		window.onclick = function(event) {
			if (event.target == modal) {
				modal.style.display = "none";
			}
		}
		
		document.getElementById('openModal').addEventListener('click', () => {
			modal.style.display = 'block';
		});

		span.addEventListener('click', () => {
			modal.style.display = 'none';
		});

		window.addEventListener('click', (event) => {
			if (event.target == modal) {
				modal.style.display = 'none';
			}
		});

		document.querySelector('form').addEventListener('submit', function(event) {
			const appointmentDate = document.getElementById('appointment-date').value;
			const selectedTime = selectedTimeInput.value;

			// Check if both date and time are selected
			if (!appointmentDate || !selectedTime) {
				alert('All fields are required! Please select a date and time.');
				event.preventDefault(); // Prevent form submission if date or time is missing
			}
		});


    </script>
<footer>
                <span>&copy; Care Hospitals 2024</span>
            </footer>
        </main>
	</div>
</body>
</html>