<?php
include 'db_connection.php';
include 'session_start.php';

$id = $_GET['idConcert'];

$sql =
  "SELECT *, extract(DAY from data_concerto) AS giorno, extract(MONTH from data_concerto) AS mese, extract(YEAR from data_concerto) AS anno
   FROM (Concerti JOIN Citta ON idCitta = Citta_idCitta) JOIN Admin ON username = Admin_username
   WHERE idConcerto=".$id;

$result = $conn->query($sql);
  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $idConcerto = $row['idConcerto'];
    $nome_concerto = $row['nome_concerto'];
    $info  = $row['info'];
    $giorno = $row['giorno'];
    $mese = $row['mese'];
    $anno = $row['anno'];
    $citta = $row['nome_citta'];
    $provincia = $row['Provincia'];
    $nome = $row['nome'];
    $cognome = $row['cognome'];
  }
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>Info concerto</title>
    <link rel='stylesheet' href="../css/font.css" type='text/css'>
    <link rel="stylesheet" href="../css/style.css">
  </head>

  <body>
    <div class="form" style="max-width: 800px">
      <h1> INFO CONCERTO </h1>
      <table align="center">
        <tr><th>  ID CONCERTO </th><th>  NOME </th><th> DATA CONCERTO </th><th> CITTA </th><th> INSERITO DA </th></tr>
        <tr><td><?php echo $id; ?></td><td><?php echo $nome_concerto; ?></td><td><?php echo $giorno.'/'.$mese.'/'.$anno; ?></td><td><?php echo $citta.' ('.$provincia.')'; ?></td><td><?php echo $nome.' '.$cognome; ?></td></tr>
      </table>
      <br>
      &nbsp Info
      <textarea style="width: 100%"><?php echo $info; ?></textarea>
      <br>
      <input type="button" onclick="myf(<?php echo $id; ?>)" value="Cancella Concerto" />

    </div> <!-- /form -->
  </body>
  <script>
    function myf(id) {
      var b = confirm("Vuoi cancellare il Concerto?");
      if(b) window.open("deleteConcert.php?idConcert="+id, "_self");
    }
  </script>
</html>
