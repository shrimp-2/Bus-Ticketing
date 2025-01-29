<?php
include 'auth.php';
include 'connection.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Your Seat</title>
    <link rel="stylesheet" href="css/bookseat.css"> 
</head>
<body>
<div>
    <?php include 'nav.php'; ?>
</div>

<?php
if (isset($_GET['route_id']) && isset($_GET['bus_number'])) {
    $route_id = $_GET['route_id'];
    $bus_number = $_GET['bus_number'];

    // Fetch garxa bus details
    $query = "
        SELECT 
            b.bus_type_id,
            rp.price,
            b.capacity,
            r.pickup_location,
            r.dropoff_location,
            b.departure_time,
            b.arrival_time
        FROM buses b
        JOIN route_prices rp ON rp.bus_type_id = b.bus_type_id AND rp.route_id = ?
        JOIN routes r ON r.id = ?
        WHERE b.bus_number = ?
    ";

    $stmt = $con->prepare($query);
    $stmt->bind_param("iis", $route_id, $route_id, $bus_number);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $bus_details = $result->fetch_assoc();
        $seat_price = $bus_details['price'];
        $capacity = $bus_details['capacity'];

        // Fetch booked seats and store them in a hash table for O(1) lookup
        $booked_seats = []; // This will act as our hash table
        $booked_seats_query = "SELECT seat_id FROM booked_seats WHERE bus_number = ?";
        $booked_stmt = $con->prepare($booked_seats_query);
        $booked_stmt->bind_param("s", $bus_number);
        $booked_stmt->execute();
        $booked_result = $booked_stmt->get_result();

        while ($row = $booked_result->fetch_assoc()) {
            $booked_seats[$row['seat_id']] = true; // Store seat_id as the key in the hash table
        }

        // Check if all seats are booked
        if (count($booked_seats) == $capacity) {
            echo "<div class='container'>";
            echo "<h2>All Seats Are Booked</h2>";
            echo "<p>Unfortunately, there are no seats available for this bus.</p>";
            echo "</div>";
        } else {
            echo "<div class='container'>";
            
            // Display bus information
            echo "<div class='section bus-info'>";
            echo "<h2>Bus Information</h2>";
            echo "<p>Bus Number: <strong>$bus_number</strong></p>";
            echo "<p>Pickup Location: <strong>{$bus_details['pickup_location']}</strong></p>";
            echo "<p>Dropoff Location: <strong>{$bus_details['dropoff_location']}</strong></p>";
            echo "<p>Departure Time: <strong>{$bus_details['departure_time']}</strong></p>";
            echo "<p>Arrival Time: <strong>{$bus_details['arrival_time']}</strong></p>";
            echo "</div>";

            // Seat selection
            echo "<div class='section seat-selection'>";
            echo "<h2>Select Your Seats</h2>";

            for ($row = 1; $row <= ceil($capacity / 4); $row++) {
                echo "<div class='row'>";
                for ($seat = 'A'; $seat <= 'D'; $seat++) {
                    $seat_id = $row . $seat;

                    // Check if seat is booked using the hash table
                    $is_booked = isset($booked_seats[$seat_id]); // O(1) lookup
                    $seat_class = $is_booked ? 'booked' : 'seat';
                    $onclick = $is_booked ? "" : "onclick='selectSeat(this, $seat_price)'";

                    echo "<div class='$seat_class' data-seat='$seat_id' $onclick>$seat_id</div>";
                }
                echo "</div>";
            }

            echo "</div>";

            
            echo "<div class='section total-price'>";
            echo "<p>Total Price: Rs <span id='totalPrice'>0</span></p>";
            echo "</div>";

            echo "<div class='section book-button'>";
            echo "<button id='bookButton' onclick='bookSeats()'>Book Selected Seats</button>";
            echo "</div>";

            echo "</div>"; 
        }
    } else {
        echo "<p>No bus found for this route.</p>";
    }
}
?>

<script>
    const selectedSeats = [];
    let seatPrice = 0;

    function selectSeat(element, price) {
        const seatId = element.getAttribute('data-seat');
        if (selectedSeats.includes(seatId)) {
            selectedSeats.splice(selectedSeats.indexOf(seatId), 1);
            element.classList.remove('selected');
        } else {
            selectedSeats.push(seatId);
            element.classList.add('selected');
        }
        seatPrice = price;
        document.getElementById('totalPrice').innerText = (selectedSeats.length * seatPrice).toFixed(2);
    }

    function bookSeats() {
        if (selectedSeats.length > 0) {
            const totalPrice = (selectedSeats.length * seatPrice).toFixed(2);
            const selectedSeatsStr = selectedSeats.join(",");
            window.location.href = `checkout.php?seats=${selectedSeatsStr}&total_price=${totalPrice}&route_id=<?= $route_id; ?>&bus_number=<?= $bus_number; ?>`;
        } else {
            alert("Please select at least one seat.");
        }
    }
</script>

</body>
</html>
