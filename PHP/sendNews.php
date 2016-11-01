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


    $chatId = "127080847";

      if($type=="text") {
          $text = urlencode($message);

          $result=file_get_contents($botUrl."sendmessage?chat_id=".$chatId."&text=".$text);
          //INS QUI INVIO A TUTTI
      }
      else if($type=="image") {
        $img = curl_file_create('../img/file.'.$ext);
        // FOTO
        $target_url = $botUrl.'sendPhoto';
        $post = array(
            'chat_id'   => $chatId,
            'photo'  => $img,
            'caption'   => $message
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$target_url);
        curl_setopt($ch, CURLOPT_POST,1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        $result=curl_exec ($ch);
        curl_close ($ch);

        $array = json_decode($result, true);
        $image_id = $array['result']['photo'][0]['file_id'];
        $text = urlencode($message);
        file_get_contents($botUrl.'sendPhoto?chat_id='.$chatId.'&photo='.$image_id.'&caption='.$text);

      }
      else {
        $file = curl_file_create('../img/file.'.$ext);
        //FILE
        $target_url = $botUrl.'sendDocument';
        $post = array(
            'chat_id'   => $chatId,
            'document'  => $file,
            'caption'   => $message
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$target_url);
        curl_setopt($ch, CURLOPT_POST,1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        $result=curl_exec ($ch);
        curl_close ($ch);

        $array = json_decode($result, true);
        $document_id = $array['result']['document']['file_id'];
        $text = urlencode($message);
        file_get_contents($botUrl.'sendDocument?chat_id='.$chatId.'&document='.$document_id.'&caption='.$text);
      }

      if(strlen($result)>0) print json_encode(array('type'=>'done', 'text' => 'News sended!'));
      else { print json_encode(array('type'=>'error', 'text' => 'News failed!')); }
}

$conn->close();
?>
