<?php

$db_name = "FSO_Database";
$user = "root";
$password = "admin";

$conn = mysqli_connect("localhost", $user, $password, $db_name) or die("Errore connessione database");

$sql = "
  SELECT *
  FROM Utenti
  WHERE nome LIKE '" .$_POST['value']. "%' OR cognome LIKE '" .$_POST['value']. "%' OR telefono LIKE '" .$_POST['value']. "%'";
$result = $conn->query($sql);

echo '
<table align="center">
<tr>
  <th> NOME </th><th> COGNOME </th><th> TELEFONO </th>
</tr>';

while ($row = $result->fetch_assoc()) {
  echo '
  <tr>
    <td>'.$row["nome"].'</td>
    <td>'.$row["cognome"].'</td>
    <td>'.$row["telefono"].'</td>
  </tr>';

}

echo '</table>';
?>
