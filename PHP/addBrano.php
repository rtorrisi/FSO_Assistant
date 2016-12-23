<?php
include 'db_connection.php';
session_start();

$result=0;

if($_POST){
    $titolo = $_POST['titolo'];
    $autore = $_POST['autore'];

    if ($autore!="") {
      $sql = "INSERT INTO Brani (titolo, autore) VALUES ('$titolo', '$autore')";
      $result = $conn->query($sql);
      if($result!=0) print json_encode(array('type'=>'done', 'text' => 'Brano inserito correttamente con autore!'));
      else { print json_encode(array('type'=>'error', 'text' => 'Brano non inserito correttamente!')); }
    }
    else {
      $sql = "INSERT INTO Brani (titolo) VALUES ('$titolo')";
      $result = $conn->query($sql);
      if($result!=0) print json_encode(array('type'=>'done', 'text' => 'Brano inserito correttamente!'));
      else { print json_encode(array('type'=>'error', 'text' => 'Brano non inserito correttamente!')); }
    }


}

//controllo errori durante inserimento


$conn->close();

?>
