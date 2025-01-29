<?php
$query = "SELECT * FROM bookings WHERE booking_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $bookingID);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();

$passengerName = $result['passenger_name'];

$qrCodeUrl = "https://chart.googleapis.com/chart?chs=150x150&cht=qr&chl=BookingID:$bookingID&choe=UTF-8";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .ticket-container {
            background-color: #ffffff;
            width: 350px;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            color: #333333;
        }

        .ticket-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .ticket-header h1 {
            font-size: 1.5em;
            color: #ff5722;
        }

        .ticket-header h3 {
            margin-top: -10px;
            color: #555555;
        }

        .ticket-details {
            font-size: 0.9em;
            margin-bottom: 15px;
        }

        .ticket-details span {
            font-weight: bold;
            color: #3f51b5;
        }

        .ticket-section {
            border-top: 1px solid #dddddd;
            padding-top: 10px;
            margin-top: 10px;
        }

        .ticket-section p {
            margin: 5px 0;
        }

        .qr-code {
            text-align: center;
            margin-top: 15px;
        }

        .qr-code img {
            width: 100px;
            height: 100px;
        }

        .footer {
            text-align: center;
            font-size: 0.8em;
            color: #777777;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="ticket-container">
        <div class="ticket-header">
            <h1>Bus Ticket</h1>
            <h3>Travel with Comfort</h3>
        </div>
        
        <div class="ticket-details">
            <p><span>Passenger Name:</span> <?php echo htmlspecialchars($passengerName); ?></p>
            <p><span>Bus Number:</span> <?php echo htmlspecialchars($busNumber); ?></p>
            <p><span>Route:</span> <?php echo htmlspecialchars($route); ?></p>
            <p><span>Date:</span> <?php echo htmlspecialchars($date); ?></p>
            <p><span>Departure Time:</span> <?php echo htmlspecialchars($departureTime); ?></p>
            <p><span>Seats:</span> <?php echo htmlspecialchars($seats); ?></p>
            <p><span>Total Price:</span> <?php echo htmlspecialchars($totalPrice); ?></p>
        </div>

        <div class="ticket-section">
            <p><span>Booking ID:</span> <?php echo htmlspecialchars($bookingID); ?></p>
        </div>

        <div class="qr-code">
            <img src="<?php echo htmlspecialchars($qrCodeUrl); ?>" alt="QR Code">
        </div>

        <div class="footer">
            Thank you for choosing our service!
        </div>
    </div>
</body>
</html>
