<?php
/* Log out process, unsets and destroys session variables */
session_start();
session_unset();
session_destroy();
?>

<!DOCTYPE html>
<html>
<head>
  <title>Camagru</title>
  <link rel="stylesheet" href="index.css" charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
   <?php include 'includes/header.php'; ?>
  <h2 style="text-align: left;"><?= 'Goodbye !'; ?></h2>
  <div class="footer">
    <footer>Copyright &copy; 2018 - tbouline@student.42.fr</footer>
  </div>
</body>
</html>