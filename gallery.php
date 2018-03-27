<?php
 session_start();
 include 'config/setup.php';

// pagination
$ppp = 5;
$select = $dbh->prepare('SELECT COUNT(*) AS total FROM gallery');
$select->execute();
$total_pic = $select->fetch();
$nb_pic = $total_pic['total'];

$nb_page = ceil($nb_pic / $ppp);

$i = 0;

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

// affiche les images
$select = $dbh->prepare("SELECT * FROM gallery ORDER BY date DESC LIMIT $first, $ppp");
$select->execute();
$images = $select->fetchAll();


// likes
try {
      $req = $dbh->prepare("SELECT gallery.galleryid, gallery.img_name,
                              COUNT(likes.id) AS countlikes
                              FROM gallery
                              LEFT JOIN likes
                              ON gallery.galleryid = likes.galleryid
                              GROUP BY galleryid 
                              ORDER BY date DESC LIMIT $first, $ppp
                              ");
      $req->execute();
      while ($row = $req->fetchObject()) {
        $articles[] = $row;
      }
} catch (PDOException $e) {
    var_dump($e->getMessage());
    $_SESSION['error'] = "ERROR: ".$e->getMessage();
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
<?php } else { ?>
<?php include 'includes/header.php'; ?>
  <?php } ?>

  <div style="width: 100%;">
    <?php foreach ($images as $image) : ?>
      <div class="display" style="text-align:center; margin-left: 0%;">
        <img style="list-style: none; text-decoration: none; display: inline-block; margin-right: 10px;
          margin-top: 20px;" class="img" src="<?php echo 'img/' . htmlspecialchars($image['img_name']); ?>" title="<?php echo htmlspecialchars($image['img_name']); ?>" width="240px" height="240px">
         <?php
         if (isset($_SESSION['logged_in'])) { ?>
            <p style="text-indent: 0px;"><a  style="" href="<?php echo 'add_comment.php?id='.htmlspecialchars($image['galleryid']);?>">Comment</a></p>
            <?php
            $galleryid = $image['galleryid'];
            $userid = $_SESSION['userid'];
            $checkalready = $dbh->query("SELECT id FROM likes WHERE galleryid=$galleryid AND userid=$userid");
            $alreadyone = $checkalready->fetch();
            if ($alreadyone['id']) { ?>
            <form>
              <input type="hidden" name="token" value="<?= $_SESSION['token']; ?>">
              <p style="text-indent: 0px;"><a  style="" href="<?php echo 'cancel_like.php?id='.$image['galleryid']. '&token=' .$_SESSION['token'];?>">Unlike</a></p>
              <?php } else { ?>
              <p style="text-indent: 0px;"><a  style="" href="<?php echo 'add_like.php?id='.$image['galleryid']. '&token=' .$_SESSION['token'];?>">Like</a></p>
              <?php } ?>
            </form>
            <p style="display: inline-block; text-indent: 0px;"><?php echo htmlspecialchars($articles[$i]->countlikes) ?> people like this.</p>
         </div>
         <?php $i = $i + 1; ?>
        <?php } ?>
       <?php endforeach; ?>
   </div>

  <div class="paginate" style=>
    <p><?php
      if ($current_page > 1) {
        echo ' <a href="gallery.php?p='. (htmlspecialchars($current_page) - 1) . '">previous</a>';
      } ?> [ <?php echo htmlspecialchars($current_page); ?> ] <?php
      if ($current_page < $nb_page) {
        echo ' <a href="gallery.php?p='. (htmlspecialchars($current_page) + 1) . '">next</a>';
      }
    ?></p>
  </div>
  <div class="footer">
    <footer>Copyright &copy; 2018 - tbouline@student.42.fr</footer>
  </div>
</body>
</html>
