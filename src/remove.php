<?php
if(($con = mysqli_connect("localhost", "tanish", "tanish", "webdev", "3306"))==false){
die(mysqli_connect_error());
}

$id = 0;
if(isset($_POST['id'])){
   $id = mysqli_real_escape_string($con,$_POST['id']);
}

    // Delete record
    $query = "DELETE FROM Visits WHERE id='".$id."'";
    mysqli_query($con,$query);
    mysqli_free_result($result);
    mysqli_close($conn);
    exit;
?>
