<?php
 session_start();
 include 'config/setup.php';

// pagination
$ppp = 5;
$select = $dbh->query('SELECT COUNT(*) AS total FROM gallery');
$total_pic = $select->fetch();
$nb_pic = $total_pic['total'];

$nb_page = ceil($nb_pic / $ppp);

$i = 0;

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

// affiche les images
$select = $dbh->query("SELECT * FROM gallery ORDER BY date DESC LIMIT $first, $ppp");
$images = $select->fetchAll();


// likes
try {
      $req = $dbh->query("SELECT gallery.galleryid, gallery.img_name,
                              COUNT(likes.id) AS countlikes
                              FROM gallery
                              LEFT JOIN likes
                              ON gallery.galleryid = likes.galleryid
                              GROUP BY galleryid 
                              ORDER BY date DESC LIMIT $first, $ppp
                              ");
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

  <div class="display-images" style="width: 100%;">
    <?php foreach ($images as $image) : ?>
      <div class="display" style="text-align:center; margin-left: 0%;">
        <img style="list-style: none; text-decoration: none; display: inline-block; margin-right: 10px;
          margin-top: 20px;" class="img" src="<?php echo 'img/' . $image['img_name']; ?>" title="<?php echo $image['img_name']; ?>" width="240px" height="240px">
         <?php
         if (isset($_SESSION['logged_in'])) { ?>
            <p><a  style="" href="<?php echo 'add_comment.php?id='.$image['galleryid'];?>">Comment</a></p>
            <?php
            $galleryid = $image['galleryid'];
            $userid = $_SESSION['userid'];
            $checkalready = $dbh->query("SELECT id FROM likes WHERE galleryid=$galleryid AND userid=$userid");
            $alreadyone = $checkalready->fetch();
            if ($alreadyone['id']) { ?>
              <p><a  style="text-indent: 0px;" href="<?php echo 'cancel_like.php?id='.$image['galleryid'];?>">Unlike</a></p>
            <?php } else { ?>
              <p><a  style="text-indent: 0px;" href="<?php echo 'add_like.php?id='.$image['galleryid'];?>">Like</a></p>
           <?php } ?>
            <p style="display: inline-block; text-indent: 0px;"><?php echo $articles[$i]->countlikes ?> people like this.</p>
         </div>
         <?php $i = $i + 1; ?>
        <?php } ?>
       <?php endforeach; ?>
   </div>

  <div class="paginate" style=>
    <p><?php
      if ($cp > 1) {
        echo ' <a href="gallery.php?p='. ($cp - 1) . '">previous</a>';
      } ?> [ <?php echo $cp; ?> ] <?php
      if ($cp < $nb_page) {
        echo ' <a href="gallery.php?p='. ($cp + 1) . '">next</a>';
      }
    ?></p>
  </div>
  <div class="footer">
    <footer>Copyright &copy; 2018 - tbouline@student.42.fr</footer>
  </div>
</body>
</html>
