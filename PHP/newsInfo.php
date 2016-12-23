<?php
include 'db_connection.php';
session_start();
$user = $_SESSION['username'];
if(!isset($user)) header("Location: ../index.php");

$id = $_GET['idNews'];

$sql =
  "SELECT *, extract(DAY from data_news) AS giorno, extract(MONTH from data_news) AS mese, extract(YEAR from data_news) AS anno
   FROM News n JOIN Admin a ON n.Admin_username = a.username
   WHERE idNews=".$id;

$result = $conn->query($sql);
  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $idNews = $row['idNews'];
    $news  = $row['news'];
    $giorno = $row['giorno'];
    $mese = $row['mese'];
    $anno = $row['anno'];
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
        <tr><td id=id_news><?php echo $id; ?></td><td><?php echo $giorno.'/'.$mese.'/'.$anno; ?></td><td><?php echo $nome." ".$cognome; ?></td></tr>
      </table>
      <br>
      &nbsp News
      <textarea style="width: 100%"><?php echo $news; ?></textarea>
      <br>
      <input type="button" onclick="myf(<?php echo $id; ?>)" value="Cancella News" />

      <br>
      <h1> VISUALIZZAZIONI </h1>

        <table align="center">
          <tr><th> ID </th><th> NOME </th><th> COGNOME </th></tr>

        <?php

        $sql =
          "SELECT idUtente, nome, cognome
           FROM Visualizzazioni_News v JOIN Utenti u ON v.Utenti_idUtente = u.idUtente
           WHERE News_idNews=".$id;

        $result = $conn->query($sql);

          if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
              $idUtente = $row['idUtente'];
              $nome  = $row['nome'];
              $cognome = $row['cognome'];
              echo '<tr><td>'.$idUtente.'</td><td>'.$nome.'</td><td>'.$cognome.'</td></tr>';
            }
          }
          else echo '<tr><td colspan="3">Nessun risultato trovato. </td></tr>';
        ?>
      </table>

    </div> <!-- /form -->
  </body>
  <script>
    function myf(id) {
      var b = confirm("Vuoi cancellare la News?");
      if(b) window.open("deleteNews.php?idNews="+id, "_self");
    }
  </script>
</html>
