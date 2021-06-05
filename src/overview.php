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
    <link href="overviewdesign.css" rel="stylesheet" type="text/css">
    <title>Visits Overview</title>
    <h1 class="mainheading">COVID-19 Contact Tracing</h1>
  </head>
  <body>
    <div class="sidenav">
        <a href="home.php">Home</a>
      <div id="active">
        <a href="overview.php">Overview</a>
      </div>
        <a href="addvisit.php">Add Visit</a>
        <a href="report.php">Report</a>
        <a href="settings.php">Settings</a>
      <div id="logout">
        <a href="logout.php">Logout</a>
      </div>
    </div>

    <div class="container">
    <table id="table1" style="margin:auto; margin-top:50px;">
        <thead>
            <tr>
                <th>Date</th>
                <th>Time</th>
                <th>Duration</th>
                <th>X</th>
                <th>Y</th>
                <th></th>
            </tr>
        </thead>
        <tbody>

        <?php
        if(($conn = mysqli_connect("localhost", "tanish", "tanish", "webdev", "3306"))==false){
        die(mysqli_connect_error());
        }
        $sql = "SELECT * from Visits WHERE user='". $_SESSION['username'] ."'";
        if(($result=mysqli_query($conn,$sql))===false){
          die("Error executing " . htmlspecialchars($sql));
        }
        while( $row = mysqli_fetch_array( $result ) ) :
          $id = $row['id'];
          ?>

            <tr id="<?= $id; ?>" >
                <td height="50"><?php echo "<span class='tabledatas'>" . $row['date'] . "</span>"; ?></td>
                <td height="50"><?php echo "<span class='tabledatas'>" . $row['time'] . "</span>"; ?></td>
                <td height="50"><?php echo "<span class='tabledatas'>" . $row['duration'] . "</span>"; ?></td>
                <td height="50"><?php echo "<span class='tabledatas'>" . $row['X'] . "</span>"; ?></td>
                <td height="50"><?php echo "<span class='tabledatas'>" . $row['Y'] . "</span>"; ?></td>
                <td height="50"> <img src="cross.png" alt="delete" onClick="divFunction(<?= $id; ?>)" style="width:20px; height:auto;"/> </td>

            </tr>

        <?php endwhile;

        mysqli_free_result($result);
        mysqli_close($conn);?>

        </tbody>
    </table>

    </div>

    <script type="text/javascript">

    function divFunction(event) {
      var el = event.target;
      var confirmalert = confirm("Are you sure?");
      if (confirmalert == true){
      document.getElementById(event).remove();
      var params = "id=" + event;
      var request = new XMLHttpRequest();
      request.open("POST", "remove.php", true);
      request.setRequestHeader(
        "Content-type",
        "application/x-www-form-urlencoded"
      );
      request.send(params);
      }
      else{
        alert(aborted);
      }

    }

    </script>
  </body>
</html>
