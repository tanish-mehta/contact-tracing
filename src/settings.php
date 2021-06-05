<?php
session_start();
if(!isset($_SESSION['username']))
{
  header("Location:login.php");
}
?>
<!DOCTYPE html>
<html>
  <head>
    <link href="settingsdesign.css" rel="stylesheet" type="text/css">
    <title>Settings</title>
    <h1 class="mainheading">COVID-19 Contact Tracing</h1>
    <script type="text/javascript">
      function validateForm(){
        var x= document.forms["myform"]["window"].value;
        var y= document.forms["myform"]["distance"].value;
        if(x== ""){
          alert("Please input a window");
          return false;
        }
        else if(y== ""){
          alert("Please input a distance between 0 and 500");
          return false;
        }
        else if (isNaN(y))
        {
          alert("Please input a distance between 0 and 500");
          return false;
        }
        else if((y<0) || (y>500)){
          alert("Please input a distance between 0 and 500");
          return false;
        }
        else{
          return true;
        }
      }
      </script>
  </head>
  <body>
    <div class="sidenav">
        <a href="home.php">Home</a>
        <a href="overview.php">Overview</a>
        <a href="addvisit.php">Add Visit</a>
        <a href="report.php">Report</a>
      <div id="active">
        <a href="settings.php">Settings</a>
      </div>
      <div id="logout">
        <a href="logout.php">Logout</a>
      </div>
    </div>

    <div class="container">
      <h1>Alert Settings</h1>
      <hr width=90%>
      <p>Here you may change the alert distance and the time span for which the contact tracing will be performed.</p>
        <form name="myform" action="<?php echo $_SERVER['PHP_SELF'];?>" method="POST" onsubmit="return validateForm()"/>
          <label for="window">window</label>
          <!--<input class="topbutton" id="window" type="text" name="window">-->
          <select class="topbutton" id="window" name="window" style="width: 415px; height: 40px;">
            <option id="setvalue" value="" selected><?php echo ($_COOKIE["window"]/7); ?></option>
            <option value="7">1 week</option>
            <option value="14">2 weeks</option>
            <option value="21">3 weeks</option>
            <option value="28">4 weeks</option>
          </select>
          <br>
          <label for="distance">distance</label>
          <input class="topbutton" id="distance" type="text" name="distance" placeholder="<?php echo $_COOKIE["distance"]; ?>">
          <br>
          <input id="reportbutton" type="submit" value="Report" name="report">
          <input id="cancelbutton" type="reset" value="Cancel">
        </form>
    </div>
    <?php
    if(isset($_POST['window']) and isset($_POST['distance'])){
      $window=mysqli_real_escape_string($conn, $_POST['window']);
      $distance=mysqli_real_escape_string($conn, $_POST['distance']);
    setcookie('window',$_POST["window"],'/');
    setcookie('distance',$_POST["distance"],'/');
  }
    ?>
  </body>
</html>
