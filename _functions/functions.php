<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once("../_class/class.gprs.php");
require_once("../_class/class.resize.php");
require_once("../_class/class.client.php");
require_once("./eventCode.status.php");
 $GPRS = new GPRS();
$Client = new Client();

function distanceCalculation($point1_lat, $point1_long, $point2_lat, $point2_long, $unit = 'km', $decimals = 2) {
	// Cálculo de la distancia en grados
	$degrees = rad2deg(acos((sin(deg2rad($point1_lat))*sin(deg2rad($point2_lat))) + (cos(deg2rad($point1_lat))*cos(deg2rad($point2_lat))*cos(deg2rad($point1_long-$point2_long)))));
 
	// Conversión de la distancia en grados a la unidad escogida (kilómetros, millas o millas naúticas)
	switch($unit) {
		case 'km':
			$distance = $degrees * 111.13384; // 1 grado = 111.13384 km, basándose en el diametro promedio de la Tierra (12.735 km)
			break;
		case 'mi':
			$distance = $degrees * 69.05482; // 1 grado = 69.05482 millas, basándose en el diametro promedio de la Tierra (7.913,1 millas)
			break;
		case 'nmi':
			$distance =  $degrees * 59.97662; // 1 grado = 59.97662 millas naúticas, basándose en el diametro promedio de la Tierra (6,876.3 millas naúticas)
	}
	return round($distance, $decimals);
}

if($_POST['action']=='setSpeed'){
 	
	$speed = $_POST['speed_n'];
	$imei = $_POST['imei_n'];
	$result = $GPRS->setSpeed($imei,$speed);
	 
	echo $result;
 
}

if($_POST['action']=='saveRoute'){
	$new_row = "<tr>";
 	 $imei = $_POST['imei'];
	 $points = $_POST['points'];
	 $track = array();
	  $image = $_POST['map'];
	 
	 $mtsDistance = 0;
	 //print_r($points);
	 $totalMts = 0;
	 foreach ($points as $key => $point) {
	 	
	 		$nextPoint = $key+1;
	  		
	 		

	 		$break = split(',', $point);
	 	      $lat = $break[0];
	 	      $lang = $break[1];

 
	 		
	 		$nextPoint = $key +1; 
	 		$break2 = split(',', $points[$nextPoint]);
              
              if($break2[0] ==""){
              	continue;
              }
              
	 	      $lat2 =  $break2[0]  ;
	 	      $lang2 = $break2[1];
	 	     // echo "calcular: " .$key . "con" . $nextPoint . "***";
	 		 
	 		$point1_lat =  $lat ;
	 		$point1_long = $lang;

	 		$point2_lat = $lat2;
	 		$point2_long = $lang2; 

	 		$distance = distanceCalculation($point1_lat, $point1_long, $point2_lat, $point2_long, $unit = 'km', $decimals = 8);
	 		$mtsDistance = $distance * 1000; 
	 	    $totalMts = $totalMts + $mtsDistance;
	 	 
	 		$p1 = array(0=>$lat.",".$lang,1=>$lat2.",".$lang2,3=>$mtsDistance);
	 		array_push($track, $p1);
	 	//distanceCalculation($point1_lat, $point1_long, $point2_lat, $point2_long, $unit = 'km', $decimals = 2)
	 }
	  
	  $track = json_encode($track); 
	  //echo $totalMts;
	  //print_r(json_decode($track));
	  $trackName = $_POST['trak_name'];
	  $tolerancia = $_POST['tolerancia'];
	  $client_id = $_POST['id'];
	  if($tracksaved = $GPRS->saveTrack($client_id,$track,$image,$totalMts,$trackName,$tolerancia)){
	  	$new_row .= "<td class='rowtrack'>";
	  	
	  	$new_row .= "<p>Nombre: ".$trackName.$tracksaved[0]['id']."f</p>";
	  	$new_row .= "<p>Tolerancia: ".$tolerancia."</p>";
	  	$new_row .= "<p>Distancia Total: ".$totalMts."</p>";
	  	//$new_row .= "<p class='setTrackonMap'>Ver</p>";
	  	$new_row .=" <img src='".$image."'></td><tr>";
	  	//PENDIENTE ID
	  	//$new_row.="<button  id='deltrack'>Eliminar</button>";
		$result['row'] = $new_row;

		echo json_encode($result);
	  }
 
}
  
if($_POST['action'] == 'del_track'){
	$id = $_POST['idetrack'];
	$result = $GPRS->deltrack($id);
	 
	echo $result;
}
if($_POST['action']=='setSpeedLimit'){
 	
	$speed = $_POST['speed_n'];
	$imei = $_POST['imei_n'];
	$result = $GPRS->setSpeedLimit($imei,$speed);
	 
	echo $result;
 
}

if($_POST['action']== 'setLastResetTime'){
	$imei = $_POST['imei_n'];
	$result = $GPRS->setLastResetTime($imei);
	 
	echo $result;
}
if($_POST['action']== 'sendCordsByEmail'){
	echo "funcionsphp";
	$imei = $_POST['imei_n'];

	$to = "alanisdg@gmail.com";
	$cc = "norttrek";
	$subject = "nortrel subject";
	$message = "prueba " ;

	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	$headers .= 'From: '.$cc.' <'.$cc.'>' . "\r\n";
	if($cc!=NULL){ $headers .= 'Cc: '.$cc. "\r\n"; }
	if(mail($to, $subject, utf8_decode($message), $headers)){
	  echo '[M] -> Sent Successful';
	}else{
		echo '[M] -> Sent Error';
	}

 
}
if($_POST['action']== 'update_asset'){
	echo "update -> ";
	 $imei = $_POST['imei'] ;
	 $elock = $_POST['elock'];
	 $temp1 = $_POST['temp1'];
	 $temp2 = $_POST['temp2'];
	 $engine = $_POST['engine'];
	 echo $formula = $_POST['formula'];
	 $marcha = $_POST['marcha'];

	 $POS1 = $_POST['pos1'];
	 $POS2 = $_POST['pos2'];
	 $POS3 = $_POST['pos3'];

	 $LT1 = $_POST['lt1'];
	 $LT2 = $_POST['lt2'];
	 $LT3 = $_POST['lt3'];

	 $DA1 = $_POST['da1'];
	 $DA2 = $_POST['da2'];
	 $DA3 = $_POST['da3'];

	 $LG1 = $_POST['lg1'];
	 $LG2 = $_POST['lg2'];
	 $LG3 = $_POST['lg3'];

	 $VAR1 = $_POST['var1'];
	 $VAR2 = $_POST['var2'];
	 $VAR3 = $_POST['var3'];

	 $VA1 = $_POST['va1'];
	 $VA2 = $_POST['va2'];
	 $VA3 = $_POST['va3'];

	 $AS1 = $_POST['as1'];
	 $AS2 = $_POST['as2'];
	 $AS3 = $_POST['as3'];

	 $VL1 = $_POST['vl1'];
	 $VL2 = $_POST['vl2'];
	 $VL3 = $_POST['vl3'];

 
	 echo $result = $GPRS->update_asset($imei,$elock,$temp1,$temp2,$engine,$marcha,$formula,$POS1,$POS2,$POS3,$LT1,$LT2,$LT3,$DA1,$DA2,$DA3,$LG1,$LG2,$LG3,$VAR1,$VAR2,$VAR3,$VA1,$VA2,$VA3,$AS1,$AS2,$AS3,$VL1,$VL2,$VL3); 
}

if($_POST['action']== 'insert_asset'){
	$imei = $_POST['imei_n'];
	 
	 
	echo 'se inserto';
}


if($_POST['action']== 'saveUnidad'){
	 

	 $imei = $_POST['imei'];
	$nombre_u = $_POST['nombre_u'];
	$odometro = $_POST['odometro'];
	$marca = $_POST['marca'];
	$modelo = $_POST['modelo'];
	$anio = $_POST['anio'];
	$color = $_POST['color'];
	$placas = $_POST['placas'];
	$pasajeros = $_POST['pasajeros'];
	$rendimiento = $_POST['rendimiento'];
	$ptara = $_POST['ptara'];
	$ejes = $_POST['ejes'];
	$chasis = $_POST['chasis'];
	$ccarga = $_POST['ccarga'];
	$carrastre = $_POST['carrastre'];
	$tcarga = $_POST['action'];
	if($_POST['active']=="false"){
		$active = 0;
	}else{
		$active = 1;
	} 
	 

	$uploads_dir =  '../clientes/_img/profile/';
	$tmp_name = $_FILES["imagen_u"]["tmp_name"];
	$name = $_FILES["imagen_u"]["name"];
	$newname =  normalizeString(  $name);
	 


	if (file_exists($uploads_dir.$newname && $newname !='')) {
	    echo "La imagen ya existe";
	} else {
	     move_uploaded_file($tmp_name, $uploads_dir.$newname);
	      $resizeObj = new resize( $uploads_dir.$newname);
	      $resizeObj -> resizeImage(100, 100, 'crop');
	       $resizeObj -> saveImage($uploads_dir.$newname, 1000);
	     echo $result = $GPRS->saveUnidad($imei,$nombre_u,$odometro,$marca,$modelo,$anio,$color,$placas,$pasajeros,$rendimiento,$ptara,$ejes,$chasis,$ccarga,$carrastre,$tcarga,$active,$newname);
	}
 
}



if($_POST['action']== 'saveEngine'){
	 

	$imei = $_POST['imei'];
	$rendimientokl = $_POST['rendimientokl'];
	$cilindros = $_POST['cilindros'];
	$transmision = $_POST['transmision'];
	$velocidades = $_POST['velocidades'];
	$diferencial = $_POST['diferencial'];
	$seriemotor = $_POST['seriemotor'];
	 
	echo $result = $GPRS->saveEngine($imei,$rendimientokl,$cilindros,$transmision,$velocidades,$diferencial,$seriemotor);
 
 
}
if($_POST['action']== 'saveTempAlarms'){
	 

	$imei = $_POST['imei'];
	$alarmtemp1a = $_POST['alarmtemp1-a'];
	$alarmtemp1b = $_POST['alarmtemp1-b'];
	$transmision = $_POST['transmision'];
	$velocidades = $_POST['velocidades'];
	$diferencial = $_POST['diferencial'];
	$seriemotor = $_POST['seriemotor'];
	 
	echo $result = $GPRS->saveTempAlarms($imei,$alarmtemp1a,$alarmtemp1b);
}

if($_POST['action']== 'getAlarms'){
	 

	$imei = $_POST['imei_n'];
	//echo $imei;
	 
	 
	 $result = $GPRS->getAlarms($imei);

	foreach ($result as $value) {
		$alarm = eventCodeStatus($value['v2_eventCode'],$value['v2_sos']);
 
	
			$date = split(' ', $value['date']);
			$fecha = $date[0];
			$hour = $date[1];
			echo "<tr>";
			echo "<td>".$alarm['icono']."</td>";
			echo "<td>".$alarm['nombre']."</td>";
			echo "<td>".$fecha. " " . $hour . "</td>"; 
			echo '<td><a href="javascript:objTrack.go_to('.$value['lat'].','.$value['lng'].')" >Ver</a></td>';
			echo "</tr>";
		 
		//echo "<td>".$alarm['alert']."</td>";
	}
}


if($_POST['action']== 'saveMecanic'){
	 

	$imei = $_POST['imei'];
	if($_POST['tecnomecanica']=="false"){
		$tecnomecanica = 0;
	}else{
		$tecnomecanica = 1;
	} 
	if($_POST['ambiental']=="false"){
		$ambiental = 0;
	}else{
		$ambiental = 1;
	} 
	if($_POST['neec']=="false"){
		$neec = 0;
	}else{
		$neec = 1;
	} 
	if($_POST['fisicomecanica']=="false"){
		$fisicomecanica = 0;
	}else{
		$fisicomecanica = 1;
	} 
	if($_POST['tpat']=="false"){
		$tpat = 0;
	}else{
		$tpat = 1;
	} 
	$seguro = $_POST['seguro'];
	$vencimiento = $_POST['vencimiento'];
	$poliza = $_POST['poliza'];
	$dof = $_POST['dof'];
	$usdot = $_POST['usdot'];
	$txdot = $_POST['txdot'];
	$fmDate = $_POST['fmDate'];
	$tcDate = $_POST['tcDate'];
	$vaDate = $_POST['vaDate'];
	$ctDate = $_POST['ctDate'];
	$neDate = $_POST['neDate']; 
 
	 
	echo $result = $GPRS->saveMecanic($imei,$tecnomecanica,$ambiental,$neec,$fisicomecanica,$tpat,$seguro,$vencimiento,$poliza,$dof,$fmDate,$tcDate,$vaDate,$ctDate,$neDate,$usdot,$txdot);
 
 
}

 function normalizeString ($str = '')
{
  $str = strip_tags($str); 
    $str = preg_replace('/[\r\n\t ]+/', ' ', $str);
    $str = preg_replace('/[\"\*\/\:\<\>\?\'\|]+/', ' ', $str);
    $str = strtolower($str);
    $str = html_entity_decode( $str, ENT_QUOTES, "utf-8" );
    $str = htmlentities($str, ENT_QUOTES, "utf-8");
    $str = preg_replace("/(&)([a-z])([a-z]+;)/i", '$2', $str);
    $str = str_replace(' ', '-', $str);
    $str = rawurlencode($str);
    $str = str_replace('%', '-', $str);
    return $str;
}


 
if($_POST['action']== 'update_general_info'){
	$sms = $_POST['sms'];
	$id = $_POST['id'];

 $result = $GPRS->update_general_info($sms,$id);
echo $result ;
 }
 
 if($_POST['action']== 'update_active_client'){
	$id = $_POST['id'];
	$onff = $_POST['onoff'];
	$result = $Client->update_active_client($id,$onff);
	echo $result ;
 }


 if($_POST['action']== 'updateAlarm'){
	$alarm = $_POST['alarm'];
	$imei = $_POST['imei'];
	$alarmVal  = $_POST['alarm_val']; 
	$result = $GPRS->update_alarm($imei,$alarm,$alarmVal);
	echo $result ;
 }
  

  if($_POST['action']== 'updateGeoAlarm'){
	$id_geo = $_POST['id_geo'];
	$pos = $_POST['pos'];
	$imei = $_POST['imei'];
	$active  = $_POST['active']; 
	$result = $GPRS->updateGeoAlarm($imei,$id_geo,$active,$pos);
	echo $result ;
 }
 
 if($_POST['action']== 'active_track'){
	$imei = $_POST['imei'];
	
	$idetrack = $_POST['idetrack'];
	$active = $_POST['active'];
 
	$result = $GPRS->activeGeoRoute($imei,$idetrack,$active);
	echo $result ;
 }


  if($_POST['action']== 'updateEmail'){
	$user_id = $_POST['user_id'];
	
	$email = $_POST['email']; 
 
	$result = $GPRS->updateEmail($user_id,$email);
	echo $result ;
 }