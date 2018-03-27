<?php
  include 'config/setup.php';
  session_start();
try {
  if (isset($_GET['id']) && isset($_GET['token'])) {
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
    $galleryid = $_GET['id'];
    $my_userid = $_SESSION['userid'];

    // Prevents articles that don't exists beeing added and users to like multiple times the same article
    $req = $dbh->prepare("INSERT INTO likes (userid, galleryid)
            SELECT {$_SESSION['userid']}, {$galleryid} FROM gallery
            WHERE EXISTS (SELECT galleryid FROM gallery WHERE galleryid={$galleryid})
            AND NOT EXISTS (SELECT id FROM likes WHERE userid={$_SESSION['userid']} AND galleryid={$galleryid})
            LIMIT 1
            ");
    $req->execute();
    header('Location: gallery.php');
  }
} catch (PDOException $e) {
    var_dump($e->getMessage());
    $_SESSION['error'] = "ERROR: ".$e->getMessage();
  }


?>


