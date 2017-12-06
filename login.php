<?php
	// require 'config/database.php';
	session_start();
/* User login process, checks if user exists and password is correct */

    if (isset($_POST['login'])) {

        $username = $_POST['username'];
        $password = $_POST['password'];
    try {
            $query = $connect->prepare('SELECT id, username, password FROM pdo WHERE username = :username');
            $query->execute(array(
                ':username' => $username
                ));
            $data = $query->fetch(PDO::FETCH_ASSOC);

            if ($data == false){
                $e = "User $username not found.";
            }
            else {
                if ($password == $data['password']) {
                    $_SESSION['username'] = $data['username'];
                    $_SESSION['password'] = $data['password'];
                    $_SESSION['active'] = $user['active'];

                    $_SESSION['logged)_in'] = true;
                    header('Location: profile.php');
                }
                else {
                    $_SESSION['message'] = "You have entered wrong password, try again!";
                    header("location: error.php");
                }
            }
        }
        catch(PDOException $e) {
        $e->getMessage();
    }
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