<?php
include 'db_connection.php';
session_start();

if(isset($_POST["submit"])) {
    // Define $username and $password
    $username=$_POST['username'];
    $password=$_POST['password'];
    // To protect from MySQL injection
    $username = stripslashes($username);
    $password = stripslashes($password);
    $username = mysqli_real_escape_string($conn, $username);
    $password = mysqli_real_escape_string($conn, $password);
    //$password = md5($password);

    //Check username and password from database
    $sql="SELECT * FROM Admin WHERE username='$username' and password='$password'";
    $result=mysqli_query($conn,$sql);
    $row=mysqli_fetch_array($result,MYSQLI_ASSOC);

    if(mysqli_num_rows($result) == 1)
    {
      $_SESSION['username'] = $username; // Initializing Session
      header("location: ../profile.php"); // Redirecting To Other Page
    }
}
?>
