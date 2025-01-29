<?php
include "auth.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Buses</title>
    <link rel="stylesheet" href="css/findbuses.css"> 
</head>
<body>
<div>
<?php include 'nav.php'; ?>
</div>
<div class="container">
<?php
include 'connection.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pickup_location = $_POST['pickup_location'] ?? '';
    $dropoff_location = $_POST['dropoff_location'] ?? '';
    $departure_date = $_POST['departure_date'] ?? '';
    

    if ($pickup_location && $dropoff_location && $departure_date) {
        // Query to find buses based on user input
        $sql = "
            SELECT 
                b.bus_number, 
                b.capacity, 
                r.pickup_location, 
                r.dropoff_location, 
                bt.type_name AS bus_type, 
                rp.price,
                b.departure_time,
                b.arrival_time,
                r.id AS route_id  -- Adding route_id for booking
            FROM buses b
            JOIN routes r ON b.current_route_id = r.id
            JOIN bus_types bt ON b.bus_type_id = bt.id
            JOIN route_prices rp ON rp.route_id = r.id AND rp.bus_type_id = bt.id
            WHERE 
                r.pickup_location = ? 
                AND r.dropoff_location = ? 
                AND b.departure_date = ?
        ";

        $stmt = $con->prepare($sql);
        $stmt->bind_param("sss", $pickup_location, $dropoff_location, $departure_date);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "<h2>Available Buses</h2>";
            echo "<table border='1'>
                    <tr>
                        <th>Bus Number</th>
                        <th>Capacity</th>
                        <th>Bus Type</th>
                        <th>Price</th>
                        <th>Departure Time</th>
                        <th>Arrival Time</th>
                        <th>Action</th>  <!-- New column for action -->
                    </tr>";
            
            while($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>" . $row['bus_number'] . "</td>
                        <td>" . $row['capacity'] . "</td>
                        <td>" . $row['bus_type'] . "</td>
                        <td>" . $row['price'] . "</td>
                        <td>" . $row['departure_time'] . "</td>
                        <td>" . $row['arrival_time'] . "</td>
                        <td><a href='book.php?route_id=" . $row['route_id'] . "&bus_number=" . $row['bus_number'] . "'>Book Now</a></td>
                      </tr>";
            }
            echo "</table>";
        } else {
            echo "No buses available for the selected route and date.";
        }

        $stmt->close();
    } else {
        echo "Please provide pickup location, dropoff location, and departure date.";
    }
} else {
    echo "Please use the form to submit data.";
}

$con->close();
?>
</div>
</body>
</html>