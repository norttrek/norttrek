<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once('../_firephp/FirePHP.class.php');
ob_start();
$mifirePHP = FirePHP::getInstance(true);

$mifirePHP->log('fire');

require_once("../../_class/class.gprs.php");
require_once("../../_class/class.asset.php");
require_once("../../_functions/functions_reports.php");

$id_client = $_SESSION['logged']['id_client'];
$imei = $_POST['imei'];
$sdate = $_POST['sdate'];
$edate = $_POST['edate'];

$objAsset = new Asset();
$objGPRS = new GPRS();

$result = NULL;



$geocode_is_blocked = false;

$asset = $objAsset->set_imei($imei)->getAsset(NULL);
$fuel_calib = json_decode($asset[0]['fuel'],true);
$t1 = $fuel_calib['t1'];
$t2 = $fuel_calib['t2'];
$t3 = $fuel_calib['t3'];

$mifirePHP->log($asset,'asset');

//CODIGO NUEVO
$fsensors = json_decode($asset[0]['sensor'],true);
$formulaTruck = $fsensors['formula'];

$fuel_calib = json_decode($asset[0]['fuel'],true);
$tank1 = $fuel_calib['t1'];
$tank2 = $fuel_calib['t2'];
$tank3 = $fuel_calib['t3'];
$mifirePHP->log($tank3,'tank');

if($formulaTruck==2){
  $fields_route = array('id','date','gps_speed','lat','lng','v2_fuel1','v2_fuel2','v2_fuel3','iostatus','v2_temp1','temp',"v2_eventCode");
}elseif ($formulaTruck==1) {
  $fields_route = array('id','date','gps_speed','lat','lng','v2_fuel1','v2_fuel2','v2_fuel3','iostatus','v2_temp1','temp',"v2_eventCode");
}

$route = $objGPRS->set_status("V")->set_between("'".$sdate."' AND '".$edate."'")->getAssetRouteOpt($imei,$fields_route);

$mifirePHP->log($route,'route');
$data = get_tank_fuel_report($asset, $route, $tank1, $tank2, $tank3, $imei,$formulaTruck);
$rpt = array_reverse($data);
$mifirePHP->log($rpt);
//CODIGO NUEVO


for($i=0;$i<count($route);$i++){
  $iostatus = $objGPRS->get_iostatus($route[$i]['iostatus']);
  
  //$asset = $objAsset->getAssetByIMEI($_POST['imei']); //rolc: se comenta porque ya se tiene la misma variale arriba
  $fsensors = json_decode($asset[0]['sensor'],true);
  
  $speed = 0;
  
  $fuel_a = substr($route[$i]['ada_v'],0,4)/100;
  $fuel_b = substr($route[$i]['ada_v'],4,4)/100;
  $fuel_c = substr($route[$i]['fuel'],0,4)/100;
  
  
  
  $temp = 0;
  $temp =  substr($route[$i]['temp'],0,4)/10;
  #FARENHEIT
  if($_SESSION['logged']['temp']=="f"){ $temp  = ($temp*1.8+32); }
  
  if($route[$i]['gps_speed']=='000'){ $speed = 0; }else{ $speed = intval($route[$i]['gps_speed']); }
  $result[$i]['imei'] = $route[$i]['imei'];
  $result[$i]['speed'] = $speed;
  
  
  // aad
  //$result[$i]['ignition'] = $iostatus['ignition'];
  $result[$i]['ignition'] = $route[$i]['v2_eng_status'];
  
  
  $result[$i]['ignition_cut'] = $iostatus['ignition_cut'];
  $result[$i]['ignition_blocked'] = $iostatus['ignition_blocked'];
  
  $result[$i]['datetime'] = $objGPRS->formatDateTime($route[$i]['date'],"min");
  $result[$i]['status'] = $objAsset->get_status($route[$i]['status']);
  $result[$i]['lat'] = number_format($route[$i]['lat'],5);
  $result[$i]['lng'] = number_format($route[$i]['lng'],5);
  $result[$i]['lat_lng'] = $result[$i]['lat'].','.$result[$i]['lng'];
  $result[$i]['geocoding'] = NULL;
  $result[$i]['tbl_route'] .= '<tr id="tr_route_'.($i).'" class="onMarkerHover">';
  $result[$i]['tbl_route'] .= '<td align="center">'.(count($route)-$i).'</td>';
  $result[$i]['tbl_route'] .= '<td>'.$result[$i]['datetime'].'</td>';
  $result[$i]['tbl_route'] .= '<td>'.$speed.'</td>';
  
  if(!isset($sensors['formula']) || $sensors['formula']==1){
    $result[$i]['tbl_route'] .= '<td>'.number_format($objGPRS->get_fuel_lt($fsensors['fuel_a_d'],$fsensors['fuel_a_l'],$fsensors['fuel_a_as'],$fsensors['fuel_a_v'],$fsensors['fuel_a_vl'],$fuel_a),2).'</td>';
    $result[$i]['tbl_route'] .= '<td>'.number_format($objGPRS->get_fuel_lt($fsensors['fuel_b_d'],$fsensors['fuel_b_l'],$fsensors['fuel_b_as'],$fsensors['fuel_b_v'],$fsensors['fuel_b_vl'],$fuel_b),2).'</td>';
    $result[$i]['tbl_route'] .= '<td>'.number_format($objGPRS->get_fuel_lt($fsensors['fuel_c_d'],$fsensors['fuel_c_l'],$fsensors['fuel_c_as'],$fsensors['fuel_c_v'],$fsensors['fuel_c_vl'],$fuel_c),2).'</td>';
  }else{
    $result[$i]['tbl_route'] .= '<td>'.$objGPRS->get_fuel_alt($fuel_a,$t1).'</td>';
    $result[$i]['tbl_route'] .= '<td>'.$objGPRS->get_fuel_alt($fuel_b,$t2).'</td>';
    $result[$i]['tbl_route'] .= '<td>'.$objGPRS->get_fuel_alt($fuel_c,$t3).'</td>';
  }
 
  $result[$i]['tbl_route'] .= '<td>'.number_format($temp,1).'</td>';
  $result[$i]['tbl_route'] .= '<td align="center"><a href="javascript:void(0)" rel="'.$result[$i]['lat_lng'].'" class="onLatLngOver">ver</a></td>';
  
  if($result[$i]['speed']==0 && $result[$i]['ignition']==1){ $color = '#ff5a00'; }
  if($result[$i]['speed'] > 0){ $color = '#0097ee'; }
  if($result[$i]['ignition']==0){ $color = '#bebebe'; }
  $result[$i]['tbl_route'] .= '<td><i class="fa fa-circle" style="color:'.$color.'"></i></td>';
  
  $result[$i]['tbl_route'] .= '</tr>';
  
}
detect($route, $asset);   //rolc: se pasa el valor de $asset





echo json_encode($rpt);

function detect($route, $asset=NULL){
  $objGPRS = new GPRS();
  $objAsset = new Asset();
  
  if($asset==NULL){ //rolc: solo si en null se obtiene el valor de $asset
    $asset = $objAsset->getAssetByIMEI($_POST['imei']);    
  }  
  $fsensors = json_decode($asset[0]['sensor'],true);
 
  
  for($i=0;$i<count($route);$i++){
    $fuel_a = substr($route[$i]['ada_v'],0,4)/100;
   $data[$i]['fecha'] = $objGPRS->formatDateTime($route[$i]['date'],"min");
   if($route[$i]['gps_speed']=='000'){ $data[$i]['km'] = 0; }else{ $data[$i]['km'] = intval($route[$i]['gps_speed']); }
   $data[$i]['lt'] = number_format($objGPRS->get_fuel_lt($fsensors['fuel_a_d'],$fsensors['fuel_a_l'],$fsensors['fuel_a_as'],$fsensors['fuel_a_v'],$fsensors['fuel_a_vl'],$fuel_a),2);
  } 
  
  for($i=0;$i<count($data);$i++){
    if($i<count($data)-2){ 
      $lts = ($data[$i]['lt']+$data[$i+1]['lt']+$data[$i+2]['lt'])/3; 
    $data[$i]['lts_prom'] = $lts;
    }else{
      $data[$i]['lts_prom'] = 0;
    }
  }

  $idx;
  $index = array();
  $index_values = array();
  $index_flag = false;
  $pos = array();
  $sum = 0;
  for($i=0;$i<count($data);$i++){
    $val = '';
    $guarda = 0;
    $data[$i]['tot'] = '';
    if($data[$i]['km']<5){ 
      if((abs($data[$i]['lts_prom']-$data[$i+1]['lts_prom']))>10){ 
      $val = $data[$i]['lt']-$data[$i+1]['lt']; 
      if($val!=0 && !$index_flag){
      $idx = $i; 
      $index_flag = true; 
      }
    } 
    $sum += $val;
    }else{
     $guarda = $sum;
     $sum = 0;
     $index_values = 0;
     if($index_flag){ 
       array_push($index,array($idx,$guarda)); 
       $index_flag = false; 
     }
    }
    if($val==0){ $val = ''; }
    $data[$i]['val'] = $val;
  }
  //for($i=0;$i<count($index);$i++){ $data[$index[$i][0]+1]['tot'] = $index[$i][1]; }
  //echo '<pre.';
 // print_r($index);
  //return $index;
}
 
function geocode($lat,$lng){
/*
  if($geocode_is_blocked){ return '-'; }
  $url = 'http://www.geocodefarm.com/api/reverse/json/d0d473c292b770987df520e30bbee06baf8d91de/'.$lat.'/'.$lng.'/';
  $curl = curl_init($url); 
  curl_setopt($curl, CURLOPT_FAILONERROR, true); 
  curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true); 
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, true); 
  curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false); 
  curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);   
  $result = curl_exec($curl); 
  $data = json_decode($result,true);
  if($data['geocoding_results']['STATUS']['access']=="OVER_QUERY_LIMIT"){ $geocode_is_blocked = true; }
  return $data['geocoding_results']['ADDRESS']['address'];
  */
}
?>