<?php
	include 'config/setup.php';
	session_start();

	$already = 0;
	$invalid = 0;
	$email_already = 0;
	$short = 0;
	$no_digit = 0;
	$alpha = 0;
	$shortusername = 0;
	try {
    	if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password'])){
			$email = ($_POST['email']);
			$username = ($_POST['username']);
	    	$password = ($_POST['password']);
	    	
	    	// check if user already exists, if it only contains letter and if is more than 3 characters
	    	$query= $dbh->prepare("SELECT id FROM users WHERE username=:username");
	    	$query->execute(array(':username' => $username));
	        if ($row = $query->fetch(PDO::FETCH_ASSOC)) {
	        	$error = 'user';
	        	$already = 1;
	    		$query->closeCursor();
	    	}
	    	if (!ctype_alpha($_POST['username'])){
				$error = 'Your username must only contain letters';
				$alpha = 1;
			}
			if (strlen($_POST['username']) < 3){
	    		$error = 'Your username is too short.';
          		$shortusername = 1;
        	}

	    	// check if password is more than 3 characters and if it contains a number
	    	if (strlen($_POST['password']) < 3){
	    		$error = 'Your password is too short.';
          		$short = 1;
        	}
			if (ctype_alpha($_POST['password']) || ctype_digit($_POST['password'])){
				$error = 'Your password must contain at least one digit and one letter.';
				$no_digit = 1;
			} 
			// email validation
			if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
			    $error = 'Please enter a valid email address';
			    $invalid = 1;
			} 
			else {
			    $query = $dbh->prepare('SELECT email FROM users WHERE email = :email');
			    $query->execute(array(':email' => $_POST['email']));
			    $row = $query->fetch(PDO::FETCH_ASSOC);
			    if(!empty($row['email'])){
			        $error = 'Email provided is already in use.';
			        $email_already = 1;
			    }
			}

			if (!isset($error)){
				//create the activation code
		        $active = md5(uniqid(rand(),true));
		        $hash = password_hash($password, PASSWORD_BCRYPT);
				$sql = "INSERT INTO users (username, email, hash, active) VALUES(:username, :email, :hash, :active)";
				$query = $dbh->prepare($sql);
				$query->execute(array(
				    ':username' => $username,
				    ':email' => $email,
				    ':hash' => $hash,
				    ':active' => $active
				));
				$id = $dbh->lastInsertId('id');

				// send confirmation email
				$to = $_POST['email'];
				$subject = "Registration Confirmation";
				$body = "
				<html>
					<head>
					<title>Thank you for registering at Camagru.</title>
					</head>
				<body>
					<p>To activate your account, please click on this <a href='http://localhost:8080/camagru/activate.php?x=$id&y=$active'>link.</a></p>

					</body>
				</html>";
				// To send HTML mail, the Content-type header must be set
				$headers  = 'MIME-Version: 1.0' . "\r\n";
				$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
				// Additionnal headers
				$headers .= 'From: tbouline@student.42.fr' . "\r\n" .
				 'Reply-To: tbouline@student.42.fr' . "\r\n" .
				 'X-Mailer: PHP/' . phpversion();
		 		mail($to, $subject, $body, $headers);
				header('Location: register.php?action=joined');
				exit;
			}
		}
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
	<div class="all">
		<a href="gallery.php">All</a>
  	</div>
	<?php if (isset($_SESSION['logged_in'])) { ?>
	<div class="logout">
		<a href="logout.php">Logout</a>
	</div>
	<div class="dropdown">
		<a button class="admin">Settings</a>
		<div class="dropdown-content">
			<a href="modify_username.php">Change username</a>
            <a href="modify_password.php">Change password</a>
            <a href="modify_email.php">Change email</a>
            <a href="desactivate.php">Disable notifications</a>
        </div>
    </div>
    <div class="mygallery">
    	<a href="my_gallery.php">My Gallery</a>
    </div>
    <div class="newcreation">
    	<a href="main_section.php">New creation</a>
    </div>
    <div class="container" id="login">
    	Vous n'êtes pas censé être ici.
    </div>
    <?php } else { ?>
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
				<input type="text" placeholder="Enter Username" name="username" autocomplete="off" value="" />
					<label>
						Password
					</label>
				<input type="password" placeholder="Enter Password" name="password" autocomplete="off" value="" />
				
				<button type="submit" class="registerbtn" value="ok"> Register </button>
			</form>
		</div>
	</div>
	<?php
	if ($invalid != 0)
	{
    	echo "<h2>Please enter a valid email address.</h2>";
    }
    if ($already != 0)
	{
    	echo "<h2>Data provided is already in use.</h2>";
    }
    if ($already != 0)
	{
		echo "<h2>Email provided is already in use.</h2>";
	}
    if ($short != 0)
	{
		echo "<h2>Your password is too short !</h2>";
	}
	if ($no_digit != 0)
	{
		echo "<h2>Sorry your password must contain at least one digit and one letter.</h2>";
	}
	if ($alpha != 0)
	{
		echo "<h2>Sorry your username must only contain letters.</h2>";
	}
	if ($shortusername != 0)
	{
		echo "<h2>Sorry your username is too short.</h2>";
	}
	?>
	<?php } ?>
	<div class="footer">
    	<footer>Copyright &copy; 2018 - tbouline@student.42.fr</footer>
	</div>
</body>
</html>