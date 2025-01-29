<?php
require('connection.php');
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] != true) {
    echo "
    <script>
        alert('You must be logged in to access the dashboard.');
        window.location.href = 'index.php';
    </script>";
    exit;
}

if (!isset($_SESSION['username'])) {
    echo "
    <script>
        alert('Session error: Username not found. Please log in again.');
        window.location.href = 'index.php';
    </script>";
    exit;
}

// Fetch user information
$username = $_SESSION['username'];
$user_query = "SELECT full_name, username, email FROM `users` WHERE `username` = ?";
$stmt = $con->prepare($user_query);
$stmt->bind_param("s", $username);
$stmt->execute();
$user_result = $stmt->get_result();

if ($user_result->num_rows == 1) {
    $user = $user_result->fetch_assoc();
} else {
    echo "
    <script>
        alert('User not found.');
        window.location.href = 'index.php';
    </script>";
    exit;
}

// Fetch user bookings
$booking_query = "
    SELECT b.id AS booking_id, buses.bus_number, buses.departure_date, buses.departure_time, b.seats, b.total_price
    FROM bookings AS b
    JOIN buses ON b.bus_id = buses.id
    WHERE b.username = ?";
$stmt_booking = $con->prepare($booking_query);
$stmt_booking->bind_param("s", $username);
$stmt_booking->execute();
$booking_result = $stmt_booking->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="css/dashboard1.css">
</head>
<body>

    <div class="user-info">
        <h1>Welcome, <?= htmlspecialchars($user['full_name']); ?>!</h1>
        <p><strong>Username:</strong> <?= htmlspecialchars($user['username']); ?></p>
        <p><strong>Email:</strong> <?= htmlspecialchars($user['email']); ?></p>
        <a href="ticketcancel/cancel_form.php">Cancel Ticket</a>
        <a href="logout.php" class="logout">Logout</a>
        <a href="bookingpage.php" class="logout">Booking Page</a>
    </div>

    <div class="bookings">
        <h2>Your Bookings</h2>
        <?php if ($booking_result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Booking ID</th>
                        <th>Bus Number</th>
                        <th>Departure Date</th>
                        <th>Departure Time</th>
                        <th>Seats</th>
                        <th>Total Price</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($booking = $booking_result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($booking['booking_id']); ?></td>
                            <td><?= htmlspecialchars($booking['bus_number']); ?></td>
                            <td><?= htmlspecialchars($booking['departure_date']); ?></td>
                            <td><?= htmlspecialchars($booking['departure_time']); ?></td>
                            <td><?= htmlspecialchars($booking['seats']); ?></td>
                            <td><?= htmlspecialchars($booking['total_price']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>You have no bookings yet.</p>
        <?php endif; ?>
    </div>
</body>
</html>
