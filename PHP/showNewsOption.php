<?php
include 'db_connection.php';

echo '
    <div class="four-in-row">
      Dal
      <select name="opt_dataStart">
        <option value="all"> tutto </option>
      </select>
    </div>

    <div class="four-in-row">
      Al
      <select name="opt_dataEnd">
        <option value="all"> tutto </option>
      </select>
    </div>

    <div class="four-in-row" style="width: 18%">
      Tipo
      <select id="opt_type" onchange="newsOptionChanged()">
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
      </select>
    </div>

    <div class="four-in-row" style="width: 26%; margin-right: 0">
      Inviato da
      <select id="opt_admin" onchange="newsOptionChanged()">
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
      </select>
    </div>
';

?>
