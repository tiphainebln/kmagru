<?php
	require 'config/database.php';
	session_start();
/* User login process, checks if user exists and password is correct */

// Escape email to protect against SQL injections
	// $result = $dbh->prepare("SELECT * FROM users WHERE email='$email'");
	// $result = execute( array(':email' => $_REQUEST['email']) );

if ( $result->num_rows == 0 ){ // User doesn't exist
    $_SESSION['message'] = "User with that email doesn't exist!";
    // header("location: error.php");
}
else { // User exists
    	$user = $result->fetch_assoc(); //retourne un tableau indexé par le nom de la colonne comme retourné dans le jeu de résultats
    	if ( password_verify($_POST['password'], $user['password']) ) {
        	$_SESSION['email'] = $user['email'];
        	$_SESSION['first_name'] = $user['first_name'];
        	$_SESSION['last_name'] = $user['last_name'];
        	$_SESSION['active'] = $user['active'];
        
        // This is how we'll know the user is logged in
        $_SESSION['logged_in'] = true;
        header("location: profile.php");
    }
    else {
        $_SESSION['message'] = "You have entered wrong password, try again!";
        // header("location: error.php");
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
<div id="login">
	<div class="container">
    	<label>
    		<b>Username</b>
    	</label>
    <input type="text" placeholder="Enter Username" name="uname" required>
    <label>
    	<b>Password</b>
    </label>
    <input type="password" placeholder="Enter Password" name="psw" required>
    <button type="submit">
    	Login
    </button>
    <input type="checkbox" checked="checked"> Remember me
  	</div>
  	<div class="container">
    <button type="button" class="cancelbtn">
    	Cancel
    </button>
    <span class="psw">
    	Forgot <a href=" ">password?</a>
    </span>
  	</div>
	</div>
	<div class="footer">
		<p>Footer</p>
	</div>
</body>
</html>