<?php
session_start(); 
include 'connection.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['username'])) {
        die('User is not logged in. Please log in to proceed.');
    }

    $username = $_SESSION['username'];
    $bus_number = $_POST['bus_number'];
    $seats = $_POST['seats'];
    $total_price = $_POST['total_price'];

    if (empty($bus_number) || empty($seats) || empty($total_price)) {
        die('Missing required booking details. Please try again.');
    }

    // Fetch the bus ID using the bus number
    $bus_query = "SELECT id FROM buses WHERE bus_number = ?";
    $stmt = $con->prepare($bus_query);
    $stmt->bind_param("s", $bus_number);
    $stmt->execute();
    $result = $stmt->get_result();
    $bus = $result->fetch_assoc();

    if ($bus) {
        $bus_id = $bus['id'];

        // Insert booking details into the bookings table
        $insert_query = "INSERT INTO bookings (bus_id, username, seats, total_price) VALUES (?, ?, ?, ?)";
        $stmt = $con->prepare($insert_query);
        $stmt->bind_param("isss", $bus_id, $username, $seats, $total_price);

        if ($stmt->execute()) {
            $booking_id = $stmt->insert_id;

            // Redirect to the ticket download page
            header("Location: download_tickets.php?booking_id=$booking_id");
            exit;
        } else {
            echo "Failed to save booking details.";
        }
    } else {
        echo "Invalid bus number.";
    }

    $stmt->close();
} else {
    echo "Invalid request method.";
}

$con->close();
?>
