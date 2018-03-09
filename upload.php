<?php

session_start();
include "config/database.php";

function imagecopymerge_alpha($dst_im, $src_im, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h, $pct){
  // function patch for respecting alpha work find on http://php.net/manual/fr/function.imagecopymerge.php
  $result = imagecreatetruecolor($src_w, $src_h);
  imagecopy($result, $dst_im, 0, 0, $dst_x, $dst_y, $src_w, $src_h);
  imagecopy($result, $src_im, 0, 0, $src_x, $src_y, $src_w, $src_h);
  imagecopymerge($dst_im, $result, $dst_x, $dst_y, 0, 0, $src_w, $src_h, $pct);
}

    $target_dir = "img/";
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    // Check if image file is a actual image or fake image
    if(isset($_POST["submit"])) {
        $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
        if($check !== false) {
            echo "File is an image - " . $check["mime"] . ".";
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }
    }
    // Check file size
    if ($_FILES["fileToUpload"]["size"] > 300000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }
    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif" ) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }
    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    // if everything is ok, try to upload file
    } else {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }

    $dbh = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $user = $_SESSION['userid'];
    $image_id = $dbh->lastInsertId();
    $filename = $_SESSION['username'].'_'. mktime() . '.' . $imageFileType;
    if ($imageFileType == 'jpg')
      $upload = imagecreatefromjpeg('img/'. $filename);
    else if ($imageFileType == 'png')
      $upload = imagecreatefrompng('img/'. $filename);
    $imgpng = imagecreatefrompng('img/'.$_POST['img'].'.png');
    imagecopymerge_alpha($upload, $imgpng, 0, 0, 0, 0, imagesx($imgpng), imagesy($imgpng), 100);
    imagepng($upload,'img/'. $filename);
    // free memory
    imagedestroy($upload);
    try {
      $req = $dbh->prepare("INSERT INTO gallery (userid, img_name) VALUES (:user, :filename)");
      $req->execute(array(":user" => $user, ":filename" => $filename));
    }
    catch (PDOException $e) {
      echo $req . "<br>" . $e->getMessage();
    }

?>