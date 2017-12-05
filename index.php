<?php
require 'config/database.php';
session_start();
?>

<!DOCTYPE html>
<html>
<head>
	<title>Camagru</title>
	<link rel="stylesheet" href="index.css" charset="utf-8">
</head>
<?php
	if ($_SERVER['REQUEST_METHOD'] == 'POST') {
 		if (isset($_POST['login'])) {
 			require 'login.php';
 		}
 		else if (isset($_POST['register'])) {
 			require 'register.php';
 		}
 	}
?>
<body>
<a href="index.php"><h1>Camagru</h1></a>
<div class="connect">
	<a href="login.php">Login</a>
</div>
<div class="signin">
	<a href="register.php">Register</a>
</div>
	<p> test </p>
<div class="footer">
	<p>Footer</p>
</div>
</body>
</html>