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
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['username']) &&  isset($_POST['oldpassword']) && isset($_POST['newpassword']) ) {
        $user = $_POST['username'];
        $oldpassword = md5($_POST['oldpassword']);
        $newpassword = md5($_POST['newpassword']);
       //check if user exists in database
       $check_exist = mysqli_query($connection, "SELECT username, password FROM users where username = '$user'  
       AND password= '$oldpassword' ");
       
       if(mysqli_num_rows($check_exist) > 0){
//update password
$update_password = mysqli_query($connection, "UPDATE users SET password='$newpassword' WHERE username = '$user' ");
echo($user.' password has been updated');

}
else{
    echo('username/password is invalid');

 }

}

}
mysqli_close($connection);

}

?>

</html>