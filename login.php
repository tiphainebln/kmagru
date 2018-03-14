<?php
    include 'config/setup.php';
	session_start();

    $wrong = 0;
    $not_found = 0;
    try {
        if (isset($_POST['login'])) {

        $username = !empty($_POST['username']) ? trim($_POST['username']) : null;
        $password = !empty($_POST['password']) ? trim($_POST['password']) : null;
        $query= $dbh->prepare("SELECT id, username, hash FROM users WHERE username=:username AND active='Yes'");
        $query->execute(array(':username' => $username));
        $data = $query->fetch(PDO::FETCH_ASSOC);
        if ($data == null){
            $query->closeCursor();
            $not_found = 1;
            $e = "User not found.";
           } else { 
                if (password_verify($password, $data['hash']))
                {
                    $_SESSION['userid'] = $data['id'];
                    $_SESSION['username'] = $data['username'];
                    $_SESSION['logged_in'] = true;
                    $query->closeCursor();
                    header('Location: profile.php');
                    die();
                }
                else
                {
                    $wrong = 1;
                }
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
                <span class="psw">
                    <a href="forgot.php">Forgot password?</a>
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
                if ($wrong != 0)
                    echo "<h2>You entered the wrong Password.</h2>";
                else if ($not_found != 0)
                    echo "<h2>User not found..</h2>";
                ?>
            </form>
  	   </div>
    </div>
    <?php } ?>
    <div class="footer">
        <footer>Copyright &copy; 2018 - tbouline@student.42.fr</footer>
    </div>
</body>
</html>