<?php
include 'db_connection.php';
include 'session_start.php';

$id = $_GET['idBrano'];

$sql = "
  SELECT idBrano, titolo, autore, count(idBasi) num_basi
  FROM Brani LEFT JOIN Basi ON Brani_idBrano = idBrano
  WHERE idBrano = ".$id."
  GROUP BY idBrano";

$result = $conn->query($sql);
  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $idBrano = $row['idBrano'];
    $titolo  = $row['titolo'];
    $autore = $row['autore'];
    $num_basi = $row['num_basi'];
  }
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>Info Brano</title>
    <link rel='stylesheet' href="../css/font.css" type='text/css'>
    <link rel="stylesheet" href="../css/style.css">
  </head>

  <body>
    <div class="form" style="max-width: 600px">
      <h1> INFO BRANO </h1>
      <table align="center">
        <tr><th>  ID BRANO </th><th> TITOLO </th><th> AUTORE </th><th> #BASI </th></tr>
        <tr><td><?php echo $idBrano; ?></td><td><?php echo $titolo; ?></td><td><?php echo $autore; ?></td><td><?php echo $num_basi; ?></td></tr>
      </table>
      <br>

      <?php
        $sql = "DELETE FROM Brani WHERE idBrano = '$id'";
        if($conn->query($sql)) echo "<div align='center'><b> Brano cancellato </b> </div>";
        else echo "<div align='center'><b> Brano non cancellato </b> </div>";
      ?>

    </div> <!-- /form -->
    </body>
    </html>
