<?php
include 'db_connection.php';

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
      <textarea style="width: 100%"><?php echo $news; ?></textarea><br>

      <?php
        $sql = "DELETE FROM News WHERE idNews = '$id'";
        if($conn->query($sql)) echo "<div align='center'><b> News cancellata </b> </div>";
        else echo "<div align='center'><b> News non cancellata </b> </div>";
      ?>

    </div> <!-- /form -->
  </body>
</html>
