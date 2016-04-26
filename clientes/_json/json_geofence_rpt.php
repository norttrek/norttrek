<?php

session_start();
date_default_timezone_set('America/Monterrey');
require_once("../../_class/class.asset.php");
require_once("../../_class/class.gprs.php");
require_once("../../_class/class.client.php");

$objGPRS = new GPRS();
$objClient = new Client();
$objAsset = new Asset();
$imei = NULL;

if($_POST['lst_imei']!="*"){ 
  $imeis = $_POST['lst_imei']; 
   $buff_imei = $_POST['lst_imei'];
}else{
  $assets = $objClient->set_id_client($_SESSION['logged']['id_client'])->set_id_device('0')->getClientImeis();
  $imeis = array();
  for($i=0;$i<count($assets);$i++){ array_push($imeis,$assets[$i]['imei']); }
  $buff_imei = implode(",",$imeis);
}

$geofences = $objClient->set_in(implode(",",$_POST['chk_geofence']))->getClientGeofence();


$gprs_data = $objGPRS->set_between("'".$_POST['date_from']." ".$_POST['hour_from']."' AND '".$_POST['date_to'].' '.$_POST['hour_to']."'")->set_order("date DESC")->set_in($buff_imei)->getGprsGeoFenceReport();

$geofence_rpt = NULL;
$geofence_rpt_c = 0;
$geofence_buff = '';
for($i=0;$i<count($geofences);$i++){
    $aux = json_decode($geofences[$i]['data'],true);
	//$aux['radius'] = 20;
    for($k=0;$k<count($gprs_data);$k++){
	  $distance = getDistance($gprs_data[$k]['lat'],$gprs_data[$k]['lng'],$aux['lat'],$aux['lng']);
	  if($distance<=$aux['radius']){ 
	    switch($geofences[$i]['category']){
	      case "zs": $geofence_rpt[$geofence_rpt_c]['type'] = 'Zona Segura'; break;
	      case "zr": $geofence_rpt[$geofence_rpt_c]['type'] = 'Zona de Riesgo'; break;
	      case "base": $geofence_rpt[$geofence_rpt_c]['type'] = 'Base'; break;
	      case "cli": $geofence_rpt[$geofence_rpt_c]['type'] = 'Clientes'; break;
	    }
		$asset = $objAsset->getAssetByIMEI($gprs_data[$k]['imei']);
		$name = $objAsset->getNameByImei($gprs_data[$k]['imei']);
		$sensor = json_decode($asset[0]['sensor'],true);
		
		$fuel_calib = json_decode($asset[0]['fuel'],true);
		$speed = 0;
		
		$fuel_a = substr($gprs_data[$k]['ada_v'],0,4)/100;
 		$fuel_b = substr($gprs_data[$k]['ada_v'],4,4)/100;
		$fuel_c = substr($gprs_data[$k]['fuel'],0,4)/100;
		
		$fuel_calib = json_decode($asset[0]['fuel'],true);
		$t1 = $fuel_calib['t1'];
		$t2 = $fuel_calib['t2'];
		$t3 = $fuel_calib['t3'];
		
		if(!isset($sensors['formula']) || $sensors['formula']==1){
		  $fuel_a_lts  = number_format($objGPRS->get_fuel_lt($sensor['fuel_a_d'],$sensor['fuel_a_l'],$sensor['fuel_a_as'],$sensor['fuel_a_v'],$sensor['fuel_a_vl'],$fuel_a),2);
		  $fuel_b_lts  = number_format($objGPRS->get_fuel_lt($sensor['fuel_b_d'],$sensor['fuel_b_l'],$sensor['fuel_b_as'],$sensor['fuel_b_v'],$sensor['fuel_b_vl'],$fuel_b),2);
		  $fuel_c_lts  = number_format($objGPRS->get_fuel_lt($sensor['fuel_c_d'],$sensor['fuel_c_l'],$sensor['fuel_c_as'],$sensor['fuel_c_v'],$sensor['fuel_c_vl'],$fuel_c),2);
		}else{
		  $fuel_a_lts  = $objGPRS->get_fuel_alt($fuel_a,$t1);
		  $fuel_b_lts  = $objGPRS->get_fuel_alt($fuel_b,$t2);
		  $fuel_c_lts  = $objGPRS->get_fuel_alt($fuel_c,$t3);	
		}
		
		$temp = 0;
		$temp =  number_format((substr($gprs_data[$k]['temp'],3,5)/10),1);
		#FARENHEIT
		if($_SESSION['logged']['temp']=="f"){ $temp  = ($temp*1.8+32); }
		
		if($gprs_data[$k]['gps_speed']=='000'){ $speed = 0; }else{ $speed = intval($gprs_data[$k]['gps_speed']); }
		$geofence_rpt[$geofence_rpt_c]['imei'] = $name;
		$geofence_rpt[$geofence_rpt_c]['speed'] = $speed;
		$geofence_rpt[$geofence_rpt_c]['geofence'] = $aux['name'];
		$geofence_rpt[$geofence_rpt_c]['date'] = $objGPRS->formatDateTime($gprs_data[$k]['date'],"max");
		$geofence_rpt[$geofence_rpt_c]['distance'] = $distance;
		$geofence_buff .= '<tr><td>'.$geofence_rpt[$geofence_rpt_c]['imei'].'</td>
						       <td>'.$geofence_rpt[$geofence_rpt_c]['type'].'</td>
							   <td>'.$geofence_rpt[$geofence_rpt_c]['speed'].' km</td>
							   <td>'. $fuel_a_lts.'</td>
							   <td>'. $fuel_b_lts.'</td>
							   <td>'. $fuel_a_lts.'</td>
							   <td>'.$temp.' F&deg;</td>
x							   <td>'.$geofence_rpt[$geofence_rpt_c]['geofence'].'</td>
							   <td>'.$geofence_rpt[$geofence_rpt_c]['date'].'</td>';
		$geofence_rpt_c++;
	  }
	  
	}
  }
  echo $geofence_buff;
  
  

  function getDistance($latitude1, $longitude1, $latitude2, $longitude2) {  
    $earth_radius = 6371;  
      
    $dLat = deg2rad($latitude2 - $latitude1);  
    $dLon = deg2rad($longitude2 - $longitude1);  
      
    $a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * sin($dLon/2) * sin($dLon/2);  
    $c = 2 * asin(sqrt($a));  
    $d = $earth_radius * $c;  
      
    return $d;  
	
}  