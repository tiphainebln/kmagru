<?php
    include 'config/setup.php';
    session_start();
    
    $changed = 0;
    $missmatch = 0;
    $no_digit = 0;
    $short = 0;
    try {
        $userid = $_SESSION['userid'];
        if (isset($_POST['password']) && isset($_POST['newpassword']) && isset($_POST['newpasswordbis'])){
            $pass = $_POST['newpassword'];
            $cpass = $_POST['newpasswordbis'];
            if ($pass !== $cpass)
            {
              $error = 'Mismatch.';
              $mismatch = 1;
            }
            if (strlen($_POST['newpassword']) < 3){
              $error = 'Your password is too short.';
              $short = 1;
            }
            if (ctype_alpha($_POST['newpassword']) || ctype_digit($_POST['newpassword'])){
              $error = 'Your password must contain at least one digit and one letter.';
              $no_digit = 1;
            } 
            if (!isset($error))
            {
              $hash = password_hash($cpass, PASSWORD_BCRYPT);
              $query = $dbh->prepare("UPDATE users SET hash='$hash' WHERE id=$userid");
              $query->execute();
              $changed = 1;
            }
        }
    }
    catch(PDOException $e){
        echo $query . "<br>" . $e->getMessage();
    }
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
  ?>

  <!--  FORMULAIRE -->
  <div id="username">
    <div class="container">
      <form method="post">
        <label>
          <b>Password</b>
        </label>
        <input type="password" placeholder="Enter your current password" name="password" autocomplete="off" required>
        <label>
          <b>New password</b>
        </label>
        <input type="password" placeholder="Enter your new password" name="newpassword" autocomplete="off" required>
        <label>
          <b>Repeat new password</b>
        </label>
        <input type="password" placeholder="Enter your password again" name="newpasswordbis" autocomplete="off" required>
        <button type="submit" name="changeusername">
          Reset your password
        </button>
      </form>
    </div>
  </div>
  <?php 
  if ($changed != 0)
  {
    echo "<h2>Records updated successfully.</h2>";
  }
  if ($mismatch != 0)
  {
    echo "<h2>Sorry! Password Mismatch.</h2>";
  }
  if ($short != 0)
  {
    echo "<h2>Your password is too short.</h2>";
  }
  if ($no_digit != 0)
  {
    echo "<h2>Your password must contain at least one digit and one letter.</h2>";
  }
  } else {
    include 'includes/header.php'; ?>
  <div class="container" id="login">  You're not supposed to see this. </div>
  <?php } ?>
  <div class="footer">
    <footer>Copyright &copy; 2018 - tbouline@student.42.fr</footer>
  </div>
</body>
</html>