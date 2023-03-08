<!DOCTYPE html>
<html>
<?php
$host = "localhost";
$database = "lab9";
//$user = "webuser";
//$password = "P@ssw0rd";
$user = "dVader";
$password = "password";
$refURL = $_SERVER['HTTP_REFERER'];
$connection = mysqli_connect($host, $user, $password, $database);

//error handling
$error = mysqli_connect_error();
if($error != null)
{
  $output = "<p>Unable to connect to database!</p>";
  exit($output);
}
//request method and verify that parameters are set
else
{
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if ( isset($_POST['firstname']) && isset($_POST['lastname']) && isset($_POST['username']) 
  && isset($_POST['email']) && isset($_POST['password']) ) {
 $user = $_POST['username'];
 $firstname=  $_POST['firstname'];
 $lastname=  $_POST['lastname'];
 $email=  $_POST['email'];
 $password = $_POST['password'];
//check email
$check_email = mysqli_query($connection, "SELECT email FROM users where email = '$email' ");
//check username
$check_username = mysqli_query($connection, "SELECT username FROM users where username = '$user' ");

//verify image on submit

$target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES["userImage"]["name"]);
$uploadOk = 1;
$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
  $check = getimagesize($_FILES["userImage"]["tmp_name"]);
  if($check !== false) {
    echo "File is an image - " . $check["mime"] . ".";
    $uploadOk = 1;
  } else {
    echo "File is not an image.";
    $uploadOk = 0;
  }
}

//check file size
if ($_FILES["userImage"]["size"] > 100000) {
  echo "Sorry, your file is too large. <br>";
  $uploadOk = 0;
}

// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "gif" ) {
  echo "Sorry, only JPG, PNG & GIF files are allowed. <br>";
  $uploadOk = 0;
}
$imagedata = file_get_contents($_FILES['userImage']['tmp_name']);
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
  echo "Sorry, your file was not uploaded.<br>";
// if everything is ok, try to upload file
} else {

  if (move_uploaded_file($_FILES["userImage"]["tmp_name"], $target_file)) {
    echo "The file ". htmlspecialchars( basename( $_FILES["userImage"]["name"])). " has been uploaded. <br>";
  } else {
    echo "Sorry, there was an error uploading your file. <br>";
  }
}


if(mysqli_num_rows($check_email) > 0){
  echo('User already exists with this name and/or email </br>');
  echo "<a href='".$refURL."'>Return to User Entry</a>";
 }
else{
  //hash password
  $password = md5($_POST['password']);

 $query = "INSERT INTO users (username , firstName ,lastName, email, password) VALUES('$user', ' $firstname', '$lastname', '$email', '$password')";
 $result = mysqli_query($connection, $query);
 $select_userID = mysqli_query($connection, "SELECT userID FROM users WHERE username = '$user' " );
 $fetch_userID= mysqli_fetch_assoc($select_userID);
 echo('An account for the user '.$user.' has been created <br>');
 echo("UserId is ".$fetch_userID['userID']);
 $userID= $fetch_userID['userID'];

//insert image to database

 //store the contents of the files in memory in preparation for upload
 $sql = "INSERT INTO userImages (userID, contentType, image) VALUES(?,?,?)";
  // create a new statement to insert the image into the table. Recall
 // that the ? is a placeholder to variable data.
 $stmt = mysqli_stmt_init($connection); //init prepared statement object
 
 mysqli_stmt_prepare($stmt, $sql); // register the query
 
 $null = NULL;
 mysqli_stmt_bind_param($stmt, "isb", $userID, $imageFileType, $null);
 // bind the variable data into the prepared statement. You could replace
 // $null with $data here and it also works. You can review the details
 // of this function on php.net. The second argument defines the type of
 // data being bound followed by the variable list. In the case of the
 // blob, you cannot bind it directly so NULL is used as a placeholder.
 // Notice that the parametner $imageFileType (which you created previously)
 // is also stored in the table. This is important as the file type is
 // needed when the file is retrieved from the database.
 
 mysqli_stmt_send_long_data($stmt, 2, $imagedata);
 // This sends the binary data to the third variable location in the
 // prepared statement (starting from 0).
 $result = mysqli_stmt_execute($stmt) or die(mysqli_stmt_error($stmt));
 // run the statement
 
 mysqli_stmt_close($stmt); // and dispose of the statement.
}
  }
}

mysqli_close($connection);

}








/*
try {
  //step 1 connection
  $connString = "mysql:host=localhost;dbname=lab9";
  //$user = "webuser";
  //$pass = "P@ssw0rd";
  $user = "dVader";
  $pass = "password";
  $refURL = $_SERVER['HTTP_REFERER'];
  $pdo = new PDO($connString,$user,$pass);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

//step 2 validate the request type and make sure that all parameters are set
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ( isset($_POST['firstname']) && isset($_POST['lastname']) && isset($_POST['username']) 
    && isset($_POST['email']) && isset($_POST['password']) ) {
   $validate= True;
   $user = $_POST['username'];
   $firstname=  $_POST['firstname'];
   $lastname=  $_POST['lastname'];
   $email=  $_POST['email'];
   $password = $_POST['password'];

   //$username = $pod->quote($username);
   $stmt1 = $pdo ->prepare("SELECT email FROM users where email = '$email' ");
   $stmt2 = $pdo ->prepare("SELECT username FROM users where username = '$user' ");
   //execute the statement
   $stmt1->bindParam(':email', $user, PDO::PARAM_STR);
   $stmt1->execute(); 
   $stmt1->bindParam(':email', $email, PDO::PARAM_STR);
   $stmt2->execute(); 
   //fetch result
   $check_email = $stmt1->fetch();
   $check_username = $stmt2->fetch();
   if($check_email || $check_username){
       echo('User already exists with this name and/or email');
       echo "<a href='".$refURL."'>Return to User Entry</a>";
   }
   else{
    $sql = "INSERT INTO 'users' (username , firstName ,lastName, email, password) 
  VALUES('$user', ' $firstname', '$lastname', '$email', '$password')";
  $result = $pdo->query($sql); //execute query
  //or $result = mysqli_query($connection,$query);
   }
    
  
    }
  
  while ($row = $result->fetch()) {
  echo $row['ID'] . " - " . $row['CategoryName'] . ",<br/>";
  } //process query
}

  
  $pdo = null;

}
  catch (PDOException $e) {
  die( $e->getMessage() );
  }
  */



    /* //good connection, so do your thing
    $sql = "SELECT * FROM users;";

    $results = mysqli_query($connection, $sql);

    //and fetch requsults
    while ($row = mysqli_fetch_assoc($results))
    {
      echo $row['username']." ".$row['firstName']." ".$row['lastName']." ".$row['email']." ".$row['password']."<br/>";
    }

    mysqli_free_result($results);
    mysqli_close($connection);
    */





?>

</html>