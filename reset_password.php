<?php
session_start();
 
if($_SESSION['active'] = 1 && isset($_POST['username']) && isset($_POST['q']) && isset($_POST['password'])){
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
  <span><a href="#" class="admin">Admin</a></span>
    <ul id="menu">
     <ul id="choix">
        <li><a class="grey" href="#">Settings ▾</a>
      <ul>
        <li><a href="reset_username.php" class="grey">Change username</a></li>
        <li><a href="reset_password.php" class="grey">Change password</a></li>
        <li><a href="reset_email.php" class="grey">Change email</a></li>
          </ul>
        </li>
        <li><a class="grey" href="#">Comments</a></li>
        <li><a class="grey" href="#">Gallery</a></li>
      </ul>
    </ul>
  </div></div>
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