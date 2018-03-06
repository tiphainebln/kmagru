<?php
require('config/database.php');

//collect values from the url
$id = trim($_GET['x']);
$active = trim($_GET['y']);
//if id is number and the active token is not empty carry on
if(is_numeric($id) && !empty($active)){
    $dbh = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //update users record set the active column to Yes where the ID and active value match the ones provided in the array
    $query = $dbh->prepare("UPDATE users SET active = 'Yes' WHERE id = :id AND active = :active");
    $query->execute(array(
        ':id' => $id,
        ':active' => $active
    ));
    //if the row was updated redirect the user
    if($query->rowCount() == 1){
        //redirect to login page
        $active = 1;
        header('Location: login.php?action=active');
        exit;

    } else {
        $error = 1;
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
    <div class="all">
     <a href="gallery.php">All</a>
  </div>
    <div class="connect">
        <a href="login.php">Login</a>
    </div>
    <div class="signin">
        <a href="register.php">Register</a>
    </div>
    <div class="footer">
        <p>Footer</p>
    </div>
    <?php 
    if (isset($active)) {
        echo  "<h2>Your account is activated.</h2>";
    }
    else if (isset($error)) {
        echo "<h2>Your account could not be activated.</h2>";
    }
    ?>
</body>
</html>