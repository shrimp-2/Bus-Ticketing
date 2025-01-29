<?php require('connection.php'); 
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style1.css">
    <link rel="stylesheet" href="css/loginsignup.css">
    <link rel="stylesheet" href="css/logout1.css">
    <title>bus</title>
</head>
<body>
    <nav>
        <div class="logo">
            <h1>Bus<span>IT</span></h1>
        </div>
        <div class="links">
             <div class="links"><a href="index.php">Home</a></div>
             <div class="links"><a href="info/services.php">Services</a></div>
             <div class="links"><a href="info/contact.php">Contact</a></div>
             <div class="links"><a href="info/about.php">About</a></div>
        </div>
          <?php  
          if(isset($_SESSION['logged_in'])&& $_SESSION['logged_in']==true)
          {
            echo "
            <div class='user'>
            $_SESSION[username]- <a href='logout.php'>LOGOUT</a>
            </div>";
          }
          else
          {
            echo"
              <div class='buttons'>
                  <div class='btn'><button type='button' onclick=\"popup('login-popup')\">Login</button></a></div>
                  <div class='btn'><button type='button' onclick=\"popup('signup-popup')\">SignUp</button></a></div>
              </div>
              ";
          }
          
          ?>
    </nav>
    <div class="popup-container" id="login-popup">
    <div class="popup">
      <form method="POST" action="login_register.php">
        <h2>
          <span>USER LOGIN</span>
          <button type="reset" onclick="popup('login-popup')">X</button>
        </h2>
        <input type="text" placeholder="E-mail or Username" name="email_username">
        <input type="password" placeholder="Password" name="password">
        <button type="submit" class="login-btn" name="login">LOGIN</button>
      </form>
    </div>
  </div>

  <div class="popup-container" id="signup-popup">
    <div class="register popup">
      <form method="POST" action="login_register.php">
        <h2>
          <span>USER REGISTER</span>
          <button type="reset" onclick="popup('signup-popup')">X</button>
        </h2>
        <input type="text" placeholder="Full Name" name="fullname">
        <input type="text" placeholder="Username" name="username">
        <input type="email" placeholder="E-mail" name="email">
        <input type="password" placeholder="Password" name="password">
        <button type="submit" class="register-btn" name="register">SIGNUP</button>
      </form>
    </div>
  </div>

  <script>
    function popup(popup_name)
    {
      get_popup=document.getElementById(popup_name);
      if(get_popup.style.display=="flex")
      {
        get_popup.style.display="none";
      }
      else
      {
        get_popup.style.display="flex";
      }
    }
  </script>


   
    <section>
      <div class="content">
           <h1>welcome</h1>
           <h2>To<span> Bus<span>IT</span></span></h2>
           <div class="para">
           <p>BusIT is your go-to online bus reservation system designed to make travel easy and efficient. With user-friendly features, real-time booking options, and secure payment methods, we streamline your journey from start to finish. Whether you're planning a weekend getaway or a business trip, busIT connects you to reliable bus services at your fingertips. Experience hassle-free travel with just a few clicks!</p>
           </div>
    <?php  
    if(isset($_SESSION['logged_in'])&& $_SESSION['logged_in']==true)
    {
      echo"
      <div class='mainbtn'><a href='bookingpage.php'><button>BUY YOUR TICKET</button></a></div>
      ";
    }   
    ?>
      </div>
    </section>   

</body>
</html>
     
</body>
</html>