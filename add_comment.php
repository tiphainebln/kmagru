<?php
include 'config/setup.php';
session_start();
// Récupérer l'image

if (isset($_GET['id'])) {
  $galleryid = $_GET['id'];
  $select = $dbh->prepare("SELECT img_name, userid FROM gallery WHERE galleryid=$galleryid");
  $select->execute();
  $image = $select->fetch();

  if (isset($_GET['delete'])) {
    // recuperer le commentaire a supprimer
     if(!isset($_POST['token'])){
        echo "No token !";
        throw new Exception('No token found!');
        exit;
      }
      if (strcasecmp($_POST['token'], $_SESSION['token']) != 0){
        echo "Mismatch token!";
        throw new Exception('Mismatch Token !');
        exit;
      }
    $id = $dbh->quote($_GET['delete']);
    $select = $dbh->prepare("SELECT userid, galleryid FROM comment WHERE id=$id");
    $select->execute();
    $comment = $select->fetch();
    if ($comment['userid'] == $_SESSION['userid']) {
      $req = $dbh->prepare("DELETE FROM comment WHERE id=$id");
      $req->execute();
      header('Location: add_comment.php?id='.$comment['galleryid']);
      die();
    }
  }

        // enregistre le commentaire en db
  try {
    if (isset($_POST['content']) && isset($_POST['submit']) && $_POST['content'] != "") {
      if(!isset($_POST['token'])){
        echo "No token !";
        throw new Exception('No token found!');
        exit;
      }
      if (strcasecmp($_POST['token'], $_SESSION['token']) != 0){
        echo "Mismatch token!";
        throw new Exception('Mismatch Token !');
        exit;
      }
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
            <p>You received a comment from " . htmlspecialchars($_SESSION['username']) . " on <a href='http://localhost:8080/tbouline/add_comment.php?id=" . htmlspecialchars($galleryid) . "'>Your Picture</a> ! The message is :\n".htmlspecialchars($_POST['content'])."</p>
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
  <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
  <?php if (isset($_SESSION['logged_in'])) { ?>
<!--   MENU -->
 <?php include 'includes/header_log.php'; ?>

<!--     DISPLAY -->
<div class="comment-display" style= "margin-top:  6%;">
  <div>
    <img src="<?php echo 'img/' . $image['img_name']; ?>" title="<?php echo $image['img_name']; ?>">
  </div>

  <div>
    <form action="" method="post">
      <input type="hidden" name="token" value="<?= $_SESSION['token']; ?>">
      <div>
        Comment: <textarea style="margin-top: 1%;" name="content" rows="5" cols="40"></textarea>
      </div>
      <button style="width: 100px; margin-top: 1%; margin-left: 5%; padding: 9px 20px;" type="submit" name="submit" value="submit">Envoyer</button>
    </form>
  </div>
</div>
  <div id="commentlist">
      <?php 
            $comment = $querycomment->fetch();
            while ($comment) {
                echo "<p style='margin-top:15px;' id='auteur'>" .htmlspecialchars($comment['username']). "</p><p id='comment'>" .htmlspecialchars($comment['comment']). "</p>";
                if ($comment['username'] == $_SESSION['username'])
                  echo "<a href='add_comment.php?id=" .htmlspecialchars($galleryid). "&delete=" .htmlspecialchars($comment['id']). "&token=" .htmlspecialchars($_SESSION['token'])."'>Delete</a>";
                $comment = $querycomment->fetch();
            }
      ?>
  </div>

  <?php 
   } else { ?>
    <?php include 'includes/header.php'; ?>
  <div class="container" id="login">  You're not supposed to see this. </div>
  <?php } ?>
  <div class="footer">
    <footer>Copyright &copy; 2018 - tbouline@student.42.fr</footer>
  </div>
</body>
</html>