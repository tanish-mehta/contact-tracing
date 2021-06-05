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
    <link href="registrationdesign.css" rel="stylesheet" type="text/css">
    <style>
            input[type="text"] {
                text-align: center;
            }
            input[type="password"] {
                text-align: center;
            }
    </style>

    <script type="text/javascript">
      function validateForm(){
        var x= document.forms["myform"]["name"].value;
        var y= document.forms["myform"]["username"].value;
        var z= document.forms["myform"]["password"].value;
        if(x== ""){
          alert("Please input a name");
          return false;
        }
        else if(y== ""){
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

    <title>Registration</title>
    <h1 class="mainheading">COVID-19 Contact Tracing</h1>
  </head>
  <body>
    <div class="container">
      <form name="myform" action="<?php echo $_SERVER['PHP_SELF'];?>" method="POST" onsubmit="return validateForm()">
        <input type="text" name="name" placeholder="Name" id="namebutton">
        <input type="text" name="surname" placeholder="Surname" id="surnamebutton">
        <input type="text" name="username" placeholder="Username" id="usernamebutton">
        <input type="password" name="password" placeholder="Password" id="passwordbutton">
        <input type="submit" value="<?=Register?>" id="registerbutton">
      </form>

      <?php
      if(isset($_POST['name']) and isset($_POST['surname']) and isset($_POST['username']) and isset($_POST['password'])){
          echo "<hr>";
          if(($conn = mysqli_connect("localhost", "tanish", "tanish", "webdev", "3306"))==false){
          die(mysqli_connect_error());
        }

        $password=$_POST['password'];
        $uppercase = preg_match('@[A-Z]@', $password);
        $lowercase = preg_match('@[a-z]@', $password);
        $number    = preg_match('@[0-9]@', $password);
        $specialChars = preg_match('@[^\w]@', $password);

        if($specialChars>0){
          die("Password cannot contain a special character.<br>Please try again!");
        }

        if(!$uppercase || !$lowercase || !$number || strlen($password) < 8) {
            die("Password should be at least 8 characters in length and should include at least one upper case letter,  one lower case letter, one number.<br>Please try again!");
        }

        $username= mysqli_real_escape_string($conn, $_POST['username']);
        $name= mysqli_real_escape_string($conn, $_POST['name']);
        $surname= mysqli_real_escape_string($conn, $_POST['surname']);


        $cost=10;
        $passwd=password_hash($_POST['password'], PASSWORD_BCRYPT, ["cost" => $cost]);

        $sql="INSERT INTO users (uname, passwd, name, surname) VALUES ('" . $username . "', '" . $passwd . "', '" . $name . "', '" . $surname . "' )";
        if(mysqli_query($conn, $sql)===false){
          die("<br>An error has occured. Please try again with a a different username.");
        }

        echo "<br>Successfully inserted!";
      }

      mysqli_free_result($result);
      mysqli_close($conn);
      ?>


    </div>
  </body>
</html>
