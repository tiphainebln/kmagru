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