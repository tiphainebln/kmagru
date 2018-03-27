<?php

session_start();
include 'config/setup.php';

?>

<!DOCTYPE html>
<html>
<head>
  <title>Camagru</title>
  <link rel="stylesheet" href="index.css" charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
  <?php if (isset($_SESSION['logged_in'])) {
  include 'includes/header_log.php'; ?>
<!--       FORMULAIRE -->
  <form action="upload.php" method="post" enctype="multipart/form-data">
    <input type="hidden" name="token" value="<?= $_SESSION['token']; ?>">
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
      <img src="img/imgtest4.png"><input type="radio" name="img" value="imgtest4"> 
    </div>

    <div style="margin-top: 7%; margin-left: 7%;"> 
      Select image to upload:
      <br><input type="file" name="fileToUpload" id="fileToUpload">
      <input type="submit" value="Upload Image" name="submit">
    </div>
  </form>

   <?php } else { ?>
   <?php include 'includes/header.php'; ?>

      <div class="container" id="login">  You're not supposed to see this. </div>
  <?php } ?>
  <div class="footer">
    <footer>Copyright &copy; 2018 - tbouline@student.42.fr</footer>
  </div>
</body>
</html>

