

<?php
session_start();
include 'config/database.php';
 // include 'includes.php';

function imagecopymerge_alpha($dst_im, $src_im, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h, $pct){

  // function patch for respecting alpha work find on http://php.net/manual/fr/function.imagecopymerge.php
  $cut = imagecreatetruecolor($src_w, $src_h);
  imagecopy($cut, $dst_im, 0, 0, $dst_x, $dst_y, $src_w, $src_h);
  imagecopy($cut, $src_im, 0, 0, $src_x, $src_y, $src_w, $src_h);
  imagecopymerge($dst_im, $cut, $dst_x, $dst_y, 0, 0, $src_w, $src_h, $pct);
}

// https://stackoverflow.com/questions/30389392/capture-image-from-webcam-and-save-in-folder-using-php-and-javascript


  // $image = $_POST['image'];
  // $imgpng = $_POST['img'];
  // $login = $_SESSION['Auth'];
  // if (empty($image) || empty ($imgpng))
  // {
  //   echo "Required fields missing.";
  //   return;
  // }
  // else if (empty($login))
  // {
  //   echo "You must be connected to see this page.";
  // }
  // else
  // {''
    if (isset($_POST['cpt_1']) && $_POST['cpt_1'] != "" && isset($_POST['img'])) {
     var_dump("okokok");
      // get the content of the captured image from the webcam put it in a tmp img
      $timestamp = mktime();
      $file = $timestamp.'.png';
      $filename = $file;
      list($type, $data) = explode(';', $_POST['cpt_1']);
      list(, $data) = explode(',', $data);
      $data = base64_decode($data);
      file_put_contents($filename, $data);

      //creat image from this temporary
      $image = imagecreatefrompng($_POST['cpt_1']);

     // get selected picture
      $imgpng = imagecreatefrompng('img/'.$_POST['img'].'.png');

      imagecopymerge_alpha($image, $imgpng, 0, 0, 0, 0, imagesx($imgpng), imagesy($imgpng), 100);

        // Create file name and register the image in database
      $dbh = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
      $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      // $user = $_SESSION['Auth'];
      // $userid = $dbh->quote($user['id']);
      // $dbh->query("INSERT INTO gallery SET userid=$userid");
      $image_id = $dbh->lastInsertId();
      // $image_name = $user['username'].'_'. $image_id . '.png';
      $image_name = $image_id.'png';
      
      imagepng($image, $filename);
      // free memory
      imagedestroy($image);
      

      $image_name = $dbh->quote($filename);
      $image_id = $dbh->lastInsertId();

      $dbh->query("UPDATE gallery SET img_name=$image_name WHERE galleryid=$image_id");
      header('Location: my_gallery.php');
      die();
    }
    // }

if (isset($_FILES['image']) && isset($_POST['img'])) {
  var_dump("ùùùùùùùùùùù");
  $imageready = $_FILES['image'];
  $extension = pathinfo($imageready['name'], PATHINFO_EXTENSION);
  if (in_array($extension, array('jpg', 'png'))){
    
    // Le format du fichier est correct
    $user = $_SESSION['Auth'];
    $userid = $dbh->quote($user['id']);
    $dbh->query("INSERT INTO gallery SET userid=$userid");
    $image_id = $dbh->lastInsertId();
    
     $timestamp = mktime();
    $file = $timestamp.'.png';
    $filename = 'img/'.$file;
    move_uploaded_file($imageready['tmp_name'], 'img/'. $filename);
    if ($extension == 'jpg')
      $image = imagecreatefromjpeg('img/'. $filename);
    else if ($extension == 'png')
      $image = imagecreatefrompng('img/'. $filename);
    $imgpng = imagecreatefrompng('img/'.$_POST['img'].'.png');
    imagecopymerge_alpha($image, $imgpng, 0, 0, 0, 0, imagesx($imgpng), imagesy($imgpng), 100);
    imagepng($image,'img/'. $filename);
    // free memory
    imagedestroy($im);
    $image_name = $dbh->quote($filename);
    $dbh->query("UPDATE gallery SET img_name=$image_name WHERE galleryid=$image_id");
  }
  header('Location: my_gallery.php');
  die();
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
      <a href="reset_username.php">Change username</a>
      <a href="reset_password.php">Change password</a>
      <a href="reset_email.php">Change email</a>
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
   <div>
    <video id="video"></video>
    <button id="startbutton">Prendre une photo</button>
    <canvas style="display: none" id="canvas"></canvas>
    <img id="photo" src="">
  <form action="#" method="post" enctype="multipart/form-data">
      <div>
         <img id="photo" src="" name="photo">
      <ul class="selection">
        <li><label><img src="img/imgtest1.png"><input type="radio" name="img" value="imgtest1" checked="checked"></label></li>
        <li><label><img src="img/imgtest2.png"><input type="radio" name="img" value="imgtest2"></label></li>
        <li><label><img src="img/imgtest3.png"><input type="radio" name="img" value="imgtest3"></label></li>
        <li><label><img src="img/imgtest4.png"><input type="radio" name="img" value="imgtest3"></label></li>
       </ul>
       </div>
       <div id="bottom_form">
        <div>
          <input type="file" name="image">
        </div>
        <div>
          <input id="cpt_1" type="hidden" name="cpt_1">
        </div>
        <button id="submitphoto" type="submit">Envoyer</button>
      </div>
    </form>
/
<!--   <div class="side">
      <p>Liste des images déja crées (cliquez sur une image pour la supprimer)</p>
      <?PHP
      //listPhotos();
      ?>
  </div> -->
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

