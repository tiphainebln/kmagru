<?php
session_start();
include 'config/setup.php';

function merge_images($dst_im, $src_im, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h, $pct){
  $result = imagecreatetruecolor($src_w, $src_h);
  imagecopy($result, $dst_im, 0, 0, $dst_x, $dst_y, $src_w, $src_h);
  imagecopy($result, $src_im, 0, 0, $src_x, $src_y, $src_w, $src_h);
  imagecopymerge($dst_im, $result, $dst_x, $dst_y, 0, 0, $src_w, $src_h, $pct);
}

    if (isset($_SESSION['logged_in']) && isset($_POST['img']) && isset($_POST['capture']) && $_POST['capture'] != "")
    {
  // get the content of the captured image from the webcam
      list($type, $data) = explode(';', $_POST['capture']);
      list(, $data) = explode(',', $data);
      $data = base64_decode($data);
      file_put_contents('img/tmp.png', $data); // put content into a tmp

      $destination = imagecreatefrompng('img/tmp.png');
      $source = imagecreatefrompng('img/'.$_POST['img'].'.png');

      merge_images($destination, $source, 0, 0, 0, 0, imagesx($source), imagesy($source), 100);
      $user = $_SESSION['userid'];
      $req = $dbh->prepare("SELECT img_name FROM gallery ORDER BY galleryid DESC LIMIT 1;");
      $req->execute();
      $compare = $req->fetch();
      $timestamp = mktime();
      $fullp = 'img/'.$timestamp.'.png';
      $filename = $timestamp.'.png';
      $comparedatabase = explode('.', $compare['img_name'])[0];
      if (intval($timestamp) - intval($comparedatabase) > 5) {
        imagepng($destination, 'img/'.$filename);
        // register the image in database
        try {
            $req = $dbh->prepare("INSERT INTO gallery (userid, img_name) VALUES (:user, :filename)");
            $req->execute(array(":user" => $user, ":filename" => $filename));
                      header('Location:main_section.php');
        }
        catch (PDOException $e) {
          echo $req . "<br>" . $e->getMessage();
        }
      }
      else {
        imagedestroy($destination);
      }
    }

    if (isset($_SESSION['logged_in'])) {
      $user = $_SESSION['userid'];
      $select = $dbh->prepare("SELECT * FROM gallery WHERE userid=$user ORDER BY date DESC");
      $select->execute();
      $images = $select->fetchAll();

      $user = $_SESSION['userid'];
      $query = $dbh->prepare("SELECT * FROM gallery WHERE userid=$user ORDER BY date DESC LIMIT 1");
      $query->execute();
      $result = $query->fetchAll();
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

<!-- DISPLAY -->
        <video id="video"></video>
        <canvas style="display:none" id="canvas"></canvas>
        <img id="photo" style="display:none" src="">

         <div class="picture">
          <?php
            foreach ($result as $image) :
          ?>
            <img class="img" id="lastmanstanding" src="<?php echo 'img/' . htmlspecialchars($image['img_name']); ?>" title="<?php echo htmlspecialchars($image['img_name']); ?>">
          <?php
            endforeach;
          ?>
        </div>








        <div class="display-images">
          <?php 
              foreach ($images as $image) : 
          ?>
          
          <div class="imgandbutton">
            <img class="img" style="margin-bottom:20px;" id="miniatures" src="<?php echo 'img/' . $image['img_name']; ?>" title="<?php echo $image['img_name']; ?>">
          </div>
          <?php 
              endforeach;
          ?>
        </div>
      <div class="wholeselection">
        <form action="#" method="post" enctype="multipart/form-data">
          <input type="hidden" name="token" value="<?= $_SESSION['token']; ?>">
          <div class="pngs">
           <div class="selection">
              <img src="img/imgtest1.png"><input type="radio" name="img" value="imgtest1" id="box1" onclick="validate()">
            </div>
            <div class="selection">
              <img src="img/imgtest2.png"><input type="radio" name="img" value="imgtest2" id="box2" onclick="validate()">
            </div>
            <div class="selection">
              <img src="img/imgtest3.png"><input type="radio" name="img" value="imgtest3" id="box3" onclick="validate()">
            </div>
            <div class="selection">
              <img src="img/imgtest4.png"><input type="radio" name="img" value="imgtest4" id="box4" onclick="validate()">
            </div>
          </div>
          <input id="capture" type="hidden" name="capture">
          <button type="submit" name="startbutton" id="startbutton">Prendre une photo</button>
        </form>
      </div>
      <span class="alternative"><a href="upload_menu.php">Or maybe you don't like to get your picture taken ?</a></span>
      <script type="text/javascript">
        if (document.getElementById('box1').checked == false && document.getElementById('box2').checked == false && document.getElementById('box3').checked == false && document.getElementById('box4').checked == false) {
          console.log("in");
          document.getElementById("startbutton").className = "make-background-grey"; 
          document.getElementById("startbutton").disabled = true;
        }
        
        function validate() {
                  if (document.getElementById('box1').checked == false && document.getElementById('box2').checked == false && document.getElementById('box3').checked == false && document.getElementById('box4').checked == false) {
          console.log("in");
          document.getElementById("startbutton").className = "make-background-grey"; 
          document.getElementById("startbutton").disabled = true;
        }
                    else {
            console.log("out");
          document.getElementById("startbutton").className = ""; 
          document.getElementById("startbutton").disabled = false;
        }

        }

      (function() {
        var streaming = false,
        video        = document.querySelector('#video'),
        cover        = document.querySelector('#cover'),
        canvas       = document.querySelector('#canvas'),
        photo        = document.querySelector('#photo'),
        startbutton  = document.querySelector('#startbutton'),
        capture      = document.querySelector('#capture'),
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
            height = 240;
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
          capture.setAttribute('value', data);
          console.log(data);
        }

        startbutton.addEventListener('click', function(ev){
            takepicture();
        }, false);

  })();
      </script>
<?php } else {
  include 'includes/header.php'; ?>
  <div class="container" id="login">  You're not supposed to see this. </div>
    <?php } ?>
  <div class="footer">
    <footer>Copyright &copy; 2018 - tbouline@student.42.fr</footer>
  </div>
</body>
</html>

