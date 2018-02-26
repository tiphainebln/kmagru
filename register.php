<?php
	session_start();
	include 'config/database.php';
	
	try {
    	if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password'])){
		$email = ($_POST['email']);
		$username = ($_POST['username']);
    	$password = ($_POST['password']); }
    	$dbh = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    	//check for any errors
		if(isset($error)){
		  foreach($error as $error){
		    echo '<p class="bg-danger">'.$error.'</p>';
		  }
		}
    	$query= $dbh->prepare("SELECT id FROM users WHERE username=:username");
    	$query->execute(array(':username' => $username));
        if ($row = $query->fetch(PDO::FETCH_ASSOC)) {
        	$error[] = 'user';
    		echo "user already exist.";
    		$query->closeCursor();
    	}
		
		// //email validation
		// if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
		//     $error[] = 'Please enter a valid email address';
		// } else {
		//     $query = $dbh->prepare('SELECT email FROM users WHERE email = :email');
		//     $query->execute(array(':email' => $_POST['email']));
		//     $row = $query->fetch(PDO::FETCH_ASSOC);

		//     if(!empty($row['email'])){
		//         $error[] = 'Email provided is already in use.';
		//     }   
		// }

		if (!isset($error)){
		//create the activation code
        $active = md5(uniqid(rand(),true));
        $hash = password_hash($password, PASSWORD_BCRYPT);
		$sql = "INSERT INTO users (username, password, email, hash, active) VALUES(:username, :password, :email, :hash, :active)";
		$query = $dbh->prepare($sql);
		$query->execute(array(
		    ':username' => $_POST['username'],
		    ':password' => $password,
		    ':email' => $_POST['email'],
		    ':hash' => $hash,
		    ':active' => $active
		));
		$id = $dbh->lastInsertId('id');
		$_SESSION['Auth'] = $id;
		echo $_SESSION['Auth'];
		// send confirmation email
		// $to = $_POST['email'];
		// $subject = "Registration Confirmation";
		// $activeurl = 'http://active.php?x=.$id&y=$active';
		// $body = "
		// <html>
		// 	<head>
		// 	<title>Thank you for registering at Camagru.</title>
		// 	</head>
		// <body>
		// 	<p>To activate your account, please click on this <a href='http://localhost:8100/activate.php?x=$id&y=$active'>link.</a></p>
			
		// 	</body>
		// </html>";
		// // To send HTML mail, the Content-type header must be set
		// $headers  = 'MIME-Version: 1.0' . "\r\n";
		// $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		// // Additionnal headers
		// $headers .= 'From: tbouline@student.42.fr' . "\r\n" .
		//  'Reply-To: tbouline@student.42.fr' . "\r\n" .
		//  'X-Mailer: PHP/' . phpversion();
 	// 	mail($to, $subject, $body, $headers);

		header('Location: register.php?action=joined');
		exit; }
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
				<input type="text" placeholder="Enter Email" name="email" autocomplete="off" value="" />
				<label>
					Username
				</label>
				<input type="text" placeholder="Enter Username" name="username" autocomplete="off"/>
				<label>
					Password
				</label>
				<input type="password" placeholder="Enter Password" name="password" autocomplete="off" value="" />
				 <button type="submit" class="registerbtn" value="ok"> Register </button>
			</form>
		</div>
	</div>
	<div class="footer">
		<p>Footer</p>
	</div>

</body>
</html>