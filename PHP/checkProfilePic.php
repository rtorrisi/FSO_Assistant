<?php
include 'db_connection.php';

if($_POST) {
  $username=$_POST['username'];

  $sql = "SELECT * FROM Admin WHERE username = '".$username."'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    header("Content-type: image/jpeg");
    echo 'data:image/jpeg;base64,'.base64_encode($row['profile_pic']);
  }
  else echo 'Data/website_img/profile_default.png';
}
$conn->close();
?>
