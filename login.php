<?php
	include 'config/database.php';
	session_start();
    try {
        if ($_SESSION['registered'] = true) {
        $dbh = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $query= $dbh->prepare("SELECT id, username FROM users WHERE email=:email AND password=:password AND active=:1");
        $query->execute(array(':email' => $email, 'password' => $password));
        
        $data = $query->fetch(PDO::FETCH_ASSOC);
        if ($data == null){
            $query->closeCursor();
            $e = "User $username not found.";
           } else {
                $query->closeCursor();
                $_SESSION['active'] = 1;
                header('Location: profile.php');
            }
        }
        }catch(PDOException $e) {
        $e->getMessage();
    }

// Escape email to protect against SQL injections
	// $result = $dbh->prepare("SELECT * FROM users WHERE email='$email'");
	// $result = execute( array(':email' => $_REQUEST['email']) );

// if ( $result->num_rows == 0 ){ // User doesn't exist
//     $_SESSION['message'] = "User with that email doesn't exist!";
//     // header("location: error.php");
// }
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
        <form method="post">
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
        </form>
  	</div>
	</div>
	<div class="footer">
		<p>Footer</p>
	</div>
</body>
</html>