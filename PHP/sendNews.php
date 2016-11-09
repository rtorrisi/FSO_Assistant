<?php
include 'db_connection.php';
include 'bot_info.php';
session_start();

$user = $_SESSION['username'];
if(!isset($user)) header("Location: ../index.php");

$sql = mysqli_query($conn,"SELECT max(idNews) AS lastID FROM News");
$row=mysqli_fetch_array($sql);
$lastID = $row['lastID'];

$data = date('Y-m-d');

$sql = mysqli_query($conn,"SELECT username FROM Admin WHERE username='$user' ");
$row=mysqli_fetch_array($sql);
$login_username = $row['username'];

$keyboard = [ 'inline_keyboard' => [ [['text' =>  '⬅️', 'callback_data' => 'prev'],['text' =>  '➡️', 'callback_data' => 'next']],[['text' =>  'News letta 👍', 'callback_data' => 'news_received']] ] ];
$markup = json_encode($keyboard, true);


if($_POST){

    $type = $_POST['type'];
    $news = $_POST['message'];
    $newsID = $lastID+1;
    $message = "#news".$newsID."\n\n".$news;

    $file_to_db = addslashes(file_get_contents($_FILES['file_attach']['tmp_name'][0]));
    $info = pathinfo($_FILES['file_attach']['name'][0]);
    $ext = $info['extension'];
    $target = '../Data/website_img/file.'.$ext;
    move_uploaded_file($_FILES['file_attach']['tmp_name'][0], $target);
    $sended=1;

      if($type=="text") {
          $sql = "SELECT chat_id FROM Utenti WHERE idUtente=1";
          $rs = $conn->query($sql);

          while ($row = $rs->fetch_assoc()) {
            $result = file_get_contents($botUrl."sendmessage?chat_id=".$row["chat_id"]."&text=".urlencode($message)."&reply_markup=".$markup);
            if(strlen($result)==0) $sended=0;
          }

          $sql = "INSERT INTO News (news, estensione, data_news, Admin_username)
          VALUES ('$news', 'testo', '$data', '$login_username')";
          $conn->query($sql);
      }

      else if($type=="image") {
        $img = curl_file_create('../Data/website_img/file.'.$ext);

        $sql = "SELECT chat_id FROM Utenti WHERE idUtente=1";
        $rs = $conn->query($sql);

        $count=0;

        while ($row = $rs->fetch_assoc()) {

          if($count!=0) { //se è già stato inviato
              $result = file_get_contents($botUrl."sendPhoto?chat_id=".$row["chat_id"]."&photo=".$image_id);
              if(strlen($result)==0) $sended=0;
              $result = file_get_contents($botUrl."sendmessage?chat_id=".$row["chat_id"]."&text=".urlencode($message)."&reply_markup=".$markup);
              if(strlen($result)==0) $sended=0;
          }
          else { //invia per la prima volta ai server Telegram
            file_get_contents($botUrl."sendChatAction?chat_id=".$row["chat_id"]."&action=upload_photo");

            $target_url = $botUrl.'sendPhoto';
            $post = array(
                'chat_id'   => $row["chat_id"],
                'photo'  => $img
            );

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL,$target_url);
            curl_setopt($ch, CURLOPT_POST,1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
            $result=curl_exec ($ch);
            if(strlen($result)==0) $sended=0;
            curl_close ($ch);

            $result = file_get_contents($botUrl."sendmessage?chat_id=".$row["chat_id"]."&text=".urlencode($message)."&reply_markup=".$markup);
            if(strlen($result)==0) $sended=0;

            $array = json_decode($result, true);
            $image_id = $array['result']['photo'][0]['file_id'];
          }

          $count++;
        }

        $sql = "INSERT INTO News (news, allegato, estensione, data_news, Admin_username)
        VALUES ('$news', '$file_to_db', '$ext', '$data','$login_username')";
        $conn->query($sql);
      }

      else {
        $file = curl_file_create('../Data/website_img/file.'.$ext);

        $sql = "SELECT chat_id FROM Utenti WHERE idUtente=1";
        $rs = $conn->query($sql);

        $count=0;

        while ($row = $rs->fetch_assoc()) {

          if($count!=0) {
            $result=file_get_contents($botUrl.'sendDocument?chat_id='.$row["chat_id"].'&document='.$document_id);
            if(strlen($result)==0) $sended=0;
            $result = file_get_contents($botUrl."sendmessage?chat_id=".$row["chat_id"]."&text=".urlencode($message)."&reply_markup=".$keyboard);
            if(strlen($result)==0) $sended=0;
          }
          else {  //invia per la prima volta ai server Telegram
            file_get_contents($botUrl."sendChatAction?chat_id=".$row["chat_id"]."&action=upload_document");
            $target_url = $botUrl.'sendDocument';
            $post = array(
                'chat_id'   => $row["chat_id"],
                'document'  => $file
            );

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL,$target_url);
            curl_setopt($ch, CURLOPT_POST,1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
            $result=curl_exec ($ch);
            if(strlen($result)==0) $sended=0;
            curl_close ($ch);

            $result = file_get_contents($botUrl."sendmessage?chat_id=".$row["chat_id"]."&text=".urlencode($message)."&reply_markup=".$keyboard);
            if(strlen($result)==0) $sended=0;

            $array = json_decode($result, true);
            $document_id = $array['result']['document']['file_id'];
          }

          $count++;
        }

        $sql = "INSERT INTO News (news, allegato, estensione, data_news, Admin_username)
        VALUES ('$news', '$file_to_db', '$ext', '$data','$login_username')";
        $conn->query($sql);
      }

      //controllo errori durante i trasferimenti
      if($sended!=0) print json_encode(array('type'=>'done', 'text' => 'News inviata correttamente!'));
      else { print json_encode(array('type'=>'error', 'text' => 'News non inviata correttamente!')); }
}

$conn->close();

?>
