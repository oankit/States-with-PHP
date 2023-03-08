<!DOCTYPE html>
<html>
<?php
session_start();
if(isset($_SESSION['user'])){
echo'Welcome to test page!</br>';
echo "<a href='secure.php'>Secure Data Page</a></br>";
echo "<a href='logout.php'>Logout</a>";
}

else{
    header("Location: login.php");
}


?>