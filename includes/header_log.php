<!DOCTYPE html>
  <div class="topnav" id="myTopnav">
    <a href="index.php" style="color: #FF6A80;" class="active">Camagru</a>
    <a href="gallery.php">All</a>
    <a href="main_section.php">New creation</a>
    <a href="my_gallery.php">My gallery</a>
<!--     <div class="dropdown" style="display: inline-block;
        color: #bbc3c8;
        background: #292929;
        position: absolute;
        text-decoration: none;
        margin-left: 5%;">
      <a href="">Settings</a>
      <div class="dropdown-content"> -->
        <a href="modify_username.php">Change username</a>
        <a href="modify_password.php">Change password</a>
        <a href="modify_email.php">Change email</a>
        <a href="desactivate.php">Disable notifications</a>
        <a href="logout.php">Logout</a>
<!--       </div>
    </div> -->
    <a href="javascript:void(0);" style="font-size:15px;" class="icon" onclick="navMod()">&#9776;</a>
  </div>

<script>
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