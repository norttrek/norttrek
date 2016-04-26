<?php
session_start();
require_once("../../_class/class.gprs.php");
$objGPRS = new GPRS();

$imei = $_POST['imei'];
$sdate = $_POST['sdate'];
$edate = $_POST['edate'];

$gprs_data = array_reverse($objGPRS->set_order("date DESC")->set_between("'".$sdate."' AND '".$edate."'")->getGprsReport($imei)); 


$fuel_chart = '';
$fuel_buffer_time = '';
$fuel_buffer_value = '';
for($i=0;$i<count($gprs_data);$i++){
  $speed = 0;
  
  if($gprs_data[$i]['gps_speed']=='000'){ $speed = '0'; }else{ $speed = intval($gprs_data[$i]['gps_speed']); }
  $fuel = substr($gprs_data[$i]['ada_v'],0,4)/1000;
  $fuel_chart[$i]['time'] = $gprs_data[$i]['date'];
  $fuel_chart[$i]['value'] = $fuel;	
  $fuel_buffer_time .= substr($fuel_chart[$i]['time'],11,5).",";
  $fuel_buffer_value .= (($fuel_chart[$i]['value']*100)/4).',';
}

$result = NULL;
$result['series'] = $fuel_buffer_time;
$result['values'] = $fuel_buffer_value;
echo json_encode($result);
?>