<?php

$conn = mysqli_connect("localhost", "root", "admin", "FSO_Database") or die("Errore connessione database");

$mess = $_POST['value'];
$searchType = substr($mess, 0, 3);
$value = substr($mess, 3);

echo '<table align="center"';

switch ($searchType) {
  //########################################################
  case 'ass':
  $sql = "
    SELECT idUtente, nome, cognome, assenze
    FROM Utenti
    WHERE nome LIKE '" .$value. "%' OR cognome LIKE '" .$value. "%' ORDER by NOME";
  $result = $conn->query($sql);

  echo '
  <tr>
    <th> ID UTENTE </th><th> NOME </th><th> COGNOME </th><th colspan="3"> ASSENZE </th>
  </tr>';

  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      echo '
      <tr>
        <td>'.$row["idUtente"].'</td>
        <td>'.$row["nome"].'</td>
        <td>'.$row["cognome"].'</td>
        <td><button id="'.$value.'" onclick="subAssenza(this, '.$row["idUtente"].')"> - </button></td>
        <td>'.$row["assenze"].'</td>
        <td><button id="'.$value.'" onclick="addAssenza(this, '.$row["idUtente"].')"> + </button></td>
      </tr>';
    }
  } else { echo '<tr><td colspan="6">Nessun risultato trovato. </td></tr>'; }
  break;

  //########################################################
  case 'rub':
  $sql = "
    SELECT nome, cognome, telefono
    FROM Utenti
    WHERE nome LIKE '" .$value. "%' OR cognome LIKE '" .$value. "%' OR telefono LIKE '" .$value. "%' ORDER by NOME";
  $result = $conn->query($sql);

  echo '
  <tr>
    <th> NOME </th><th> COGNOME </th><th> TELEFONO </th>
  </tr>';

  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      echo '
      <tr>
        <td>'.$row["nome"].'</td>
        <td>'.$row["cognome"].'</td>
        <td>'.$row["telefono"].'</td>
      </tr>';
    }
  } else { echo '<tr><td colspan="3">Nessun risultato trovato. </td></tr>'; }
  break;
}

echo '</table>';
?>
