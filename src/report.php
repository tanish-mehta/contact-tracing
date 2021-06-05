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
    <link href="reportdesign.css" rel="stylesheet" type="text/css">
    <title>Visits Overview</title>
    <h1 class="mainheading">COVID-19 Contact Tracing</h1>

    <script type="text/javascript">
      function validateForm(){
        var x= document.forms["myform"]["date"].value;
        var y= document.forms["myform"]["time"].value;
        if(x== ""){
          alert("Please input a date");
          return false;
        }
        else if(y== ""){
          alert("Please input a time");
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

      <div id="active">
        <a href="report.php">Report</a>
      </div>

        <a href="settings.php">Settings</a>
      <div id="logout">
        <a href="logout.php">Logout</a>
      </div>
    </div>

    <div class="container">
      <h1>Report an Infection</h1>
      <hr width=90%>
      <p>Please report the date and time when you tested positive for COVID - 19</p>
        <form name="myform" action="<?php echo $_SERVER['PHP_SELF'];?>" method="POST" onsubmit="return validateForm()"/>
          <input class="topbutton" id="datebutton" type="text" name="date" placeholder="Date">
          <br>
          <input class="topbutton" type="text" name="time" placeholder="Time">
          <br>
          <input id="reportbutton" type="submit" value="Report" name="report">
          <input id="cancelbutton" type="reset" value="Cancel">
        </form>
    </div>
  </body>

  <?php

  if(($conn = mysqli_connect("localhost", "tanish", "tanish", "webdev", "3306"))==false){
  die(mysqli_connect_error());
}

  if(!empty($_POST)) {
  $usersname=$_SESSION['username'];
  $sql = "select date, time, duration, x, y from Visits WHERE user='".$usersname."'";

  $result = mysqli_query($conn, $sql) or die("Error in Selecting " . mysqli_error($conn));

  $emparray = array();
  while($row =mysqli_fetch_assoc($result))
  {
    $dateconvert=$row["date"];
    $timeconvert=$row["time"];
      array_push($emparray, array("x"=>$row["x"], "y"=>$row["y"], "date"=>$dateconvert, "time"=>$timeconvert, "duration"=>$row["duration"]));
  }
  json_encode($emparray);

  $dateformat= mysqli_real_escape_string($conn, $_POST['date']);
  $timeformat=mysqli_real_escape_string($conn, $_POST['time']);

  $sql="INSERT INTO Infections (user, date, time) VALUES ('" . $usersname . "', '" . $dateformat . "', '" . $timeformat . "')";
  if(mysqli_query($conn, $sql)===false){
    echo mysqli_error($conn);
    die("<br>Error2: ".$sql);
  }

  mysqli_free_result($result);
  mysqli_close($conn);

  if (($handle = curl_init())===false) {
    echo 'Curl-Error: ' . curl_error($handle);
  } else {
    curl_setopt($handle, CURLOPT_RETURNTRANSFER,true);
    curl_setopt($handle, CURLOPT_FAILONERROR,true);
  }

    $url = "http://ml-lab-7b3a1aae-e63e-46ec-90c4-4e430b434198.ukwest.cloudapp.azure.com:60999/ctracker/report.php";
    curl_setopt($handle, CURLOPT_URL, $url);
    curl_setopt($handle, CURLOPT_HTTPHEADER, array("Content-type: application/json"));
    $post=$emparray;
    curl_setopt($handle, CURLOPT_POSTFIELDS, json_encode($post));
    $server_output = curl_exec($handle);
  }
  ?>
</html>
