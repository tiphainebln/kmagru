<?php
  include 'config/setup.php';
  session_start();

  $uid = $_SESSION['userid'];
  if (isset($_POST['submit']))
  {
    $checked = $_POST['check'];
    if (isset($checked)) {
      $select = $dbh->prepare("UPDATE users SET notification='1' WHERE id=:uid");
      $select->execute(array(':id' => $uid));
    }
    else
    {
      $select = $dbh->prepare("UPDATE users SET notification='0' WHERE id=:uid");
      $select->execute(array(':id' => $uid));
    }
  }

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
      <a href="desactivate.php">Disable notifications</a>
    </div>
  </div>
  <div class="mygallery">
    <a href="my_gallery.php">My Gallery</a>
  </div>
  <div class="newcreation">
    <a href="main_section.php">New creation</a>
  </div>
  <div class="container">
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
      <button style="width: 10%; margin-top: 1%; margin-left: 5%; padding: 9px 20px;" type="submit" name="submit">Confirmer ?</button>
    </form>
  </div>
  <?php } else { ?>
  <div class="connect">
    <a href="login.php">Login</a>
  </div>
  <div class="signin">
    <a href="register.php">Register</a>
  </div>
  <div class="container" id="login">  You're not supposed to see this. </div>
  <?php } ?>
  <div class="footer">
    <footer>Copyright &copy; 2018 - tbouline@student.42.fr</footer>
  </div>
</body>
</html>
