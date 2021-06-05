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
    <link href="homesdesign.css" rel="stylesheet" type="text/css">
    <title>Home Page</title>
    <h1 class="mainheading">COVID-19 Contact Tracing</h1>
    <script type="text/javascript">
    function markerdetails(id){
      alert("Infected person visited this place");
    }
    </script>
  </head>
  <body>
    <div class="sidenav">
      <div id="active">
        <a href="home.php">Home</a>
      </div>
        <a href="overview.php">Overview</a>
        <a href="addvisit.php">Add Visit</a>
        <a href="report.php">Report</a>
        <a href="settings.php">Settings</a>
      <div id="logout">
        <a href="logout.php">Logout</a>
      </div>
    </div>

    <div class="container">
      <h1>Status</h1>
      <hr width=90%>
      <div class="conincon">
        <img id=exetermap src="exeter.jpeg" style="height: 450px; width: 500px; border: 1px solid black;" alt="exeter map">
        <p>Hi, you might have had a connection to an infected person shown at the location shown in red.</p>
        <p id="bottomdetails">Click on the marker to see details about the infection</p>

        <?php
        $posts=[];
        $url = "http://ml-lab-7b3a1aae-e63e-46ec-90c4-4e430b434198.ukwest.cloudapp.azure.com:60999/ctracker/report.php";
        curl_setopt($handle, CURLOPT_URL, $url);
        curl_setopt($handle, CURLOPT_HTTPGET, true);
        curl_setopt($handle, CURLOPT_HEADER,false);
        if (($output=curl_exec($handle))!==false){
          $posts=json_decode($output, true);
          foreach($posts as $post){
             $x=$posts["X"];
             $y=$posts["Y"];
             ?>
        <img src="marker_black.png" id="<?= $id; ?>" style="display: none; position: absolute; z-index:5; height:30px; width:30px;" onclick="markerdetails("<?= $id; ?>")">

        <script type="text/javascript">
        var setmarker = document.getElementById("<?= $id; ?>");
        setmarker.style.display = '';
        setmarker.style.position = 'absolute';
        setmarker.style.left = '<?php echo $x; ?>' + 'px';
        setmarker.style.top = '<?php echo $y; ?>' + 'px';
        </script>
        <?php
          }
        }
        else{
          echo "Curl-error " . curl_error($handle);
        }

        if(($conn = mysqli_connect("localhost", "tanish", "tanish", "webdev", "3306"))==false){
        die(mysqli_connect_error());
        }

        $usersnamestore=$_SESSION['username'];
        $distancestore=$_COOKIE['distance'];
        $windowstore=$_COOKIE['window'];
        $sql= "SELECT users.uname, Infections.date FROM users INNER JOIN Infections ON users.uname = Infections.user INNER JOIN Visits ON users.uname = Visits.user WHERE Visits.date BETWEEN DATE_SUB(CURDATE(), INTERVAL '.$windowstore.' DAY) AND CURDATE() AND EXISTS (SELECT * FROM Visits V WHERE V.user = '.$usersnamestore.'
     AND SQRT (POWER(V.X - Visits.X, 2) + POWER(V.Y - Visits.Y, 2)) < '.$distancestore.' AND (Visits.date BETWEEN V.date AND DATE_ADD(V.date, INTERVAL V.duration MINUTE) OR (V.date BETWEEN Visits.date AND DATE_ADD(Visits.date, INTERVAL Visits.duration MINUTE))))";

        if(($result=mysqli_query($conn,$sql))===false){
          die("Error executing " . htmlspecialchars($sql));
        }

        ?>

      </div>
    </div>

  </body>
</html>
