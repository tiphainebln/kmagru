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
    include 'includes/header_log.php';
  } else {
    include 'includes/header.php';
  }
  if (isset($_SESSION['logged_in'])) {
    echo "<h2>Thanks for registering</h2>";
  } else {
    echo "<h2>You must log in before viewing your profile page!</h2>";}
  ?>
<div class="footer">
    <footer>Copyright &copy; 2018 - tbouline@student.42.fr</footer>
</div>
</body>
</html>
