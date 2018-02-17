<?php
include 'config/database.php';
session_start();

// if ($_SESSION['active'] == 'Yes' && isset($_POST['username']) && isset($_POST['newusername']) && isset($_POST['newusernamebis'])){
if (isset($_POST['email']) && isset($_POST['newemail']) && isset($_POST['newemailbis'])){
  $dbh = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
  $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
   $username = $dbh->quote($_POST['username']);
   $email = $dbh->quote($_POST['email']);
   $id = $dbh->lastInsertId();
   $query = $dbh->query("SELECT * FROM users WHERE username=$username");
   $query->execute(array(':username' => $username));
   $rows = $query->fetch(PDO::FETCH_ASSOC);
   var_dump("okokokok");
   if($query->rowCount() == 1)
   {
      if(isset($_POST['newemailbis']) && isset($_POST['newemail']))
      {
        $mail = $_POST['newemail'];
        $cmail = $_POST['newemailbis'];
        if($cmail !== $mail)
        {
          echo "Sorry! Email Mismatch. ";
        } 
        else 
        {
          // $username = $user;
          var_dump("hereeee");
          $email = $mail;
          var_dump($id);
          var_dump($email);
          $query = $dbh->query("UPDATE users SET email='$email' WHERE username=$username");
          $query->execute(array(':email' => $cmail));
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
    <div class="footer">
    <p>Footer</p>
  </div>
</body>
</body>
</html>