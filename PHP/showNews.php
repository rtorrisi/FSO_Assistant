<?php
include 'db_connection.php';
include 'session_start.php';

$estensione = $_POST['estensione'];
$admin = $_POST['admin'];
$data_start = $_POST['data_start'];
$data_end = $_POST['data_end'];

if($estensione=="all") $estensione="";
if($admin=="all") $admin="";

if($data_start=="start" || $data_start=="today" || $data_end=="today") {
  $sql = "SELECT max(data_news) AS max_data, min(data_news) AS min_data FROM News";
  $result = $conn->query($sql);
  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
      $actual_data = date('Y-m-d');
      if($data_start=="start") $data_start = $row['min_data'];
      else if($data_start=="today") $data_start = $actual_data;
      if($data_end=="today") $data_end = $actual_data;
  }
}

$sql = "SELECT idNews, news, estensione, extract(DAY from data_news) AS giorno, extract(MONTH from data_news) AS mese, extract(YEAR from data_news) AS anno, count(Utenti_idUtente) as num_visualizzazioni
        FROM News LEFT JOIN Visualizzazioni_News ON idNews = News_idNews
        WHERE estensione LIKE '".$estensione."%' AND Admin_username LIKE '".$admin."%' AND data_news BETWEEN '".$data_start."' AND '".$data_end."'
        GROUP BY idNews
        ORDER BY data_news DESC, idNews DESC";

echo '
<br><br><br>
<h1> News </h1>
<table align="center">
  <tr><th> ID </th><th> NEWS </th><th> DATA </th><th> TIPO </th><th> #VISUAL. </th></tr>
';

$result = $conn->query($sql);
  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      $idNews = $row['idNews'];
      $news  = $row['news'];
      $ext = $row['estensione'];
      $num_vis = $row['num_visualizzazioni'];
      $giorno = $row['giorno'];
        $giorno = ($giorno<10) ? '0'.$giorno : $giorno;
      $mese = $row['mese'];
        $mese = ($mese<10) ? '0'.$mese : $mese;
      $anno = $row['anno'];
      $data = $giorno.'-'.$mese.'-'.$anno;
      echo '<tr id="'.$idNews.'" onclick="searchNewsId(this)"><td>'.$idNews.'</td><td><textarea>'.$news.'</textarea></td><td>'.$data.'</td><td>'.$ext.'</td><td>'.$num_vis.'</td></tr>';
    }
  } else { echo '<tr><td colspan="5">Nessun risultato trovato. </td></tr>'; }

echo '</table>';
?>
