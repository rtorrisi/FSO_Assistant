<?php
include 'db_connection.php';
session_start();
$user = $_SESSION['username'];
if(!isset($user)) header("Location: ../index.php");

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
      <input type="button" onclick="myf(<?php echo $id; ?>)" value="Cancella Brano (e Basi)" />
      <br>

      <h1> BASI </h1>

        <table align="center">
          <tr><th> ID BASE </th><th> FILE ID </th><th> TIPOLOGIA </th><th> CODICE </th></tr>

        <?php

        $sql =
          "SELECT idBasi, file_id, tipologia, codice
          FROM Brani JOIN Basi ON Brani_idBrano = idBrano
          WHERE idBrano =".$id;

        $result = $conn->query($sql);

          if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
              $idBase = $row['idBasi'];
              $file_id = $row['file_id'];
              $tipo = $row['tipologia'];
              $codice = $row['codice'];
              echo '<tr><td>'.$idBase.'</td><td>'.$file_id.'</td><td>'.$tipo.'</td><td>'.$codice.'</td></tr>';
            }
          }
          else echo '<tr><td colspan="4">Nessuna base trovata. </td></tr>';
        ?>
      </table>
    </div> <!-- /form -->
  </body>
  <script>
    function myf(id) {
      var b = confirm("Vuoi cancellare il Brano e le relative Basi?");
      if(b) window.open("deleteBrano.php?idBrano="+id, "_self");
    }
  </script>
</html>
