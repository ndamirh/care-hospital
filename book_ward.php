<?php
include 'dbconnect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if all required form data is present
    if (isset($_POST['patientName'], $_POST['patientIC'], $_POST['patientNumber'], $_POST['patientEmail'], $_POST['wardType'], $_POST['wardPrice'])) {
        // Sanitize input data
        $patient_name = filter_var(trim($_POST['patientName']), FILTER_SANITIZE_STRING);
        $patient_ic = filter_var(trim($_POST['patientIC']), FILTER_SANITIZE_STRING);
        $patient_phone = filter_var(trim($_POST['patientNumber']), FILTER_SANITIZE_STRING);
        $patient_email = filter_var(trim($_POST['patientEmail']), FILTER_SANITIZE_EMAIL);
        $ward_type = filter_var(trim($_POST['wardType']), FILTER_SANITIZE_STRING);
        $ward_price = filter_var(trim($_POST['wardPrice']), FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

        // Validate the sanitized data
        if (filter_var($patient_email, FILTER_VALIDATE_EMAIL) && is_numeric($ward_price) && $ward_price > 0) {

            // Calculate 50% deposit
            $deposit_amount = $ward_price * 0.50;

            // Prepare SQL statement to insert booking details into 'ward_bookings' table
            $stmt = $conn->prepare("INSERT INTO ward_bookings (patient_name, patient_ic, patient_phone, patient_email, ward_name, total_price, deposit_amount) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssddd", $patient_name, $patient_ic, $patient_phone, $patient_email, $ward_type, $ward_price, $deposit_amount);

            // Execute the query and check for errors
            if ($stmt->execute()) {
                // Get the generated booking ID
                $booking_id = $stmt->insert_id;

                // Redirect to the payment page with the booking ID and deposit amount
                header("Location: payment.php?booking_id=$booking_id&deposit_amount=$deposit_amount");
                exit();
            } else {
                echo "Error: " . $stmt->error;
            }

            // Close the statement
            $stmt->close();
        } else {
            echo "Invalid input. Please check your data.";
        }
    } else {
        echo "Error: Missing form data!";
    }
}
?>
