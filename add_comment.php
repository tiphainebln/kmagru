<?php
include "config/database.php";
session_start();
// Récupérer l'image

if (isset($_GET['id'])) {
  $dbh = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
  $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $galleryid = $_GET['id'];
  $select = $dbh->prepare("SELECT img_name, userid FROM gallery WHERE galleryid=$galleryid");
  $select->execute();
  $image = $select->fetch();

  if (isset($_GET['delete'])) {
    // recuperer le commentaire a supprimer
    $dbh = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $id = $dbh->quote($_GET['delete']);
    $select = $dbh->query("SELECT userid, galleryid FROM comment WHERE id=$id");
    $comment = $select->fetch();
    if ($comment['userid'] == $_SESSION['userid']) {
      $dbh->query("DELETE FROM comment WHERE id=$id");
      header('Location: add_comment.php?id='.$comment['galleryid']);
      die();
    }
  }

        // enregistre le commentaire en db
  try {
    if (isset($_POST['content']) && isset($_POST['submit']) && $_POST['content'] != "") {
      $my_userid = $_SESSION['userid'];
      $my_username = $_SESSION['username'];
      $comment = $_POST['content'];
      $req = $dbh->prepare("INSERT INTO comment (userid, galleryid, comment, username) VALUES (:userid, :galleryid, :comment, :username)");
      $req->execute(array(":userid" => $my_userid, ":galleryid" => $galleryid, ":comment" => $comment, ":username" => $my_username));
    }
  } catch (PDOException $e) {
    var_dump($e->getMessage());
    $_SESSION['error'] = "ERROR: ".$e->getMessage();
  }

        // get les commentaires
  try {
      $querycomment = $dbh->prepare("SELECT comment, username, id FROM comment WHERE galleryid=$galleryid");
      $querycomment->execute();
  } catch (PDOException $e) {
    var_dump($e->getMessage());
    $_SESSION['error'] = "ERROR: ".$e->getMessage();
  }

        // Envoyer une notification par mail
  try{
    if (isset($_POST['content']) && isset($_POST['submit'])) {
      $for_this_user = $image['userid'];

      $query = $dbh->prepare("SELECT email FROM users WHERE id=$for_this_user");
      $query->execute();
      $mail = $query->fetch();

      $query = $dbh->prepare("SELECT notification FROM users WHERE id=$for_this_user");
      $query->execute();
      $toggle = $query->fetch()['notification'];

      if ($toggle == 1)
      {
        $to = $mail['email'];
        $subject = "You received a new comment on your picture !";
        $body ="
        <html>
          <head>
            <title>New comment.</title>
          </head>
          <body>
            <p>You received a comment from " . $_SESSION['username'] . " on <a href='http://localhost:8080/camagru/add_comment.php?id=" . $galleryid . "'>Your Picture</a> ! The message is :\n".$_POST['content']."</p>
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
      }
      header('Location: add_comment.php?id=' .$galleryid. '&action=submitted');
      exit;
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
        Comment: <textarea style="margin-top: 1%;" name="content" rows="5" cols="40"></textarea>
      </div>
      <button style="width: 10%; margin-top: 1%; margin-left: 5%; padding: 9px 20px;" type="submit" name="submit" value="submit">Envoyer</button>
    </form>
  </div>

  <div id="commentlist">
      <?php 
            $comment = $querycomment->fetch();
            while ($comment) {
                echo "<p style='margin-top:15px;' id='auteur'>" .$comment['username']. "</p><p id='comment'>" .$comment['comment']. "</p>";
                if ($comment['username'] == $_SESSION['username'])
                  echo "<a href='add_comment.php?id=" .$galleryid. "&delete=" .$comment['id']. "'>Delete</a>";
                $comment = $querycomment->fetch();
            }
      ?>
  </div>

  <?php 
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