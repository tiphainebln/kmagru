<?php
session_start();
include 'config/setup.php';

$mismatch = 0;
$wrong = 0;
$changed = 0;
$already = 0;
try {
  $username = $_SESSION['username'];
  $userid = $_SESSION['userid'];
  if (isset($_POST['email']) && isset($_POST['newemail']) && isset($_POST['newemailbis'])){
    $mail = $_POST['newemail'];
    $cmail = $_POST['newemailbis'];
    if($cmail !== $mail){
      $mismatch = 1;
      $error = 1;
    }
    if (!filter_var($_POST['newemail'], FILTER_VALIDATE_EMAIL) || !filter_var($_POST['newemailbis'], FILTER_VALIDATE_EMAIL)) {
      $wrong = 1;
      $error = 1;
    }
    else if (!isset($error))
    {
      // check if user already exists, if it only contains letter and if is more than 3 characters
      $query= $dbh->prepare("SELECT * FROM users WHERE email=:email");
      $query->execute(array(':email' => $mail));
      if ($row = $query->fetch(PDO::FETCH_ASSOC)) {
        $error = 'taken';
        $already = 1;
        $query->closeCursor();
      } else {
        $query = $dbh->prepare("UPDATE users SET email='$mail' WHERE id=$userid");
        $query->execute();
        $changed = 1;
      }
    }
  }
}
catch(PDOException $e){
    echo $query . "<br>" . $e->getMessage();
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
  include 'includes/header_log.php';
    ?>
<!--   FORMULAIRE -->
  <div id="username">
  <div class="container">
        <form method="post">
          <label>
            <b>Current Email</b>
          </label>
            <input type="text" placeholder="Enter your current email" name="email" autocomplete="off" required>
            <label>
             <b>New Email</b>
            </label>
            <input type="text" placeholder="Enter your new email" name="newemail" autocomplete="off" required>
            <label>
             <b>Repeat new email</b>
            </label>
            <input type="text" placeholder="Enter your new email again" name="newemailbis" autocomplete="off" required>
            <button type="submit" name="changeemail">
                Submit
            </button>
        </form>
    </div>
  </div>
  <?php 
    if ($changed != 0)
    {
     echo "<h2>Records updated successfully.</h2>";
    }
    if ($mismatch != 0)
    {
     echo "<h2>Sorry! Email Mismatch.</h2>";
    }
    if ($wrong != 0)
    {
     echo "<h2>Please enter a valid email address</h2>";
    }
    if ($already != 0)
    {
     echo "<h2>This email is already taken.</h2>";
    }
  } else {
    include 'includes/header.php'; ?>
  <div class="container" id="login">  You're not supposed to see this. </div>
  <?php } ?>
  <div class="footer">
    <footer>Copyright &copy; 2018 - tbouline@student.42.fr</footer>
  </div>
</body>
</html>