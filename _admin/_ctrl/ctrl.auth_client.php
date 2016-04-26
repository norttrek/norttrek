<?php
session_start();
require_once('../../_class/class.client.php');
$obj = new Client();
if(!isset($_GET['idc'])){ die(); } 
$data = $obj->set_type(1)->getUsersClient($_GET['idc']);
$result = $obj->set_user($data[0]['user'])->set_password($data[0]['password'])->set_status(1)->set_limit(1)->auth_client_user();
if($result){ 
  $_SESSION['logged']['user'] = $result[0]['nombre'];
  $_SESSION['logged']['id_user'] = $result[0]['id'];
  $_SESSION['logged']['id_client'] = $result[0]['id_client'];
  $_SESSION['logged']['type'] = $result[0]['type'];
  $settings = json_decode($obj->getSettings($result[0]['id_client']),true);
  $temp = $settings["temp"];
  if($temp==NULL){ $temp = "c"; }
  $_SESSION['logged']['temp'] = $temp;
  header('Location: ../../clientes/home.php');
}
?>
