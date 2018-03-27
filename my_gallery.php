<?php
session_start();
include 'config/setup.php';

if (isset($_SESSION['logged_in'])) {
  $user = $_SESSION['userid'];
  if (isset($_GET['delete'])) {
    
    if(!isset($_GET['token'])){
      echo "No token !";
      throw new Exception('No token found!');
      exit;
    }
    if (strcasecmp($_GET['token'], $_SESSION['token']) != 0){
      echo "Mismatch token!";
      throw new Exception('Mismatch Token !');
      exit;
    }

    // recuperer l'image a supprimer
    $id = $dbh->quote($_GET['delete']);
    $select = $dbh->prepare("SELECT img_name, userid FROM gallery WHERE galleryid=$id");
    $select->execute();
    $image = $select->fetch();
    if ($image['userid'] == $user) {
      unlink('img/' . $image['img_name']);
      $dbh->query("DELETE FROM gallery WHERE galleryid=$id");
      header('Location: my_gallery.php');
      die();
    }
  }

  $ppp = 5;
  $select = $dbh->prepare('SELECT COUNT(*) AS total FROM gallery');
  $select->execute();
  $total_pic = $select->fetch();
  $nb_pic = $total_pic['total'];

  $nb_page = ceil($nb_pic / $ppp);

  if(isset($_GET['p'])) {
    $current_page = intval($_GET['p']);

    if($current_page > $nb_page) {
      $current_page=$nb_page;
    } else if ($current_page < 1) {
      $current_page = 1;
    }

  } else {
    $current_page = 1;
  }

  $first = ($current_page-1) * $ppp;

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
  <div style="width: 100%; text-align: center;">
    <?php foreach ($images as $image) : ?>
      <div class="imgandbutton">
        <img style="list-style: none; text-decoration: none; display: inline-block; margin-right: 10px;
    margin-top: 20px;" class="img" src="<?php echo 'img/' . htmlspecialchars($image['img_name']); ?>" title="<?php echo htmlspecialchars($image['img_name']); ?>" width="240px" height="240px">
        <a href="?delete=<?php echo htmlspecialchars($image['galleryid']);?>&token=<?php echo $_SESSION['token']?>">Supprimer</a>
      </div>
    <?php endforeach; ?>
  </div>

  <div class="paginate">
    <p><?php
      if ($current_page > 1) {
        echo ' <a href="my_gallery.php?p='. htmlspecialchars(($current_page) - 1) . '">previous</a>';
      } ?> [ <?php echo htmlspecialchars($current_page); ?> ] <?php
      if ($current_page < $nb_page) {
        echo ' <a href="my_gallery.php?p='. (htmlspecialchars($current_page) + 1) . '">next</a>';
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