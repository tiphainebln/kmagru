<?php
if (isset($_POST['email'])) {

    if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {

        // Email is Valid
        $email = $db->quote($_POST['email']);
        $row = $db->query("SELECT * FROM users WHERE email=$email");

        if ($row->rowCount() > 0) {
            $salt = 'r9P+*p3CBT^qP^t@Y1|{~g9F[jOL)3_qlj>O)vPXymMyGiPQW(:aYkk^x?I63/.y';
            $password = hash('sha512', $salt.$_POST['email']);
            $forgotpwdurl = 'localhost:8100'.'Camagru/'.'reset.php?q='.$password;

            $bodymail = "Hello,\nIf you really want to reset your password follow this link ".$forgotpwdurl."\n";
            mail( $_POST['email'] , "Forget password" , $bodymail);

        } else {
            // No User Found
        }
    }
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
<div id="login">
    <div class="container">
            <form method="post">        
                <label>
                    Email
                </label>
                <input type="text" placeholder="Enter Email" name="email" autocomplete="off" value="<?php if(isset($error)){ echo $_POST['email']; } ?>" tabindex="2"/>
           <button type="submit" name="login">
                  Submit
            </button>
            </div>
        </form>
    <div class="footer">
        <p>Footer</p>
    </div>
</body>
</html>
