<?php
  session_start();
  if(isset($_SESSION['username']))
 {
    header("Location:home.php");
 }
?>

<!DOCTYPE html>
<html>
  <head>
    <link href="logindesign.css" rel="stylesheet" type="text/css">

    <style>
            input[type="text"] {
                text-align: center;
            }
            input[type="password"] {
                text-align: center;
            }
    </style>

    <title>Login</title>
    <script type="text/javascript">
      function validateForm(){
        var y= document.forms["myform"]["username"].value;
        var z= document.forms["myform"]["password"].value;
        if(y== ""){
          alert("Please input a username");
          return false;
        }
        else if(z== ""){
          alert("Please input a password");
          return false;
        }
        else{
          return true;
        }
      }
      </script>

    <h1 class="mainheading">COVID-19 Contact Tracing</h1>

  </head>
  <body>
    <div class="container">
      <form name="myform" action="<?php echo $_SERVER['PHP_SELF'];?>" method="POST" onsubmit="return validateForm()">
        <input id="usernamebutton" type="text" name="username" placeholder="Username">
        <input id="passwordbutton" type="password" name="password" placeholder="Password">
        <input id="loginbutton" type="submit" value="Login">
        <input id="resetbutton" type="reset" value="Reset">
      </form>
      <form action="registration.php">
          <input id="registerbutton" type="submit" value="Register" />
      </form>

      <?php
        if(($conn = mysqli_connect("localhost", "tanish", "tanish", "webdev", "3306"))===false){
          die(mysqli_connect_error());
        }

        if(isset($_POST['username']) and isset($_POST['password'])){

          $username= mysqli_real_escape_string($conn, $_POST['username']);
          $password= mysqli_real_escape_string($conn, $_POST['password']);


          $sql= "SELECT passwd from users WHERE uname='".$username."'";
          if(($result=mysqli_query($conn,$sql))===false){
            die("Error executing " . htmlspecialchars($sql));
          }

          if (mysqli_num_rows($result)!==1){
            die("Username does not exist ".htmlspecialchars($username));
          }

          $user=mysqli_fetch_row($result);
            if(password_verify($password, $user[0])){
              $_SESSION['username']=$username;
              $cookie_name="window";
              $cookie_value="7";
              $path="/";
              setcookie($cookie_name, $cookie_value, $path);
              $cookie_name="distance";
              $cookie_value="20";
              $path="/";
              setcookie($cookie_name, $cookie_value, $path);
              header("Location: home.php");
            } else {
              echo "Wrong password";
            }
            mysqli_free_result($result);
            mysqli_close($conn);
        }
       ?>
    </div>
  </body>
</html>
