<?php
session_start();
require_once("../../_class/class.gprs.php");
require_once("../../_class/class.asset.php");
$objAsset = new Asset();
$objGPRS = new GPRS();

$imei = $_POST['imei'];
$sdate = $_POST['sdate'];
$edate = $_POST['edate'];

$asset = $objAsset->set_imei($imei)->getAsset(NULL);

 $fuel_calib = json_decode($asset[0]['fuel'],true);
 $t1 = $fuel_calib['t1'];
 $t2 = $fuel_calib['t2'];
 $t3 = $fuel_calib['t3'];

$gprs_data = array_reverse($objGPRS->set_order("date DESC")->set_between("'".$sdate."' AND '".$edate."'")->getGprsReport($imei)); 
$sensor = json_decode($asset[0]['sensor'],true);

$fuel_chart = '';
$fuel_buffer_time = '';
$fuel_buffer_value = '';

$chart = NULL;

$time_values = '';
$fuel_a_values = '';
$fuel_b_values =  '';
$fuel_c_values =  '';
$speed_values = '';
$temp_values = '';

for($i=0;$i<count($gprs_data);$i++){
  $speed = 0;
  if($gprs_data[$i]['gps_speed']=='000'){ $speed = '0'; }else{ $speed = intval($gprs_data[$i]['gps_speed']); }
  
  $fuel_a = substr($gprs_data[$i]['ada_v'],0,4)/100;
  $fuel_b = substr($gprs_data[$i]['ada_v'],4,8)/100;
  $fuel_c = substr($gprs_data[$i]['fuel'],0,4)/100;
  
  
  
  $chart[$i]['time'] = substr($gprs_data[$i]['date'],11,5);
  $time_values .= substr($gprs_data[$i]['date'],11,5).',';
  
  $chart[$i]['date'] = $gprs_data[$i]['date'];
  
  $chart[$i]['speed'] = $speed;
  $speed_values .= $speed.',';
 
   if(!isset($sensors['formula']) || $sensors['formula']==1){
     $chart[$i]['fuel_a'] = number_format($objGPRS->get_fuel_lt($sensor['fuel_a_d'],$sensor['fuel_a_l'],$sensor['fuel_a_as'],$sensor['fuel_a_v'],$sensor['fuel_a_vl'],$fuel_a),2);
	 $chart[$i]['fuel_b'] = number_format($objGPRS->get_fuel_lt($sensor['fuel_a_d'],$sensor['fuel_a_l'],$sensor['fuel_a_as'],$sensor['fuel_a_v'],$sensor['fuel_a_vl'],$fuel_b),2);
	 $chart[$i]['fuel_c'] = number_format($objGPRS->get_fuel_lt($sensor['fuel_c_d'],$sensor['fuel_c_l'],$sensor['fuel_c_as'],$sensor['fuel_c_v'],$sensor['fuel_c_vl'],$fuel_c),2);
   }else{
	 $chart[$i]['fuel_a'] = $objGPRS->get_fuel_alt($fuel_a,$t1);
	 $chart[$i]['fuel_b'] = $objGPRS->get_fuel_alt($fuel_b,$t2);
	 $chart[$i]['fuel_c'] = $objGPRS->get_fuel_alt($fuel_c,$t3);
   }
 
  $fuel_a_values .= $chart[$i]['fuel_a'].',';
  $fuel_b_values .= $chart[$i]['fuel_b'].',';  
  $fuel_c_values .= $chart[$i]['fuel_c'].',';  
  
  $chart[$i]['temp'] = substr($gprs_data[$i]['temp'],0,4)/10;
  $tunit = 'C&deg';
  if($_SESSION['logged']['temp']=="f"){
	$chart[$i]['temp']  = ($chart[$i]['temp']*1.8+32);
	$tunit = 'F&deg';
  }
   
  $temp_values .=  $chart[$i]['temp'].',';
  
}
$charts['fuel_a'] = substr_replace($fuel_a_values, "", -1); 
$charts['fuel_b'] = substr_replace($fuel_b_values, "", -1); 
$charts['fuel_c'] = substr_replace($fuel_c_values, "", -1); 

$charts['temp'] = $temp_values;
$charts['speed'] = $speed_values;
$charts['time'] = $time_values;
echo json_encode($charts);
?>