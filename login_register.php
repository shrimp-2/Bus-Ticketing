<?php  
require('connection.php');
session_start();

function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function validatePassword($password) {
    return preg_match('/^(?=.*[!@#$%^&*(),.?":{}|<>])(?=.*[0-9]).{8,}$/', $password);
}

# Login
if(isset($_POST['login'])) {
    $email_or_username = trim($_POST['email_username']);
    $password = $_POST['password'];

    if(empty($email_or_username) || empty($password)) {
        echo "
        <script>
        alert('Please fill in all fields.');
        window.location.href='index.php';
        </script>";
        exit;
    }
    # Check if input is an email and validate the format
    if (strpos($email_or_username, '@') !== false) {
        if (!filter_var($email_or_username, FILTER_VALIDATE_EMAIL)) {
            echo "
            <script>
            alert('Invalid email format.');
            window.location.href='index.php';
            </script>";
            exit;
        }
    }

    $query = "SELECT * FROM `users` WHERE `email`=? OR `username`=?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("ss", $email_or_username, $email_or_username);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result) {
        if($result->num_rows == 1) {
            $result_fetch = $result->fetch_assoc();
            if(password_verify($password, $result_fetch['password'])) {
                # Password matched
                $_SESSION['logged_in'] = true;
                $_SESSION['username'] = $result_fetch['username'];
                header("location:index.php");
            } else {
                # Incorrect password
                echo "
                <script>
                alert('Incorrect password.');
                window.location.href='index.php';
                </script>"; 
            }
        } else {
            echo "
            <script>
            alert('Email or username not registered.');
            window.location.href='index.php';
            </script>"; 
        }
    } else {
        echo "
        <script>
        alert('Cannot run query.');
        window.location.href='index.php';
        </script>"; 
    }
}

# Registration
if(isset($_POST['register'])) {
    $fullname = trim($_POST['fullname']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    if(empty($fullname) || empty($username) || empty($email) || empty($password)) {
        echo "
        <script>
        alert('Please fill in all fields.');
        window.location.href='index.php';
        </script>";
        exit;
    }

    if(!validateEmail($email)) {
        echo "
        <script>
        alert('Invalid email format.');
        window.location.href='index.php';
        </script>";
        exit;
    }

    if(!validatePassword($password)) {
        echo "
        <script>
        alert('Password must be at least 8 characters long and include a number and a special character.');
        window.location.href='index.php';
        </script>";
        exit;
    }

    $user_exit_query = "SELECT * FROM `users` WHERE `username`=? OR `email`=?";
    $stmt = $con->prepare($user_exit_query);
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result) {
        if($result->num_rows > 0) {
            $result_fetch = $result->fetch_assoc();
            if($result_fetch['username'] == $username) {
                echo "
                <script>
                alert('Username already exists.');
                window.location.href='index.php';
                </script>"; 
            } else {
                echo "
                <script>
                alert('Email already registered.');
                window.location.href='index.php';
                </script>"; 
            }
        } else {
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            $query = "INSERT INTO `users`(`full_name`, `username`, `email`, `password`) VALUES (?, ?, ?, ?)";
            $stmt = $con->prepare($query);
            $stmt->bind_param("ssss", $fullname, $username, $email, $hashed_password);

            if($stmt->execute()) {
                echo "
                <script>
                alert('Registration successful.');
                window.location.href='index.php';
                </script>";
            } else {
                echo "
                <script>
                alert('Cannot run query.');
                window.location.href='index.php';
                </script>";
            }
        }
    } else {
        echo "
        <script>
        alert('Cannot run query.');
        window.location.href='index.php';
        </script>";
    }
}
?>
