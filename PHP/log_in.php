<?php
include 'db_connection.php';

if($_POST) {
  $username=$_POST['username'];
  $password=$_POST['password'];

  $sql = "SELECT * FROM Admin WHERE username = '".$username."' AND password = '".$password."'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) print json_encode(array('type'=>'done', 'text' => 'Accesso eseguito!'));
  else print json_encode(array('type'=>'error', 'text' => 'Accesso fallito!'));
}
$conn->close();
?>
