<?php
  include "config/database.php";
  session_start();

var_dump("2");

  if (isset($_POST['submit'])) {
    if (isset($_POST['notification'])) {
        $_SESSION['disable'] == true;
    } else {
        $_SESSION['disable'] == false;
    }
}
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
    </div>
  </div>
  <div class="mygallery">
    <a href="my_gallery.php">My Gallery</a>
  </div>
  <div class="newcreation">
    <a href="main_section.php">New creation</a>
  </div>
  <div class="container">
    <form method="post">
      <label>Disable notification</label> 
      <input type="radio" name="notification" value="notification"> <br>
      <button style="width: 10%; margin-top: 1%; padding: 9px 20px;" type="submit" name="submit" value="submit">Validate</button>
    </form>
  </div>
  <?php 
    if ($_SESSION['disable'] == false)
    {
      echo "<h2>notifications are on.</h2>";
    }
    else if ($_SESSION['disable'] == true)
    {
      echo "<h2>notifications are off.</h2>";
    }
  ?>
  <?php } else { ?>
  <div class="connect">
    <a href="login.php">Login</a>
  </div>
  <div class="signin">
    <a href="register.php">Register</a>
  </div>
  <div class="container" id="login">  You're not supposed to see this. </div>
  <?php } ?>
  <div class="footer">
    <p>Footer</p>
  </div>
</body>
</html>