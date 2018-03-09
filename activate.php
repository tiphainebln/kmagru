<?php
session_start();
require('config/database.php');

//collect values from the url
$id = trim($_GET['x']);
$active = trim($_GET['y']);
//if id is number and the active token is not empty carry on
if(is_numeric($id) && !empty($active)){
    $dbh = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //update users record set the active column to Yes where the ID and active value match the ones provided in the array
    $query = $dbh->prepare("UPDATE users SET active = 'Yes' WHERE id = :id AND active = :active");
    $query->execute(array(
        ':id' => $id,
        ':active' => $active
    ));
    //if the row was updated redirect the user
    if($query->rowCount() == 1){
        //redirect to login page
        $active = 1;
        header('Location: login.php?action=active');
        exit;

    } else {
        $error = 1;
    }
    
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Camagru</title>
    <link rel="stylesheet" href="index.css" charset="utf-8">
</head>
<body>
    <a href="index.php"><h1>Camagru</h1></a>
    <div class="all">
        <a href="gallery.php">All</a>
    </div>
  <?php if (isset($_SESSION['logged_in'])) { ?>
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
       <a href="gallery.php">All</a>
    </div>
    <div class="mygallery">
        <a href="my_gallery.php">My Gallery</a>
    </div>
    <div class="newcreation">
       <a href="main_section.php">New creation</a>
    </div>
    <div class="container" id="login">
        Vous n'êtes pas censé être ici.
    </div>
  <?php } else { ?>

    <a href="index.php"><h1>Camagru</h1></a>
    <div class="all">
       <a href="gallery.php">All</a>
    </div>
    <div class="connect">
        <a href="login.php">Login</a>
    </div>
    <div class="signin">
        <a href="register.php">Register</a>
    </div>
    <div class="footer">
        <p>Footer</p>
    </div>
    <?php

        if (isset($active)) {
            echo  "<h2>Your account is activated.</h2>";
        }
        else if (isset($error)) {
            echo "<h2>Your account could not be activated.</h2>";
        }
    }
    ?>
</body>
</html>