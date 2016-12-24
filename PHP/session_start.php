<?php
session_start();
$user = $_SESSION['username'];
if(!isset($user)) header("Location: ../index.php");
?>
