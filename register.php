<?php
	include 'config/database.php';
	
	try {
    	if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password'])){
		$email = ($_POST['email']);
		$username = ($_POST['username']);
    	$password = ($_POST['password']); }
    	$dbh = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    	$query= $dbh->prepare("SELECT id FROM users WHERE username=:username OR email=:email");
    	$query->execute(array(':username' => $username, ':email' => $email));
    	if ($data = $query->fetch(PDO::FETCH_ASSOC)) {
    		echo "user already exist.";
    		$query->closeCursor();
    	}

        $password = password_hash($password, PASSWORD_BCRYPT);
		$sql = "INSERT INTO users (email, username, password) VALUES(:email, :username, :password)";
		$query = $dbh->prepare($sql);
		$query->execute(array(':username'=>$username,':password'=>$password,':email'=>$email));
		var_dump('expression');
		echo "Thank you for registering.";
		$_SESSION['registered'] = true;
		//need to verify if email account exists here
	} catch (PDOException $e) {
		var_dump($e->getMessage());
          $_SESSION['error'] = "ERROR: ".$e->getMessage();
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
					Email
				</label>
				<input type="text" placeholder="Enter Email" name="email" autocomplete="off"/>
				<label>
					Username
				</label>
				<input type="text" placeholder="Enter Username" name="username" autocomplete="off"/>
				<label>
					Password
				</label>
				<input type="password" placeholder="Enter Password" name="password" autocomplete="off" value="test" />
				 <button type="submit" class="registerbtn" value="ok"> Register </button>
			</form>
		</div>
	</div>
	<div class="footer">
		<p>Footer</p>
	</div>

</body>
</html>