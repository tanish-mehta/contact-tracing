<?php
setcookie("window","",time()-1);
setcookie("distance","",time()-1);
session_start();
session_unset();
 session_destroy();
 header("Location:login.php");
 ?>
