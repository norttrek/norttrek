<?php
date_default_timezone_set('America/Monterrey');
require_once("../_class/class.client.php");
require_once("../_class/class.gprs.php");
$objGPRS = new GPRS();
$objClient = new Client();

$georoute = $objClient->getActiveGeoRoute($_GET['imei']);
if($georoute){
  $georoute_info = $objClient->getGeoRoute($georoute[0]['id_georoute']);
  $georoute_path = json_decode($georoute_info[0]['data']);
  $sdate = $georoute[0]['date_init'];
  $edate = $georoute[0]['date_end'];
  $imei = $georoute[0]['imei'];
  $unit_route = array_reverse($objGPRS->set_status("V")->set_between("'".$sdate."' AND '".$edate."'")->getAssetRoute($imei)); 
}


echo '<pre>';
print_r($georoute);
echo '<hr />';
//print_r($georoute_path);
echo '</pre>';
//
echo $georoute[0]['date_init'];
echo '<br/>';
echo date("Y-m-d H:i:s");
echo '<table width="100%" border="1">';
echo '<tr><td>Nombre</td><td>Distancia</td><td>Tiempo</td><td>rango</td><td>Coordenadas</td><td>Extra</td></tr>';
for($i=0;$i<count($georoute_path);$i++){
	
  $minutes_to_add = $georoute_path[$i][2];

  $time = new DateTime($georoute[0]['date_init']);
  $time->add(new DateInterval('PT' . $minutes_to_add . 'M'));
	
  echo '<tr>';
  echo '<td>'.$georoute_path[$i][0].'</td>';
  echo '<td>'.$georoute_path[$i][1].'</td>';
  echo '<td>'.$georoute_path[$i][2].'</td>';
  echo '<td>'.$time->format('Y-m-d H:i').'</td>';
  echo '<td>'.$georoute_path[$i][3].'</td>';
  echo '<td>';
  $aux = explode(",",$georoute_path[$i][3]);
  $cruzo = false;
  if(strtotime($time->format('Y-m-d H:i')) <=strtotime(date("Y-m-d H:i:s"))){
  for($k=0;$k<count($unit_route);$k++){
	
	$entiempo = false;
	$distance = distance($aux[0],$aux[1],$unit_route[$k]['lat'],$unit_route[$k]['lng']);
	//if($distance<=10 && strtotime($unit_route[$k]['date']) < strtotime($time->format('Y-m-d H:i'))){
	if($distance<=3){
	  $cruzo = true;
	  
	  if(strtotime($unit_route[$k]['date']) < strtotime($time->format('Y-m-d H:i'))){
	    $entiempo = true;
		echo $distance;
	    echo " (".$unit_route[$k]['date'].") - EN TIEMPO";
	    echo '<br/>';
	  }else{
		$entiempo = false;
		echo $distance;
	    echo " (".$unit_route[$k]['date'].") - FUERA DE TIEMPO";
	    echo '<br/>';
	  }
	  
      
	}
	
  } //k
  if(!$cruzo){ echo 'no ha llegado. (ALARMA!)'; }
  }else{
	  echo 'en espera';
  }
  
  echo '</td>';
  echo '</tr>';
}
echo '</table>';
echo '<pre>';
echo '<hr />';
print_r($unit_route);

$temp = $unit_route[32];

echo distance(20.27970,-99.91000,20.37260,-99.99130);


function distance($lat1, $lon1, $lat2, $lon2, $unit) {

 $theta = $lon1 - $lon2;
 $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
 $dist = acos($dist);
 $dist = rad2deg($dist);
 $miles = $dist * 60 * 1.1515;
 $unit = strtoupper($unit);

 if ($unit == "K") {
   return ($miles * 1.609344);
 } else if ($unit == "N") {
     return ($miles * 0.8684);
   } else {
       return $miles;
     }
}

//echo distance(32.9697, -96.80322, 29.46786, -98.53506, "M") . " Miles<br>";
//echo distance(32.9697, -96.80322, 29.46786, -98.53506, "K") . " Kilometers<br>";
//echo distance(32.9697, -96.80322, 29.46786, -98.53506, "N") . " Nautical Miles<br>";



?>