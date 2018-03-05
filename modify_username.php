<?php
include 'config/database.php';
session_start();
$changed = 0;
$missmatch = 0;
try {
  $username = $_SESSION['username'];
  $userid = $_SESSION['userid'];
  if (isset($_POST['username']) && isset($_POST['newusername']) && isset($_POST['newusernamebis'])){
  $dbh = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
  $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $user = $_POST['newusername'];
  $cuser = $_POST['newusernamebis'];
  if($cuser !== $user)
  {
     $mismatch = 1;
    echo "Sorry! Email Mismatch. ";
  }
  else
  {
    $query = $dbh->prepare("UPDATE users SET username='$user' WHERE id=$userid");
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
  <link rel="stylesheet" href="index.css" href="main_section.php" charset="utf-8">
</head>
<body>
  <a href="index.php"><h1>Camagru</h1></a>
  <div class="logout">
    <a href="logout.php">Logout</a>
  </div>
    <div class="dropdown">
    <a button class="admin">Admin</a>
    <div class="dropdown-content">
      <a href="modify_username.php">Change username</a>
      <a href="modify_password.php">Change password</a>
      <a href="modify_email.php">Change email</a>
    </div>
  </div>

  <div class="all">
     <a href="#">All</a>
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
    else if ($mismatch != 0)
    {
     echo "<h2>Sorry! Username Mismatch.</h2>";
    }
  ?>
    <div class="footer">
    <p>Footer</p>
  </div>
</body>
</body>
</html>