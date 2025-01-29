<?php
include "auth.php";
?>
<nav>
    <div class="logo">
        <h1>Bus<span>IT</span></h1>
    </div>
    <div class="links">
        <div class="links"><a href="index.php">Home</a></div>
        <div class="links"><a href="info/services.php">Services</a></div>
        <div class="links"><a href="info/contact.php">Contact</a></div>
        <div class="links"><a href="features/features.php">Our Features</a></div>
        <?php 
        if (session_status() ===PHP_SESSION_NONE)
        session_start(); 
        if (isset($_SESSION['username'])): ?>
            <div class="links"><a href="dashboard.php">user profile</a></div>
        <?php else: ?>
            <div class="links"><a href="login_register.php">Login</a></div>
        <?php endif; ?>
    </div>
</nav>
