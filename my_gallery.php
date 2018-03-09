<!--  miniatures a faire, forgot.php a retravailler : l'envoi de mail ne fo…  …
…nctionne pas pour cette page. Lastinsertid retourne toujours 0 dans modify_username : peut etre du a pamp. creation de la galerie general. a venir : implementation des commentaires et des likes. -->


<?php
session_start();
include 'config/database.php';

if (isset($_SESSION['logged_in'])) {
  $user = $_SESSION['userid'];
  if (isset($_GET['delete'])) {
    // recuperer l'image a supprimer
    $dbh = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $id = $dbh->quote($_GET['delete']);
    $select = $dbh->query("SELECT img_name, userid FROM gallery WHERE galleryid=$id");
    $image = $select->fetch();
    if ($image['userid'] == $user) {
      // l'image est bien celle de l'utilisateur connecté
      // suppression du fichier
      unlink('img/' . $image['img_name']);
      // supression en bdd
      $dbh->query("DELETE FROM gallery WHERE galleryid=$id");
      // message de confirmation
      echo "artwork deleted.";
      header('Location: my_gallery.php');
      die();
    }
  }

  // GET MY IMAGES
  // $pp -> Pictures Per Pages
  $ppp = 5;

  // recuperer le nombre d'image enregistrées
  $dbh = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
  $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $select = $dbh->query('SELECT COUNT(*) AS total FROM gallery');
  $total_pic = $select->fetch();
  $nb_pic = $total_pic['total'];

  $nb_page = ceil($nb_pic / $ppp);

  // Pagination 

  if(isset($_GET['p'])) {

    // recuperer la valeur de la page courante passer en GET
    $cp = intval($_GET['p']);

    if($cp > $nb_page) {
      $cp=$nb_page;
    } else if ($cp < 1) {
      $cp = 1;
    }

  } else {
    $cp = 1;
  }

  $first = ($cp-1) * $ppp;

  $dbh = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
  $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $user = $_SESSION['userid'];
  $select = $dbh->prepare("SELECT * FROM gallery WHERE userid=$user ORDER BY date DESC LIMIT $first, $ppp");
  $select->execute();
  $images = $select->fetchAll();
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
    <a button class="admin">Admin</a>
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
  <div class="form">
</div>

  <div class="display-images" style="width: 90%; margin-left: 10%;">
    <?php foreach ($images as $image) : ?>
      <div class="imgandbutton">
        <img style="list-style: none; text-decoration: none; display: inline-block; margin-right: 10px;
    margin-top: 20px;" class="img" src="<?php echo 'http://localhost:8080/camagru/img/' . $image['img_name']; ?>" title="<?php echo $image['img_name']; ?>" width="240px" height="240px"> <br>
        | <a href="?delete=<?php echo $image['galleryid'];?>" onclick="alert('Supprimer ?')">Supprimer</a>
      </div>
    <?php endforeach; ?>
  </div>

  <div class="paginate">
    <p><?php
      if ($cp > 1) {
        echo ' <a href="http://localhost:8080/camagru/my_gallery.php?p='. ($cp - 1) . '">previous</a>';
      } ?> [ <?php echo $cp; ?> ] <?php
      if ($cp < $nb_page) {
        echo ' <a href="http://localhost:8080/camagru/my_gallery.php?p='. ($cp + 1) . '">next</a>';
      }
    ?></p>
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