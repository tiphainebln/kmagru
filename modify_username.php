<?php
include 'config/setup.php';
session_start();

$changed = 0;
$missmatch = 0;
$alpha = 0;
$shortusername = 0;
$already = 0;
try {
  $username = $_SESSION['username'];
  $userid = $_SESSION['userid'];
  if (isset($_POST['username']) && isset($_POST['newusername']) && isset($_POST['newusernamebis'])){
    $user = $_POST['newusername'];
    $cuser = $_POST['newusernamebis'];
    if($cuser !== $user)
    {
       $error = "CE N'EST PAS LA MÊME CHOSE !";
       $mismatch = 1;
    }
    if (!ctype_alpha($_POST['username']) || !ctype_alpha($user))
    {
       $error = 'Your username must only contain letters';
       $alpha = 1;
    }
    if (strlen($_POST['username']) < 3 || strlen($user) < 3){
      $error = 'Your username is too short.';
      $shortusername = 1;
    }
    if (!isset($error))
    {
      // check if user already exists, if it only contains letter and if is more than 3 characters
      $query= $dbh->prepare("SELECT * FROM users WHERE username=:username");
      $query->execute(array(':username' => $user));
      if ($row = $query->fetch(PDO::FETCH_ASSOC)) {
        $error = 'user';
        $already = 1;
        $query->closeCursor();
      }
      else {
        $query = $dbh->prepare("UPDATE users SET username='$user' WHERE id=$userid");
        $query->execute();
        $changed = 1;
        $_SESSION['username'] = $user;
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
    include 'includes/header_log.php'; ?>

<!--   FORMULAIRE -->
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
      <input type="text" placeholder="Enter your new username" name="newusername" autocomplete="off" required>
      <label>
        <b>Repeat new username</b>
      </label>
      <input type="text" placeholder="Enter your new username again" name="newusernamebis" autocomplete="off" required>
      <button type="submit" name="changeusername">
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
    echo "<h2>Sorry! Username Mismatch.</h2>";
  }
  if ($alpha != 0)
  {
    echo "<h2>Your username must only contain letters.</h2>";
  }
  if ($shortusername != 0)
  {
    echo "<h2>Your username is too short.</h2>";
  }
  if ($already != 0)
  {
    echo "<h2>Sorry, this username is already taken, please choose another one.</h2>";
  }
} else {
  include 'includes/header.php';
?>
<div class="container" id="login">  You're not supposed to see this. </div>
<?php } ?>
<div class="footer">
  <footer>Copyright &copy; 2018 - tbouline@student.42.fr</footer>
</div>
</body>
</html>