<?php
session_start();
    require('config/database.php');

    try {
        //email validation
        if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
            $error[] = 'Please enter a valid email address';
        } else {
            $dbh = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $query = $dbh->prepare('SELECT email FROM users WHERE email = :email');
            $query->execute(array(':email' => $_POST['email']));
            $row = $query->fetch(PDO::FETCH_ASSOC);

            if(empty($row['email'])){
                $error[] = 'Email provided is not recognised.';
            }
        }
        //create the activation code
        $token = md5(uniqid(rand(),true));

        $dbh = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $query = $dbh->prepare("UPDATE users SET resetToken = :token, resetComplete='No' WHERE email = :email");
        $query->execute(array(
            ':email' => $row['email'],
            ':token' => $token
        ));
        
        if ($_POST['email']) {
            $to = $_POST['email'];
            $subject = "Password Reset";
            $body = "
            <html>
            <head>
                <title>Resetting password request.</title>
            </head>
            <body>
                <p>To reset your password, please click on this <a href='http://localhost:8080/camagru/reset_password.php?key=$token'>link.</a></p>
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

            //redirect to login page
            header('Location: login.php?action=reset');
            exit;
        }
    } catch(PDOException $e) {
        $error[] = $e->getMessage();
        print_r( $e );
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

    <div class="all">
       <a href="gallery.php">All</a>
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
                           Email
                     </label>
                     <input type="text" placeholder="Enter Email" name="email" autocomplete="off" value=""/>
                     <button type="submit" name="login" value="ok">
                           Submit
                     </button>
            </form>
    </div>
</div>
<?php } ?>
<div class="footer">
    <footer>Copyright &copy; 2018 - tbouline@student.42.fr</footer>
</div>
</body>
</html>
<?php
if(isset($_GET['action'])){

    //check the action
    switch ($_GET['action']) {
        case 'active':
            echo "<h2'>Your account is now active you may now log in.</h2>";
            break;
        case 'reset':
            echo "<h2>Please check your inbox for a reset link.</h2>";
            break;
    }
}
?>
