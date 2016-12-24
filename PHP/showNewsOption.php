<?php
include 'db_connection.php';
include 'session_start.php';

echo '
    <div class="four-in-row">
      Dal
      <select id="opt_dataStart" onchange="newsOptionChanged()">
        <optgroup style="max-height: 80px;">
        <option value="today"> oggi </option>
        <option value="start"> inizio </option>
';

$sql = "SELECT DISTINCT data_news, extract(DAY from data_news) AS giorno, extract(MONTH from data_news) AS mese, extract(YEAR from data_news) AS anno FROM News WHERE data_news IS NOT NULL ORDER BY data_news DESC";
$result = $conn->query($sql);
  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      $sql_data = $row['data_news'];
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
      <select id="opt_dataEnd" onchange="newsOptionChanged()">
        <optgroup style="max-height: 80px;">
        <option value="today"> oggi </option>
';

  $sql = "SELECT DISTINCT data_news, extract(DAY from data_news) AS giorno, extract(MONTH from data_news) AS mese, extract(YEAR from data_news) AS anno FROM News WHERE data_news IS NOT NULL ORDER BY data_news DESC";
  $result = $conn->query($sql);
    if ($result->num_rows > 0) {
      while ($row = $result->fetch_assoc()) {
        $sql_data = $row['data_news'];
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
      Tipo
      <select id="opt_type" onchange="newsOptionChanged()">
        <optgroup style="max-height: 80px;">
        <option value="all"> tutto </option>
';

$sql = "SELECT DISTINCT estensione FROM News WHERE estensione IS NOT NULL ORDER BY estensione";
$result = $conn->query($sql);
  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      $ext = $row['estensione'];
      echo "<option value='$ext'>".$ext."</option>";
    }
  }

echo '
      </optgroup>
      </select>
    </div>

    <div class="four-in-row" style="width: 26%; margin-right: 0">
      Inviato da
      <select id="opt_admin" onchange="newsOptionChanged()">
        <optgroup style="max-height: 80px;">
        <option value="all"> tutto </option>
';

$sql =
  "SELECT DISTINCT Admin_username, nome, cognome
   FROM News n JOIN Admin a ON n.Admin_username=a.username
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
