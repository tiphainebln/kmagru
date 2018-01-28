<?php
session_start();
if($_SESSION['active'] = 1 && isset($_POST['username']) && isset($_POST['newusername']) && isset($_POST['newusernamebis'])){
   $query = $dbh->query("SELECT * FROM users WHERE username=$username");
   $query->execute(array(':username' => $username));
   $rows = $query->fetch(PDO::FETCH_ASSOC);
   if($query->rowCount() == 1)
   {
      if(isset($_POST['newusernamebis'])) {
        $user = $_POST['newusername'];
        $cuser = $_POST['newusernamebis'];
        if($cuser!==$user)
        {
          echo "Sorry! Username Mismatch. ";
        } else {
      $query = $dbh->query("UPDATE users SET username=$username WHERE email=$email");
      $query->execute(array(':user' => $cuser));
      echo "Username changed successefully."; }
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
            <b>Username</b>
          </label>
            <input type="text" placeholder="Enter your current username" name="username" autocomplete="off" required>
            <label>
             <b>New username</b>
            </label>
            <input type="password" placeholder="Enter your new username" name="newusername" autocomplete="off" required>
            <label>
             <b>Repeat new username</b>
            </label>
            <input type="password" placeholder="Enter your new username again" name="newusernamebis" autocomplete="off" required>
            <button type="submit" name="changeusername">
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