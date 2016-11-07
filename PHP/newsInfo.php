<?php
include 'db_connection.php';

$id = $_GET['idNews'];

$sql =
  "SELECT *
   FROM News n JOIN Admin a ON n.Admin_username = a.username
   WHERE idNews=".$id;

$result = $conn->query($sql);
  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $idNews = $row['idNews'];
    $news  = $row['news'];
    $ext = $row['estensione'];
    $nome = $row['nome'];
    $cognome = $row['cognome'];
  }
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>Info news</title>
    <link rel='stylesheet' href="../css/font.css" type='text/css'>
    <link rel="stylesheet" href="../css/style.css">
  </head>

  <body>
    <div class="form" style="max-width: 600px">
      <h1> INFO NEWS </h1>
      <table align="center">
        <tr><th>  ID NEWS </th><th> DATA </th><th> INVIATO DA </th></tr>
        <tr><td><?php echo $id; ?></td><td></td><td><?php echo $nome." ".$cognome; ?></td></tr>
      </table>
      <br>
      &nbsp News
      <textarea style="width: 100%"><?php echo $news; ?></textarea>
      <br>
      <h1> VISUALIZZAZIONI </h1>
    </div> <!-- /form -->
  </body>
</html>
