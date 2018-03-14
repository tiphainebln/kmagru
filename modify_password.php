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
              $query = $dbh->prepare("UPDATE users SET hash=':hash' WHERE id=:userid");
              $query->execute(array(
                ':hash' => $hash,
                ':id' => $userid));
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
  <div id="username">
  <div class="container">
        <form method="post">
          <label>
            <b>Password</b>
          </label>
            <input type="text" placeholder="Enter your current password" name="password" autocomplete="off" required>
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
  ?>
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