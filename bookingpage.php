<?php
include "auth.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bus Ticketing System</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/bookingpage.css">
</head>
<body>
    <div>
    <?php include 'nav.php'; ?>
    <?php
    include 'connection.php'; 

    $pickup_sql = "SELECT DISTINCT pickup_location FROM routes";
    $pickup_result = $con->query($pickup_sql);

    $dropoff_sql = "SELECT DISTINCT dropoff_location FROM routes";
    $dropoff_result = $con->query($dropoff_sql);

    $dropoff_locations = [];
    while ($dropoff = $dropoff_result->fetch_assoc()) {
        $dropoff_locations[] = $dropoff['dropoff_location'];
    }
    ?>
    </div>
    <section>
    <div class="ticket-container">
        <h2>Choose Your Ticket</h2>
        <form action="find_buses.php" method="POST">
          
            <div class="form-group">
                <label for="pickup-point">Pickup Point</label>
                <select name="pickup_location" id="pickup-point" required onchange="filterDropoffOptions()">
                    <option value="">Select Pickup Location</option>
                    <?php while ($pickup = $pickup_result->fetch_assoc()): ?>
                        <option value="<?= $pickup['pickup_location']; ?>">
                            <?= $pickup['pickup_location']; ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            
            
            <div class="form-group">
                <label for="drop-point">Dropping Point</label>
                <select name="dropoff_location" id="drop-point" required>
                    <option value="">Select Dropoff Location</option>
                    <?php foreach ($dropoff_locations as $dropoff_location): ?>
                        <option value="<?= $dropoff_location; ?>">
                            <?= $dropoff_location; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            
            <div class="form-group">
                <label for="departure-date">Departure Date</label>
                <input type="date" name="departure_date" id="departure-date" required min="<?= date('Y-m-d'); ?>">
            </div>
            
            <button type="submit">Find Tickets</button>
        </form>
    </div>

    </section>

    <script>
        function filterDropoffOptions() {
            const pickupPoint = document.getElementById("pickup-point").value;
            const dropoffSelect = document.getElementById("drop-point");
            const dropoffOptions = dropoffSelect.getElementsByTagName("option");

            for (let i = 0; i < dropoffOptions.length; i++) {
                const option = dropoffOptions[i];
                if (option.value === pickupPoint) {
                    option.style.display = "none"; 
                    option.disabled = true;        
                } else {
                    option.style.display = "block"; 
                    option.disabled = false;        
                }
            }
        }
    </script>
</body>
</html>
