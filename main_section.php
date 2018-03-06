<?php
session_start();
include 'config/database.php';

function imagecopymerge_alpha($dst_im, $src_im, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h, $pct){
  // function patch for respecting alpha work find on http://php.net/manual/fr/function.imagecopymerge.php
  $result = imagecreatetruecolor($src_w, $src_h);
  imagecopy($result, $dst_im, 0, 0, $dst_x, $dst_y, $src_w, $src_h);
  imagecopy($result, $src_im, 0, 0, $src_x, $src_y, $src_w, $src_h);
  imagecopymerge($dst_im, $result, $dst_x, $dst_y, 0, 0, $src_w, $src_h, $pct);
}
var_dump("photo");

    if (isset($_SESSION['logged_in']) && isset($_POST['cpt_1']) && $_POST['cpt_1'] != "" && isset($_POST['img'])) {
      // get the content of the captured image from the webcam put it in a tmp img
      $timestamp = mktime();
      $file = 'img/'.$timestamp.'.png';
      $filename = $timestamp.'.png';
      list($type, $data) = explode(';', $_POST['cpt_1']);
      list(, $data) = explode(',', $data);
      $data = base64_decode($data);
      file_put_contents($file, $data);

      //creat image from this temporary
      $destination = imagecreatefrompng($file);
     // get selected picture
      $source = imagecreatefrompng('img/'.$_POST['img'].'.png');

      imagecopymerge_alpha($destination, $source, 0, 0, 0, 0, imagesx($source), imagesy($source), 100);
      
      imagepng($destination, $file);
      // imagepng($image, $file);
      // free memory
      imagedestroy($image);

        // Create file name and register the image in database
      $dbh = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
      $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      $user = $_SESSION['userid'];

      try {
      $req = $dbh->prepare("INSERT INTO gallery (userid, img_name) VALUES (:user, :filename)");
          $req->execute(array(":user" => $user, ":filename" => $filename));
      }
      catch (PDOException $e) {
        echo $req . "<br>" . $e->getMessage();
      }
      header('Location: my_gallery.php');
    }
    

if (isset($_FILES['imageready']) && isset($_POST['img'])) {
  
  $upload = $_FILES['imageready'];
  $extension = pathinfo($imageready['name'], PATHINFO_EXTENSION);
  
  if (in_array($extension, array('jpg', 'png'))){
    
    $user = $_SESSION['userid'];
    
    $timestamp = mktime();

    $image_id = $dbh->lastInsertId();

    $filename = $_SESSION['username'].'_'. $image_id . '.' . $extension;
    move_uploaded_file($upload['tmp_name'], $file);

    if ($extension == 'jpg')
      $upload = imagecreatefromjpeg('img/'. $filename);
    else if ($extension == 'png')
      $upload = imagecreatefrompng('img/'. $filename);

    $imgpng = imagecreatefrompng('img/'.$_POST['img'].'.png');
    imagecopymerge_alpha($upload, $imgpng, 0, 0, 0, 0, imagesx($imgpng), imagesy($imgpng), 100);
    imagepng($upload,'img/'. $filename);
    // free memory
    imagedestroy($upload);
    try {
      $dbh = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
      $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $req = $dbh->prepare("INSERT INTO gallery (userid, img_name) VALUES (:user, :filename)");
      $req->execute(array(":user" => $user, ":filename" => $filename));
    }
    catch (PDOException $e) {
      echo $req . "<br>" . $e->getMessage();
    }
  }
  header('Location: my_gallery.php');
}

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
     <a href="gallery.php">All</a>
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
   <div>
    <video id="video"></video>
    <canvas style="display: none" id="canvas"></canvas>
    <img id="photo" src="">
     <button id="startbutton">Prendre une photo</button> 
    <form action="#" method="post" enctype="multipart/form-data">
      <div class="selection">
        <img src="img/imgtest1.png"><input type="radio" name="img" value="imgtest1" checked="checked">
      </div>
      <div class="selection">
        <img src="img/imgtest2.png"><input type="radio" name="img" value="imgtest2">
      </div>
      <div class="selection">
        <img src="img/imgtest3.png"><input type="radio" name="img" value="imgtest3">
      </div>
      <div class="selection">
        <img src="img/imgtest4.png"><input type="radio" name="img" value="imgtest3"> 
      </div>
      </div>
      <div>
        <input type="file" name="image">
      </div>
      <div>
        <input id="cpt_1" type="hidden" name="cpt_1">
      </div>

      <button type="submit">Envoyer</button>
    </form>
    </div>
<!--   <div class="side">
      <p>Liste des images déja crées (cliquez sur une image pour la supprimer)</p>
      <?PHP
      //
      ?> -->
  </div>
</div>
  </div>

    <script type="text/javascript">
    (function() {
      var streaming = false,
      video        = document.querySelector('#video'),
      cover        = document.querySelector('#cover'),
      canvas       = document.querySelector('#canvas'),
      photo        = document.querySelector('#photo'),
      startbutton  = document.querySelector('#startbutton'),
      cpt_1         = document.querySelector('#cpt_1'),
      width = 320,
      height = 0;

      navigator.getMedia = ( navigator.getUserMedia ||
                             navigator.webkitGetUserMedia ||
                             navigator.mozGetUserMedia ||
                             navigator.msGetUserMedia);

      function sleep(milliseconds) {
      var start = new Date().getTime();
        for (var i = 0; i < 1e7; i++) {
          if ((new Date().getTime() - start) > milliseconds){
            break;
        }
      }
    }

      navigator.getMedia({
        video: true,
         audio: false
      },
      function(stream) {
          if (navigator.mozGetUserMedia) {
            video.mozSrcObject = stream;
           } else {
            var vendorURL = window.URL || window.webkitURL;
            video.src = vendorURL.createObjectURL(stream);
            video.play();
          }
          video.play();
        },
        function(err) {
          console.log("An error occured! " + err);
        }
      );

      video.addEventListener('canplay', function(ev){
        if (!streaming) {
          height = video.videoHeight / (video.videoWidth/width);
          video.setAttribute('width', width);
          video.setAttribute('height', height);
          canvas.setAttribute('width', width);
          canvas.setAttribute('height', height);
          streaming = true;
        }
      }, false);

      function takepicture() {
        canvas.width = width;
        canvas.height = height;
        canvas.getContext('2d').drawImage(video, 0, 0, width, height);
        var data = canvas.toDataURL('image/png');
        photo.setAttribute('src', data);
        cpt_1.setAttribute('value', data);
        console.log(data);
      }

      startbutton.addEventListener('click', function(ev){
          takepicture();
      }, false);

})();
    </script>
</body>
</html>

