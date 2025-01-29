<?php
session_start();
session_regenerate_id(true);

if (!isset($_SESSION['adminloginid'])) {
    header("location:adminlogin.php");
    exit();
}

include '../connection.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete']) && isset($_POST['id'])) {
    $id = intval($_POST['id']); 

    if ($id <= 0) {
        echo "Invalid ID!";
        exit();
    }

    $query = "DELETE FROM contact_form WHERE id = $id";

    if (mysqli_query($con, $query)) {
        // Redirect back to the same page to refresh the list
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
      
        echo "Error deleting record: " . mysqli_error($con);
    }
}

$query = "SELECT * FROM contact_form ORDER BY submitted_at DESC";
$result = mysqli_query($con, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Contact Submissions</title>
    <link rel="stylesheet" href="css/managecontact.css">
</head>
<body>
    <h1>Contact Form Submissions</h1>
    
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Address</th>
                <th>Message</th>
                <th>Submitted At</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                    <td><?php echo htmlspecialchars($row['phone']); ?></td>
                    <td><?php echo htmlspecialchars($row['address']); ?></td>
                    <td><?php echo htmlspecialchars($row['message']); ?></td>
                    <td><?php echo $row['submitted_at']; ?></td>
                    <td>
                       
                        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST" style="display:inline;">
                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                            <button type="submit" name="delete" onclick="return confirm('Are you sure you want to delete this submission?');">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    <div class="button-container">
        <a href="adminpanel.php" class="dashboard-button">Go to Dashboard</a>
    </div>
</body>
</html>

<?php
mysqli_close($con);
?>
