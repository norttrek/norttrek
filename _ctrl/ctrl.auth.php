<?php
date_default_timezone_set('America/Mexico_City');
session_start();
require_once('../../_class/class.client.php');
$obj = new Client();
$result = $obj->set_user($_POST['txt_user'])->set_password($_POST['txt_password'])->set_status(1)->set_limit(1)->auth_client_user();
if($result && ($result[0]['date_exp'] != '')){ 
  $today=strtotime(date("Y-m-d H:i:s"));
  $exp=strtotime($result[0]['date_exp']);
  if($today > $exp){ $result = false; }
}
if($result){ 
  $_SESSION['logged']['user'] = $result[0]['nombre'];
  $_SESSION['logged']['id_user'] = $result[0]['id'];
  $_SESSION['logged']['id_client'] = $result[0]['id_client'];
  $_SESSION['logged']['type'] = $result[0]['type'];
  header('Location: ../home.php');
}else{
  header('Location: ../index.php?s=401');
}
?>
