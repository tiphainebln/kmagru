<?php

session_start();
include 'config/database.php';
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
        <a button class="admin">Admin</a>
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
      <div class="footer">
        <p>Footer</p>
      </div>

      <form action="upload.php" method="post" enctype="multipart/form-data">
          <div class="selection">
            <img src="img/imgtest1.png"><input type="radio" name="img" value="imgtest1" checked="checked">
          </div>
          <div class="selection">
            <img src="img/imgtest2.png"><input type="radio" name="img" value="imgtest2">
          </div>
          <div class="selection">
            <img src="img/imgtest3.png"><input type="radio" name="img" value="imgtest3">
          </div>
          <div class="selection">
            <img src="img/imgtest4.png"><input type="radio" name="img" value="imgtest3"> 
          </div>

    Select image to upload:
<!--     <input type="hidden" name="MAX_FILE_SIZE" value="30000" /> -->
    <input type="file" name="fileToUpload" id="fileToUpload">
    <input type="submit" value="Upload Image" name="submit">
  </form>

   <?php } else { ?>
      <div class="connect">
        <a href="login.php">Login</a>
      </div>

      <div class="signin">
        <a href="register.php">Register</a>
      </div>

      <div class="container" id="login">  You're not supposed to see this. </div>
  <?php } ?>
</body>
</html>

