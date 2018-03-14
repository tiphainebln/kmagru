<?php
include 'config/setup.php';
session_start();

$changed = 0;
$missmatch = 0;
$alpha = 0;
try {
  $username = $_SESSION['username'];
  $userid = $_SESSION['userid'];
  if (isset($_POST['username']) && isset($_POST['newusername']) && isset($_POST['newusernamebis'])){
    $user = $_POST['newusername'];
    $cuser = $_POST['newusernamebis'];
    if($cuser !== $user)
    {
       $error = "CE N'EST PAS LA MÊME CHOSE !";
       $mismatch = 1;
    }
    if (!ctype_alpha($_POST['username']))
    {
       $error = 'Your username must only contain letters';
       $alpha = 1;
    }
    if (!isset($error))
    {
       $query = $dbh->prepare("UPDATE users SET username='$user' WHERE id=:userid");
       $query->execute(array(
         ':username' => $user,
         'id' => $userid));
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
  <div class="footer">
    <p>Footer</p>
  </div>
  </div></div>
  <div id="username">
  <div class="container">
        <form method="post">
          <label>
            <b>Username</b>
          </label>
            <input type="text" placeholder="Enter your current username" name="username" autocomplete="off" required>
            <label>
             <b>New username</b>
            </label>
            <input type="text" placeholder="Enter your new username" name="newusername" autocomplete="off" required>
            <label>
             <b>Repeat new username</b>
            </label>
            <input type="text" placeholder="Enter your new username again" name="newusernamebis" autocomplete="off" required>
            <button type="submit" name="changeusername">
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
    if ($mismatch != 0)
    {
      echo "<h2>Sorry! Username Mismatch.</h2>";
    }
    if ($alpha != 0)
    {
      echo "<h2>Your username must only contain letters.</h2>";
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