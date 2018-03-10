<?php
include "config/database.php";
session_start();
// Récupérer l'image
if (isset($_GET['id'])) {
  $dbh = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
  $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $id = $_GET['id'];
  $select = $dbh->prepare("SELECT img_name FROM gallery WHERE galleryid=$id");
  $image = $select->fetch();

  // Envoyer un commentaire par mail
  // if (isset($_POST['content'])) {
  //   $user = $_SESSION['userid'];
  //   $select = $dbh->prepare("SELECT email FROM users WHERE id=$user");
  //   $mail = $select->fetch();
  //   // send confirmation email

  //   $to = $mail;
  //   $subject = "You received a new comment !";
  //   $body = "
  //   <html>
  //     <head>
  //       <title>New comment</title>
  //     </head>
  //     <body>
  //       <p>"You received a comment from ".$_SESSION['username']."\n".$_POST['content']</p>
  //     </body>
  //   </html>";
  //   // To send HTML mail, the Content-type header must be set
  //   $headers  = 'MIME-Version: 1.0' . "\r\n";
  //   $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
  //   // Additionnal headers
  //   $headers .= 'From: tbouline@student.42.fr' . "\r\n" .
  //   'Reply-To: tbouline@student.42.fr' . "\r\n" .
  //   'X-Mailer: PHP/' . phpversion();
  //   mail($to, $subject, $body, $headers);
  // }

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
    </div>
  </div>
  <div class="mygallery">
    <a href="my_gallery.php">My Gallery</a>
  </div>
  <div class="newcreation">
    <a href="main_section.php">New creation</a>
  </div>
  <div>
    <p><img src="<?php echo 'http://localhost:8080/camagru/img/' .$image['img_name']; ?>"></p>
  </div>

  <div>
    <form action="#" method="post">
      <div>
        <label for="content">Envoyer un Commentaire</label>
        <?php echo textarea('content'); ?>
      </div>
      <button type="submit">Envoyer</button>
    </form>
  </div>
  <?php } else { ?>
  <div class="connect">
    <a href="login.php">Login</a>
  </div>
  <div class="signin">
    <a href="register.php">Register</a>
  </div>
  <div class="container" id="login">  You're not supposed to see this. </div>
  <?php } ?>
  <div class="footer">
    <p>Footer</p>
  </div>
</body>
</html>