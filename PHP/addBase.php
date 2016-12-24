<?php error_reporting( E_ALL );
include 'db_connection.php';
include 'bot_info.php';

session_start();
$user = $_SESSION['username'];
if(!isset($user)) header("Location: ../index.php");

if($_POST){

    $idBrano = $_POST['idBrano'];
    $type = $_POST['type'];

    $info = pathinfo($_FILES['file_attach']['name'][0]);
    $ext = $info['extension'];
    $target = '../Data/website_img/file.'.$ext;
    move_uploaded_file($_FILES['file_attach']['tmp_name'][0], $target);

    $sended=1;

    $file = curl_file_create('../Data/website_img/file.'.$ext);

    $sql = "SELECT chat_id FROM Utenti WHERE idUtente=1";
    $rs = $conn->query($sql);
    $row = $rs->fetch_assoc();

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
        curl_close ($ch);

        $array = json_decode($result, true);
        $audio_id = $array['result']['audio']['file_id'];
          if(strlen($audio_id)<1) $result = 0;
          else $result = 1;

        $sql = "INSERT INTO Basi (file_id, tipologia, Brani_idBrano) VALUES ('$audio_id', '$type', '$idBrano')";
        $db_result = $conn->query($sql);

      //controllo errori durante i trasferimenti
      if($result!=0 && $db_result!=0) print json_encode(array('type'=>'done', 'text' => 'Base inserita correttamente!'));
      else { print json_encode(array('type'=>'error', 'text' => 'Base non inserita correttamente!')); }
}

$conn->close();

?>
