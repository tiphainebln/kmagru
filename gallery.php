<?php
 session_start();
 include 'config/database.php';

$ppp = 5;
$dbh = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
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

$select = $dbh->query("SELECT * FROM gallery ORDER BY date DESC LIMIT $first, $ppp");
$images = $select->fetchAll();

?> 

<!DOCTYPE html>
<html>
<head>
  <title>Camagru</title>
  <link rel="stylesheet" href="index.css" charset="utf-8">
</head>
<body>
  <a href="index.php"><h1>Camagru</h1></a>
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
  <?php } ?>
  <div class="all">
     <a href="gallery.php">All</a>
  </div>
  <?php if (isset($_SESSION['logged_in'])) { ?>
      <div class="mygallery">
          <a href="my_gallery.php">My Gallery</a>
      </div>
      <div class="newcreation">
         <a href="main_section.php">New creation</a>
      </div>
  <?php } ?>
  <div class="form">
</div>

  <ul class="display-images" style="width: 90%; margin-left: 10%;">
    <?php foreach ($images as $image) : ?>
      <div class="display" style="text-align:center; float:left; clear:right;">
        <img style="list-style: none; text-decoration: none; display: inline-block; margin-right: 10px;
          margin-top: 20px;" class="img" src="<?php echo 'http://localhost:8080/camagru/img/' . $image['img_name']; ?>" title="<?php echo $image['img_name']; ?>" width="240px" height="240px">
         <p><a  style="" href="<?php echo 'add_comment.php?id='.$image['galleryid'];?>">Comment</a></p>
         <p><a  style="" href="<?php echo 'add_like.php?id='.$image['galleryid'];?>">Like</a></p>
      </div>
     <?php  endforeach; ?>
  </ul>

  <div class="paginate">
    <p style="margin-top: 20%;"><?php
      if ($cp > 1) {
        echo ' <a href="http://localhost:8080/camagru/gallery.php?p='. ($cp - 1) . '">previous</a>';
      } ?> [ <?php echo $cp; ?> ] <?php
      if ($cp < $nb_page) {
        echo ' <a href="http://localhost:8080/camagru/gallery.php?p='. ($cp + 1) . '">next</a>';
      }
    ?></p>
  </div>
  <div class="footer">
    <p>Footer</p>
  </div>
</body>
</html>
