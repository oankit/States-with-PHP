<!DOCTYPE html>
<html>
<?php
$host = "localhost";
$database = "lab9";
//$user = "webuser";
//$password = "P@ssw0rd";
$user = "dVader";
$password = "password";
$connection = mysqli_connect($host, $user, $password, $database);

$error = mysqli_connect_error();
if($error != null)
{
  $output = "<p>Unable to connect to database!</p>";
  exit($output);
}

else
{
session_start();
if(!isset($_SESSION['user'])){
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (isset($_POST['username']) &&  isset($_POST['password']) ) {
 $user = $_POST['username'];
 $password = md5($_POST['password']);
//check if user exists in database
$check_exist = mysqli_query($connection, "SELECT username, password FROM users where username = '$user'  
AND password= '$password' ");


if(mysqli_num_rows($check_exist) > 0){
  $_SESSION['user'] = $_POST['username'];
  echo('Welcome to the test site! </br>');
  header("Location: home.php");
 }

 else{
    echo('username/password is invalid');
    header("Location: login.php");

 }

}
}


}
//redirect to home page
else{
  header("Location: home.php");
}
mysqli_close($connection);
}


?>