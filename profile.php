<?php

session_start();
include 'config/setup.php';

?>
<!DOCTYPE html>
<html>
<head>
  <title>Camagru</title>
  <link rel="stylesheet" href="index.css" charset="utf-8">
</head>
<body>
  <a href="index.php"><h1>Camagru</h1></a>
    <div class="all">
     <a href="gallery.php">All</a>
  </div>
  <?php if (isset($_SESSION['logged_in'])) { ?>
    <div class="logout">
      <a href="logout.php">Logout</a>
    </div>
    <div class="dropdown">
      <a button class="admin">Settings</a>
      <div class="dropdown-content">
        <a href="modify_username.php">Change username</a>
        <a href="modify_password.php">Change password</a>
        <a href="modify_email.php">Change email</a>
        <a href="desactivate.php">Disable notifications</a>
      </div>
    </div>

    <div class="mygallery">
        <a href="my_gallery.php">My Gallery</a>
    </div>
    <div class="newcreation">
       <a href="main_section.php">New creation</a>
    </div>
    <p>
  <?php } else { ?>
    <div class="connect">
      <a href="login.php">Login</a>
    </div>
    <div class="signin">
      <a href="register.php">Register</a>
    </div>
  <?php } ?>
    <?php if (isset($_SESSION['logged_in'])) {
    echo "<h2>Thanks for registering</h2>";
} else {
   echo "<h2>You must log in before viewing your profile page!</h2>";} ?>
  </p>
  <div class="form">
</div>
<div class="footer">
    <footer>Copyright &copy; 2018 - tbouline@student.42.fr</footer>
</div>
</body>
</html>
