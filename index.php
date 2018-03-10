<?php
require 'config/database.php';
session_start();
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
    <p>
  <?php } else { ?>
    <div class="connect">
      <a href="login.php">Login</a>
    </div>
    <div class="signin">
      <a href="register.php">Register</a>
    </div>
  <?php } ?>
<div class="footer">
	<p>Footer</p>
</div>
</body>
</html>