<?php 
    session_start();
    session_regenerate_id(true);
   if(!isset($_SESSION['adminloginid']))
    {
        header("location:adminlogin.php");
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>admin panel</title>
    <link rel="stylesheet" href="css/adminpanel.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="header">
        <h1>ADMIN PANEL-<?php echo $_SESSION['adminloginid'] ?></h1>
        <form method="POST" action="<?php echo $_SERVER['PHP_SELF']  ?>">
            <button type="submit" name="logout">LOGOUT</button>

        </form>
    </div>
    <style>
        body {
            background-color: #f8f9fa;
        }
        .sidebar {
            background-color: #343a40;
            height: 100vh;
            padding-top: 1rem;
            color: #ffffff;
        }
        .sidebar .nav-link {
            color: #ffffff;
            margin-bottom: 1rem;
        }
        .sidebar .nav-link.active {
            background-color: #495057;
        }
        .main-content {
            padding: 2rem;
        }
    </style>
<div class="container-fluid">
    <div class="row">
        
        <nav class="col-md-3 col-lg-2 d-md-block bg-dark sidebar">
            <div class="sidebar-sticky">
                <h3 class="text-center py-2">Admin Panel</h3>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link active" href="adminpanel.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="view_buses.php">Manage Buses</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="view_users.php">Manage users</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="manage_booking.php">Manage Bookings</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="manage_contact.php">Manage Contact</a>
                    </li>
                </ul>
            </div>
        </nav>

       
        <main class="col-md-9 ml-sm-auto col-lg-10 px-4 main-content">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Dashboard</h1>
                <div class="btn-toolbar mb-2 mb-md-0">
                </div>
            </div>

           
            <div class="row">
                <div class="col-md-3">
                    <div class="card text-white bg-primary mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Total Buses</h5>
                            <p class="card-text">
                                <?php
                                    include '../connection.php'; 
                                    
                                    // Query to count the total number of buses
                                    $sql = "SELECT COUNT(*) AS total_buses FROM buses";
                                    $result = $con->query($sql);
                                    
                                    if ($result->num_rows > 0) {
                                        // Fetch the total count
                                        $row = $result->fetch_assoc();
                                        $total_buses = $row['total_buses'];
                                        echo "Total Number of Buses: " . $total_buses;
                                    } else {
                                        echo "No buses found.";
                                    }
                                    
                                    
                                    $con->close();
                                    ?>
                                    
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-success mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Total Routes</h5>
                            <p class="card-text">
                            <?php
                                    include '../connection.php'; 
                                    
                                    // Query to count the total number of buses
                                    $sql = "SELECT COUNT(*) AS total_routes FROM routes";
                                    $result = $con->query($sql);
                                    
                                    if ($result->num_rows > 0) {
                                        // Fetch the total count
                                        $row = $result->fetch_assoc();
                                        $total_routes = $row['total_routes'];
                                        echo "Total Number of routes: " . $total_routes;
                                    } else {
                                        echo "No routes found.";
                                    }
                                    
                                    $con->close();
                                    ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-info mb-3">
                        <div class="card-body">
                            <h5 class="card-title">Total users</h5>
                            <p class="card-text">
                            <?php
                                    include '../connection.php';
                                    
                                    // Query to count the total number of buses
                                    $sql = "SELECT COUNT(*) AS total_users FROM users";
                                    $result = $con->query($sql);
                                    
                                    if ($result->num_rows > 0) {
                                        // Fetch the total count
                                        $row = $result->fetch_assoc();
                                        $total_users = $row['total_users'];
                                        echo "Total Number of users: " . $total_users;
                                    } else {
                                        echo "No users";
                                    }
                                    
                                    $con->close();
                                    ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-danger mb-3">
                        <div class="card-body">
                            <h5 class="card-title">total bookings</h5>
                            <p class="card-text">

                            <?php
                                    include '../connection.php';
                                    
                                    // Query to count the total number of buses
                                    $sql = "SELECT COUNT(*) AS total_booking FROM booked_seats";
                                    $result = $con->query($sql);
                                    
                                    if ($result->num_rows > 0) {
                                        // Fetch the total count
                                        $row = $result->fetch_assoc();
                                        $total_booking = $row['total_booking'];
                                        echo "Total Number of booking: " . $total_booking;
                                    } else {
                                        echo "No bookings.";
                                    }
                                    
                                    $con->close();
                                    ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <p>Welcome to the admin dashboard! Use the links on the left to manage buses, users, and bookings.</p>
                </div>
            </div>
        </main>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>


    <?php  
    if(isset($_POST['logout']))
    {   
        session_destroy();
        header("location:adminlogin.php");
    }
    
    
    ?>



</body>
</html>