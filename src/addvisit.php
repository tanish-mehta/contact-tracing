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
    <link href="addvisitdesign.css" rel="stylesheet" type="text/css">
    <title>Add Visit</title>
    <h1 class="mainheading">COVID-19 Contact Tracing</h1>
    <script type="text/javascript">
    function FindPosition(oElement)
    {
      if(typeof( oElement.offsetParent ) != "undefined")
      {
        for(var positionx = 0, positiony = 0; oElement; oElement = oElement.offsetParent)
        {
          positionx += oElement.offsetLeft;
          positiony += oElement.offsetTop;
        }
          return [ positionx, positiony ];
        }
        else
        {
          return [ oElement.x, oElement.y ];
        }
    }
    function GetCoordinates(e)
    {
      var positionx = 0;
      var positiony = 0;
      var imageposition;
      imageposition = FindPosition(mainimage);
      if (!e) var e = window.event;
      if (e.pageX || e.pageY)
      {
        positionx = e.pageX;
        positiony = e.pageY;
      }
      else if (e.clientX || e.clientY)
        {
          positionx = e.clientX + document.body.scrollLeft + document.documentElement.scrollLeft;
          positiony = e.clientY + document.body.scrollTop + document.documentElement.scrollTop;
        }
        positionx = positionx - imageposition[0];
        positiony = positiony - imageposition[1];
        document.getElementById("xcoord").value = positionx;
        document.getElementById("ycoord").value = positiony;
        var x = event.clientX;
        var y = event.clientY;
        var setmarker = document.getElementById("marker");
        setmarker.style.display = '';
        setmarker.style.position = 'absolute';
        setmarker.style.left = x + 'px';
        setmarker.style.top = y + 'px';
    }
      function validateForm(){
        var x= document.forms["myform"]["date"].value;
        var y= document.forms["myform"]["time"].value;
        var z= document.forms["myform"]["duration"].value;
        var xx= document.forms["myform"]["duration"].value;
        var yy= document.forms["myform"]["duration"].value;
        if(x== ""){
          alert("Please input a date");
          return false;
        }
        else if(y== ""){
          alert("Please input a time");
          return false;
        }
        else if(z== ""){
          alert("Please input a duration");
          return false;
        }
        else if(xx<0){
          alert("Please input coordinates by clicking on the map");
          return false;
        }
        else if(yy<0){
          alert("Please input coordinates by clicking on the map");
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
      <div id="active">
        <a href="addvisit.php">Add Visit</a>
      </div>
        <a href="report.php">Report</a>
        <a href="settings.php">Settings</a>
      <div id="logout">
        <a href="logout.php">Logout</a>
      </div>
    </div>
    <div class="container" >
      <img src="marker_black.png" id="marker" style="display: none; position: absolute; z-index:5;">
      <h1>Add a new Visit</h1>
      <hr width=90%>
      <div class="conincon" style="position: relative;">
      <img id="exetermap" alt="" src="exeter.jpeg" width="500" height="450" style="z-index:2; border: 1px solid black;"/>
      <script type="text/javascript">
      var mainimage = document.getElementById("exetermap");
      mainimage.onmousedown = GetCoordinates;
      </script>
        <form name="myform" action="<?php echo $_SERVER['PHP_SELF'];?>" method="POST" onsubmit="return validateForm()">
          <input class="topbutton" type="text" name="date" placeholder="Date">
          <br>
          <input class="topbutton" type="text" name="time" placeholder="Time">
          <br>
          <input class="topbutton" type="text" name="duration" placeholder="Duration">
          <br>
          <input id="addbutton" class="topbutton" type="submit" value="Add">
          <br>
          <input id="cancelbutton" class="topbutton" type="reset" value="Cancel">
          <input type="hidden" id="xcoord" name="xcoord" value="">
          <input type="hidden" id="ycoord" name="ycoord" value="">
        </form>
      </div>
    </div>

    <?php
      if(($conn = mysqli_connect("localhost", "tanish", "tanish", "webdev", "3306"))===false){
        die(mysqli_connect_error());
      }

      if(isset($_POST['date']) and isset($_POST['time']) and isset($_POST['duration']) and isset($_POST['xcoord']) and isset($_POST['ycoord'])){

        $date= mysqli_real_escape_string($conn, $_POST['date']);
        $time= mysqli_real_escape_string($conn, $_POST['time']);
        $duration= mysqli_real_escape_string($conn, $_POST['duration']);
        $xcoord= mysqli_real_escape_string($conn, $_POST['xcoord']);
        $ycoord= mysqli_real_escape_string($conn, $_POST['ycoord']);

        $sql="INSERT INTO Visits (user, date, time, duration, X, Y) VALUES ('" . $_SESSION['username'] . "', '" . $date . "', '" . $time . "', '" . $duration . "', '" . $xcoord . "', '" . $ycoord . "' )";

        if(($result=mysqli_query($conn,$sql))===false){
          echo mysqli_error($conn);
          die("Error executing " .htmlspecialchars($sql));
        }

          mysqli_free_result($result);
          mysqli_close($conn);
      }
     ?>

</body>
</html>
