<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once("../../_class/class.gprs.php");
include ( "src/NexmoMessage.php" );
 $GPRS = new GPRS();
 function validateNumber($number = null){
 	if(strlen($number)== 10){
 		return true;
 	}else{
 		return false;
 	}

 }
if($_POST['action']=='sendCords'){
	$lat =  $_POST['lat'];
	$lang = $_POST['lang'];
	$cel = $_POST['num'];
	$imei_n = $_POST['imei'];
  $address = $_POST['address'];

	$nexmo_sms = new NexmoMessage('16541359', 'b5b1d20d');

	$mensaje = "Posicion Actual 2: " . $address .  " http://www.google.com/maps/place/".$lat.",".$lang."";
	
	$info = $nexmo_sms->sendText( '+521'.$cel, 'Norttrek', $mensaje );
	$status =  $nexmo_sms->displayOverview($info);
	if($status == "Your message was sent"){
		echo "actualizart";
	}else{
		echo $status . "el estatus";
	}
}
if($_POST['action']=='lockEngine'){
	
	 $imei = $_POST['imei_n'];
	$device = $GPRS->get_device($imei);
	 $device[0];
	$nexmo_sms = new NexmoMessage('16541359', 'b5b1d20d');
	switch($device[0]) {
          case "LMU-700": 
                $code ="#!R3,8,0";
          break;
          case "LMU-800": 
               $code ="#!R3,8,0";
          break;
          case "LMU-4200": 
                $code ="#!R3,8,0";
          break;
          case "LMU-1100": 
                $code ="#!R3,8,0";
          break;
          case "AT09": 
                $code ="*615011,016,A,1#";
          break;
      };
      echo $cel = $device[1];
      echo $code;
	 $info = $nexmo_sms->sendText( '+521'.$cel, 'Norttrek', $code );
	 echo $nexmo_sms->displayOverview($info); 
}
if($_POST['action']=='unlockEngine'){
	
	 $imei = $_POST['imei_n'];
	$device = $GPRS->get_device($imei);
	 $device[0];
	$nexmo_sms = new NexmoMessage('16541359', 'b5b1d20d');
	switch($device[0]) {
          case "LMU-700": 
                $code ="#!R3,9,0";
          break;
          case "LMU-800": 
               $code ="#!R3,9,0";
          break;
          case "LMU-4200": 
                $code ="#!R3,9,0";
          break;
          case "LMU-1100": 
                $code ="#!R3,9,0";
          break;
          case "AT09": 
                $code ="*615011,016,A,0#";
          break;
      };
      echo $cel = $device[1];
      echo $code;
	 $info = $nexmo_sms->sendText( '+521'.$cel, 'Norttrek', $code );
	 echo $nexmo_sms->displayOverview($info); 
}

if($_POST['action']=='bloquearMarcha'){
	
	 $imei = $_POST['imei_n'];
	$device = $GPRS->get_device($imei);
	 $device[0];
	 print_r($device);
	$nexmo_sms = new NexmoMessage('16541359', 'b5b1d20d');
	switch($device[0]) {
          case "LMU-700": 
                $code ="!R3,8,1";
          break;
          case "LMU-800": 
               $code ="!R3,8,1";
          break;
          case "LMU-4200": 
                $code ="!R3,8,1";
          break;
          case "LMU-1100": 
                $code = 'no';
          break;
          case "AT09": 
                $code ="*615011,016,B,1#";
          break;
      }; 
      echo $code;
      if($code == 'no'){
      	echo "no";
      	echo $code;
      	return "El Equipo no bloquea marcha";
      }else{
      	
      	echo $cel = $device[1];
      	echo "al " . $cel . " codigo " . $code;
      	if(validateNumber($cel) ==  true){
      		$info = $nexmo_sms->sendText( '+521'.$cel, 'Norttrek', $code );
	 echo $nexmo_sms->displayOverview($info);
	}else{
		echo "numero no valido";
	}
      		 
      }
}

if($_POST['action']=='desbloquearMarcha'){
	
	 $imei = $_POST['imei_n'];
	$device = $GPRS->get_device($imei);
	 $device[0];
	 
	$nexmo_sms = new NexmoMessage('16541359', 'b5b1d20d');
	switch($device[0]) {
          case "LMU-700": 
                $code ="!R3,9,1";
          break; 
          case "LMU-800": 
               $code ="!R3,9,1";
          break;
          case "LMU-4200": 
                $code ="!R3,9,1";
          break;
          case "LMU-1100": 
                $code = 'no';
          break;
          case "AT09": 
                $code ="*615011,016,B,0#";
          break;
      };
      echo $code;
      if($code == 'no'){
      	echo "nox";
      	echo $code;
      	return "El Equipo no desbloquea marcha";
      }else{ 
      	echo "w";
      	$cel = $device[1];
      		 $info = $nexmo_sms->sendText( '+521'.$cel, 'Norttrek', $code );
	 echo $nexmo_sms->displayOverview($info);
      }
}
if($_POST['action']=='closeElock'){
	
	 $imei = $_POST['imei_n'];
	$device = $GPRS->get_device($imei);
	 $device[0];
	 
	$nexmo_sms = new NexmoMessage('16541359', 'b5b1d20d');
	switch($device[0]) {
          case "LMU-700": 
                $code ="!R3,8,2";
          break; 
          case "LMU-800": 
               $code ="!R3,8,2";
          break;
          case "LMU-4200": 
                $code ="!R3,8,2";
          break;
          case "LMU-1100": 
                $code = 'no';
          break;
          case "AT09": 
                $code ="*615011,016,B,1#";
          break;
      };
      echo $code;
      if($code == 'no'){
      	echo "nox";
      	echo $code;
      	return "El Equipo no desbloquea Elock";
      }else{ 
      	echo "w";
      	$cel = $device[1];
      	if(validateNumber($cel) ==  true){
      		$info = $nexmo_sms->sendText( '+521'.$cel, 'Norttrek', $code );
	 echo $nexmo_sms->displayOverview($info);
	}else{
		echo "numero no valido";
	}
      		  
      }
}

if($_POST['action']=='openElock'){
	
	 $imei = $_POST['imei_n'];
	$device = $GPRS->get_device($imei);
	 $device[0];
	 
	$nexmo_sms = new NexmoMessage('16541359', 'b5b1d20d');
	switch($device[0]) {
          case "LMU-700": 
                $code ="!R3,9,2";
          break; 
          case "LMU-800": 
               $code ="!R3,9,2";
          break;
          case "LMU-4200": 
                $code ="!R3,9,2";
          break;
          case "LMU-1100": 
                $code = 'no';
          break;
          case "AT09": 
                $code ="*615011,016,C,0#";
          break;
      };
      echo $code;
      if($code == 'no'){
      	echo "nox";
      	echo $code;
      	return "El Equipo no abre Elock";
      }else{ 
      	echo "w";
      	$cel = $device[1];
      	if(validateNumber($cel) ==  true){
      		$info = $nexmo_sms->sendText( '+521'.$cel, 'Norttrek', $code );
	 echo $nexmo_sms->displayOverview($info);
	}else{
		echo "numero no valido";
	}
      		  
      }
}

if($_POST['action']=='activeAlarm'){
	
	 $imei = $_POST['imei_n'];
	 echo $speed = $_POST['speed'];
	 $speedAT09 = $_POST['speed'];
	 $speed = $speed / 0.036;
	 $speed = round($speed);
	 echo "ahora speed" . $speed;
	 $device = $GPRS->get_device($imei);
	 $device[0];
	 
	$nexmo_sms = new NexmoMessage('16541359', 'b5b1d20d');
	switch($device[0]) {
          case "LMU-700": 
                $code ="!R1,257,0,".$speed;
          break; 
          case "LMU-800": 
               $code ="!R1,257,0,".$speed;
          break;
          case "LMU-4200": 
                $code ="!R1,257,0,".$speed;
          break;
          case "LMU-1100": 
                $code = "!R1,257,0,".$speed;
          break;
          case "AT09": 
                $code ="*615011,104,0,".$speedAT09."#";
          break;
      };
      echo $code;
      if($code == 'no'){ 
      		return "El Equipo no abre Elock";
      	}else{ 
      	 	$cel = $device[1];
      		if(validateNumber($cel) ==  true){
      		  	$info = $nexmo_sms->sendText( '+521'.$cel, 'Norttrek', $code );
	       		$status =  $nexmo_sms->displayOverview($info);
				if($status == "Your message was sent"){
					 $device = $GPRS->ActiveSpeedAlarm($imei);
					}else{
						echo $status . "el estatus";
					}
			}else{
					echo "numero no valido";
				}
      		  
            } 
}

if($_POST['action']=='activeSpeedLimit'){
	
	 $imei = $_POST['imei_n'];
	 echo $speed = $_POST['speed'];
	 $speedAT09 = $_POST['speed'];
	 $speed = $speed / 0.036;
	 $speed = round($speed);
	 echo "ahora speed" . $speed;
	 $device = $GPRS->get_device($imei);
	 $device[0];
	 
	$nexmo_sms = new NexmoMessage('16541359', 'b5b1d20d');
	switch($device[0]) {
          case "LMU-700": 
                $code ="!R1,257,1,".$speed;
          break; 
          case "LMU-800": 
               $code ="!R1,257,1,".$speed;
          break;
          case "LMU-4200": 
                $code ="!R1,257,1,".$speed;
          break;
          case "LMU-1100": 
                $code = "!R1,257,1,".$speed;
          break;
          case "AT09": 
                $code ="no";
          break;
      };
      echo $code;
      if($code == 'no'){ 
      		return "El Equipo no abre Elock";
      	}else{ 
      	 	$cel = $device[1];
      		if(validateNumber($cel) ==  true){
      		  	$info = $nexmo_sms->sendText( '+521'.$cel, 'Norttrek', $code );
	       		$status =  $nexmo_sms->displayOverview($info);
				if($status == "Your message was sent"){
					echo "sent";
					 echo $device = $GPRS->ActiveSpeedLimit($imei);
					}else{
						echo $status . "el estatus";
					}
			}else{
					echo "numero no valido";
				}
      		  
            } 
}
if($_POST['action']=='disableSpeedAlarm'){
	
	 $imei = $_POST['imei_n'];
	
	 
	 $device = $GPRS->get_device($imei);
	 $device[0];
	 
	$nexmo_sms = new NexmoMessage('16541359', 'b5b1d20d');
	switch($device[0]) {
          case "LMU-700": 
                $code ="!R1,257,0,8500";
          break; 
          case "LMU-800": 
               $code ="!R1,257,0,8500";
          break;
          case "LMU-4200": 
                $code ="!R1,257,0,8500";
          break;
          case "LMU-1100": 
                $code = "!R1,257,0,8500";
          break;
          case "AT09": 
                $code ="*615011,104,1,300#";
          break;
      };
      echo $code;
      if($code == 'no'){
      	echo "nox";
      	echo $code;
      	return "El Equipo no abre Elock";
      }else{ 
      	echo "w";
      	$cel = $device[1];
      	if(validateNumber($cel) ==  true){
      		echo 'done: ' . $cel . $code;
      		$info = $nexmo_sms->sendText( '+521'.$cel, 'Norttrek', $code );
	        $status = $nexmo_sms->displayOverview($info);
	        if($status == "Your message was sent"){
					 $device = $GPRS->DisableSpeedAlarm($imei);
					}else{
						echo $status . "el estatus";
					}
	}else{
		echo "numero no valido";
	}
      		  
      } 
}

if($_POST['action']=='DisableSpeedLimit'){
	
	 $imei = $_POST['imei_n'];
	
	 
	 $device = $GPRS->get_device($imei);
	 $device[0];
	 
	$nexmo_sms = new NexmoMessage('16541359', 'b5b1d20d');
	switch($device[0]) {
          case "LMU-700": 
                $code ="!R1,257,1,8500";
          break; 
          case "LMU-800": 
               $code ="!R1,257,1,8500";
          break;
          case "LMU-4200": 
                $code ="!R1,257,1,8500";
          break;
          case "LMU-1100": 
                $code = "!R1,257,1,8500";
          break;
          case "AT09": 
                $code ="no";
          break;
      };
      echo $code;
      if($code == 'no'){
      	echo "nox";
      	echo $code;
      	return "El Equipo no abre Elock";
      }else{ 
      	echo "w";
      	$cel = $device[1];
      	if(validateNumber($cel) ==  true){
      		echo 'done: ' . $cel . $code;
      		$info = $nexmo_sms->sendText( '+521'.$cel, 'Norttrek', $code );
	        $status = $nexmo_sms->displayOverview($info);
	        if($status == "Your message was sent"){
					 $device = $GPRS->DisableSpeedLimit($imei);
					}else{
						echo $status . "el estatus";
					}
	}else{
		echo "numero no valido";
	}
      		  
      } 
}

if($_POST['action']=='setReportTime'){
  
   $imei = $_POST['imei_n'];
   $time = $_POST['time'];
   $time_in_minutes = $_POST['time'];
   $time = $time * 60;
   echo $time;
   $device = $GPRS->get_device($imei);
   $device[0];
   
  $nexmo_sms = new NexmoMessage('16541359', 'b5b1d20d');
  switch($device[0]) {
          case "LMU-700": 
                $code ="!R1,262,1,".$time;
          break; 
          case "LMU-800": 
               $code ="!R1,262,1,".$time;
          break;
          case "LMU-4200": 
                $code ="!R1,262,1,".$time;
          break;
          case "LMU-1100": 
                $code = "!R1,262,1,".$time;
          break;
          case "AT09": 
                $code ="*615011,102,".$time.",5000,70,900#";
          break;
      };
      echo $code;
      if($code == 'no'){
        echo "nox";
        echo $code;
        return "El Equipo no abre Elock";
      }else{ 
        echo "w";
        $cel = $device[1];
        if(validateNumber($cel) ==  true){
          echo 'done: ' . $cel . $code;
          $info = $nexmo_sms->sendText( '+521'.$cel, 'Norttrek', $code );
          $status = $nexmo_sms->displayOverview($info);
          if($status == "Your message was sent"){
           $device = $GPRS->setReportTime($imei,$time_in_minutes);
          }else{
            echo $status . "el estatus";
          }
  }else{
    echo "numero no valido";
  }
            
      } 
}

if($_POST['action']=='reset'){
  
   $imei = $_POST['imei_n']; 
   $device = $GPRS->get_device($imei);
   $device[0];
   
  $nexmo_sms = new NexmoMessage('16541359', 'b5b1d20d');
  switch($device[0]) {
          case "LMU-700": 
                $code ="!R3,70,0";
          break; 
          case "LMU-800": 
               $code ="!R3,70,0";
          break;
          case "LMU-4200": 
                $code ="!R3,70,0";
          break;
          case "LMU-1100": 
                $code = "!R3,70,0";
          break;
          case "AT09": 
                $code ="*615011,006#";
          break;
      };
      echo $code;
      if($code == 'no'){
        echo "nox";
        echo $code;
        return "El Equipo no abre Elock";
      }else{ 
 
        $cel = $device[1];
        if(validateNumber($cel) ==  true){
 
          $info = $nexmo_sms->sendText( '+521'.$cel, 'Norttrek', $code );
          $status = $nexmo_sms->displayOverview($info);
          if($status == "Your message was sent"){
            echo "ok!";
           $device = $GPRS->setReportTime($imei,$time_in_minutes);
          }else{
            echo $status . "el estatus";
          }
  }else{
    echo "numero no valido";
  }
            
      } 
}
?>