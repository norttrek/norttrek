<?php
session_start();
$points = NULL;
$file = NULL;
$file = 'rpt_trayectoria.xls';
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$file.'"');
header('Cache-Control: max-age=0');
header('Cache-Control: max-age=1');
header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header ('Pragma: public'); // HTTP/1.0

error_reporting(E_ALL);
ini_set('display_errors', FALSE);
ini_set('display_startup_errors', FALSE);
ini_set('max_execution_time', 5000);
date_default_timezone_set('America/Monterrey');

require_once '_class/PHPExcel.php';
$objPHPExcel = new PHPExcel();
$objPHPExcel->getProperties()->setCreator("Norttrek")
							 ->setLastModifiedBy("Norttrek")
							 ->setTitle("Office 2007 XLSX Test Document")
							 ->setSubject("Office 2007 XLSX Test Document")
							 ->setDescription("Norttrek Report")
							 ->setKeywords("office 2007 openxml php")
							 ->setCategory("Norttrek");



  $objPHPExcel->setActiveSheetIndex(0)
  			->setCellValue('A1', 'No')
            ->setCellValue('B1', 'Fecha')
            ->setCellValue('C1', 'Velocidad KM')
			->setCellValue('D1', 'Combustible I L')
			->setCellValue('E1', 'Combustible II L')
			->setCellValue('F1', 'Combustible III L')
			->setCellValue('G1', 'Temperatura C')
			->setCellValue('H1', 'Motor')
			->setCellValue('I1', 'Coordenadas')
			->setCellValue('J1', 'Ubicación');



require_once("../../_class/class.gprs.php");
require_once("../../_class/class.asset.php");

$id_client = $_SESSION['logged']['id_client'];
$imei = $_GET['imei'];
$sdate = $_GET['sdate'];
$edate = $_GET['edate'];

$objAsset = new Asset();
$objGPRS = new GPRS();
$asset = $objAsset->getAssetByIMEI($imei);
$fuel_calib = json_decode($asset[0]['fuel'],true);
$t1 = $fuel_calib['t1'];
$t2 = $fuel_calib['t2'];
$t3 = $fuel_calib['t3'];
$route_temp = $objGPRS->set_status("V")->set_between("'".$sdate."' AND '".$edate."'")->getAssetRoute($imei);
$route = array_reverse($route_temp);
$result = NULL;

$fsensors = json_decode($asset[0]['sensor'],true);

$geocode_is_blocked = false;
$cont = 2;
for($i=0;$i<count($route);$i++){
  $iostatus = $objGPRS->get_iostatus($route[$i]['iostatus']);

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

  $result[$i]['ignition'] = $iostatus['ignition'];
  $result[$i]['ignition'] = $route[$i]['v2_eng_status'];
  $result[$i]['ignition_cut'] = $iostatus['ignition_cut'];
  $result[$i]['ignition_blocked'] = $iostatus['ignition_blocked'];

  $result[$i]['datetime'] = $objGPRS->formatDateTime($route[$i]['date'],"min");
  $result[$i]['status'] = $objAsset->get_status($route[$i]['status']);
  $result[$i]['lat'] = number_format($route[$i]['lat'],5);
  $result[$i]['lng'] = number_format($route[$i]['lng'],5);
  $result[$i]['lat_lng'] = $result[$i]['lat'].','.$result[$i]['lng'];
  $result[$i]['geocoding'] = NULL;
  $motor = NULL;
	$address = '';

	if($result[$i]['ignition']==1){
		$motor = "Encendidos";
	}else{
		$motor = "Apagado";
	}

  $idx_points = 0;
  $is_duplicate = is_duplicate($result[$i]['lat'],$result[$i]['lng'],$points);

  if($is_duplicate>=0){
		$address = $points[$is_duplicate]['address'];
  }else{

		$geocoding = get_position($result[$i]['lat'],$result[$i]['lng']);
		$address = $geocoding['formatted_address'];

    $points[$idx_points]['lat'] = $result[$i]['lat'];
    $points[$idx_points]['lng'] = $result[$i]['lng'];
		$points[$idx_points]['address'] = $address;

		$idx_points++;

  }

  if(!isset($sensors['formula']) || $sensors['formula']==1){
  $objPHPExcel->setActiveSheetIndex(0)
	  		->setCellValue('A'.$cont, ($i+1))
            ->setCellValue('B'.$cont, $result[$i]['datetime'])
            ->setCellValue('C'.$cont, $speed)
            ->setCellValue('D'.$cont, number_format($objGPRS->get_fuel_lt($fsensors['fuel_a_d'],$fsensors['fuel_a_l'],$fsensors['fuel_a_as'],$fsensors['fuel_a_v'],$fsensors['fuel_a_vl'],$fuel_a),2))
            ->setCellValue('E'.$cont, number_format($objGPRS->get_fuel_lt($fsensors['fuel_b_d'],$fsensors['fuel_b_l'],$fsensors['fuel_b_as'],$fsensors['fuel_b_v'],$fsensors['fuel_b_vl'],$fuel_b),2))
            ->setCellValue('F'.$cont, number_format($objGPRS->get_fuel_lt($fsensors['fuel_c_d'],$fsensors['fuel_c_l'],$fsensors['fuel_c_as'],$fsensors['fuel_c_v'],$fsensors['fuel_c_vl'],$fuel_c),2))
            ->setCellValue('G'.$cont, $temp)
			->setCellValue('H'.$cont, $motor)
			->setCellValue('I'.$cont, $result[$i]['lat_lng'])
			->setCellValue('J'.$cont, $address);
  }else{
	  $objPHPExcel->setActiveSheetIndex(0)
	  		->setCellValue('A'.$cont, ($i+1))
            ->setCellValue('B'.$cont, $result[$i]['datetime'])
            ->setCellValue('C'.$cont, $speed)
            ->setCellValue('D'.$cont, $objGPRS->get_fuel_alt($fuel_a,$t1))
		    ->setCellValue('E'.$cont, $objGPRS->get_fuel_alt($fuel_b,$t2))
			->setCellValue('F'.$cont, $objGPRS->get_fuel_alt($fuel_c,$t3))
			->setCellValue('G'.$cont, $temp)
			->setCellValue('H'.$cont, $motor)
			->setCellValue('I'.$cont, $result[$i]['lat_lng'])
			->setCellValue('J'.$cont, $address);
  }
  $cont++;

}

$objPHPExcel->getActiveSheet()->setTitle('Trayectoria');
$objPHPExcel->setActiveSheetIndex(0);

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');

function get_position($lat,$lng){

	// https://www.geocode.farm/v3/json/reverse/?lat=45.2040305&lon=-93.3995728&country=us&lang=en&count=1
	// "http://www.geocodefarm.com/api/reverse/json/e609ccad34060a43a59a4351a8d28f1c2588c5e8/".$lat."/".$lng."/"
// v3 API Key: 605e596a-31b84556a748-8415e4e06d35
	$json_string = @file_get_contents("http://www.geocode.farm/v3/json/reverse/?key=605e596a-31b84556a748-8415e4e06d35&lat=".$lat."&lon=".$lng."&count=1");
  $parsed_json = json_decode($json_string,true);
	//print_r($parsed_json);
	return $parsed_json['geocoding_results']['RESULTS'][0];

	// aad oct 2015
	/*
	$url = "http://maps.googleapis.com/maps/api/geocode/json?latlng=".$lat.",".$lng."&sensor=true";

  $data = @file_get_contents($url);
  $jsondata = json_decode($data,true);
  if(is_array($jsondata) && $jsondata['status'] == "OK")
  {
        //$city = $jsondata['results']['0']['address_components']['2']['long_name'];
        //$country = $jsondata['results']['0']['address_components']['5']['long_name'];
        //$street = $jsondata['results']['0']['address_components']['1']['long_name'];
				return $jsondata['results'][0]['formatted_address'];
  }else {
		return print_r($jsondata);
	}
	*/

}

function is_duplicate($lat,$lng,$data){
  $idx = -1;
  for($i=0;$i<count($data);$i++){
    if(round($data[$i]['lat'],2) == round($lat,2) && round($data[$i]['lng'],2)==round($lng,2)){ $idx = $i; break; }
  }
  return $idx;
}

function is_near($lat,$lng,$data){
  $idx = -1;
  for($i=0;$i<count($data);$i++){
    if( (abs($data[$i]['lat']-$lat)>0.5) || (abs($data[$i]['lng']-$lng))>0.5){
			$idx = $i;
			break;
		}
  }
  return $idx;
}

exit;
?>
