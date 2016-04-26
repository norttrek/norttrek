<?php
error_reporting(1);
session_start();
require_once('../../_class/class.user.php');
$obj = new User();
$result = $obj->set_user($_POST['txt_user'])->set_password($_POST['txt_password'])->set_status(1)->set_limit(1)->auth();
if($result){ 
  $_SESSION['onUserSession']['id_client'] = $result[0]['id_client'];
  $_SESSION['onUserSession']['id_user'] = $result[0]['id'];
  $_SESSION['onUserSession']['type'] = $result[0]['type'];
  $_SESSION['onUserSession']['permissions'] = json_decode($result[0]['permissions'],true);
  header('Location: ../index.php');
}else{
  header('Location: ../login.php?s=401');
}
?>
