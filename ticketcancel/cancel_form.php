<?php
include "../auth.php";
include '../connection.php';

// Fetch user bookings
$username = $_SESSION['username'];
$sql = "SELECT b.id, b.bus_id, b.seats, b.total_price, b.booking_date, bus.bus_number 
        FROM bookings b 
        JOIN buses bus ON b.bus_id = bus.id 
        WHERE b.username = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cancel Ticket</title>
    <link rel="stylesheet" href="css/cancelform.css">
</head>
<body>
    <div class="container">
        <header>
            <h2>Cancel Your Ticket</h2>
        </header>
        <main>
            <form action="cancel_ticket.php" method="POST">
                <div class="form-group">
                    <label for="booking">Select Your Booking</label>
                    <select name="booking_id" id="booking" required>
                        <option value="">Select Booking</option>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <option value="<?= $row['id']; ?>">
                                <?= "Bus Number: " . $row['bus_number'] . " - Seats: " . $row['seats']; ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <button type="submit">Cancel Ticket</button>
            </form>
        </main>
        <footer>
            <a href="../dashboard.php" class="back-button">Back to Dashboard</a>
        </footer>
    </div>
</body>
</html>
