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

if($_GET['lst_imei']!="*"){ 
  $imeis = $_GET['lst_imei']; 
   $buff_imei = $_GET['lst_imei'];
}else{
  $assets = $objClient->set_id_client($_SESSION['logged']['id_client'])->getClientImeis();
  $imeis = array();
  for($i=0;$i<count($assets);$i++){ array_push($imeis,$assets[$i]['imei']); }
  $buff_imei = implode(",",$imeis);
}
$geofences = $objClient->set_in(implode(",",$_GET['chk_geofence']))->getClientGeofence();
$gprs_data = $objGPRS->set_between("'".$_GET['date_from']." ".$_GET['hour_from']."' AND '".$_GET['date_to'].' '.$_GET['hour_to']."'")->set_order("date DESC")->set_in($buff_imei)->getGprsGeoFenceReport();

$geofence_rpt = NULL;
$geofence_rpt_c = 0;
$geofence_buff = '';
for($i=0;$i<count($geofences);$i++){
    $aux = json_decode($geofences[$i]['data'],true);
	$aux['radius'] = 20;
    for($k=0;$k<count($gprs_data);$k++){
	  $distance = getDistance($gprs_data[$k]['lat'],$gprs_data[$k]['lng'],$aux['lat'],$aux['lng']);
	  if($distance<=$aux['radius']){ 
	    switch($geofences[$i]['category']){
	      case "zs": $geofence_rpt[$geofence_rpt_c]['type'] = 'Zona Segura'; break;
	      case "zr": $geofence_rpt[$geofence_rpt_c]['type'] = 'Zona de Riesgo'; break;
	      case "base": $geofence_rpt[$geofence_rpt_c]['type'] = 'Base'; break;
	      case "cliente": $geofence_rpt[$geofence_rpt_c]['type'] = 'Clientes'; break;
	    }
		$name = $objAsset->getNameByImei($gprs_data[$k]['imei']);
		$geofence_rpt[$geofence_rpt_c]['imei'] = $name;
		$geofence_rpt[$geofence_rpt_c]['geofence'] = $aux['name'];
		$geofence_rpt[$geofence_rpt_c]['date'] = $objGPRS->formatDateTime($gprs_data[$k]['date'],"max");
		$geofence_rpt[$geofence_rpt_c]['distance'] = $distance;
		$geofence_buff .= '<tr><td>'.$geofence_rpt[$geofence_rpt_c]['imei'].'</td><td>'.$geofence_rpt[$geofence_rpt_c]['type'].'</td><td>'.$geofence_rpt[$geofence_rpt_c]['geofence'].'</td><td>'.$geofence_rpt[$geofence_rpt_c]['date'].'</td>';
		$geofence_rpt_c++;
	  }
	  
	}
  }
  
  
  
  

  function getDistance($latitude1, $longitude1, $latitude2, $longitude2) {  
    $earth_radius = 6371;  
      
    $dLat = deg2rad($latitude2 - $latitude1);  
    $dLon = deg2rad($longitude2 - $longitude1);  
      
    $a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * sin($dLon/2) * sin($dLon/2);  
    $c = 2 * asin(sqrt($a));  
    $d = $earth_radius * $c;  
      
    return $d;  
	
}  
?>
<?php
$points = NULL;
$file = NULL;
$file = 'rpt_geocercas.xls';
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$file.'"');
header('Cache-Control: max-age=0');
header('Cache-Control: max-age=1');
header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
header ('Pragma: public'); // HTTP/1.0

error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
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
            ->setCellValue('B1', 'Unidad')
            ->setCellValue('C1', 'Tipo')
			->setCellValue('D1', 'Geo-Cerca')
			->setCellValue('E1', 'Fecha');
			
			
$cont = 2;

for($i=0;$i<count($geofence_rpt);$i++){
  $type = "n/a";
  if(isset($geofence_rpt[$i]['type'])){ $type = $geofence_rpt[$i]['type']; }
  $objPHPExcel->setActiveSheetIndex(0)
	  		->setCellValue('A'.$cont, $cont-1)
            ->setCellValue('B'.$cont, $geofence_rpt[$i]['imei'])
            ->setCellValue('C'.$cont, $type)
			->setCellValue('D'.$cont, $geofence_rpt[$i]['geofence'])
			->setCellValue('E'.$cont, $geofence_rpt[$i]['date']);
  $cont++;
  
}



							 

$objPHPExcel->getActiveSheet()->setTitle('Reporte de Geo-Cercas');
$objPHPExcel->setActiveSheetIndex(0);

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');


exit;
?>