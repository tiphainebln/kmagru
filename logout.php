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
</head>
<body>
  <a href="index.php"><h1>Camagru</h1></a>
  <div class="connect">
    <a href="login.php">Login</a>
  </div>
  <div class="signin">
    <a href="register.php">Register</a>
  </div>
      <p><?= 'You have been logged out!'; ?></p>
    </div>
  </div>
  <div class="footer">
    <p>Footer</p>
  </div>

</body>