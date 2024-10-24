<?php
session_start();
include('dbconnect.php');

// Check if the user is logged in
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit;
}

// Fetch ward bookings data from the database
$sql = "SELECT * FROM appointments";
$bookingsResult = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ward Booking Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 12px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }

        .total {
            font-weight: bold;
            background-color: #f9f9f9;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #777;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Ward Booking Report</h1>

    <table>
        <thead>
            <tr>
                <th>Booking ID</th>
                <th>Patient Name</th>
                <th>Patient IC</th>
                <th>Patient Phone</th>
                <th>Deposit Amount (RM)</th>
                <th>Payment Status</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($bookingsResult->num_rows > 0) {
                $totalDeposits = 0;
               while ($row = $bookingsResult->fetch_assoc()) {
				echo "<tr>";
				echo "<td>" . htmlspecialchars($row['id']) . "</td>";
				echo "<td>" . htmlspecialchars($row['appointment_date']) . "</td>";
				echo "<td>" . htmlspecialchars($row['time']) . "</td>";
				echo "<td>" . htmlspecialchars($row['name']) . "</td>";
				echo "<td>" . htmlspecialchars($row['dob']) . "</td>";
				echo "<td>" . htmlspecialchars($row['email']) . "</td>";
				echo "<td>" . htmlspecialchars($row['nric']) . "</td>";
				echo "</tr>";
			}

                
            } else {
                echo "<tr><td colspan='6'>No data available.</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<div class="footer">
    &copy; Care Hospital 2024. All rights reserved.
</div>

</body>
</html>
