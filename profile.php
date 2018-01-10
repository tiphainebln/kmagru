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
<nav>
  <li>
  <div class="admin">
    <a href="">Admin</a>
     <ul class="hidden">
        <li><a href="">Settings</a></li>
        <li><a href="">Comments</a></li>
      </ul>
    </li>
  </div>
</nav>
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
</body>
</html>
