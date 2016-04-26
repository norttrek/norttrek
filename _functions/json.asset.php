<?php
date_default_timezone_set('America/Monterrey');
require_once("../../_class/class.asset.php");
require_once("../../_class/class.gprs.php");
require_once("../../_class/class.client.php");

$objGPRS = new GPRS();
$objClient = new Client();
$objAsset = new Asset();

$asset = $objAsset->getAssetByIMEI($_POST['id']);
$asset_info = json_decode($asset[0]['data'],true);
$gprs_data = $objGPRS->set_limit(1)->set_order("date DESC")->getGprsReport($_POST['id']);
$iostatus = $objGPRS->get_iostatus($gprs_data[0]['iostatus']);
$geocode = geocode($gprs_data[0]['lat'],$gprs_data[0]['lng']);
$direccion = explode(",",$geocode);


$speed = 0;
$motor = '';
if($gprs_data[0]['gps_speed']=='000'){ $speed = '0'; }else{ $speed = intval($gprs_data[0]['gps_speed']); }

// aad
$iostatus['ignition'] = $gprs_data[0]['v2_eng_status'];

if($iostatus['ignition']==1){ $motor = 'Encendido'; }else{ $motor = 'Apagado'; }
$wicon = get_weather($gprs_data[0]['lat'].','.$gprs_data[0]['lng']);

$buffer = '<div id="modal" class="small darkblue" style="width:350px;  font-size:12px; border-radius:6px;">
  <h1>'.$asset_info[1]['value'].'</h1>
  <div style="padding-left:10px; padding-right:10px; background:url(_weather/'.$wicon.') top right no-repeat">
  <p align="left" style="font-size:12px;">'.$objAsset->formatDateTime($gprs_data[0]['date'],"max").'</p>
  Motor: '.$motor.'<br>
  Evento: '.$objGPRS->get_status($gprs_data[0]['status']).'<br>
  '.$speed.' kms por hora.<br>
  <br>
  Calle: '.$direccion[0].'<br>
  Colonia: '.$direccion[1].'<br>
  Estado: '.$direccion[3].'<br><br>
  </div>
</div>
';


echo $buffer;


function geocode($lat,$lon){
  /*
   $details_url = "http://maps.googleapis.com/maps/api/geocode/json?latlng=".$lat.",".$lon."&sensor=false";
   $ch = curl_init();
   curl_setopt($ch, CURLOPT_URL, $details_url);
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
   $response = json_decode(curl_exec($ch), true);

   // If Status Code is ZERO_RESULTS, OVER_QUERY_LIMIT, REQUEST_DENIED or INVALID_REQUEST
   if ($response['status'] != 'OK') {
    return null;
   }


   $add = $response['results'][0]['formatted_address'];
   return $add;
   return 1;
   */
   $json_string = @file_get_contents("http://www.geocode.farm/v3/json/reverse/?key=605e596a-31b84556a748-8415e4e06d35&lat=".$lat."&lon=".$lon."&count=1");
   $parsed_json = json_decode($json_string,true);

   return $parsed_json['geocoding_results']['RESULTS'][0]['formatted_address'];
   
}

function get_weather($lat_lng){
  $json_string = file_get_contents("http://api.wunderground.com/api/7d81aebf18de759e/conditions/lang:SP/q/".$lat_lng.".json");
  $parsed_json = json_decode($json_string,true);
  $temp = str_replace("http://icons.wxug.com/i/c/k/","",$parsed_json['current_observation']['icon_url']);
  $temp = str_replace(".gif",".png",$temp);
  return $temp;
 }
?>
