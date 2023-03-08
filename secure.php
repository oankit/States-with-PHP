<!DOCTYPE html>
<html>
<?php
session_start();
if(isset($_SESSION['user'])){
echo'this is the secure page</br>';
echo "<a href='logout.php'>Logout</a>";
}

else{
    echo("This page is only accesible to users, please login </br>");
    echo "<a href='login.php'>Login</a>";
}


?>