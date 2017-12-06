<?php
/* Displays user information and some useful messages */
session_start();

// Check if user is logged in using the session variable
if ( $_SESSION['logged_in'] != 1 ) {
  $_SESSION['message'] = "You must log in before viewing your profile page!";
  // header("location: error.php");    
}
else {
    $username = $_SESSION['username'];
    $email = $_SESSION['email'];
    $active = $_SESSION['active'];
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Camagru</title>
  <link rel="stylesheet" href="index.css" href="main_section.php" charset="utf-8">
</head>
<body>
  <a href="index.php"><h1>Camagru</h1></a>
  <div class="connect">
    <a href="login.php">Login</a>
  </div>
  <div class="signin">
    <a href="register.php">Register</a>
  </div>

<body>
  <div class="form">
  <h1>Welcome</h1>       
  <p>
  <?php 
     // Display message about account verification link only once
      if ( isset($_SESSION['message']) ){
          echo $_SESSION['message'];
          // Don't annoy the user with more messages upon page refresh
          unset( $_SESSION['message'] );
          }
  ?>
  </p>      
  <?php
  // Keep reminding the user this account is not active, until they activate
  if ( !$active ){
    echo
    '<div class="info">
    Account is unverified, please confirm your email by clicking
    on the email link!
    </div>';
  }
  ?>
  <h2><?php echo $first_name.' '.$last_name; ?></h2>
  <p><?= $email ?></p>   
  <a href="logout.php"><button class="button button-block" name="logout"/>Log Out</button></a>
</div>
</body>
</html>