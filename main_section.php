
<?php
include 'config/database.php';
function imagecopymerge_alpha($dst_im, $src_im, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h, $pct){

  // function patch for respecting alpha work find on http://php.net/manual/fr/function.imagecopymerge.php
  $cut = imagecreatetruecolor($src_w, $src_h);
  imagecopy($cut, $dst_im, 0, 0, $dst_x, $dst_y, $src_w, $src_h);
  imagecopy($cut, $src_im, 0, 0, $src_x, $src_y, $src_w, $src_h);
  imagecopymerge($dst_im, $cut, $dst_x, $dst_y, 0, 0, $src_w, $src_h, $pct);
}

if (isset($_POST['cpt_1']) && $_POST['cpt_1'] != "" && isset($_POST['img'])) {
  checkCsrf();

  print('bob');
  // get the content of the captured image from the webcam put it in a tmp img
  list($type, $data) = explode(';', $_POST['cpt_1']);
  list(, $data) = explode(',', $data);
  $data = base64_decode($data);
  file_put_contents('img/tmp1.png', $data);

  // creat image from this temporary
  $im = imagecreatefrompng('img/tmp1.png');

  // get selected alpha
  $image = imagecreatefrompng('img/'.$_POST['img'].'.png');

  imagecopymerge_alpha($im, $image, 0, 0, 0, 0, imagesx($image), imagesy($image), 100);

  // Create file name and register the image in database
  $query = $dbh->query("SELECT `$username` FROM `$users` WHERE $id='".$id."'");
  $f = $query->fetch();
  $username = $f[$username];
  $db->query("INSERT INTO gallery SET id=$id");
  $image_id = $db->lastInsertId();
  $image_name = $username.'_'. $image_id . '.png';

  imagepng($im,'img/'. $image_name);
  // free memory
  imagedestroy($im);

  $image_name = $db->quote($image_name);
  $db->query("UPDATE gallery SET name=$img_name WHERE id=$galleryid");
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
      <a href="my_gallery.php">My Gallery</a>
  </div>
  <div class="newcreation">
     <a href="main_section.php">New creation</a>
  </div>
	<div class="footer">
		<p>Footer</p>
	</div>
    <video id="video"></video>
    <button id="startbutton">Prendre une photo</button>
    <canvas style="display: none" id="canvas"></canvas>
    <img id="photo" src="">

    <form action="#" method="post" enctype="multipart/form-data">
      <div>
      <ul class="selection">
        <li><label><img src="img/blossom.png"><input type="radio" name="img" value="alphatest1" checked="checked"></label></li>
        <li><label><img src="img/cherry_blossom.png"><input type="radio" name="img" value="imgtest2"></label></li>
        <li><label><img src="img/vulpix.png"><input type="radio" name="img" value="imgtest3"></label></li>
        <li><label><img src="img/pikachu.png"><input type="radio" name="img" value="imgtest3"></label></li>
      </ul>
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

    <script type="text/javascript">
    (function() {
      var streaming = false,
      video        = document.querySelector('#video'),
      cover        = document.querySelector('#cover'),
      canvas       = document.querySelector('#canvas'),
      photo        = document.querySelector('#photo'),
      startbutton  = document.querySelector('#startbutton'),
      width = 320,
      height = 0;

      navigator.getMedia = ( navigator.getUserMedia ||
                             navigator.webkitGetUserMedia ||
                             navigator.mozGetUserMedia ||
                             navigator.msGetUserMedia);

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
      }

      startbutton.addEventListener('click', function(ev){
          takepicture();
        ev.preventDefault();
      }, false);

})();
    </script>
</body>
</html>