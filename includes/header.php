<!DOCTYPE html>
  <div class="topnav" id="myTopnav">
    <a href="index.php" style="color: #FF6A80;" class="active">Camagru</a>
    <a href="gallery.php">All</a>
    <a href="register.php">Register</a>
    <a href="login.php">Login</a>
    <a href="javascript:void(0);" style="font-size:15px;" class="icon" onclick="navMod()">&#9776;</a>
  </div>
  <script>
  /* Toggle between adding and removing the "responsive" class to topnav when the user clicks on the icon */
  function navMod() {
    var x = document.getElementById("myTopnav");
    if (x.className === "topnav") {
        x.className += " responsive";
    } else {
        x.className = "topnav";
    }
  }
  </script>
<div class="line"></div>