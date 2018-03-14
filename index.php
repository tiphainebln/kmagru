<?php
include 'config/setup.php';
session_start();

var_dump("2");
?>

<!DOCTYPE html>
<html>
<head>
	<title>Camagru</title>
	<link rel="stylesheet" href="index.css" charset="utf-8">
</head>
<body>
<a href="index.php"><h1>Camagru</h1></a>
<div id="menu_log" style="">
  <a href="gallery.php">All</a>
  <?php if (isset($_SESSION['logged_in'])) { ?>
    <a href="main_section.php">New creation</a>
    <a href="my_gallery.php">My Gallery</a>
    <div class="dropdown">
      <a button class="admin">Settings</a>
      <div class="dropdown-content">
        <a href="modify_username.php">Change username</a>
        <a href="modify_password.php">Change password</a>
        <a href="modify_email.php">Change email</a>
        <a href="desactivate.php">Disable notifications</a>
      </div>
    </div>
    <a href="logout.php">Logout</a>
</div>
  <?php } else { ?>
    <div class="connect">
      <a href="login.php">Login</a>
    </div>
    <div class="signin">
      <a href="register.php">Register</a>
    </div>
  <?php } ?>
  <div class="footer">
    <footer>Copyright &copy; 2018 - tbouline@student.42.fr</footer>
  </div>
</body>
</html>