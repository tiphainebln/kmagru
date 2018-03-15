<?php
    include 'config/setup.php';
    session_start();

    $invalid = 0;
    $already = 0;
    $short = 0;
    $no_digit = 0;
    try {
      if (isset($_POST['password'])) {
        $query = $dbh->prepare('SELECT resetToken, resetComplete FROM users WHERE resetToken = :token');
        $query->execute(array(':token' => $_GET['key']));
        $row = $query->fetch(PDO::FETCH_ASSOC);
        //if no token from db then kill the page
        if (empty($row['resetToken'])){
          $error = 'Invalid token provided.';
          $invalid = 1;
        }
        else if ($row['resetComplete'] == 'Yes') {
          $error = 'Reset already complete.';
          $already = 1;
        }
        // check if password is more than 3 characters and if it countains a number
        if (strlen($_POST['password']) < 3){
            $error = 'Your password is too short.';
            $short = 1;
        }
        if (ctype_alpha($_POST['password']) || ctype_digit($_POST['password'])){
            $error = 'Your password must contain at least one digit and one letter.';
            $no_digit = 1;
        } 
        //if no errors have been created carry on
        if (isset($_POST['password']) && !isset($error)) {
          $query = $dbh->prepare("UPDATE users SET resetComplete = 'Yes', hash = :hash  WHERE resetToken = :token");
          $query->execute(array(
          ':token' => $row['resetToken'],
          ':hash' =>  password_hash($_POST['password'], PASSWORD_BCRYPT)
          ));
          //redirect to index page
          header('Location: login.php?action=resetAccount');
          exit;
        }
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
  <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
  <?php if (isset($_SESSION['logged_in'])) { 
    include 'includes/header_log.php'; ?>
    <div class="container" id="login">
      Vous n'êtes pas censé être ici.
    </div>
  <?php } else { ?>
   <?php include 'includes/header.php'; ?>

<!--   FORMULAIRE -->
  <div id="signup">
    <div class="container">
      <form method="post">    
        <label>
          Password
        </label>

        <input type="password" placeholder="Enter Password" name="password" autocomplete="off" value="" />
         <button name="submit" type="submit" class="registerbtn"> submit </button>
      </form>
    </div>
  </div>
  <?php 
  if ($invalid != 0)
  {
    echo "<h2>Invalid token provided, please use the link provided in the reset email.</h2>";
  }
  if ($already != 0)
  {
    echo "<h2>Your password has already been changed!</h2>";
  }
  if ($short != 0)
  {
    echo "<h2>Your password is too short!</h2>";
  }
  if ($no_digit != 0)
  {
    echo "<h2>Your password must contain at least one digit and one letter!</h2>";
  }
  ?>
  <?php } ?>
  <div class="footer">
    <footer>Copyright &copy; 2018 - tbouline@student.42.fr</footer>
  </div>

</body>