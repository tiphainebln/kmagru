<?php
	include 'config/setup.php';
	session_start();
	try {
	  if (isset($_GET['id'])) {
	    $galleryid = $_GET['id'];
	    $my_userid = $_SESSION['userid'];

	    // Prevents articles that don't exists beeing added and users to like multiple times the same article
	    $req = $dbh->query("DELETE FROM likes WHERE userid=$my_userid AND galleryid=$galleryid");
	    header('Location: gallery.php');
	  }
	} catch (PDOException $e) {
	    var_dump($e->getMessage());
	    $_SESSION['error'] = "ERROR: ".$e->getMessage();
	  }
?>