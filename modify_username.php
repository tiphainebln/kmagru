<?php
include 'config/database.php';
session_start();

// if ($_SESSION['active'] == 'Yes' && isset($_POST['username']) && isset($_POST['newusername']) && isset($_POST['newusernamebis'])){
if (isset($_POST['username']) && isset($_POST['newusername']) && isset($_POST['newusernamebis'])){
  $dbh = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
  $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
   $username = $dbh->quote($_POST['username']);
   $id = $dbh->lastInsertId();
   $query = $dbh->query("SELECT * FROM users WHERE username=$username");
   $query->execute(array(':username' => $username));
   $rows = $query->fetch(PDO::FETCH_ASSOC);
   var_dump("okokokok");
   if($query->rowCount() == 1)
   {
      if(isset($_POST['newusernamebis']) && isset($_POST['newusername']))
      {
        $user = $_POST['newusername'];
        $cuser = $_POST['newusernamebis'];
        if($cuser !== $user)
        {
          echo "Sorry! Username Mismatch. ";
        } 
        else 
        {
          // $username = $user;
          var_dump("hereeee");
          $username = $user;
          var_dump($id);
          var_dump($username);
          $query = $dbh->query("UPDATE users SET username='$username' WHERE id=$id");
          $query->execute(array(':user' => $cuser));
          $changed = 1;
        }
      }
    }
  else
  {
    exit;
  }
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
        <?php if (isset($changed)){
        echo "Username changed successfully.";
      }
        ?>
    </div>
  </div>
    <div class="footer">
    <p>Footer</p>
  </div>
</body>
</body>
</html>