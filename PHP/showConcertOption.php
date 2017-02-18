<?php
include 'db_connection.php';
include 'session_start.php';

echo '
    <div class="four-in-row">
      Dal
      <select id="opt_dataStart" onchange="ConcertOptionChanged()">
        <optgroup style="max-height: 80px;">
        <option value="today"> oggi </option>
        <option selected="selected" value="start"> inizio </option>
';

$sql = "SELECT DISTINCT data_concerto, extract(DAY from data_concerto) AS giorno, extract(MONTH from data_concerto) AS mese, extract(YEAR from data_concerto) AS anno FROM Concerti WHERE data_concerto IS NOT NULL ORDER BY data_concerto DESC";
$result = $conn->query($sql);
  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      $sql_data = $row['data_concerto'];
      $giorno = $row['giorno'];
        $giorno = ($giorno<10) ? '0'.$giorno : $giorno;
      $mese = $row['mese'];
        $mese = ($mese<10) ? '0'.$mese : $mese;
      $anno = $row['anno'];
      $data = $giorno.'-'.$mese.'-'.$anno;
        echo "<option value='$sql_data'>".$data."</option>";
      }
    }

echo '
        </optgroup>
      </select>
    </div>

    <div class="four-in-row">
      Al
      <select id="opt_dataEnd" onchange="ConcertOptionChanged()">
        <optgroup style="max-height: 80px;">
        <option value="today"> oggi </option>
';

  $sql = "SELECT DISTINCT data_concerto, extract(DAY from data_concerto) AS giorno, extract(MONTH from data_concerto) AS mese, extract(YEAR from data_concerto) AS anno FROM Concerti WHERE data_concerto IS NOT NULL ORDER BY data_concerto DESC";
  $result = $conn->query($sql);
    if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        $sql_data = $row['data_concerto'];
        $giorno = $row['giorno'];
          $giorno = ($giorno<10) ? '0'.$giorno : $giorno;
        $mese = $row['mese'];
          $mese = ($mese<10) ? '0'.$mese : $mese;
        $anno = $row['anno'];
        $data = $giorno.'-'.$mese.'-'.$anno;
        echo "<option value='$sql_data'>".$data."</option>";
      }
    }

echo '
      </optgroup>
      </select>
    </div>

    <div class="four-in-row" style="width: 18%">
      Città
      <select id="opt_city" onchange="ConcertOptionChanged()">
        <optgroup style="max-height: 80px;">
        <option value="all"> tutto </option>
';

$sql = "SELECT DISTINCT nome_citta FROM Concerti c JOIN Citta d ON c.Citta_idCitta = d.idCitta ORDER BY nome_citta";
$result = $conn->query($sql);
  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      $city = $row['nome_citta'];
      echo "<option value='$city'>".$city."</option>";
    }
  }

echo '
      </optgroup>
      </select>
    </div>

    <div class="four-in-row" style="width: 26%; margin-right: 0">
      Inserito da
      <select id="opt_admin" onchange="ConcertOptionChanged()">
        <optgroup style="max-height: 80px;">
        <option value="all"> tutto </option>
';

$sql =
  "SELECT DISTINCT Admin_username, nome, cognome
   FROM Concerti c JOIN Admin a ON c.Admin_username=a.username
   ORDER BY nome";

$result = $conn->query($sql);
  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      $admin = $row['Admin_username'];
      $nome = $row['nome'];
      $cognome = $row['cognome'];
      echo "<option value='$admin'>".$nome." ".$cognome."</option>";
    }
  }

echo '
      </optgroup>
      </select>
    </div>
';

?>
