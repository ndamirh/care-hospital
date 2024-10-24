<?php
include 'dbconnect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if all required form data is present
    if (isset($_POST['patientName'], $_POST['patientIC'], $_POST['patientNumber'], $_POST['patientEmail'], $_POST['wardType'], $_POST['wardPrice'])) {
        // Sanitize input data
        $patient_name = trim($_POST['patientName']);
        $patient_ic = trim($_POST['patientIC']);
        $patient_phone = trim($_POST['patientNumber']);
        $patient_email = trim($_POST['patientEmail']);
        $ward_type = trim($_POST['wardType']);
        $ward_price = floatval(trim($_POST['wardPrice']));

        // Calculate 50% deposit
        $deposit_amount = $ward_price * 0.50;

        // Prepare SQL statement to insert booking details into 'ward_bookings' table
        $stmt = $conn->prepare("INSERT INTO ward_bookings (patient_name, patient_ic, patient_phone, patient_email, total_price, deposit_amount) VALUES (?, ?, ?, ?, ?, ?)");
		$stmt->bind_param("sssssd", $patient_name, $patient_ic, $patient_phone, $patient_email,  $ward_price, $deposit_amount);


        // Execute the query and check for errors
        if ($stmt->execute()) {
            // Get the generated booking ID
            $booking_id = $stmt->insert_id;

            // Redirect to the payment page with the booking ID and deposit amount
            header("Location: payment..php?booking_id=$booking_id&deposit_amount=$deposit_amount");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    } else {
        echo "Invalid input. Please check your data.";
    }
}

// Fetch ward details for display in the form
$sql1 = "SELECT ward_name, ward_price FROM Wards";
$result1 = $conn->query($sql1);
$wards = [];
if ($result1) {
    while ($row = $result1->fetch_assoc()) {
        $wards[] = $row;
    }
} else {
    echo "Error fetching ward details: " . $conn->error;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Ward</title>
    <link rel="stylesheet" href="books.css">
</head>
<body>
    <div class="container">
	    <h2 align="center"><img src="img/logos.png" width="100" height="100" alt="Hospital  Logo" align="center"></h2>
        <h2 align="center" > -FORM BOOK WARD-</h2>
		
		<!-- Ward Details Display -->
        <div  id="wardDetails" class="ward-details">
            <h3>Ward Type: <span id="displayWardType"></span></h3>
            <h3>Price: RM <span id="displayWardPrice"></span></h3>
        </div>
        
        <form id="bookingForm" action="" method="post">
            <!-- Hidden Inputs to Pass Ward Data -->
            <input type="hidden" id="wardType" name="wardType" value="<?php echo htmlspecialchars($wards[0]['ward_name']); ?>">
            <input type="hidden" id="wardPrice" name="wardPrice" value="<?php echo htmlspecialchars($wards[0]['ward_price']); ?>">

            <label for="patientName">Name:</label>
            <input type="text" id="patientName" name="patientName" required>

            <label for="patientIC">IC Number:</label>
            <input type="text" id="patientIC" name="patientIC"  required>

            <label for="patientNumber">Phone Number:</label>
            <input type="tel" id="patientNumber" name="patientNumber"   required>

            <label for="patientEmail">Email:</label>
            <input type="email" id="patientEmail" name="patientEmail"   required>
 
            <div class="button-container">
                <button type="submit" id="bookWardBtn" onclick="showTerms()">Book Now</button>
                <button type="button" id="cancelBookBtn">Cancel Booking</button>
            </div> 
        </form>
    </div>


    <script>
        // Function to retrieve URL parameters
        function getQueryParams() {
            const params = {};
            window.location.search.substring(1).split("&").forEach(function(part) {
                const [key, value] = part.split("=");
                if (key) {
                    params[decodeURIComponent(key)] = decodeURIComponent(value);
                }
            });
            return params;
        }

        // Populate ward details on page load
        window.onload = function() {
            const params = getQueryParams();
            const wardType = params['wardType'] || 'N/A';
            const wardPrice = params['wardPrice'] || '0.00';

            document.getElementById('displayWardType').innerText = wardType;
            document.getElementById('displayWardPrice').innerText = wardPrice;

            // Set hidden input values
            document.getElementById('wardType').value = wardType;
            document.getElementById('wardPrice').value = wardPrice;
        }

        function showPaymentOptions() {
            const paymentMethod = document.getElementById('paymentMethod').value;
            const bankOptions = document.getElementById('bankOptions');
            const cardDetails = document.getElementById('cardDetails');

            if (paymentMethod === 'onlineBanking') {
                bankOptions.classList.remove('hidden');
                cardDetails.classList.add('hidden');
            } else if (paymentMethod === 'creditDebitCard') {
                bankOptions.classList.add('hidden');
                cardDetails.classList.remove('hidden');
            } else {
                bankOptions.classList.add('hidden');
                cardDetails.classList.add('hidden');
            }
        }

        function showTerms() {
            document.getElementById('termsModal').style.display = 'block';
        }

        function closeTerms() {
            document.getElementById('termsModal').style.display = 'none';
        }

        document.getElementById('acceptTerms').addEventListener('change', function () {
            document.getElementById('proceedBtn').disabled = !this.checked;
        });
		
		function showTerms() {
			document.getElementById('termsModal').style.display = 'block';
		}

		function closeTerms() {
			document.getElementById('termsModal').style.display = 'none';
		}

        function proceedToPayment() {
            const paymentMethod = document.getElementById('paymentMethod').value;
            const selectedBank = document.getElementById('bankSelection').value;
            const wardType = document.getElementById('wardType').value;
            const wardPrice = document.getElementById('wardPrice').value;

            if (paymentMethod === 'onlineBanking' && selectedBank) {
                if (selectedBank === 'maybank2u') {
                    window.location.href = 'https://www.maybank2u.com.my';
                } else if (selectedBank === 'cimb') {
                    window.location.href = 'https://www.cimbclicks.com.my';
                } else if (selectedBank === 'bankIslam') {
                    window.location.href = 'https://www.bankislam.biz';
                } else if (selectedBank === 'rhb') {
                    window.location.href = 'https://www.rhb.com.my';
                } else if (selectedBank === 'affinbank') {
                    window.location.href = 'https://www.affinonline.com';
                } else if (selectedBank === 'oub') {
                    window.location.href = 'https://www.uob.com.my';
                } else {
                    alert('Please select a valid bank.');
                    return;
                }
            } else if (paymentMethod === 'creditDebitCard' && cardNumber) {
                const cardNumber = document.getElementById('cardNumber').value;
                const expirationDate = document.getElementById('expirationDate').value;
                const securityCode = document.getElementById('securityCode').value;
                const cardholderName = document.getElementById('cardholderName').value;

                if (cardNumber && expirationDate && securityCode && cardholderName) {
                    alert('Payment information received. Redirecting to payment gateway...');
                    window.location.href = 'https://www.cardpaymentgateway.com';
                } else {
                    alert('Please fill in all card details.');
                    return;
                }
            }

            // Optionally, you can submit the form here if you handle payment processing on your server
            document.getElementById('bookingForm').submit();

            closeTerms();
        }

        document.getElementById('cancelBookBtn').addEventListener('click', function() {
            const userConfirmed = confirm('Are you sure you want to cancel the booking?');
            if (userConfirmed) {
                window.location.href = 'wardss.php';
            }
        });
	</script>
</body>
</html>
