<?php
require_once("class.helper.php");

class Mail extends Helper {
  var $id;
  var $nombre;
  var $archivo;
  var $estatus;
  var $status;
  var $from = "Alertas - Norttrek";
  var $from_email = "alertas@norttrek.com";
  var $pass = "ntk#2015";
  var $admin_mail = "alertas@norttrek.com";
    
  public function __construct(){ $this->sql = new db(); }
  
  public function send($to,$cc,$subject,$message){
    echo "mail function";
  $headers  = 'MIME-Version: 1.0' . "\r\n";
  $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
  $headers .= 'From: '.$this->from.' <'.$this->from_email.'>' . "\r\n";
  if($cc!=NULL){ $headers .= 'Cc: '.$this->cc. "\r\n"; }
  if(mail($to, $subject, utf8_decode($message), $headers)){
    echo '[M] -> Sent Successful';
  }else{
    echo '[M] -> Sent Errorx ';
    echo $headers;
  }
  }
  

}
?>