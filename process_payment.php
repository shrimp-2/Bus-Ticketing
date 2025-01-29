<?php
include "auth.php";
?>
<?php
include 'connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bus_number = $_POST['bus_number'];
    $seats = explode(',', $_POST['seats']); 
    $total_price = $_POST['total_price'];

    foreach ($seats as $seat_id) {
        $insert_sql = "INSERT INTO booked_seats (bus_number, seat_id, price) VALUES (?, ?, ?)";
        $stmt = $con->prepare($insert_sql);
        $stmt->bind_param("ssi", $bus_number, $seat_id, $total_price);
        if (!$stmt->execute()) {
            echo "Error booking seat $seat_id: " . $stmt->error; 
        }
    }

    echo "Payment successful! Your seats have been booked.";
    
    // Display a form to pass data to tickets.php
    echo '<div class="download-ticket-container">
            <div class="hero">
            <form method="POST" action="tickets.php">
                <input type="hidden" name="bus_number" value="' . $bus_number . '">
                <input type="hidden" name="seats" value="' . implode(',', $seats) . '">
                <input type="hidden" name="total_price" value="' . $total_price . '">
                <button type="submit">Download Ticket</button>
            </form>
            </div>
          </div>';

}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/processpayment.css">
</head>
<body>
<a href="bookingpage.php" class="dashboard-button">Go to bookingpage</a>
</body>
</html>
