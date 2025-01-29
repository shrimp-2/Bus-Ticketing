<?php
include '../auth.php';
include '../connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $booking_id = $_POST['booking_id'];

    $sql = "SELECT bus_id, seats FROM bookings WHERE id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $booking_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $booking = $result->fetch_assoc();

    if ($booking) {
        $bus_id = $booking['bus_id'];
        $seats = explode(",", $booking['seats']);

        $bus_sql = "SELECT bus_number FROM buses WHERE id = ?";
        $bus_stmt = $con->prepare($bus_sql);
        $bus_stmt->bind_param("i", $bus_id);
        $bus_stmt->execute();
        $bus_result = $bus_stmt->get_result();
        $bus = $bus_result->fetch_assoc();

        if ($bus) {
            $bus_number = $bus['bus_number'];

            error_log("Deleting seats for bus: $bus_number | Seats: " . implode(",", $seats));

            foreach ($seats as $seat) {
                $seat = trim($seat);

                error_log("Attempting to delete seat: $seat for bus: $bus_number");

                $update_sql = "DELETE FROM booked_seats WHERE bus_number = ? AND seat_id = ?";
                $update_stmt = $con->prepare($update_sql);
                $update_stmt->bind_param("ss", $bus_number, $seat);
                $update_stmt->execute();

                if ($update_stmt->affected_rows === 0) {
                    error_log("Failed to delete seat: $seat");
                }
            }

            $delete_sql = "DELETE FROM bookings WHERE id = ?";
            $delete_stmt = $con->prepare($delete_sql);
            $delete_stmt->bind_param("i", $booking_id);
            $delete_stmt->execute();

            if ($delete_stmt->affected_rows > 0) {
                header("Location: confirmation.php?status=success");
                exit();
            } else {
                echo "Failed to delete booking record.";
            }
        } else {
            echo "Bus not found for the booking.";
        }
    } else {
        echo "Booking not found.";
    }
}
?>
