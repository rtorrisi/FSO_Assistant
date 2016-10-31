<?php

$conn = mysqli_connect("localhost", "root", "admin", "FSO_Database") or die("Errore connessione database");

if($_POST){

    $username=$_POST['username'];
    $cognome=$_POST['surname'];
    $chat_id=$_POST['chat_id'];
    $password=$_POST['password'];
    $nome = $_POST["name"];
    $image=addslashes(file_get_contents($_FILES['file_attach']['tmp_name'][0]));

    $sql = "INSERT INTO Admin (username, nome, cognome, chat_id, password, profile_pic)
    VALUES ('$username', '$nome', '$cognome', '$chat_id', '$password', '$image')";

    if ($conn->query($sql) === TRUE) {
      print json_encode(array('type'=>'done', 'text' => 'New account created!'));
    }
    else { print json_encode(array('type'=>'error', 'text' => 'Sign Up failed!')); }

}

$conn->close();
?>
