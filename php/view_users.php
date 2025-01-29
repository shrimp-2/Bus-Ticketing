<?php
include '../connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['delete_user']) && isset($_POST['username'])) {
        $username = $_POST['username'];

        if (!empty($username)) {
            $sql = "DELETE FROM users WHERE username = ?";
            $stmt = $con->prepare($sql);

            if ($stmt === false) {
                
                die('Error preparing statement: ' . $con->error);
            }

            $stmt->bind_param("s", $username);

            if ($stmt->execute()) {
                
                echo "User deleted successfully!";
            } else {
                
                echo "Error deleting user: " . $stmt->error;
            }

            $stmt->close();
        }
    }
}

$result = $con->query("SELECT * FROM users");

if (!$result) {
    
    die('Error fetching users: ' . $con->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - Manage Users</title>
    <link rel="stylesheet" href="css/view_users.css">
</head>
<body>
    <h2>Manage Users</h2>

    
    <h3>Current Users</h3>

    <div class="table-container">
        <table>
            <tr>
                <th>Username</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['username']); ?></td>
                <td><?= htmlspecialchars($row['email']); ?></td>
                <td>
                    <div class="action-buttons">
                        
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="username" value="<?= htmlspecialchars($row['username']); ?>">
                            <button type="submit" name="delete_user" onclick="return confirm('Are you sure you want to delete this user?')">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    </div>

    <div class="button-container">
        <a href="adminpanel.php" class="dashboard-button">Go to Dashboard</a>
    </div>

</body>
</html>

<?php
$con->close();
?>
