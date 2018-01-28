<?php
  require('config/database.php');

  try { $dbh = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
  $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $query = $dbh->prepare('SELECT resetToken, resetComplete FROM users WHERE resetToken = :token');
  $query->execute(array(':token' => $_GET['key']));
  $row = $query->fetch(PDO::FETCH_ASSOC);

  //if no token from db then kill the page
  if(empty($row['resetToken'])){
      $stop = 'Invalid token provided, please use the link provided in the reset email.';
  } elseif($row['resetComplete'] == 'Yes') {
      $stop = 'Your password has already been changed!';
  }

  if(isset($stop)){
      echo "<p class='bg-danger'>$stop</p>";
  }

  if(isset($_POST['submit'])){
      //basic validation
      if(strlen($_POST['password']) < 3){
          $error[] = 'Password is too short.';
      }
      if(strlen($_POST['passwordConfirm']) < 3){
          $error[] = 'Confirm password is too short.';
      }
      if($_POST['password'] != $_POST['passwordConfirm']){
          $error[] = 'Passwords do not match.';
      }

    //if no errors have been created carry on
    if(!isset($error)){
        $hash = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $query = $dbh->prepare("UPDATE users SET password = :hash, resetComplete = 'Yes'  WHERE resetToken = :token");
        $query->execute(array(
            ':hash' => $hash,
            ':token' => $row['resetToken']
        ));
        //redirect to index page
        header('Location: login.php?action=resetAccount');
        exit;
        //else catch the exception and show the error.
      } catch(PDOException $e) {
        $error[] = $e->getMessage();
        print_r( $e );
        }
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
          Username
        </label>
        <input type="text" placeholder="Enter Username" name="username" autocomplete="off"/>
        <label>
          Password
        </label>
          <input type="hidden" name="q" value='<?php if (isset($_GET["q"])) { echo $_GET["q"];} ?>' />
        <input type="password" placeholder="Enter Password" name="password" autocomplete="off" value="test" />
         <button type="submit" class="registerbtn" value="ok"> submit </button>
      </form>
    </div>
  </div>
  <div class="footer">
    <p>Footer</p>
  </div>

</body>

?>