<?php
session_start();
if($_SESSION['active'] = 1 && isset($_POST['email']) && isset($_POST['newemail']) && isset($_POST['newemailbis'])){
   $query = $dbh->query("SELECT * FROM users WHERE username=$username");
   $query->execute(array(':username' => $username));
   $rows = $query->fetch(PDO::FETCH_ASSOC);
   if($query->rowCount() == 1)
   {
      if(isset($_POST['newemail'])) {
        $mail = $_POST['newemail'];
        $cmail = $_POST['newemailbis'];
        if($cmail!==$mail)
        {
          echo "Sorry! Email Mismatch. ";
        } else {
      $query = $dbh->query("UPDATE users SET email=$email WHERE username=$username");
      $query->execute(array(':email' => $cmail));
      echo "Email changed successefully."; }
    } 
 } else {
  exit; }
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
            <b>Email</b>
          </label>
            <input type="text" placeholder="Enter your current username" name="email" autocomplete="off" required>
            <label>
             <b>New Email</b>
            </label>
            <input type="password" placeholder="Enter your new username" name="newemail" autocomplete="off" required>
            <label>
             <b>Repeat new email</b>
            </label>
            <input type="password" placeholder="Enter your new username again" name="newemailbis" autocomplete="off" required>
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