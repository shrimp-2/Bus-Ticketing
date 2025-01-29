<?php
include "auth.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout</title>
    <link rel="stylesheet" href="css/checkoutt.css">
</head>
<body>
<div class="nav">
    <?php include 'nav.php'; ?>
</div>

<div class="checkout-wrapper">
    <div class="checkout-container">
        <?php
        include 'connection.php'; 

        if (isset($_GET['seats']) && isset($_GET['total_price']) && isset($_GET['route_id']) && isset($_GET['bus_number'])) {
            $seats = explode(',', $_GET['seats']);
            $total_price = $_GET['total_price'];
            $route_id = $_GET['route_id'];
            $bus_number = $_GET['bus_number'];

            echo "<h2>Checkout</h2>";
            echo "<p>Bus Number: <strong>$bus_number</strong></p>";
            echo "<p>Total Price: <strong>Rs$total_price</strong></p>";
            echo "<div class='seat-details'>Selected Seats: <strong>" . implode(", ", $seats) . "</strong></div>";
            echo "<form action='process_payment.php' method='POST'>";
            echo "<input type='hidden' name='bus_number' value='$bus_number'>";
            echo "<input type='hidden' name='seats' value='" . implode(',', $seats) . "'>";
            echo "<input type='hidden' name='total_price' value='$total_price'>";
            
            // Generate QR code (using a free QR code generator API)
            $qr_data = "Payment for Bus Ticket: Bus Number: $bus_number, Seats: " . implode(',', $seats) . ", Total Price: Rs$total_price"; // Payment info string
            $qr_code_url = "https://api.qrserver.com/v1/create-qr-code/?data=" . urlencode($qr_data) . "&size=150x150"; // Create QR Code URL
        
            // Display the QR code with payment instructions
            echo '<h3>Scan this QR code to complete your payment:</h3>';
            echo '<div class="qr-container">';
            echo "<img src='$qr_code_url' alt='QR Code for Payment'>";
            echo '</div>';
            echo "<button type='submit'>Pay Now</button>";
            echo "</form>";
        } else {
            echo "<p>Invalid request. Please try again.</p>";
        }
        ?>
    </div>
</div>
</body>
</html>
