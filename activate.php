<?php
require('config/database.php');
session_start();
//collect values from the url
$id = trim($_GET['x']);
$active = trim($_GET['y']);
//if id is number and the active token is not empty carry on
if(is_numeric($id) && !empty($active)){
    $dbh = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //update users record set the active column to Yes where the ID and active value match the ones provided in the array
    $query = $dbh->prepare("UPDATE users SET active = 'Yes' WHERE id = :id");
    $query->execute(array(
        ':active' => $active
    ));
    //if the row was updated redirect the user
    if($query->rowCount()){
        //redirect to login page
        "Your account is activated.";
        $_SESSION['userid'] = $id;
        header('Location: login.php?action=active');
        exit;

    } else {
        $error[] = "Your account could not be activated.";
        echo "Your account could not be activated.";
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
    <div class="footer">
        <p>Footer</p>
    </div>
</body>
</html>