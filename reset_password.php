<?php
    require('config/database.php');
    session_start();

    $invalid = 0;
    $already = 0;
    if (isset($_GET["key"]))
    {
        echo $_GET["key"];
    }

    try {
      $dbh = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
      $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $query = $dbh->prepare('SELECT resetToken, resetComplete FROM users WHERE resetToken = :token');
      $query->execute(array(':token' => $_GET['key']));
      $row = $query->fetch(PDO::FETCH_ASSOC);
      echo $row['resetToken'];
      //if no token from db then kill the page
      if (empty($row['resetToken'])){
        $invalid = 1;
      }
      else if ($row['resetComplete'] == 'Yes') {
        $already = 1;
      }
      // if(isset($_POST['submit'])){
      //     //basic validation
      //     if(strlen($_POST['password']) < 3){
      //         $error[] = 'Password is too short.';
      //     }
      //     if($_POST['password'] != $_POST['passwordConfirm']){
      //        $error[] = 'Passwords do not match.';
      //     }
      // }
      //if no errors have been created carry on
      else if ($_POST['password']) {
          $query = $dbh->prepare("UPDATE users SET password = :pass, resetComplete = 'Yes', hash = :hash  WHERE resetToken = :token");
          $query->execute(array(
          ':pass' => $_POST['password'], // DELETE
          ':token' => $row['resetToken'],
          ':hash' =>  password_hash($_POST['password'], PASSWORD_BCRYPT)
          ));
          //redirect to index page
          header('Location: login.php?action=resetAccount');
          exit;
          //else catch the exception and show the error.
      }
    }


    catch(PDOException $e) {
      $error[] = $e->getMessage();
      print_r( $e );
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
  <?php if (isset($_SESSION['logged_in'])) { ?>
  <div class="logout">
    <a href="logout.php">Logout</a>
  </div>
  <div class="dropdown">
    <a button class="admin">Settings</a>
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
  <div class="connect">
    <a href="login.php">Login</a>
  </div>
  <div class="signin">
    <a href="register.php">Register</a>
  </div>
  <div id="signup">
    <div class="container">
      <form method="post">    
        <label>
          Password
        </label>

        <input type="password" placeholder="Enter Password" name="password" autocomplete="off" value="" />
         <button type="submit" class="registerbtn" value="ok"> submit </button>
      </form>
    </div>
  </div>
  <?php 
  if ($invalid != 0)
  {
    echo "<h2>Invalid token provided, please use the link provided in the reset email.</h2>";
  }
  else if ($already != 0)
  {
    echo "<h2>Your password has already been changed!.</h2>";
  }
  ?>
  <?php } ?>
  <div class="footer">
    <p>Footer</p>
  </div>

</body>