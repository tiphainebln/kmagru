<!DOCTYPE html>
<html>
<head>
	<title>Camagru</title>
	<link rel="stylesheet" href="index.css" href="main_section.php" charset="utf-8">
</head>
<body>
	<a href="index.php"><h1>Camagru</h1></a>
	<div class="connect">
		<a href="login.php">Login</a>
	</div>
	<div class="signin">
		<a href="register.php">Register</a>
	</div>
    <video id="video"></video>
    <button id="startbutton">Prendre une photo</button>
    <canvas id="canvas"></canvas>
    <img src="http://cdn.wonderfulengineering.com/wp-content/uploads/2016/02/one-piece-wallpaper-52-610x309.png" id="photo" alt="photo">
	</div>
	<div class="footer">
		<p>Footer</p>
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