<?php
	
	require 'config/database.php';

	if (isset($_POST['register'])){

	// Set session variables to be used on profile.php page
	$_SESSION['email'] = $_POST['email'];
	$_SESSION['username'] = $_POST['username'];
	$_SESSION['password'] = $_POST['lastname'];

	try{
		$query = $connect->prepare('INSERT INTO pdo (email, username, password) VALUES(:email, :username, :password)');
		$query->execute(array(
			':email' => $email,
			':username' => $username,
			':password' => $password
			));
		header('Location: register.php?action=joined');
		exit;
	}
	catch(PDOException $e){
		echo $e->getMessage();
	}

}
	//protect against sql injections
	// $result = $dbh->prepare("SELECT * FROM user WHERE first_name='$first_name'");
	// $result = execute( array(':username' => $_REQUEST['username']) );

	// check if email exists
	// $query = $dbh->prepare( "SELECT `email` FROM `users` WHERE `email` = ?" ) or die($dbh->error());
	// $query->execute();
	// if ($query->rowCount() > 0) { # If rows are found for query
	//     echo "Email found!";
	// }
	// else {
 // 	    echo "Email not found!";
 	    // email doesn't exist in the database, need to proceed
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
	<div id="signup">
	<div class="container">
	<label>
		Email
	</label>
	<input type="text" placeholder="Enter Email" name="emailReg" autocomplete="off" />
	<label>
		Username
	</label>
	<input type="text" placeholder="Enter Username" name="usernameReg" autocomplete="off" />
	<label>
		Password
	</label>
	<input type="password" placeholder="Enter Password" name="passwordReg" autocomplete="off"/>
	<div class="errorMsg">
		<?php echo $errorMsgReg; ?>
	</div>
    <button type="button" class="registerbtn"> Register
	</button>
	</div>
	</form>
	</div>
	<div class="footer">
		<p>Footer</p>
	</div>
</body>
</html>