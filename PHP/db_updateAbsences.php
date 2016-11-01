<?php
include 'db_connection.php';

$MAX_ASSENZE = 5;

$mess = $_POST['value'];
$content = explode(",", $mess);
$updateType = $content[0];
$search = $content[1];
$target = $content[2];

echo '<table align="center"';

switch ($updateType) {
  //########################################################
  case 'addA':
  $sql = "UPDATE Utenti
  SET assenze = assenze + 1
  WHERE idUtente = '".$target."'";

  $conn->query($sql);

    $sql = "
      SELECT idUtente, nome, cognome, assenze
      FROM Utenti
      WHERE nome LIKE '" .$search. "%' OR cognome LIKE '" .$search. "%'
      ORDER by NOME";
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
          <td><button id="'.$search.'" onclick="subAssenza(this, '.$row["idUtente"].')"> - </button></td>';
          if($row["assenze"]>$MAX_ASSENZE){
            echo '<td style="color:red">'.$row["assenze"].'</td>';
          }
          else {
            echo '<td>'.$row["assenze"].'</td>';
          }
          echo '<td><button id="'.$search.'" onclick="addAssenza(this, '.$row["idUtente"].')""> + </button></td>
        </tr>';
      }
    }
  break;

  //########################################################
  case 'subA':
  $sql = "UPDATE Utenti
  SET assenze = assenze - 1
  WHERE idUtente = '".$target."'";

  $conn->query($sql);

    $sql = "
      SELECT idUtente, nome, cognome, assenze
      FROM Utenti
      WHERE nome LIKE '" .$search. "%' OR cognome LIKE '" .$search. "%'
      ORDER by NOME";
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
          <td><button id="'.$search.'" onclick="subAssenza(this, '.$row["idUtente"].')"> - </button></td>';
          if($row["assenze"]>$MAX_ASSENZE){
            echo '<td style="color:red">'.$row["assenze"].'</td>';
          }
          else {
            echo '<td>'.$row["assenze"].'</td>';
          }
          echo '<td><button id="'.$search.'" onclick="addAssenza(this, '.$row["idUtente"].')""> + </button></td>
        </tr>';
      }
    }
  break;
}

echo '</table>';
?>
