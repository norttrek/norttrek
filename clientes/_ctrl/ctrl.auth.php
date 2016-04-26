<?php
date_default_timezone_set('America/Mexico_City');
session_start();
require_once('../../_class/class.client.php');
$obj = new Client();
$result = $obj->set_user($_POST['txt_user'])->set_password($_POST['txt_password'])->set_status(1)->set_limit(1)->auth_client_user();
 
if($result==""){
 header('Location: ../index.php?s=401');
}
elseif($result[0]['activo']==0 && $result[0]['type']==1){
  
  header('Location: ../index.php?s=40');
}
elseif($result[0]['activo']==0 && $result[0]['type']==2){
   header('Location: ../index.php?s=41');
}
else{
  if($result && ($result[0]['date_exp'] != '0000-00-00 00:00:00')){ 
  $today=strtotime(date("Y-m-d H:i:s"));
  $exp=strtotime($result[0]['date_exp']);
  //echo "<br><br><br>today".$today."<br>"."expira".$exp."<br>";
  if($today > $exp ){ 
    $result = false; 
  }else{
    echo $result[0]['activo']."<--";
  }

}
if($result){ 
  $_SESSION['logged']['user'] = $result[0]['nombre'];
  $_SESSION['logged']['id_user'] = $result[0]['id'];
  $_SESSION['logged']['id_client'] = $result[0]['id_client'];
  $_SESSION['logged']['type'] = $result[0]['type'];
  $settings = json_decode($obj->getSettings($result[0]['id_client']),true);
  $temp = $settings["temp"];
  if($temp==NULL){ $temp = "c"; }
  $_SESSION['logged']['temp'] = $temp;
   header('Location: ../home.php');
}else{
   header('Location: ../index.php?s=401');
}
}
 
?>
