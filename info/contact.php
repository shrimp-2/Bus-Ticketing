<?php
include '../connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
 
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $message = trim($_POST['message']);

    
    if (empty($name) || empty($email) || empty($phone) || empty($address) || empty($message)) {
        echo "<script>alert('All fields are required!');</script>";
    } else {
        
        $stmt = $con->prepare("INSERT INTO contact_form (name, email, phone, address, message) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $name, $email, $phone, $address, $message);

        if ($stmt->execute()) {
            
            echo "<script>
                alert('Thank you for contacting us! You will now be redirected to the homepage.');
                window.location.href = '../index.php';
            </script>";
        } else {
           
            echo "<script>alert('Error: Could not save your message. Please try again.');</script>";
        }

        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - Bus Reservation System</title>
    <link rel="stylesheet" href="infocss/contact.css"> 
</head>

<section class="contact-us">
    <h2>Contact Us</h2>
    <p>If you have any questions or need assistance, feel free to reach out to us. We are here to help!</p>

    <div class="contact-form">
        <form action="" method="POST">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" id="name" name="name" required placeholder="Enter your name">
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required placeholder="Enter your email">
            </div>
            <div class="form-group">
                <label for="phone">Phone:</label>
                <input type="text" id="phone" name="phone" required placeholder="Enter your phone number">
            </div>
            <div class="form-group">
                <label for="address">Address:</label>
                <input type="text" id="address" name="address" required placeholder="Enter your address">
            </div>
            <div class="form-group">
                <label for="message">Message:</label>
                <textarea id="message" name="message" rows="4" required placeholder="Type your message here"></textarea>
            </div>
            <button type="submit" class="submit-button">Submit</button>
        </form>
    </div>

  
    <div class="home-button-container">
        <a href="../index.php" class="home-button">Go to Home</a>
    </div>
</section>
</body>
</html>
