<?php

if (isset($_POST['username']) && isset($_POST['q']) && isset($_POST['password'])){

  $username = $db->quote($_POST['username']);
  $select = $db->query("SELECT * FROM users WHERE username=$username");
  if ($select->rowCount() == 0) {

    // No User Found
    

  } else {

    // User Found
    $user = $select->fetch();
    $salt = 'r9P+*p3CBT^qP^t@Y1|{~g9F[jOL)3_qlj>O)vPXymMyGiPQW(:aYkk^x?I63/.y';

    $p = hash('sha512', $salt.$user['email']);

    if ($p == $_POST['q']) {

      // hash Email and link matches
      $password = $db->quote(hash('sha1',$_POST['password']));
      $db->query("UPDATE users SET password=$password WHERE username=$username");
      mail( $_POST['email'] , "new count" , $_POST['username'].", your password was successefully changed. lets start !!" );
      header('Location:'.WEBROOT.'login.php');

    } else {

      // hash Email and link doesn't match
     

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