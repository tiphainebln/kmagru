<?php
session_start();
include 'config/setup.php';

if (isset($_SESSION['logged_in'])) {
  $user = $_SESSION['userid'];
  if (isset($_GET['delete'])) {
    // recuperer l'image a supprimer
    $id = $dbh->quote($_GET['delete']);
    $select = $dbh->query("SELECT img_name, userid FROM gallery WHERE galleryid=$id");
    $image = $select->fetch();
    if ($image['userid'] == $user) {
      unlink('img/' . $image['img_name']);
      $dbh->query("DELETE FROM gallery WHERE galleryid=$id");
      header('Location: my_gallery.php');
      die();
    }
  }

  $ppp = 5;
  $select = $dbh->query('SELECT COUNT(*) AS total FROM gallery');
  $total_pic = $select->fetch();
  $nb_pic = $total_pic['total'];

  $nb_page = ceil($nb_pic / $ppp);

  if(isset($_GET['p'])) {
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
  <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
  <?php if (isset($_SESSION['logged_in'])) {
    include 'includes/header_log.php'; ?>
  <!--   DISPLAY -->
  <div class="display-images" style="width: 90%; margin-left: 10%;">
    <?php foreach ($images as $image) : ?>
      <div class="imgandbutton">
        <img style="list-style: none; text-decoration: none; display: inline-block; margin-right: 10px;
    margin-top: 20px;" class="img" src="<?php echo 'img/' . $image['img_name']; ?>" title="<?php echo $image['img_name']; ?>" width="240px" height="240px"> <br>
        | <a href="?delete=<?php echo $image['galleryid'];?>" onclick="alert('Supprimer ?')">Supprimer</a>
      </div>
    <?php endforeach; ?>
  </div>

  <div class="paginate">
    <p><?php
      if ($cp > 1) {
        echo ' <a href="my_gallery.php?p='. ($cp - 1) . '">previous</a>';
      } ?> [ <?php echo $cp; ?> ] <?php
      if ($cp < $nb_page) {
        echo ' <a href="my_gallery.php?p='. ($cp + 1) . '">next</a>';
      }
    ?></p>
  </div>
  <?php
  } else {
    include 'includes/header.php';
  ?>
  <div class="container" id="login">  You're not supposed to see this. </div>
  <?php } ?>
  <div class="footer">
    <footer>Copyright &copy; 2018 - tbouline@student.42.fr</footer>
  </div>
</body>
</html>