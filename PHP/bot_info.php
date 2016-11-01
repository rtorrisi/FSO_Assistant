<?php
$file = file("../token.conf");
$token = explode("|", $file[0]);
$botUrl = "https://api.telegram.org/bot".$token[0]."/";
?>
