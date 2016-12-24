<?php
include 'db_connection.php';
include 'session_start.php';

$result=0;

if($_POST){
    $titolo = $_POST['titolo'];
    $autore = $_POST['autore'];

    if (strlen($autore)>1) $sql = "INSERT INTO Brani (titolo, autore) VALUES ('$titolo', '$autore')";
    else $sql = "INSERT INTO Brani (titolo) VALUES ('$titolo')";

    $result = $conn->query($sql);
    $conn->close();
}

//controllo errori durante inserimento
if($result!=0) print json_encode(array('type'=>'done', 'text' => 'Brano inserito correttamente!'));
else { print json_encode(array('type'=>'error', 'text' => 'Brano non inserito correttamente!')); }

?>
