<?php
include 'db_connection.php';
include 'bot_info.php';

if($_POST){

    $type = $_POST['type'];
    $message = $_POST['message'];

    $info = pathinfo($_FILES['file_attach']['name'][0]);
    $ext = $info['extension'];
    $target = '../img/file.'.$ext;
    move_uploaded_file($_FILES['file_attach']['tmp_name'][0], $target);
    $sended=1;

      if($type=="text") {
          $sql = "SELECT chat_id FROM Utenti";
          $rs = $conn->query($sql);

          while ($row = $rs->fetch_assoc()) {
            $result = file_get_contents($botUrl."sendmessage?chat_id=".$row["chat_id"]."&text=".urlencode($message));
            if(strlen($result)==0) $sended=0;
          }
      }

      else if($type=="image") {
        $img = curl_file_create('../img/file.'.$ext);

        $sql = "SELECT chat_id FROM Utenti";
        $rs = $conn->query($sql);

        $count=0;

        while ($row = $rs->fetch_assoc()) {

          if($count!=0) { //se è già stato inviato
              $result = file_get_contents($botUrl.'sendPhoto?chat_id='.$row["chat_id"].'&photo='.$image_id.'&caption='.urlencode($message));
              if(strlen($result)==0) $sended=0;
          }
          else { //invia per la prima volta ai server Telegram
            $target_url = $botUrl.'sendPhoto';
            $post = array(
                'chat_id'   => $row["chat_id"],
                'photo'  => $img,
                'caption'   => $message
            );

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL,$target_url);
            curl_setopt($ch, CURLOPT_POST,1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
            $result=curl_exec ($ch);
            if(strlen($result)==0) $sended=0;
            curl_close ($ch);

            $array = json_decode($result, true);
            $image_id = $array['result']['photo'][0]['file_id'];
          }

          $count++;
        }
      }

      else {
        $file = curl_file_create('../img/file.'.$ext);

        $sql = "SELECT chat_id FROM Utenti";
        $rs = $conn->query($sql);

        $count=0;

        while ($row = $rs->fetch_assoc()) {

          if($count!=0) {
            $result=file_get_contents($botUrl.'sendDocument?chat_id='.$row["chat_id"].'&document='.$document_id.'&caption='.urlencode($message));
            if(strlen($result)==0) $sended=0;
          }
          else {  //invia per la prima volta ai server Telegram
            $target_url = $botUrl.'sendDocument';
            $post = array(
                'chat_id'   => $row["chat_id"],
                'document'  => $file,
                'caption'   => $message
            );

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL,$target_url);
            curl_setopt($ch, CURLOPT_POST,1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
            $result=curl_exec ($ch);
            if(strlen($result)==0) $sended=0;
            curl_close ($ch);

            $array = json_decode($result, true);
            $document_id = $array['result']['document']['file_id'];
          }

          $count++;
        }
      }

      //controllo errori durante i trasferimenti
      if($sended!=0) print json_encode(array('type'=>'done', 'text' => 'News inviata correttamente!'));
      else { print json_encode(array('type'=>'error', 'text' => 'News non inviata correttamente!')); }
}

$conn->close();
?>
