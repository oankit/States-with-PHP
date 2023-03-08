<!DOCTYPE html>
<html>
<?php
session_start();
if(isset($_SESSION['user'])){
unset($_SESSION['user']);
header('Location: ' . $_SERVER['HTTP_REFERER']);
}

else{
    echo("This page is only accesible to users, please login");
    echo "<a href='login.php'>Login</a>";
}


?>