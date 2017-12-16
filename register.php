<?php
	
	require 'config/database.php';

	if (isset($_POST['register'])){
	$email = !empty($_POST['email']) ? trim($_POST['email']) : null;
	$username = !empty($_POST['username']) ? trim($_POST['username']) : null;
    $password = !empty($_POST['password']) ? trim($_POST['password']) : null;
    $passwordHash = password_hash($password, PASSWORD_BCRYPT);
    if($row['num'] > 0){
    	$_SESSION['message'] = 'User with this email already exists!';
    	header("location: error.php");
    }else{
		$sql = "INSERT INTO user (email, username, password) VALUES(:email, :username, :password)";
		$query = $db->prepare($sql);
		$query->execute(array(':username'=>$username,':password'=>$password,':email'=>$email));
		$result = $query->execute(array(':username'=>$username,':password'=>$password,':email'=>$email));
		}
	if ($result){
		echo "Thank you for registering.";
		//need to verify if email account exists here
		header("Location: profile.php");
	}
	else {
		echo "There has been a problem inserting your details.";
		header("Location: error.php");
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
    <button type="button" class="registerbtn" value ="ok"> Register
	</button>
	</div>
	</div>
	<div class="footer">
		<p>Footer</p>
	</div>
</body>
</html>