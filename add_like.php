<?php
  include 'config/setup.php';
  session_start();
try {
  if (isset($_GET['id'])) {
    $galleryid = $_GET['id'];
    $my_userid = $_SESSION['userid'];

    // Prevents articles that don't exists beeing added and users to like multiple times the same article
    $req = $dbh->query("INSERT INTO likes (userid, galleryid)
            SELECT {$_SESSION['userid']}, {$galleryid} FROM gallery
            WHERE EXISTS (SELECT galleryid FROM gallery WHERE galleryid={$galleryid})
            AND NOT EXISTS (SELECT id FROM likes WHERE userid={$_SESSION['userid']} AND galleryid={$galleryid})
            LIMIT 1
            ");
    header('Location: gallery.php');
  }
} catch (PDOException $e) {
    var_dump($e->getMessage());
    $_SESSION['error'] = "ERROR: ".$e->getMessage();
  }


?>


