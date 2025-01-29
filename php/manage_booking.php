<?php
include '../connection.php'; 

// Handle Add Booking (Create)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_booking'])) {
    $bus_number = $_POST['bus_number'];
    $seat_id = $_POST['seat_id'];
    $price = $_POST['price'];

    $query = "INSERT INTO booked_seats (bus_number, seat_id, price) VALUES (?, ?, ?)";
    $stmt = $con->prepare($query);
    $stmt->bind_param("ssd", $bus_number, $seat_id, $price);
    $stmt->execute();
    echo "Booking added successfully!";
}

// Handle Delete Booking (Delete)
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $query = "DELETE FROM booked_seats WHERE id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    echo "Booking deleted successfully!";
}

// Handle Update Booking (Update)
if (isset($_POST['update_booking'])) {
    $id = $_POST['id'];
    $bus_number = $_POST['bus_number'];
    $seat_id = $_POST['seat_id'];
    $price = $_POST['price'];

    $query = "UPDATE booked_seats SET bus_number = ?, seat_id = ?, price = ? WHERE id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("ssdi", $bus_number, $seat_id, $price, $id);
    $stmt->execute();
    echo "Booking updated successfully!";
}

// Fetch all bookings
$query = "SELECT * FROM booked_seats";
$result = $con->query($query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Bookings</title>
    <link rel="stylesheet" href="css/managebooking.css">
</head>

<body>

    <h2>Booking List</h2>
    <table  border="1">
        <thead>
            <tr>
                <th>Booking ID</th>
                <th>Bus Number</th>
                <th>Seat ID</th>
                <th>Price</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            while ($row = $result->fetch_assoc()) {
                echo "<tr>
                        <td>{$row['id']}</td>
                        <td>{$row['bus_number']}</td>
                        <td>{$row['seat_id']}</td>
                        <td>{$row['price']}</td>
                        <td>
                            <a href='?edit={$row['id']}'>Edit</a> | 
                            <a href='?delete={$row['id']}'>Delete</a>
                        </td>
                    </tr>";
            }
            ?>
        </tbody>
    </table>

    <?php
    // Handle Edit Booking Form
    if (isset($_GET['edit'])) {
        $edit_id = $_GET['edit'];
        $query = "SELECT * FROM booked_seats WHERE id = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param("i", $edit_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $booking = $result->fetch_assoc();
    ?>

        <h2>Edit Booking</h2>
        <form method="POST" action="">
            <input type="hidden" name="id" value="<?php echo $booking['id']; ?>">
            <label for="bus_number">Bus Number:</label>
            <input type="text" name="bus_number" value="<?php echo $booking['bus_number']; ?>" required>
            <br><br>
            <label for="seat_id">Seat ID:</label>
            <input type="text" name="seat_id" value="<?php echo $booking['seat_id']; ?>" required>
            <br><br>
            <label for="price">Price:</label>
            <input type="number" name="price" value="<?php echo $booking['price']; ?>" step="0.01" required>
            <br><br>
            <button type="submit" name="update_booking">Update Booking</button>
        </form>

    <?php
    }
    ?>
    <div class="button-container">
        <a href="adminpanel.php" class="dashboard-button">Go to Dashboard</a>
    </div>

</body>
<script>
    $(document).ready(function() {
        $('#bookingTable').DataTable();
    });
</script>

</html>

<?php
$con->close();
?>