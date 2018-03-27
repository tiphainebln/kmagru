<?php
include 'config/setup.php';
session_start();

    $invalid = 0;
    $not_recognised = 0;
    try {
        if ($_POST['email']){
            //email validation
            if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
                $error = 'Please enter a valid email address';
                $invalid = 1;
            } else {
                $email = $_POST['email'];
                $query= $dbh->prepare("SELECT email FROM users WHERE email=:email");
                $query->execute(array(':email' => $email));
                $row = $query->fetch(PDO::FETCH_ASSOC);
                if ($row == null) {
                    $error = 'Email provided is not recognised.';
                    $not_recognised = 1;
              }
            }
            if (!isset($error)) {
                //create the activation code
                $token = md5(uniqid(rand(),true));

                $query = $dbh->prepare("UPDATE users SET resetToken = :token, resetComplete='No' WHERE email = :email");
                $query->execute(array(
                    ':email' => $row['email'],
                    ':token' => $token
                ));
                
                $to = $_POST['email'];
                $subject = "Password Reset";
                $body = "
                <html>
                <head>
                    <title>Resetting password request.</title>
                </head>
                <body>
                    <p>To reset your password, please click on this <a href='http://localhost:8080/tbouline/reset_password.php?key=$token'>link.</a></p>
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
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
  <?php if (isset($_SESSION['logged_in'])) { ?>
   <?php include 'includes/header_log.php'; ?>
  <div class="container" id="login">
    Vous n'êtes pas censé être ici.
</div>
<?php } else { ?>
<?php include 'includes/header.php'; ?>

<!--     FORMULAIRE -->
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
if ($invalid != 0)
{
    echo "<h2>Please enter a valid email address</h2>";
}
if ($not_recognised != 0)
{
    echo "<h2>Email provided is not recognised.</h2>";
}
?>
