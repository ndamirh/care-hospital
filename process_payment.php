<!-- process_payment.php -->
<?php
include 'dbconnect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $booking_id = $_POST['booking_id'];
    $deposit_amount = $_POST['deposit_amount'];
    $payment_method = $_POST['paymentMethod'];

    // Insert the payment details into the database
    $stmt = $conn->prepare("INSERT INTO payments (booking_id, amount_paid, payment_method) VALUES (?, ?, ?)");
    $stmt->bind_param("ids", $booking_id, $deposit_amount, $payment_method);

    if ($stmt->execute()) {
        // Update the payment status in ward_bookings
        $update_stmt = $conn->prepare("UPDATE ward_bookings SET payment_status = 'completed' WHERE booking_id = ?");
        $update_stmt->bind_param("i", $booking_id);
        $update_stmt->execute();

        // Redirect to a success page
        header("Location: success.html");
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
?>
