<?php
session_start();
include 'config/setup.php';

//collect values from the url
$id = trim($_GET['x']);
$active = trim($_GET['y']);
//if id is number and the active token is not empty carry on
if(is_numeric($id) && !empty($active)){
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
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
  <?php if (isset($_SESSION['logged_in'])) { ?>

  <?php include 'includes/header_log.php'; ?>
  
    <div class="container" id="login">
        Vous n'êtes pas censé être ici.
    </div>
  <?php } else {
    include 'includes/header.php'; ?>
  <div class="footer">
      <footer>Copyright &copy; 2018 - tbouline@student.42.fr</footer>
  </div>
  <?php
  if (isset($active)) {
      echo  "<h2>Your account is activated.</h2>";
  }
  else if (isset($error)) {
      echo "<h2>Your account could not be activated.</h2>";
  }
}
?>
</body>
</html>