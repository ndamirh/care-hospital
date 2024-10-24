<?php
include 'dbconnect.php';

echo '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="books.css"> <!-- Link to your CSS file -->
    <title>Payment Page</title>
    <style>
        .hidden {
            display: none;
        }
    </style>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            function showPaymentOptions() {
                const paymentMethod = document.getElementById("paymentMethod").value;
                const bankOptions = document.getElementById("bankOptions");
                const cardDetails = document.getElementById("cardDetails");

                if (paymentMethod === "onlineBanking") {
                    bankOptions.classList.remove("hidden");
                    cardDetails.classList.add("hidden");
                } else if (paymentMethod === "creditDebitCard") {
                    bankOptions.classList.add("hidden");
                    cardDetails.classList.remove("hidden");
                } else {
                    bankOptions.classList.add("hidden");
                    cardDetails.classList.add("hidden");
                }
            }

            function proceedToPayment() {
                const paymentMethod = document.getElementById("paymentMethod").value;
                const selectedBank = document.getElementById("bankSelection").value;

                if (paymentMethod === "onlineBanking" && selectedBank) {
                    const bankUrls = {
                        maybank2u: "https://www.maybank2u.com.my",
                        cimb: "https://www.cimbclicks.com.my",
                        bankIslam: "https://www.bankislam.biz",
                        rhb: "https://www.rhb.com.my",
                        affinbank: "https://www.affinonline.com",
                        oub: "https://www.uob.com.my"
                    };

                    if (bankUrls[selectedBank]) {
                        window.location.href = bankUrls[selectedBank];
                    } else {
                        alert("Please select a valid bank.");
                        return;
                    }
                } else if (paymentMethod === "creditDebitCard") {
                    const cardNumber = document.getElementById("cardNumber").value;
                    const expirationDate = document.getElementById("expirationDate").value;
                    const securityCode = document.getElementById("securityCode").value;
                    const cardholderName = document.getElementById("cardholderName").value;

                    if (cardNumber && expirationDate && securityCode && cardholderName) {
                        alert("Payment information received. Redirecting to payment gateway...");
                        window.location.href = "https://www.cardpaymentgateway.com";
                    } else {
                        alert("Please fill in all card details.");
                        return;
                    }
                }

                // Optionally submit the form if needed
                document.getElementById("bookingForm").submit();
            }

            window.showPaymentOptions = showPaymentOptions;
            window.proceedToPayment = proceedToPayment;
        });
    </script>
</head>
<body>';

if (isset($_GET['booking_id']) && isset($_GET['deposit_amount'])) {
    $booking_id = $_GET['booking_id'];
    $deposit_amount = $_GET['deposit_amount'];

    // Display the payment form
    echo '<div class="container">
          <h3>Pay Deposit</h3>
          <p>Deposit Amount: RM ' . number_format($deposit_amount, 2) . '</p>
          <form id="bookingForm" action="process_payment.php" method="POST">
            <input type="hidden" name="booking_id" value="' . $booking_id . '">
            <input type="hidden" name="deposit_amount" value="' . $deposit_amount . '">
            <label for="paymentMethod">Payment Method:</label>
            <select id="paymentMethod" name="paymentMethod" required onchange="showPaymentOptions()">
                <option value="">Choose</option>
                <option value="onlineBanking">Online Banking</option>
                <option value="creditDebitCard">Credit/Debit Card</option>
            </select>

            <div id="bankOptions" class="hidden">
                <label for="bankSelection">Select Your Bank:</label>
                <select id="bankSelection" name="bankSelection">
                    <option value="maybank2u">Maybank2u</option>
                    <option value="cimb">CIMB Bank</option>
                    <option value="bankIslam">Bank Islam</option>
                    <option value="rhb">RHB</option>
                    <option value="affinbank">Affinbank</option>
                    <option value="oub">OUB</option>
                    <option value="other">Other</option>
                </select>
            </div>

            <div id="cardDetails" class="hidden">
                <label for="cardNumber">Card Number:</label>
                <input type="text" id="cardNumber" name="cardNumber" placeholder="1234 3212 2313 3213" required>

                <label for="expirationDate">Expiration Date:</label>
                <input type="text" id="expirationDate" name="expirationDate" placeholder="MM/YY" required>

                <label for="securityCode">Security Code:</label>
                <input type="text" id="securityCode" name="securityCode" placeholder="CVV/CVC" required>

                <label for="cardholderName">Cardholder Name:</label>
                <input type="text" id="cardholderName" name="cardholderName" required>
            </div>

            <button type="button" onclick="proceedToPayment()">Pay Now</button>
          </form>
          </div>';
}

echo '</body>
</html>';
?>
