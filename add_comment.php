<?php
include "config/database.php";
session_start();
// Récupérer l'image
var_dump("25");
$send = 0;
if (isset($_GET['id'])) {
  $dbh = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
  $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $galleryid = $_GET['id'];
  $select = $dbh->prepare("SELECT img_name, userid FROM gallery WHERE galleryid=$galleryid");
  $select->execute();
  $image = $select->fetch();


  // Envoyer un commentaire par mail
  try{
    if (isset($_POST['content']) && isset($_POST['submit'])) {
      $for_user = $image['userid'];
      $userid = $_SESSION['userid'];

      $query = $dbh->prepare("SELECT email FROM users WHERE id=$for_user");
      $query->execute();
      $mail = $query->fetch();
      // send confirmation email

      $to = $mail;
      $subject = "You received a new comment !";
      $body = " 
      You received a comment from    ".$_SESSION['username']."   The message is :\n".$_POST['content'];
      // To send HTML mail, the Content-type header must be set
      $headers  = 'MIME-Version: 1.0' . "\r\n";
      $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
      // Additionnal headers
      $headers .= 'From: tbouline@student.42.fr' . "\r\n" .
      'Reply-To: tbouline@student.42.fr' . "\r\n" .
      'X-Mailer: PHP/' . phpversion();
      mail($to, $subject, $body, $headers);
      
      $send = 1;
      $comment = $_POST['content'];
      #enregistre le commentaire en db
      $req = $dbh->prepare("INSERT INTO comment (userid, galleryid, comment) VALUES (:userid, :galleryid, :comment)");
      $req->execute(array(":userid" => $userid, ":galleryid" => $galleryid, ":comment" =>$comment));
    }
  } catch (PDOException $e) {
    var_dump($e->getMessage());
          $_SESSION['error'] = "ERROR: ".$e->getMessage();
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
    <img src="<?php echo 'http://localhost:8080/camagru/img/' . $image['img_name']; ?>" title="<?php echo $image['img_name']; ?>">
  </div>

  <div>
    <form action="" method="post">
      <div>
        Comment: <textarea style="margin-top: 1%;" name="content" rows="5" cols="40"><?php echo $comment;?></textarea>
      </div>
      <button style="width: 10%; margin-top: 1%; margin-left: 5%; padding: 9px 20px;" type="submit" name="submit" value="submit">Envoyer</button>
    </form>
  </div>

  <?php 
  if ($send != 0)
  {
    echo "<h2>The comment has been submited.</h2>";
  }
   } else { ?>
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