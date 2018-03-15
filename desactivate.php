<?php
  include 'config/setup.php';
  session_start();

  $uid = $_SESSION['userid'];
  if (isset($_POST['submit']))
  {
    $checked = $_POST['check'];
    if (isset($checked)) {
      $select = $dbh->prepare("UPDATE users SET notification='1' WHERE id=$uid");
      $select->execute();
    }
    else
    {
      $select = $dbh->prepare("UPDATE users SET notification='0' WHERE id=$uid");
      $select->execute();
    }
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
  <?php if (isset($_SESSION['logged_in'])) { ?>
<!--     MENU -->
 <?php include 'includes/header_log.php'; ?>

<!--   SWITCH -->
  <div class="container" style="margin-top: 6%;">
    <form action="" method="post">
      Enable Notifications
      <label class="switch">
        <?php 
            $select = $dbh->prepare("SELECT notification FROM users WHERE id=$uid");
            $select->execute();
            $notifications = $select->fetch()['notification'];

            if ($notifications == 0) { ?>
              <input type="checkbox" name="check" value="0"> 
        <?php } else { ?>
             <input type="checkbox" name="check" value="1" checked>
        <?php } ?>
          <span class="slider"></span>
      </label>
      <button style="width: 200px; margin-top: 1%; margin-left: 5%; padding: 9px 20px;" type="submit" name="submit">Confirmer ?</button>
    </form>
  </div>
  <?php } else { ?>
   <?php include 'includes/header.php'; ?>
  <div class="container" id="login">  You're not supposed to see this. </div>
  <?php } ?>
  <div class="footer">
    <footer>Copyright &copy; 2018 - tbouline@student.42.fr</footer>
  </div>
</body>
</html>
