<?php
include 'db_connection.php';
include 'session_start.php';


$admin = $_POST['admin'];
$mycity = $_POST['city'];
$data_start = $_POST['data_start'];
$data_end = $_POST['data_end'];

if($mycity=="all") $mycity="";
if($admin=="all") $admin="";

if($data_start=="start" || $data_start=="today" || $data_end=="today") {
  $sql = "SELECT max(data_concerto) AS max_data, min(data_concerto) AS min_data FROM Concerti";
  $result = $conn->query($sql);
  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
      $actual_data = date('Y-m-d');
      if($data_start=="start") $data_start = $row['min_data'];
      else if($data_start=="today") $data_start = $actual_data;
      if($data_end=="today") $data_end = $actual_data;
  }
}

$sql = "SELECT idConcerto, nome_concerto, nome_citta, extract(DAY from data_concerto) AS giorno, extract(MONTH from data_concerto) AS mese, extract(YEAR from data_concerto) AS anno
        FROM Concerti JOIN Citta ON idCitta = Citta_idCitta
        WHERE Admin_username LIKE '".$admin."%' AND nome_citta LIKE '%".$mycity."%' AND data_concerto BETWEEN '".$data_start."' AND '".$data_end."'
        ORDER BY data_concerto DESC, idConcerto DESC";

echo '
<br><br><br>
<h1> Concerti </h1>
<table align="center">
  <tr><th> ID </th><th> Nome Concerto </th><th> Città </th><th> Data </th></tr>
';

$result = $conn->query($sql);
  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      $idConcerto = $row['idConcerto'];
      $nomeEvento  = $row['nome_concerto'];
      $giorno = $row['giorno'];
        $giorno = ($giorno<10) ? '0'.$giorno : $giorno;
      $mese = $row['mese'];
        $mese = ($mese<10) ? '0'.$mese : $mese;
      $anno = $row['anno'];
      $data = $giorno.'-'.$mese.'-'.$anno;
      $city = $row['nome_citta'];
      echo '<tr id="'.$idConcerto.'" onclick="searchNewsId(this)"><td>'.$idConcerto.'</td><td><textarea>'.$nomeEvento.'</textarea></td><td>'.$city.'</td><td>'.$data.'</td></tr>';
    }
  } else { echo '<tr><td colspan="5">Nessun risultato trovato. </td></tr>'; }

echo '</table>';
?>
