<?php
// session_start();
  include 'config/database.php';



?> 

<!DOCTYPE html>
<html>
<head>
  <title>Camagru</title>
  <link rel="stylesheet" href="index.css" href="main_section.php" charset="utf-8">
</head>
<body>
  <a href="index.php"><h1>Camagru</h1></a>
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

  <div class="all">
     <a href="#">All</a>
  </div>
  <div class="mygallery">
      <a href="my_gallery.php">My Gallery</a>
  </div>
  <div class="newcreation">
     <a href="main_section.php">New creation</a>
  </div>
  <div class="footer">
    <p>Footer</p>
  </div>
  <p>
  </p>
  <div class="form">
</div>
  <h2> Galerie </h2>

  <ul class="display-images">
    <?php foreach ($images as $image) : ?>

      <li>
        <img class="img" src="<?php echo 'http://localhost:8100/camagru/'; ?><?php echo $image['img_name']; ?>" title="<?php echo $image['img_name']; ?>" width="100%"><br>
        | <a href="?delete=<?php echo $image['userid'];?>" onclick="return('Sur sur sur ?')">Supprimer</a>
      </li>
     <?php  endforeach; ?>
  </ul>


  <div class="paginate">
    <p><?php
      if ($cp > 1) {
        echo ' <a href="http://localhost:8100/camagru/gallery.php?p='. ($cp - 1) . '">previous</a>';
      } ?> [ <?php echo $cp; ?> ] <?php
      if ($cp < $nb_page) {
        echo ' <a href="http://localhost:8100/camagru/gallery.php?p='. ($cp + 1) . '">next</a>';
      }
    ?></p>
  </div>
  <div class="footer">
    <p>Footer</p>
  </div>
</body>
</body>
</html>