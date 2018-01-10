<?php
	include 'config/database.php';
	session_start();
    try {
        if (isset($_POST['login'])) {
        $dbh = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $username = !empty($_POST['username']) ? trim($_POST['username']) : null;
        $password = !empty($_POST['password']) ? trim($_POST['password']) : null;
        $query= $dbh->prepare("SELECT id, username, password FROM users WHERE username=:username AND password=:password");
        $query->execute(array(':username' => $username, ':password' => $password));
        $data = $query->fetch(PDO::FETCH_ASSOC);
        //var_dump('expression');
        if ($data == null){
            $query->closeCursor();
            $e = "User $username not found.";
           } else {
                $query->closeCursor();
                // need to find a solution to set 'active' to 1 in the database;
                // $_SESSION['active'] = 1;
                // $query= $dbh->prepare("UPDATE users SET active='1' WHERE id=:id");
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
    // if (isset($_POST['username']) && isset($_POST['password']))
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
            <input type="text" placeholder="Enter Username" name="username" autocomplete="off" required>
            <label>
        	   <b>Password</b>
            </label>
            <input type="password" placeholder="Enter Password" name="password" autocomplete="off" required>
            <button type="submit" name="login">
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