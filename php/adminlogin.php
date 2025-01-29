<?php require("../connection.php") ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Login Panel</title>
  <link rel="stylesheet" href="css/adminlogin.css">
</head>
<body>
  
  <div class="container">
    <div class="myform">
      <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF'])?>">
        <h2>ADMIN LOGIN</h2>
        <input type="text" placeholder="Admin Name" name="adminname"> 
        <input type="password" placeholder="Password" name="adminpassword">
        <button type="submit" name='login'>LOGIN</button>
      </form>
    </div>
  </div>

<?php 

function input_filter($data)
{
  $data=trim($data);
  $data=stripcslashes($data);
  $data=htmlspecialchars($data);
  return $data;
}

if(isset($_POST['login']))
{
  $adminname=input_filter($_POST['adminname']);
  $adminpassword=input_filter($_POST['adminpassword']);

#sql injection attack  
$adminname=mysqli_real_escape_string($con,$adminname);
$adminpassword=mysqli_real_escape_string($con,$adminpassword);

$query="SELECT * FROM `admin_login` WHERE `admin_name`=? AND `admin_password`=? ";

if($st=mysqli_prepare($con,$query))
{
  mysqli_stmt_bind_param($st,"ss",$adminname,$adminpassword); //binding values of adminname and pass
  mysqli_stmt_execute($st);
  mysqli_stmt_store_result($st);

  if(mysqli_stmt_num_rows($st)==1)
  {
    #details is correct for admin name and pass
    session_start();
    $_SESSION['adminloginid']=$adminname;
    header("location:adminpanel.php");
  }
  else
  {
    echo"<script>alert('INCORRECT ADMIN NAME OR PASSWORD');</script>";
  }
mysqli_stmt_close($st);
}
else
{
  echo"<script>alert('sql query cannot be prepared');</script>";
}


  
}



?>


</body>
</html>