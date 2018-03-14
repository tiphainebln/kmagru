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
      <a href="desactivate.php">Disable notifications</a>
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
    <footer>Copyright &copy; 2018 - tbouline@student.42.fr</footer>
  </div>
</body>
</html>