<?php

$conn = mysqli_connect("localhost", "root", "admin", "FSO_Database") or die("error");

if($_POST){

    $message = $_POST['message'];
    $image=addslashes(file_get_contents($_FILES['file_attach']['tmp_name'][0]));

    //$sql = "INSERT INTO Admin (username, nome, cognome, chat_id, password, profile_pic)
    //VALUES ('$username', '$nome', '$cognome', '$chat_id', '$password', '$image')";

    //if ($conn->query($sql) === TRUE) {
      print json_encode(array('type'=>'done', 'text' => 'News sended! '.$message));
    //}
    //else { print json_encode(array('type'=>'error', 'text' => 'News failed!')); }

}

$conn->close();
?>
