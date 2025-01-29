<?php
include '../auth.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cancellation Confirmation</title>
    <link rel="stylesheet" href="css/confirmation.css">

</head>
<body>
    <h2>Cancellation Confirmation</h2>

    <?php if (isset($_GET['status']) && $_GET['status'] == 'success'): ?>
        <p>Your ticket has been successfully canceled.</p>
        <a href="../dashboard.php">View My Bookings</a>
    <?php else: ?>
        <p>There was an issue with your cancellation. Please try again later.</p>
    <?php endif; ?>
</body>
</html>
