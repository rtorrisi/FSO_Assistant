<?php
include 'db_connection.php';

$estensione = $_POST['estensione'];
$admin = $_POST['admin'];
//$data = "";

if($estensione=="all") $estensione="";
if($admin=="all") $admin="";

$sql = "SELECT * FROM News WHERE estensione LIKE '".$estensione."%' AND Admin_username LIKE '".$admin."%'";

echo '
<br><br><br><h1> News </h1>
<table align="center">
  <tr><th> ID </th><th> NEWS </th><th> ESTENSIONE </th></tr>
';

$result = $conn->query($sql);
  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      $idNews = $row['idNews'];
      $news  = $row['news'];
      $ext = $row['estensione'];
      echo '<tr id="'.$idNews.'" onclick="searchNewsId(this)"><td>'.$idNews.'</td><td style="width: auto"><textarea>'.$news.'</textarea></td><td>'.$ext.'</td></tr>';
    }
  }

echo '</table>';
echo '
<script>
  function searchNewsId(row) {
    window.open(
    "profile.php?idNews="+$(row).attr("id"),
    "_blank"
    );
  }
</script>
';
?>
