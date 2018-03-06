<?php
	include 'config/database.php';
	session_start();

    try {
        if (isset($_POST['login'])) {
        $dbh = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $username = !empty($_POST['username']) ? trim($_POST['username']) : null;
        $password = !empty($_POST['password']) ? trim($_POST['password']) : null;
        $query= $dbh->prepare("SELECT id, username, password FROM users WHERE username=:username AND password=:password AND active='Yes'");
        $query->execute(array(':username' => $username, ':password' => $password));
        $data = $query->fetch(PDO::FETCH_ASSOC);
        if ($data == null){
            $query->closeCursor();
            $e = "User $username not found.";
           } else { 
                $_SESSION['userid'] = $data['id'];
                $_SESSION['username'] = $data['username'];
                $_SESSION['logged_in'] = true;
                $query->closeCursor();
                header('Location: profile.php');
                die();
            }
        }
        }catch(PDOException $e) {
        $e->getMessage();
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
            <span class="psw">
            	Forgot <a href="forgot.php">password?</a>
            </span>
            <?php         
            if(isset($_GET['action'])){
            //check the action
            switch ($_GET['action']) {
            case 'active':
                echo "<h2>Your account is now active you may now log in.</h2>";
                break;
            case 'reset':
                echo "<h2>Please check your inbox for a reset link.</h2>";
                break;
            case 'resetAccount':
                echo "<h2>Password changed, you may now login.</h2>";
                break;
            }
        }
            ?>
        </form>
  	</div>
	</div>
	<div class="footer">
		<p>Footer</p>
	</div>
</body>
</html>