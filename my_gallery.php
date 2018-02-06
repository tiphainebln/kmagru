
<?php
include 'config/database.php';

// DELETE A IMAGE
$user = $_SESSION['Auth'];
if (isset($_GET['delete'])) {

  // jeton de securité
  checkCsrf();

  // recuperer l'image a supprimer
  $id = $db->quote($_GET['delete']);
  $select = $db->query("SELECT name, user_id FROM gallery WHERE id=$id");
  $image = $select->fetch();

  if ($image['user_id'] == $user['id']) {

    // l'image est bien celle de l'utilisateur connecter
    // suppression du fichier
    unlink(IMAGES . '/' . $image['name']);

    // supression en bdd
    $db->query("DELETE FROM images WHERE id=$id");

    // message de confirmation
    echo "artwork deleted.";
    header('Location: my_creations.php');
    die();
  }
}

// GET MY IMAGES
// $pp -> Pictures Per Pages
$ppp = 4;

// recuperer le nombre d'image enregistrées
$dbh = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$select = $dbh->query('SELECT COUNT(*) AS total FROM gallery');
$total_pic = $select->fetch();
$nb_pic = $total_pic['total'];

$nb_page = ceil($nb_pic / $ppp);

// Pagination du type my_creation.php?p=

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


$userid = $dbh->quote($users['id']);
$select = $dbh->query("SELECT * FROM gallery WHERE userid=$userid ORDER BY date DESC LIMIT $first, $ppp");
$images = $select->fetchAll();

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
    <button><a button class="admin">Admin</a></button>
    <div class="dropdown-content">
      <a href="reset_username.php">Change username</a>
      <a href="reset_password.php">Change password</a>
      <a href="reset_email.php">Change email</a>
    </div>
  </div>

  <div class="all">
     <a href="#">All</a>
  </div>
  <div class="mygallery">
      <a href="#">My Gallery</a>
  </div>
  <div class="newcreation">
     <a href="main_section.php">New creation</a>
  </div>
  <p>
  </p>
  <div class="form">
</div>
  <h2> Mes creations </h2>

  <ul class="display-images">
    <?php foreach ($images as $image) : ?>

      <li>
        <img class="img" src="<?php echo WEBROOT; ?>img/<?php echo $image['name']; ?>" title="<?php echo $image['name']; ?>" width="100%"><br>
        | <a href="?delete=<?php echo $image['id'].'&'.csrf();?>" onclick="return('Sur sur sur ?')">Supprimer</a>
      </li>
     <?php  endforeach; ?>
  </ul>


  <div class="paginate">
    <p><?php
      if ($cp > 1) {
        echo ' <a href="http://localhost:8100/camagru/mygallery.php?p='. ($cp - 1) . '">previous</a>';
      } ?> [ <?php echo $cp; ?> ] <?php
      if ($cp < $nb_page) {
        echo ' <a href="http://localhost:8100/camagru/mygallery.php?p='. ($cp + 1) . '">next</a>';
      }
    ?></p>
  </div>
  <div class="footer">
    <p>Footer</p>
  </div>
</body>
</body>
</html>