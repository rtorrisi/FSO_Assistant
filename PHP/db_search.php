<?php
include 'db_connection.php';

$MAX_ASSENZE = 5;

$mess = $_POST['value'];
$searchType = substr($mess, 0, 3);
$value = substr($mess, 3);

echo '<table align="center">';

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
    <th> ID </th><th> NOME </th><th> COGNOME </th><th colspan="3"> ASSENZE </th>
  </tr>';

  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      echo '
      <tr>
        <td>'.$row["idUtente"].'</td>
        <td>'.$row["nome"].'</td>
        <td>'.$row["cognome"].'</td>
        <td><button id="'.$value.'" onclick="subAssenza(this, '.$row["idUtente"].')"> - </button></td>';
        if($row["assenze"]>$MAX_ASSENZE){
          echo '<td style="color:red">'.$row["assenze"].'</td>';
        }
        else {
          echo '<td>'.$row["assenze"].'</td>';
        }
        echo '<td><button id="'.$value.'" onclick="addAssenza(this, '.$row["idUtente"].')"> + </button></td>
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

  case 'bra':
  $sql = "
    SELECT idBrano, titolo, autore, count(idBasi) num_basi
    FROM Brani LEFT JOIN Basi ON Brani_idBrano = idBrano
    WHERE titolo LIKE '%" .$value. "%' OR autore LIKE '%" .$value. "%'
    GROUP BY idBrano
    ORDER BY titolo";
  $result = $conn->query($sql);

  echo '
  <tr>
    <th> ID </th><th> TITOLO </th><th> AUTORE </th><th> #BASI </th>
  </tr>';

  if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
      echo '
      <tr id="'.$row["idBrano"].'" onclick="searchBranoId(this)">
        <td style="padding: 0px 20px 0px 20px">'.$row["idBrano"].'</td>
        <td align="left">'.$row["titolo"].'</td>
        <td>'.$row["autore"].'</td>
        <td>'.$row["num_basi"].'</td>
      </tr>';
    }
  } else { echo '<tr><td colspan="4">Nessun risultato trovato. </td></tr>'; }
  break;
}

echo '</table>';
?>
