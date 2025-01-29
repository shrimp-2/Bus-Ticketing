<?php
include '../connection.php';

$search_date = '';
$result = null;

// Handle form submissions for add, update, delete, and search operations
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_bus'])) {
        $bus_number = $_POST['bus_number'];
        $capacity = $_POST['capacity'];
        $bus_type_id = $_POST['bus_type_id'];
        $route_id = $_POST['route_id'];
        $departure_date = $_POST['departure_date'];
        $departure_time = $_POST['departure_time'];
        $arrival_time = $_POST['arrival_time'];

        $sql = "INSERT INTO buses (bus_number, capacity, bus_type_id, current_route_id, departure_date, departure_time, arrival_time)
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("siiisss", $bus_number, $capacity, $bus_type_id, $route_id, $departure_date, $departure_time, $arrival_time);
        $stmt->execute();
    } elseif (isset($_POST['update_bus'])) {
        $id = $_POST['id'];
        $bus_number = $_POST['bus_number'];
        $capacity = $_POST['capacity'];
        $bus_type_id = $_POST['bus_type_id'];
        $route_id = $_POST['route_id'];
        $departure_date = $_POST['departure_date'];
        $departure_time = $_POST['departure_time'];
        $arrival_time = $_POST['arrival_time'];

        $sql = "UPDATE buses SET bus_number=?, capacity=?, bus_type_id=?, current_route_id=?, departure_date=?, departure_time=?, arrival_time=?
                WHERE id=?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("siiisssi", $bus_number, $capacity, $bus_type_id, $route_id, $departure_date, $departure_time, $arrival_time, $id);
        $stmt->execute();
    } elseif (isset($_POST['delete_bus'])) {
        $id = $_POST['id'];
        $sql = "DELETE FROM buses WHERE id=?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
    } elseif (isset($_POST['search_buses'])) {
        $search_date = $_POST['departure_date'];
        $stmt = $con->prepare("
            SELECT buses.*, bus_types.type_name 
            FROM buses 
            LEFT JOIN bus_types ON buses.bus_type_id = bus_types.id 
            WHERE departure_date = ?
        ");
        $stmt->bind_param("s", $search_date);
        $stmt->execute();
        $result = $stmt->get_result();
    }
}

if ($result === null) {
    $result = $con->query("
        SELECT buses.*, bus_types.type_name 
        FROM buses 
        LEFT JOIN bus_types ON buses.bus_type_id = bus_types.id
    ");
}

// Fetch bus data if edit button was clicked
$edit_bus = null;
if (isset($_POST['edit_bus'])) {
    $edit_id = $_POST['id'];
    $edit_result = $con->query("SELECT * FROM buses WHERE id=$edit_id");
    $edit_bus = $edit_result->fetch_assoc();
}

$bus_types_result = $con->query("SELECT * FROM bus_types");

$routes_result = $con->query("SELECT * FROM routes");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin - Manage Buses</title>
    <link rel="stylesheet" href="css/viewbuses.css">
</head>

<body>
    <h2>Manage Buses</h2>


    <form method="POST">
        <h3><?php echo $edit_bus ? "Update Bus" : "Add New Bus"; ?></h3>
        <input type="hidden" name="id" value="<?= $edit_bus['id'] ?? ''; ?>">
        <input type="text" name="bus_number" placeholder="Bus Number" required value="<?= $edit_bus['bus_number'] ?? ''; ?>">
        <input type="number" name="capacity" placeholder="Capacity" required value="<?= $edit_bus['capacity'] ?? ''; ?>">

       
        <select name="bus_type_id" required>
            <option value="">Select Bus Type</option>
            <?php while ($bus_type = $bus_types_result->fetch_assoc()): ?>
                <option value="<?= $bus_type['id']; ?>"
                    <?= isset($edit_bus['bus_type_id']) && $edit_bus['bus_type_id'] == $bus_type['id'] ? 'selected' : ''; ?>>
                    <?= $bus_type['type_name']; ?>
                </option>
            <?php endwhile; ?>
        </select>

        <select name="route_id" required>
            <option value="">Select Route</option>
            <?php while ($route = $routes_result->fetch_assoc()): ?>
                <option value="<?= $route['id']; ?>"
                    <?= isset($edit_bus['current_route_id']) && $edit_bus['current_route_id'] == $route['id'] ? 'selected' : ''; ?>>
                    <?= $route['pickup_location'] . " to " . $route['dropoff_location']; ?>
                </option>
            <?php endwhile; ?>
        </select>

        <input type="date" name="departure_date" required value="<?= $edit_bus['departure_date'] ?? ''; ?>" required min="<?= date('Y-m-d'); ?>">
        <input type="time" name="departure_time" required value="<?= $edit_bus['departure_time'] ?? ''; ?>">
        <input type="time" name="arrival_time" required value="<?= $edit_bus['arrival_time'] ?? ''; ?>">
        <button type="submit" name="<?= $edit_bus ? 'update_bus' : 'add_bus'; ?>">
            <?= $edit_bus ? "Update Bus" : "Add Bus"; ?>
        </button>
    </form>

   
    <form method="POST" style="margin-top: 20px;">
        <h3>Search Buses by Departure Date</h3>
        <input type="date" name="departure_date" required value="<?= htmlspecialchars($search_date); ?>">
        <button type="submit" name="search_buses">Search</button>
    </form>

    <h3>Current Buses</h3>
    <div class="table-container">
        <?php if ($result->num_rows > 0): ?>
            <table border="1">
                <tr>
                    <th>Bus Number</th>
                    <th>Capacity</th>
                    <th>Type</th>
                    <th>Departure Date</th>
                    <th>Departure Time</th>
                    <th>Arrival Time</th>
                    <th>Actions</th>
                </tr>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['bus_number']; ?></td>
                        <td><?= $row['capacity']; ?></td>
                        <td><?= $row['type_name'] ?? 'Unknown'; ?></td>
                        <td><?= $row['departure_date']; ?></td>
                        <td><?= $row['departure_time']; ?></td>
                        <td><?= $row['arrival_time']; ?></td>
                        <td>
                            <div class="action-buttons">
                                
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="id" value="<?= $row['id']; ?>">
                                    <button type="submit" name="edit_bus">Edit</button>
                                </form>
                                
                                <form method="POST" style="display:inline;">
                                    <input type="hidden" name="id" value="<?= $row['id']; ?>">
                                    <button type="submit" name="delete_bus">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <p>No buses are available for the selected date: <strong><?= htmlspecialchars($search_date); ?></strong></p>
        <?php endif; ?>
    </div>

    
    <div class="button-container">
        <a href="adminpanel.php" class="dashboard-button">Go to Dashboard</a>
    </div>
</body>

</html>
