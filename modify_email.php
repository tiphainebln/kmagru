<?php
session_start();
include 'config/database.php';
$mismatch = 0;
$wrong = 0;
$changed = 0;
try {
  $username = $_SESSION['username'];
  $userid = $_SESSION['userid'];
  if (isset($_POST['email']) && isset($_POST['newemail']) && isset($_POST['newemailbis'])){
  $dbh = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
  $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $mail = $_POST['newemail'];
  $cmail = $_POST['newemailbis'];
  if($cmail !== $mail){
    $mismatch = 1;
  }
  else if (!filter_var($_POST['newemail'], FILTER_VALIDATE_EMAIL) || !filter_var($_POST['newemailbis'], FILTER_VALIDATE_EMAIL)) {
    $wrong = 1;
  }
  else
  {
    $query = $dbh->prepare("UPDATE users SET email='$mail' WHERE id=$userid");
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
    </div>
  </div>
  <div class="mygallery">
    <a href="my_gallery.php">My Gallery</a>
  </div>
  <div class="newcreation">
    <a href="main_section.php">New creation</a>
  </div>
  </div></div>
  <div id="username">
  <div class="container">
        <form method="post">
          <label>
            <b>Username</b>
          </label>
            <input type="text" placeholder="Enter your username" name="username" autocomplete="off" required>
          <label>
            <b>Current Email</b>
          </label>
            <input type="text" placeholder="Enter your current email" name="email" autocomplete="off" required>
            <label>
             <b>New Email</b>
            </label>
            <input type="text" placeholder="Enter your new email" name="newemail" autocomplete="off" required>
            <label>
             <b>Repeat new email</b>
            </label>
            <input type="text" placeholder="Enter your new email again" name="newemailbis" autocomplete="off" required>
            <button type="submit" name="changeemail">
                Submit
            </button>
        </form>
    </div>
  </div>
  <?php 
    if ($changed != 0)
    {
     echo "<h2>Records updated successfully.</h2>";
    }
    else if ($mismatch != 0)
    {
     echo "<h2>Sorry! Email Mismatch.</h2>";
    }
    else if ($wrong != 0)
    {
     echo "<h2>Please enter a valid email address</h2>";
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
    <p>Footer</p>
</div>
</body>
</html>