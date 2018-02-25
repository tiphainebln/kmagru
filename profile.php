<?php
/* Displays user information and some useful messages */
  include 'config/database.php';
session_start();

// Check if user is logged in using the session variable
if (isset($_SESSION['Active']) == 'Yes') {
     
}
else {
  // header('Location : login.php');
  die;
    // the user can modify his password, his email and his name
    // access to the main section
    // logout must be visible everywhere
    //   Lorsque une image reçoit un nouveau commentaire, l’auteur de cette image doit
    // en être informé par mail. Cette préférence est activée par défaut, mais peut être
    // désactivée dans les préférences de l’utilisateur
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
      <a href="#">My Gallery</a>
  </div>
  <div class="newcreation">
     <a href="main_section.php">New creation</a>
  </div>
  <p>
    <?php if (isset($_SESSION['Active']) == 'Yes') {
    echo "Thanks for registering $_SESSION['Auth']['username']";
}
else {
   echo "You must log in before viewing your profile page!";} ?>
  </p>
  <div class="form">
</div>
  <div class="footer">
    <p>Footer</p>
  </div>
</body>
</body>
</html>
