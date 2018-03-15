<?php
include 'config/setup.php';
session_start();

?>

<!DOCTYPE html>
<html>
<head>
	<title>Camagru</title>
	<link rel="stylesheet" href="index.css" charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
  <?php
  if (isset($_SESSION['logged_in']))
    include 'includes/header_log.php';
  else
    include 'includes/header.php';
  ?>
  <div class="footer">
    <footer>Copyright &copy; 2018 - tbouline@student.42.fr</footer>
  </div>
</body>
</html>