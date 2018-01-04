<?php
/* Displays user information and some useful messages */
session_start();

// Check if user is logged in using the session variable
if ($_SESSION['active'] != 1) {
  $_SESSION['message'] = "You must log in before viewing your profile page!";
  // header("location: error.php");    
}
else {
    $username = $_SESSION['username'];
    $email = $_SESSION['email'];
    $active = $_SESSION['active'];
    $_SESSION['message'] = "Thanks for registering";
    // the user can modify his password, his email and his name
    // access to the main section
    // logout must be visible everywhere
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
  <p>
    <?php echo "You must log in before viewing your profile page!" ?>
  </p>
  <div class="form">
  <?php if ($_SESSION['registered'] != true)       
  <a href="login.php"><button class="button button-block" name="login"/>Login</button></a>
  ?>
</div>
</body>
</html>