<?php
session_start();
  var_dump("okok");
if ($_SESSION['active'] == 1 && isset($_POST['username']) && isset($_POST['q']) && isset($_POST['password'])){
   $stmt = $dbh->query("SELECT * FROM users WHERE username=$username");
   $stmt->execute(array(':username' => $username));
   $rows = $stmt->fetch(PDO::FETCH_ASSOC);
   if($stmt->rowCount() == 1)
   {
      if(isset($_POST['resetpass'])) {
        $pass = $_POST['pass'];
        $cpass = $_POST['confirm-pass'];
        if($cpass!==$pass)
        {
          echo "Sorry! Password Mismatch. ";
        } else {
      $stmt = $dbh->query("UPDATE users SET password=$password WHERE username=$username");
      $stmt->execute(array(':pass' => $cpass));
      echo "Password changed successefully."; }
    } 
 } else {
  exit; }
 }
    // the user can modify his password, his email and his name
    // access to the main section
    // logout must be visible everywhere
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
  <div id="username">
  <div class="container">
        <form method="post">
          <label>
            <b>Password</b>
          </label>
            <input type="text" placeholder="Enter your current password" name="pass" autocomplete="off" required>
            <label>
             <b>New password</b>
            </label>
            <input type="password" placeholder="Enter your new password" name="confirm-pass" autocomplete="off" required>
            <label>
             <b>Repeat new password</b>
            </label>
            <input type="password" placeholder="Enter your password again" name="confirm-pass" autocomplete="off" required>
            <button type="submit" name="changeusername">
                Reset your password
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