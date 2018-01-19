<?php
/* Displays user information and some useful messages */
session_start();

// Check if user is logged in using the session variable
if ($_SESSION['active'] = 1) {
     
}
else {
    // the user can modify his password, his email and his name
    // access to the main section
    // logout must be visible everywhere
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
  </div>
  <p>
    <?php if ($_SESSION['active'] = 1) {
    echo "Thanks for registering"; 
}
else {
   echo "You must log in before viewing your profile page!";} ?>
  </p>
    <a href="main_section.php"><h2>Start !</h2></a>
  <div class="form">
</div>
  <div class="footer">
    <p>Footer</p>
  </div>
</body>
</body>
</html>
