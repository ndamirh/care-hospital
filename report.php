<?php

include('dbconnect.php'); // Include the database connection

// Step 1: Query to retrieve data from ward_bookings table
$sql = "SELECT booking_id, patient_name, patient_ic, patient_email, deposit_amount, booking_date FROM ward_bookings"; 
$result = $conn->query($sql);

if (!$result) {
    die("Error executing query for ward bookings: " . $conn->error);
}

// Step 2: Query to retrieve payment method from payments table
$sql1 = "SELECT payment_method FROM payments"; 
$result1 = $conn->query($sql1);

if (!$result1) {
    die("Error executing query for payments: " . $conn->error);
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .report-table {
            width: 100%;
            border-collapse: collapse;
        }
        .report-table, .report-table th, .report-table td {
            border: 1px solid black;
        }
        .report-table th, .report-table td {
            padding: 8px;
            text-align: left;
        }
        .button-container {
            margin: 20px 0;
        }
    </style>
</head>
<body>

<h2>Report</h2>

<?php
// Step 4: Display the data in a table in HTML format
if ($result->num_rows > 0 && $result1->num_rows > 0) {
    echo "<table class='report-table'>";
    echo "<tr><th>Booking ID</th><th>Patient Name</th><th>Patient IC</th><th>Patient Email</th><th>Deposit Amount</th><th>Booking Date</th><th>Payment Method</th></tr>";
    
    // Loop through both results independently
    while($row = $result->fetch_assoc()) {
        $paymentRow = $result1->fetch_assoc(); // Fetch one payment method for each booking

        echo "<tr>";
        echo "<td>" . $row['booking_id'] . "</td>";
        echo "<td>" . $row['patient_name'] . "</td>";
        echo "<td>" . $row['patient_ic'] . "</td>";
        echo "<td>" . $row['patient_email'] . "</td>";
        echo "<td>" . $row['deposit_amount'] . "</td>";
        echo "<td>" . $row['booking_date'] . "</td>";
        echo "<td>" . ($paymentRow ? $paymentRow['payment_method'] : 'N/A') . "</td>"; // Check if $paymentRow exists
        echo "</tr>";
    }

    echo "</table>";
} else {
    echo "No data available.";
}

// Step 5: Close the database connection
$conn->close();
?>

<!-- Step 6: Add a button for printing the report -->
<div class="button-container">
    <button onclick="window.print();">Print Report</button>
</div>

</body>
</html>
