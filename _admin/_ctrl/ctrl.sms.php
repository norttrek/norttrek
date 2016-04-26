<?php
session_start();

$api_key ='16541359';
$api_secret = 'b5b1d20d';
$to = $_POST['to'];

switch($_POST['exec']) {
  case "reset": 
    $text = '*615011,990%23';
	$status = send_sms($to,$text);
	echo 202;
  break;
  case "wipe": 
    $text = '*615011,995%23';
	$status = send_sms($to,$text);
	echo 202;
  break;
  case "motor_cut":
  break;
  case "ignition_block":
  
  break;
}


function send_sms($to,$text){
  $url = 'http://rest.nexmo.com/sms/json?api_key=16541359&api_secret=b5b1d20d&from=Norttrek&to=521'.$to.'&text='.$text;
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL,$url);
  curl_setopt($ch, CURLOPT_FAILONERROR,1);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
  curl_setopt($ch, CURLOPT_TIMEOUT, 15);
  $retValue = curl_exec($ch);		
  curl_close($ch);
  if(isset($_GET['test'])){ print_r($retValue); }
  echo $url;
  return $retValue;
}

if(isset($_GET['test'])){ send_sms($_GET['to'],"SMS GATEWAY SENDER TEST SUCCESSFUL"); }
?>
