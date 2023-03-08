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
    if (isset($_POST['username'])) {
        $user = $_POST['username'];
        $check_exist = mysqli_query($connection, "SELECT username, firstname, lastname, email, userID FROM users where username = '$user' ");
      //display table 
        if(mysqli_num_rows($check_exist) > 0){
            $row= mysqli_fetch_assoc($check_exist);
            $firstname= $row['firstname'];
             $lastname= $row['lastname'];
              $email= $row['email'];
              $userID= $row['userID'];

              // image output
              $sql = "SELECT contentType, image FROM userImages where userID=?";
              // build the prepared statement SELECTing on the userID for the user
              $stmt = mysqli_stmt_init($connection);
              //init prepared statement object
              mysqli_stmt_prepare($stmt, $sql);
              // bind the query to the statement
              mysqli_stmt_bind_param($stmt, "i", $userID);
              // bind in the variable data (ie userID)
              $result = mysqli_stmt_execute($stmt) or die(mysqli_stmt_error($stmt));
              // Run the query. run spot run!
              mysqli_stmt_bind_result($stmt, $type, $image); //bind in results
               // Binds the columns in the resultset to variables
              mysqli_stmt_fetch($stmt);
              // Fetches the blob and places it in the variable $image for use as well
              // as the image type (which is stored in $type)
              mysqli_stmt_close($stmt);
              // release the statement

            echo("<fieldset>
            <legend>User:".$user."</legend>
                <table>
                <tr>
                    <td>First Name: ".$firstname."</td>
                </tr>
                <tr>
                    <td>Last Name: ".$lastname."</td>
                </tr>
                <tr>
                <td>Email: ".$email."</td>
                </tr>
                <tr>
                <td>UserID: ".$userID."</td>
                </tr>
            </table>
            </fieldset>");

            echo '<img src="data:image/'.$type.';base64,'.base64_encode($image).'"/>';

        }
        else{
            echo('user does not exist');
        }
    }
}
mysqli_close($connection);

}

?>


</html>