<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
<title>Norttrek - GPS</title>
<script src="//code.jquery.com/jquery-1.8.3.js"></script>
<script src="_lib/highcharts/highcharts.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>

</head>

<body>

<style>
 body { margin:0; font-family:Arial, Helvetica, sans-serif; font-size:12px; background-color:#eee; }
  #header { height:56px; background-color:#1b1e25; width:99%; margin:auto; border:#e4e4e4 solid 1 px; }
  #header .left { width:830px; height:56px;float:left; }
  #header .left .logo { margin-left:15px; margin-right:15px; }
  #header .left a.menu { display:block; color:#fff; margin-top:20px; padding-left:12px; padding-right:12px; font-weight:300; }
  #header .left a.mmenu { margin-top: 0; padding-top: 19px; height: 38px;  }
  #header .left a.menu:hover { color:#00ccff; }
  #header .left a.menu:hover i { color:#fff; }
  #header .left a.menu i { margin-right:5px; color:#fff;}
  #header .right { width:220px; height:56px;  float:right; }
  #header .right table tr td { height:56px; }
  #header .right a.menu { display:block; color:#fff; margin-top:20px; padding-left:18px; padding-right:18px; font-weight:300; }
  #header .right a.menu:hover { color:#00ccff; }
  #header .right a.menu:hover i { color:#fff; }
  #header .right a.menu i { margin-right:5px; color:#fff;}

  #layout { width:100%; margin:auto;}

   .clear { clear:both;}

.datagrid {  background-color:#fff; }
.datagrid table { }
.datagrid table thead tr th { padding:5px 5px; text-align:left; background-color:#f5f5f5; border-bottom:#ebebeb solid 1px; border-right:#f1f1f1 solid 1px; padding-right:0; }
.datagrid table thead tr th a { color:#717171; display:block;  }
.datagrid table thead tr th a.order {  background: url(_admin/_img/bck_sort.png) no-repeat right 4px; }
.datagrid table thead tr th a.order.asc { background: url(_admin/_img/bck_sort.png) no-repeat right 4px; }
.datagrid table thead tr th a.order.desc { background: url(_admin/_img/bck_sort.png) no-repeat right -22px; }

.datagrid table tbody tr:nth-child(even) { background-color:#f9f9f9; }
.datagrid table tbody tr:nth-child(odd) { background-color:#fff; }
.datagrid table tbody tr td { padding:2px 2px; border-top:#f1f1f1 solid 1px; color:#333;  }
.datagrid table tbody tr:hover { background-color:#9CF !important;}
.datagrid table tbody tr.active { background-color:#9CF !important;}

.sticky { position:fixed !important; top:0; }
.top { z-index:100000; }

.tabs { background-color:#eeeeee; }
.tabs ul { margin:0; padding:0; list-style:none; }
.tabs ul li { float:left; border-right:#d6d6d6 solid 1px; border-bottom:#e1e1e1 solid 1px;}
.tabs ul li.active { background-color:#fff; border-bottom:#fff solid 1px;}
.tabs ul li.active a { color:#4d4d4d; display: inline-block;  }
.tabs ul li a { display:inline-block; height:30px; text-align:center; text-decoration:none; padding-top:15px; color:#7f7f7f;  padding-left:45px; padding-right:45px;}
.tab_container { min-height:600px; }
.tab_container .tab { padding:15px; display:none; }
.tab_container .tab.active { display:block; }
.clear { clear:both;}

.datarpt table { height:100px; border:#e4e4e4 solid 1px; }
.datarpt table thead tr th { padding:6px 15px; text-align:left; background-color:#f5f5f5; border-bottom:#ebebeb solid 1px; border-right:#f1f1f1 solid 1px; padding-right:0; }
.datarpt table thead tr th a { color:#717171; display:block;  }
.datarpt table thead tr th a.order {  background: url(_admin/_img/bck_sort.png) no-repeat right 4px; }
.datarpt table thead tr th a.order.asc { background: url(_admin/_img/bck_sort.png) no-repeat right 4px; }
.datarpt table thead tr th a.order.desc { background: url(_admin/_img/bck_sort.png) no-repeat right -22px; }
.datarpt table tbody tr.top { background-color:#1b1e25 !important; }

.datarpt table tbody tr:nth-child(even) { background-color:#f9f9f9; }
.datarpt table tbody tr:nth-child(odd) { background-color:#fff; }
.datarpt table tbody tr.head { background-color:#ddd !important; }
.datarpt table tbody tr td { padding:3px 15px; border-top:#f1f1f1 solid 1px;  }
.datarpt table tbody tr:hover { background-color:#9CF !important;}
</style>
<div id="header">
<img class="logo" src="_img/logo.png" width="175" height="56" style="margin-left:10px;">
</div>

<?php
require_once("../_class/class.gprs.php");
require_once("../_class/class.asset.php");
$imei = $_GET['imei'];
$sdate = $_GET['sdate'];
$edate = $_GET['edate'];

$objAsset = new Asset();
$asset = $objAsset->getAssetByIMEI($imei);

$fuel_calib = json_decode($asset[0]['fuel'],true);

$tank1 = $fuel_calib['t1'];
$tank2 = $fuel_calib['t2'];
$tank3 = $fuel_calib['t3'];


$asset_cinfo = json_decode($asset[0]['info'],true);
$objGPRS = new GPRS();

$route = $objGPRS->set_status("V")->set_between("'".$sdate."' AND '".$edate."'")->getAssetRoute($imei);

$odometer = $asset_cinfo['odometro'];
$odometer += number_format($objAsset->getOdometer($imei,$sdate),2);

$t1 = get_t1($route,$imei,$tank1,$tank2,$tank3);
$rpt = array_reverse($t1);

$t2 = get_t2($route,$imei,$tank1,$tank2,$tank3);
$rpt_t2 = array_reverse($t2);

$t3 = get_t3($route,$imei,$tank1,$tank2,$tank3);
$rpt_t3 = array_reverse($t3);

$tt = get_total($route,$imei,$tank1,$tank2,$tank3);
$rpt_tt = array_reverse($tt);


$detected = array();
$ttemp;
$tfuel;



function get_t1($route,$imei,$tank1,$tank2,$tank3){
  $detected = NULL;
  $data;
  $objGPRS = new GPRS();
  $objAsset = new Asset();
  $asset = $objAsset->getAssetByIMEI($imei);
  $fsensors = json_decode($asset[0]['sensor'],true);

  for($i=0;$i<count($route);$i++){
    $data[$i]['date'] = $route[$i]['date'];
    if($route[$i]['gps_speed']=='000'){ $data[$i]['km'] = 0; }else{ $data[$i]['km'] = intval($route[$i]['gps_speed']); }

    $fuel_a = substr($route[$i]['ada_v'],0,4)/100;
    $data[$i]['t1'] = number_format($objGPRS->get_fuel_alt($fuel_a,$tank1),2);
	$data[$i]['lat_lng'] = $route[$i]['lat'].','.$route[$i]['lng'];
	$data[$i]['lat'] = $route[$i]['lat'];
	$data[$i]['lng'] = $route[$i]['lng'];
	$iostatus = $objGPRS->get_iostatus($route[$i]['iostatus']);
	$data[$i]['ignition'] = $iostatus['ignition'];
	$temp = 0;
	if($route[$i]['temp']!=0){
 	$temp = substr($route[$i]['temp'],0,4)/10;
  	  $tunit = 'C&deg';
	  if($_SESSION['logged']['temp']=="f"){
	    $temp  = ($temp*1.8+32);
	    $tunit = 'F&deg';
  	  }
	  $data[$i]['temp'] = number_format($temp,1);
	  $data[$i]['temp_unit'] = $tunit;
	}
	/* SINGLE ARRAYS */

	$ttemp[$i] = $data[$i]['temp'];



  }
  for($i=0;$i<count($data);$i++){
    if($i<count($route)-2){
      $lts = ($data[$i]['t1']+$data[$i+1]['t1']+$data[$i+2]['t1'])/3;
	  $data[$i]['t1_prom'] = $lts;
    }else{
      $data[$i]['t1_prom'] = 0;
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
      if((abs($data[$i]['t1_prom']-$data[$i+1]['t1_prom']))>10){ $val = $data[$i]['t1']-$data[$i+1]['t1']; }
    }
    if($val==0){ $val = 0; }
    $data[$i]['val'] = $val;
  }



  $acum_sum = 0;
  for($i=0;$i<count($data);$i++){
    $acum_sum = 0;
    if($data[$i]['km']<5 && abs($data[$i]['val'])>=5){
	  for($j=$i;$j<count($data);$j++){
	    $accum_idx = $i;
	    if($data[$j]['val']!=0){  $acum_sum += $data[$j]['val']; }
		if($data[$j]['val']==0){ break; }
		$i++;
	  }
	  $data[$i-1]['acum_lts'] = $acum_sum;
	  if($acum_sum > 0){ $color = '#1caa00'; }else{ $color = '#eb0000'; }
	  $data[$i-1]['acum_lts_frmt'] = '<span style="color:'.$color.'; font-weight: bold;">'.number_format($acum_sum,2).'</span>';
	  $acums_flag = true;

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
      if((abs($data[$i]['t1_prom']-$data[$i+1]['t1_prom']))>10){ $val = $data[$i]['t1_prom']-$data[$i+1]['t1_prom']; }
    }
    if($val==0){ $val = ''; }
    $data[$i]['c3'] = $val;
  }


  $acum_sum = 0;
  for($i=0;$i<count($data);$i++){
    $acum_sum = 0;
    if($data[$i]['km']<5 && abs($data[$i]['c3'])>=5){
	  for($j=$i;$j<count($data);$j++){
	    $accum_idx = $i;
	    if($data[$j]['c3']!=0){ $acum_sum += $data[$j]['c3']; }
		if($data[$j]['c3']==0){ break; }
		$i++;
	  }
	  $data[$i-1]['acum_lts_prom'] = $acum_sum;
	  if($acum_sum > 0){ $color = '#1caa00'; }else{ $color = '#eb0000'; }
	  $data[$i-1]['acum_lts_prom_frmt'] = '<span style="color:'.$color.'; font-weight: bold;">'.number_format($acum_sum,2).'</span>';
	  $acums_flag = true;

	 }
  }




  for($i=0;$i<count($data);$i++){ $data[$i]['c4'] = ($data[$i]['val']+$data[$i]['c3'])/2; }


  $acum_sum = 0;
   for($i=0;$i<count($data);$i++){
    $acum_sum = 0;
    if($data[$i]['km']<5 && abs($data[$i]['c4'])>=5){
	  for($j=$i;$j<count($data);$j++){
	    $accum_idx = $i;
	    if($data[$j]['c4']!=0){  $acum_sum += $data[$j]['c4']; }
		if($data[$j]['c4']==0){ break; }
		$i++;
	  }
	  $data[$i-1]['c5'] = $acum_sum;
	  if($acum_sum > 0){ $color = '#1caa00'; }else{ $color = '#eb0000'; }
	  $data[$i-1]['c5_frmt'] = '<span style="color:'.$color.'; font-weight: bold;">'.number_format($acum_sum,2).'</span>';
	  $acums_flag = true;

	 }
  }


  for($i=0;$i<count($data);$i++){
    if($data[$i]['val']!=0){
		$label = "Posible Recarga";

	  if($data[$i]['val']<0){ $label = "Posible Extraccion"; $data[$i]['descarga'] = number_format($data[$i]['acum_lts'],2);  }else{ $data[$i]['carga'] = number_format($data[$i]['acum_lts'],2);}
		$data[$i]['chart'] = '<p><strong>'.$label.'</strong> <br />Directa: '.number_format($data[$i]['acum_lts'],2).'<br />Suavizada: '.number_format($data[$i]['acum_lts_prom'],2).'<br />Promedio '.number_format($data[$i]['c5'],2).'<br />Tanque: '.number_format($data[$i]['t1'],2).' lts<br />Velocidad '.number_format($route[$i]['speed'],2).'<br />Fecha: '.$data[$i]['date'].'</p>'; 	}
	if($data[$i]['acum_lts']!=0){ $data[$i]['detected'] = 1; }
  }

  return $data;
}



function get_t2($route,$imei,$tank1,$tank2,$tank3){
  $detected = NULL;
  $data;
  $objGPRS = new GPRS();
  $objAsset = new Asset();
  $asset = $objAsset->getAssetByIMEI($imei);
  $fsensors = json_decode($asset[0]['sensor'],true);

  for($i=0;$i<count($route);$i++){
    $data[$i]['date'] = $route[$i]['date'];
    if($route[$i]['gps_speed']=='000'){ $data[$i]['km'] = 0; }else{ $data[$i]['km'] = intval($route[$i]['gps_speed']); }

    $fuel_a = substr($route[$i]['ada_v'],0,4)/100;
    $data[$i]['t1'] = number_format($objGPRS->get_fuel_alt($fuel_a,$tank1),2);
    $fuel_b = substr($route[$i]['ada_v'],4,4)/100;
    $data[$i]['t2'] = number_format($objGPRS->get_fuel_alt($fuel_b,$tank2),2);
    $fuel_c = substr($route[$i]['fuel'],0,4)/100;
    $data[$i]['t3'] = number_format($objGPRS->get_fuel_alt($fuel_c,$tank3),2);

  }
  for($i=0;$i<count($data);$i++){
    if($i<count($route)-2){
      $lts = ($data[$i]['t1']+$data[$i+1]['t1']+$data[$i+2]['t1'])/3;
	  $data[$i]['t1_prom'] = $lts;
	  $lts = 0;
	  $lts = ($data[$i]['t2']+$data[$i+1]['t2']+$data[$i+2]['t2'])/3;
	  $data[$i]['t2_prom'] = $lts;
	  $lts = 0;
	  $lts = ($data[$i]['t3']+$data[$i+1]['t3']+$data[$i+2]['t3'])/3;
	  $data[$i]['t3_prom'] = $lts;
    }else{
      $data[$i]['t1_prom'] = 0;
      $data[$i]['t2_prom'] = 0;
      $data[$i]['t3_prom'] = 0;
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
      if((abs($data[$i]['t2_prom']-$data[$i+1]['t2_prom']))>10){ $val = $data[$i]['t2']-$data[$i+1]['t2']; }
    }
    if($val==0){ $val = 0; }
    $data[$i]['val'] = $val;
  }

  $acum_sum = 0;
  for($i=0;$i<count($data);$i++){
    $acum_sum = 0;
    if($data[$i]['km']<5 && abs($data[$i]['val'])>=5){
	  for($j=$i;$j<count($data);$j++){
	    $accum_idx = $i;
	    if($data[$j]['val']!=0){  $acum_sum += $data[$j]['val']; }
		if($data[$j]['val']==0){ break; }
		$i++;
	  }
	  $data[$i-1]['acum_lts'] = $acum_sum;
	  if($acum_sum > 0){ $color = '#1caa00'; }else{ $color = '#eb0000'; }
	  $data[$i-1]['acum_lts_frmt'] = '<span style="color:'.$color.'; font-weight: bold;">'.number_format($acum_sum,2).'</span>';
	  $acums_flag = true;

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
      if((abs($data[$i]['t2_prom']-$data[$i+1]['t2_prom']))>10){ $val = $data[$i]['t2_prom']-$data[$i+1]['t2_prom']; }
    }
    if($val==0){ $val = ''; }
    $data[$i]['c3'] = $val;
  }


  $acum_sum = 0;
  for($i=0;$i<count($data);$i++){
    $acum_sum = 0;
    if($data[$i]['km']<5 && abs($data[$i]['c3'])>=5){
	  for($j=$i;$j<count($data);$j++){
	    $accum_idx = $i;
	    if($data[$j]['c3']!=0){ $acum_sum += $data[$j]['c3']; }
		if($data[$j]['c3']==0){ break; }
		$i++;
	  }
	  $data[$i-1]['acum_lts_prom'] = $acum_sum;
	  if($acum_sum > 0){ $color = '#1caa00'; }else{ $color = '#eb0000'; }
	  $data[$i-1]['acum_lts_prom_frmt'] = '<span style="color:'.$color.'; font-weight: bold;">'.number_format($acum_sum,2).'</span>';
	  $acums_flag = true;

	 }
  }



  for($i=0;$i<count($data);$i++){ $data[$i]['c4'] = ($data[$i]['val']+$data[$i]['c3'])/2; }

  $acum_sum = 0;
   for($i=0;$i<count($data);$i++){
    $acum_sum = 0;
    if($data[$i]['km']<5 && abs($data[$i]['c4'])>=5){
	  for($j=$i;$j<count($data);$j++){
	    $accum_idx = $i;
	    if($data[$j]['c4']!=0){  $acum_sum += $data[$j]['c4']; }
		if($data[$j]['c4']==0){ break; }
		$i++;
	  }
	  $data[$i-1]['c5'] = $acum_sum;
	  if($acum_sum > 0){ $color = '#1caa00'; }else{ $color = '#eb0000'; }
	  $data[$i-1]['c5_frmt'] = '<span style="color:'.$color.'; font-weight: bold;">'.number_format($acum_sum,2).'</span>';
	  $acums_flag = true;

	 }
  }


  for($i=0;$i<count($data);$i++){
    if($data[$i]['val']!=0){
	  $label = "Posible Recarga";
	  if($data[$i]['val']<0){ $label = "Posible Extraccion"; }
		$data[$i]['chart'] = '<p><strong>'.$label.'</strong> <br />Directa: '.number_format($data[$i]['acum_lts'],2).'<br />Suavizada: '.number_format($data[$i]['acum_lts_prom'],2).'<br />Promedio '.number_format($data[$i]['c5'],2).'<br />Tanque: '.number_format($data[$i]['t1'],2).' lts<br />Velocidad '.number_format($route[$i]['speed'],2).'<br />Fecha: '.$data[$i]['date'].'</p>'; 	}
  }

  for($i=0;$i<count($data);$i++){
    if($data[$i]['val']!=0){
	  $label = "Posible Recarga";
	  if($data[$i]['val']<0){ $label = "Posible Extraccion"; $data[$i]['descarga'] = number_format($data[$i]['acum_lts'],2);  }else{ $data[$i]['carga'] = number_format($data[$i]['acum_lts'],2);}
		$data[$i]['chart'] = '<p><strong>'.$label.'</strong> <br />Directa: '.number_format($data[$i]['acum_lts'],2).'<br />Suavizada: '.number_format($data[$i]['acum_lts_prom'],2).'<br />Promedio '.number_format($data[$i]['c5'],2).'<br />Tanque: '.number_format($data[$i]['t1'],2).' lts<br />Velocidad '.number_format($route[$i]['speed'],2).'<br />Fecha: '.$data[$i]['date'].'</p>'; 	}
	if($data[$i]['acum_lts']!=0){ $data[$i]['detected'] = 1; }
  }

  return $data;
}



function get_t3($route,$imei,$tank1,$tank2,$tank3){
  $detected = NULL;
  $data;
  $objGPRS = new GPRS();
  $objAsset = new Asset();
  $asset = $objAsset->getAssetByIMEI($imei);
  $fsensors = json_decode($asset[0]['sensor'],true);

  for($i=0;$i<count($route);$i++){
    $data[$i]['date'] = $route[$i]['date'];
    if($route[$i]['gps_speed']=='000'){ $data[$i]['km'] = 0; }else{ $data[$i]['km'] = intval($route[$i]['gps_speed']); }

    $fuel_a = substr($route[$i]['ada_v'],0,4)/100;
    $data[$i]['t1'] =  number_format($objGPRS->get_fuel_alt($fuel_a,$tank1),2);
    $fuel_b = substr($route[$i]['ada_v'],4,4)/100;
    $data[$i]['t2'] =  number_format($objGPRS->get_fuel_alt($fuel_b,$tank2),2);
    $fuel_c = substr($route[$i]['fuel'],0,4)/100;
    $data[$i]['t3'] =  number_format($objGPRS->get_fuel_alt($fuel_c,$tank3),2);

  }
  for($i=0;$i<count($data);$i++){
    if($i<count($route)-2){
      $lts = ($data[$i]['t1']+$data[$i+1]['t1']+$data[$i+2]['t1'])/3;
	  $data[$i]['t1_prom'] = $lts;
	  $lts = 0;
	  $lts = ($data[$i]['t2']+$data[$i+1]['t2']+$data[$i+2]['t2'])/3;
	  $data[$i]['t2_prom'] = $lts;
	  $lts = 0;
	  $lts = ($data[$i]['t3']+$data[$i+1]['t3']+$data[$i+2]['t3'])/3;
	  $data[$i]['t3_prom'] = $lts;
    }else{
      $data[$i]['t1_prom'] = 0;
      $data[$i]['t2_prom'] = 0;
      $data[$i]['t3_prom'] = 0;
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
      if((abs($data[$i]['t3_prom']-$data[$i+1]['t3_prom']))>10){ $val = $data[$i]['t3']-$data[$i+1]['t3']; }
    }
    if($val==0){ $val = 0; }
    $data[$i]['val'] = $val;
  }

  $acum_sum = 0;
  for($i=0;$i<count($data);$i++){
    $acum_sum = 0;
    if($data[$i]['km']<5 && abs($data[$i]['val'])>=5){
	  for($j=$i;$j<count($data);$j++){
	    $accum_idx = $i;
	    if($data[$j]['val']!=0){  $acum_sum += $data[$j]['val']; }
		if($data[$j]['val']==0){ break; }
		$i++;
	  }
	  $data[$i-1]['acum_lts'] = $acum_sum;
	  if($acum_sum > 0){ $color = '#1caa00'; }else{ $color = '#eb0000'; }
	  $data[$i-1]['acum_lts_frmt'] = '<span style="color:'.$color.'; font-weight: bold;">'.number_format($acum_sum,2).'</span>';
	  $acums_flag = true;

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
      if((abs($data[$i]['t3_prom']-$data[$i+1]['t3_prom']))>10){ $val = $data[$i]['t3_prom']-$data[$i+1]['t3_prom']; }
    }
    if($val==0){ $val = ''; }
    $data[$i]['c3'] = $val;
  }


 $acum_sum = 0;
  for($i=0;$i<count($data);$i++){
    $acum_sum = 0;
    if($data[$i]['km']<5 && abs($data[$i]['c3'])>=5){
	  for($j=$i;$j<count($data);$j++){
	    $accum_idx = $i;
	    if($data[$j]['c3']!=0){ $acum_sum += $data[$j]['c3']; }
		if($data[$j]['c3']==0){ break; }
		$i++;
	  }
	  $data[$i-1]['acum_lts_prom'] = $acum_sum;
	  if($acum_sum > 0){ $color = '#1caa00'; }else{ $color = '#eb0000'; }
	  $data[$i-1]['acum_lts_prom_frmt'] = '<span style="color:'.$color.'; font-weight: bold;">'.number_format($acum_sum,2).'</span>';
	  $acums_flag = true;

	 }
  }



  for($i=0;$i<count($data);$i++){ $data[$i]['c4'] = ($data[$i]['val']+$data[$i]['c3'])/2; }

   $acum_sum = 0;
   for($i=0;$i<count($data);$i++){
    $acum_sum = 0;
    if($data[$i]['km']<5 && abs($data[$i]['c4'])>=5){
	  for($j=$i;$j<count($data);$j++){
	    $accum_idx = $i;
	    if($data[$j]['c4']!=0){  $acum_sum += $data[$j]['c4']; }
		if($data[$j]['c4']==0){ break; }
		$i++;
	  }
	  $data[$i-1]['c5'] = $acum_sum;
	  if($acum_sum > 0){ $color = '#1caa00'; }else{ $color = '#eb0000'; }
	  $data[$i-1]['c5_frmt'] = '<span style="color:'.$color.'; font-weight: bold;">'.number_format($acum_sum,2).'</span>';
	  $acums_flag = true;

	 }
  }


  for($i=0;$i<count($data);$i++){
    if($data[$i]['val']!=0){
	  $label = "Posible Recarga";
	  if($data[$i]['val']<0){ $label = "Posible Extraccion"; }
		$data[$i]['chart'] = '<p><strong>'.$label.'</strong> <br />Directa: '.number_format($data[$i]['acum_lts'],2).'<br />Suavizada: '.number_format($data[$i]['acum_lts_prom'],2).'<br />Promedio '.number_format($data[$i]['c5'],2).'<br />Tanque: '.number_format($data[$i]['t1'],2).' lts<br />Velocidad '.number_format($route[$i]['speed'],2).'<br />Fecha: '.$data[$i]['date'].'</p>'; 	}

  }

  for($i=0;$i<count($data);$i++){
    if($data[$i]['val']!=0){
	  $label = "Posible Recarga";
	  if($data[$i]['val']<0){ $label = "Posible Extraccion"; $data[$i]['descarga'] = number_format($data[$i]['acum_lts'],2);  }else{ $data[$i]['carga'] = number_format($data[$i]['acum_lts'],2);}
		$data[$i]['chart'] = '<p><strong>'.$label.'</strong> <br />Directa: '.number_format($data[$i]['acum_lts'],2).'<br />Suavizada: '.number_format($data[$i]['acum_lts_prom'],2).'<br />Promedio '.number_format($data[$i]['c5'],2).'<br />Tanque: '.number_format($data[$i]['t1'],2).' lts<br />Velocidad '.number_format($route[$i]['speed'],2).'<br />Fecha: '.$data[$i]['date'].'</p>'; 	}
	if($data[$i]['acum_lts']!=0){ $data[$i]['detected'] = 1; }
  }
  return $data;
}




function get_total($route,$imei,$tank1,$tank2,$tank3){
  $detected = NULL;
  $data;
  $objGPRS = new GPRS();
  $objAsset = new Asset();
  $asset = $objAsset->getAssetByIMEI($imei);
  $fuel_calib = json_decode($asset[0]['fuel'],true);
  $t1 = $fuel_calib['t1'];
  $t2 = $fuel_calib['t2'];
  $t3 = $fuel_calib['t3'];

  $fsensors = json_decode($asset[0]['sensor'],true);

  for($i=0;$i<count($route);$i++){
    $data[$i]['date'] = $route[$i]['date'];
    if($route[$i]['gps_speed']=='000'){ $data[$i]['km'] = 0; }else{ $data[$i]['km'] = intval($route[$i]['gps_speed']); }
    $fuel_a = substr($route[$i]['ada_v'],0,4)/100;
    $data[$i]['t1'] = number_format($objGPRS->get_fuel_alt($fuel_a,$tank1),2);
    $fuel_b = substr($route[$i]['ada_v'],4,4)/100;
    $data[$i]['t2'] = number_format($objGPRS->get_fuel_alt($fuel_b,$tank2),2);
    $fuel_c = substr($route[$i]['fuel'],0,4)/100;
    $data[$i]['t3'] = number_format($objGPRS->get_fuel_alt($fuel_c,$tank3),2);
	//$data[$i]['t3'] = number_format($objGPRS->get_fuel_lt($fsensors['fuel_c_d'],$fsensors['fuel_c_l'],$fsensors['fuel_c_as'],$fsensors['fuel_c_v'],$fsensors['fuel_c_vl'],$fuel_c),2);
    //$data[$i]['t1'] = number_format($objGPRS->get_fuel_lt($fsensors['fuel_a_d'],$fsensors['fuel_a_l'],$fsensors['fuel_a_as'],$fsensors['fuel_a_v'],$fsensors['fuel_a_vl'],$fuel_a),2);
	//$data[$i]['t2'] = number_format($objGPRS->get_fuel_lt($fsensors['fuel_b_d'],$fsensors['fuel_b_l'],$fsensors['fuel_b_as'],$fsensors['fuel_b_v'],$fsensors['fuel_b_vl'],$fuel_b),2);
	//$data[$i]['t3'] = number_format($objGPRS->get_fuel_lt($fsensors['fuel_c_d'],$fsensors['fuel_c_l'],$fsensors['fuel_c_as'],$fsensors['fuel_c_v'],$fsensors['fuel_c_vl'],$fuel_c),2);
	$data[$i]['t1'] = $data[$i]['t1']+$data[$i]['t2']+$data[$i]['t3'];

	array_push($tfuel,$data[$i]['t1']);

  }
  for($i=0;$i<count($data);$i++){
    if($i<count($route)-2){
      $lts = ($data[$i]['t1']+$data[$i+1]['t1']+$data[$i+2]['t1'])/3;
	  $data[$i]['t1_prom'] = $lts;
    }else{
      $data[$i]['t1_prom'] = 0;
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
      if((abs($data[$i]['t1_prom']-$data[$i+1]['t1_prom']))>10){ $val = $data[$i]['t1']-$data[$i+1]['t1']; }
    }
    if($val==0){ $val = 0; }
    $data[$i]['val'] = $val;
  }



  $acum_sum = 0;
  for($i=0;$i<count($data);$i++){
    $acum_sum = 0;
    if($data[$i]['km']<5 && abs($data[$i]['val'])>=5){
	  for($j=$i;$j<count($data);$j++){
	    $accum_idx = $i;
	    if($data[$j]['val']!=0){  $acum_sum += $data[$j]['val']; }
		if($data[$j]['val']==0){ break; }
		$i++;
	  }
	  $data[$i-1]['acum_lts'] = $acum_sum;
	  if($acum_sum > 0){ $color = '#1caa00'; }else{ $color = '#eb0000'; }
	  $data[$i-1]['acum_lts_frmt'] = '<span style="color:'.$color.'; font-weight: bold;">'.number_format($acum_sum,2).'</span>';
	  $acums_flag = true;

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
      if((abs($data[$i]['t1_prom']-$data[$i+1]['t1_prom']))>10){ $val = $data[$i]['t1_prom']-$data[$i+1]['t1_prom']; }
    }
    if($val==0){ $val = ''; }
    $data[$i]['c3'] = $val;
  }


  $acum_sum = 0;
  for($i=0;$i<count($data);$i++){
    $acum_sum = 0;
    if($data[$i]['km']<5 && abs($data[$i]['c3'])>=5){
	  for($j=$i;$j<count($data);$j++){
	    $accum_idx = $i;
	    if($data[$j]['c3']!=0){ $acum_sum += $data[$j]['c3']; }
		if($data[$j]['c3']==0){ break; }
		$i++;
	  }
	  $data[$i-1]['acum_lts_prom'] = $acum_sum;
	  if($acum_sum > 0){ $color = '#1caa00'; }else{ $color = '#eb0000'; }
	  $data[$i-1]['acum_lts_prom_frmt'] = '<span style="color:'.$color.'; font-weight: bold;">'.number_format($acum_sum,2).'</span>';
	  $acums_flag = true;

	 }
  }




  for($i=0;$i<count($data);$i++){ $data[$i]['c4'] = ($data[$i]['val']+$data[$i]['c3'])/2; }


  $acum_sum = 0;
   for($i=0;$i<count($data);$i++){
    $acum_sum = 0;
    if($data[$i]['km']<5 && abs($data[$i]['c4'])>=5){
	  for($j=$i;$j<count($data);$j++){
	    $accum_idx = $i;
	    if($data[$j]['c4']!=0){  $acum_sum += $data[$j]['c4']; }
		if($data[$j]['c4']==0){ break; }
		$i++;
	  }
	  $data[$i-1]['c5'] = $acum_sum;
	  if($acum_sum > 0){ $color = '#1caa00'; }else{ $color = '#eb0000'; }
	  $data[$i-1]['c5_frmt'] = '<span style="color:'.$color.'; font-weight: bold;">'.number_format($acum_sum,2).'</span>';
	  $acums_flag = true;

	 }
  }


  for($i=0;$i<count($data);$i++){
    if($data[$i]['val']!=0){
		$label = "Posible Recarga";
	  if($data[$i]['val']<0){ $label = "Posible Extraccion"; }
		$data[$i]['chart'] = '<p><strong>'.$label.'</strong> <br />Directa: '.number_format($data[$i]['acum_lts'],2).'<br />Suavizada: '.number_format($data[$i]['acum_lts_prom'],2).'<br />Promedio '.number_format($data[$i]['c5'],2).'<br />Tanque: '.number_format($data[$i]['t1'],2).' lts<br />Velocidad '.number_format($route[$i]['speed'],2).'<br />Fecha: '.$data[$i]['date'].'</p>'; 	}
	if($data[$i]['acum_lts']!=0){ $data[$i]['detected'] = 1; }
  }

  return $data;
}



$buffer_fecha;
$buffer_vel;
$buffer_t1;
$buffer_t2;
$buffer_t3;



for($i=0;$i<count($rpt);$i++){

  $buffer_fecha .= "'".$rpt[$i]['date']."',";
  $buffer_vel .= "{ y: ".$rpt[$i]['km'].", custom : '".$rpt[$i]['km']." km/h' },";

  if($rpt[$i]['acum_lts']!=0){
	  $icon = 'up.png';
	  $custom = $rpt[$i]['chart'];
	  if($rpt[$i]['acum_lts']<0){
	    $icon = 'down.png';
	  }
	  $buffer_t1 .= "{ y: ".$rpt[$i]['t1'].", marker: { symbol: 'url(".$icon.")' }, url: 'http://maps.google.com/?q=".$rpt[$i]['lat'].",".$rpt[$i]['lng']."', custom : '".$custom."' },";
  }else{
	  $buffer_t1 .= "{ y: ".$rpt[$i]['t1'].", custom : '".$rpt[$i]['t1']." Lts' },";

  }

  if($rpt_t2[$i]['acum_lts']!=0){
	  $icon = 'up.png';
	  $custom = $rpt_t2[$i]['chart'];
	  if($rpt_t2[$i]['acum_lts']<0){
	    $icon = 'down.png';
	  }
	  $buffer_t2 .= "{ y: ".$rpt_t2[$i]['t2'].", marker: { symbol: 'url(".$icon.")' }, url: 'http://maps.google.com/?q=".$rpt[$i]['lat'].",".$rpt[$i]['lng']."', custom : '".$custom."' },";
  }else{
	  $buffer_t2 .= "{ y: ".$rpt_t2[$i]['t2'].", custom : '".$rpt_t2[$i]['t2']." Lts' },";

  }

  if($rpt_t3[$i]['acum_lts']!=0){
	  $icon = 'up.png';
	  $custom = $rpt_t3[$i]['chart'];
	  if($rpt_t3[$i]['acum_lts']<0){
	    $icon = 'down.png';
	  }
	  $buffer_t3 .= "{ y: ".$rpt_t3[$i]['t3'].", marker: { symbol: 'url(".$icon.")' }, url: 'http://maps.google.com/?q=".$rpt[$i]['lat'].",".$rpt[$i]['lng']."', custom : '".$custom."' },";
  }else{
	  $buffer_t3 .= "{ y: ".$rpt_t3[$i]['t3'].", custom : '".$rpt_t3[$i]['t3']." Lts' },";

  }


  if($rpt_tt[$i]['acum_lts']!=0){
	  $icon = 'up.png';
	  $custom = $rpt_tt[$i]['chart'];
	  if($rpt_tt[$i]['acum_lts']<0){
	    $icon = 'down.png';
	  }
	  $buffer_tt .= "{ y: ".$rpt_tt[$i]['t1'].", marker: { symbol: 'url(".$icon.")' }, url: 'http://maps.google.com/?q=".$rpt[$i]['lat'].",".$rpt[$i]['lng']."', custom : '".$custom."' },";
  }else{
	  $buffer_tt .= "{ y: ".$rpt_tt[$i]['t1'].", custom : '".$rpt_tt[$i]['t1']." Lts' },";

  }




}
$buffer_fecha = substr_replace($buffer_fecha ,"",-1);
$buffer_vel = substr_replace($buffer_vel ,"",-1);
$buffer_t1 = substr_replace($buffer_t1 ,"",-1);
$buffer_t2 = substr_replace($buffer_t2 ,"",-1);
$buffer_t3 = substr_replace($buffer_t3 ,"",-1);
$buffer_tt = substr_replace($buffer_tt ,"",-1);
?>
<style>
.container { width:99%; border:#e4e4e4 solid 1px; margin: auto; position:relative; }
.container .left { border:#e4e4e4 solid 1px; width:49.5%; min-height:500px; float:left; background-color:#fff; }
.container .right { border:#e4e4e4 solid 1px; width:49.5%; min-height:500px; float:right; position:absolute; right:0; }

</style>
<div class="chart">
<div id="holder" style="position:relative; overflow-x:scroll; width:99%; margin:auto; height:auto; border:#e4e4e4 solid 1px;"><div id="detail"></div></div>
<script>
var options;
var chart;

	doptions = {
		chart: {
		 width: <?php echo count($rpt)*15; ?>,
		  renderTo: 'detail',
		  type: 'spline'		},
        title: {
            text: '<?php echo $asset[0]['alias']; ?>',
            x: -20 //center
        },

        xAxis: {
		  categories: [<?php echo $buffer_fecha; ?>],
		  labels: { enabled : true, rotation: -90, style: { fontSize: '10px', fontFamily: 'Arial, sans-serif' } },
		  events: {
                    afterSetExtremes: function(event){
                        if(this.getExtremes().dataMin < event.min){
					    console.log(this);
						chart.xAxis[0].update({ labels: { enabled : true } });

						}else{ chart.xAxis[0].update({ labels: { enabled : false } });
						 }
                    }
                }
		},
        yAxis: { title: { text: '' },
            plotLines: [{
                value: 0,
                width: 1,
                color: '#808080'
            }] , labels: { style: { fontSize: '10px', fontFamily: 'Arial, sans-serif' } }
        },


        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle',
            borderWidth: 0
        },
		 tooltip : {
            formatter: function() {
                var tooltip;
                tooltip =  this.point.custom;
                return tooltip;
            }
        }
		,
		plotOptions: {
            series: {
                cursor: 'pointer',
                point: {
                    events: { click: function(){ if(this.options.url!=null){ window.open(this.options.url); } } }
                },

            }
        },series:
		[
		  { name: 'Velocidad', data: [<?php echo $buffer_vel; ?>], color: '#d3340b' },
		  { name: 'T1', data: [<?php echo $buffer_t1; ?>], color: '#63c000' },
		  { name: 'T2', data: [<?php echo $buffer_t2; ?>], color: '#ffab00' },
		  { name: 'T3',data: [<?php echo $buffer_t3; ?>], color: '#8612e9' },
		  { name: 'Total',data: [<?php echo $buffer_tt; ?>], color: '#20a8ff' }
		]
    };


$(function () {
 dchart = new Highcharts.Chart(doptions);

});

</script>
</div>
<div class="container">
  <div class="left">
    <div class="tabs">
      <ul>
        <li class="active"><a href="javascript:void(0)" class="onClickTab" rel="tab_1">Detalle de Recorrido</a></li>
        <li><a href="javascript:void(0)" class="onClickTab" rel="tab_2">Rendimiento de Unidad</a></li>
      </ul>
      <br class="clear" />
    </div>

    <div class="tab_container">
    <div id="tab_1" class="tab active datagrid">
          <?php
echo '
<table border="0" width="100%" align="center" cellpadding="0" cellspacing="0" >';
/*
echo '<thead>
 		<tr>
		<th width="400">Fecha</th>
		<th>Velocidad KM</th>

		<th>Combustible I L</th>
		<th>Promedio histórico de 3</th>
		<th>Litros extraidos sobre T1 Lectura real</th>
		<th>Acumulador de litros (lo que ve el cliente)</th>
		<th>Litros extraidos sobre el promedio histórico T1 de 3 (lecturas)</th>
		<th>Acumulador de litros (lo que ve el cliente)</th>
		<th>Promedio de los extraido</th>
		<th>Acumulador de litros (lo que ve el cliente)</th>


		<th>Combustible II L</th>
		<th>Promedio histórico de 3</th>
		<th>Litros extraidos sobre T2 Lectura real</th>
		<th>Acumulador de litros (lo que ve el cliente)</th>
		<th>Litros extraidos sobre el promedio histórico T2 de 3 (lecturas)</th>
		<th>Acumulador de litros (lo que ve el cliente)</th>
		<th>Promedio de los extraido</th>
		<th>Acumulador de litros (lo que ve el cliente)</th>


		<th>Combustible III L</th>
		<th>Promedio histórico de 3</th>
		<th>Litros extraidos sobre T3 Lectura real</th>
		<th>Acumulador de litros (lo que ve el cliente)</th>
		<th>Litros extraidos sobre el promedio histórico T3 de 3 (lecturas)</th>
		<th>Acumulador de litros (lo que ve el cliente)</th>
		<th>Promedio de los extraido</th>
		<th>Acumulador de litros (lo que ve el cliente)</th>

		<th>Temperatura C</th>
		<th>Motor</th>
		<th>Coordenadas</th>
		</tr>
		</thead>
		<tbody>';
*/

$tunit = 'C&deg';
if($_SESSION['logged']['temp']=="f"){ $tunit = 'F&deg'; }
$tspeed;
$distance =0;
$ton = 0;
$toff = 0;
$recarga = 0;
$descarga = 0;
$tt_raw;
$odo = $odometer;

echo '<thead>
 		<tr>
		<th>Fecha</th>
		<th>km/h</th>
		<th>Comb. Total</th>
		<th>Tanque I L</th>
		<th>Tanque II L</th>
		<th>Tanque III L</th>
		<th>Temp. '.$tunit.'</th>
		<th>Motor</th>
		<th>Odometro</th>
		<th>Ver</th>
		</tr>
		</thead>
		<tbody>';

for($i=0;$i<count($rpt);$i++){
  $iostatus = $objGPRS->get_iostatus($route[$i]['iostatus']);
  $route[$i]['ignition'] = $iostatus['ignition'];



  echo '<tr>';
  echo '<td>'.$rpt[$i]['date'].'</td>';
  echo '<td>'.$rpt[$i]['km'].'</td>';
  $tspeed[$i] = $rpt[$i]['km'];



  if($rpt_tt[$i]['detected']==1){
   if($rpt_tt[$i]['val'] > 0){ $color = '#59ad00'; }else{ $color = '#eb0000'; }
    echo '<td>
			<div style=" background-color:'.$color.'; color:#fff; max-width:55px; text-indent:10px; position:relative; cursor:pointer" align="left" class="onMover">
			<div style="position:absolute; display:none; width:170px; height:120px; border:#000 solid 1px;top:-10px;text-indent:0; padding-left:5px; background-color:#fff; color:#666; margin-left:55px;">'.$rpt_tt[$i]['chart'].'</div>
			'.$rpt_tt[$i]['t1'].'
			</div>
		  </td>';
   }else{
     echo '<td>'.$rpt_tt[$i]['t1'].'</td>';
  }

  $tt_raw[$i] = $rpt_tt[$i]['t1'];



  if($rpt[$i]['detected']==1){
   if($rpt[$i]['val'] > 0){ $color = '#59ad00'; }else{ $color = '#eb0000';}
    echo '<td>
			<div style=" background-color:'.$color.'; color:#fff; max-width:55px; text-indent:10px; position:relative; cursor:pointer" align="left" class="onMover">
			<div style="position:absolute; display:none; width:170px; height:120px; border:#000 solid 1px;top:-10px;text-indent:0; padding-left:5px; background-color:#fff; color:#666; margin-left:55px;">'.$rpt[$i]['chart'].'</div>
			'.$rpt[$i]['t1'].'
			</div>

		  </td>';
   }else{
     echo '<td>'.$rpt[$i]['t1'].'</td>';

  }

  //echo '<td>'.number_format($rpt[$i]['t1_prom'],2).'</td>';
  //echo '<td>'.$rpt[$i]['val'].' </td>';
  //echo '<td>'.$rpt[$i]['acum_lts_frmt'].'</td>';
  //echo '<td>'.number_format($rpt[$i]['c3'],2).'</td>';
  //echo '<td>'.$rpt[$i]['acum_lts_prom_frmt'].'</td>';
  //echo '<td>'.number_format($rpt[$i]['c4'],2).'</td>';
  //echo '<td>'.$rpt[$i]['c5_frmt'].'</td>';


  if($rpt_t2[$i]['detected']==1){
    if($rpt_t2[$i]['val'] > 0){ $color = '#59ad00';  }else{ $color = '#eb0000';  }
	echo '<td>
    <div style=" background-color:'.$color.'; color:#fff; max-width:55px; text-indent:10px; position:relative; cursor:pointer" align="left" class="onMover">
			<div style="position:absolute; display:none; width:170px; height:120px; border:#000 solid 1px;top:-10px;text-indent:0; padding-left:5px; background-color:#fff; color:#666; margin-left:55px;">'.$rpt_t2[$i]['chart'].'</div>
			'.$rpt_t2[$i]['t2'].'
			</div>
	</td>'; }
	else{
     echo '<td>'.$rpt_t2[$i]['t2'].'</td>';

  }
  //echo '<td>'.number_format($rpt_t2[$i]['t2_prom'],2).'</td>';
  //echo '<td>'.$rpt_t2[$i]['val'].'</td>';
  //echo '<td>'.$rpt_t2[$i]['acum_lts_frmt'].'</td>';
  //echo '<td>'.number_format($rpt_t2[$i]['c3'],2).'</td>';
  //echo '<td>'.$rpt_t2[$i]['acum_lts_prom_frmt'].'</td>';
  //echo '<td>'.number_format($rpt_t2[$i]['c4'],2).'</td>';
  //echo '<td>'.$rpt_t2[$i]['c5_frmt'].'</td>';

  if($rpt_t3[$i]['detected']==1){
   if($rpt_t3[$i]['val'] > 0){ $color = '#59ad00'; }else{ $color = '#eb0000'; }
   echo '<td>
  <div style=" background-color:'.$color.'; color:#fff; max-width:55px; text-indent:10px; position:relative; cursor:pointer" align="left" class="onMover">
			<div style="position:absolute; display:none; width:170px; height:120px; border:#000 solid 1px;top:-10px;text-indent:0; padding-left:5px; background-color:#fff; color:#666; margin-left:55px;">'.$rpt_t3[$i]['chart'].'</div>
			'.$rpt_t3[$i]['t3'].'
			</div></td>'; }
			else{
     echo '<td>'.$rpt_t3[$i]['t3'].'</td>';

  }
  //echo '<td>'.$route[$i]['t3'].'</td>';
  //echo '<td>'.number_format($rpt_t3[$i]['t3_prom'],2).'</td>';
  //echo '<td>'.$rpt_t3[$i]['val'].'</td>';
  //echo '<td>'.$rpt_t3[$i]['acum_lts_frmt'].'</td>';
  //echo '<td>'.number_format($rpt_t3[$i]['c3'],2).'</td>';
  //echo '<td>'.$rpt_t3[$i]['acum_lts_prom_frmt'].'</td>';
  //echo '<td>'.number_format($rpt_t3[$i]['c4'],2).'</td>';
  //echo '<td>'.$rpt_t3[$i]['c5_frmt'].'</td>';



  echo '<td>'.$rpt[$i]["temp"].'</td>';
   $ttemp[$i] = $rpt[$i]['temp'];



  if($rpt[$i]['ignition']==0){
    echo '<td>Apagado</td>';

  }else{
	echo '<td>Encendido</td>';

  }
  $distance  += getDistance($rpt[$i]['lat'],$rpt[$i]['lng'],$rpt[$i+1]['lat'],$rpt[$i+1]['lng']);
  $odo += getDistance($rpt[$i]['lat'],$rpt[$i]['lng'],$rpt[$i+1]['lat'],$rpt[$i+1]['lng']);
  echo '<td>'.number_format(($odo),2).'</td>';

  $direction = getCompassDirection($bearing);
  if($rpt[$i]['ignition']==1 && $rpt[$i]['km']>0){ $icon = "0_".$direction.".png"; }
  if($rpt[$i]['ignition']==1 && $rpt[$i]['km']<=0){ $icon = "1_".$direction.".png"; }
  if($rpt[$i]['ignition']==0){ $icon = "2_".$direction.".png"; }
  $rpt[$i]['direction'] = $icon;

  echo '<td><a href="javascript:void(0)" rel="'.$rpt[$i]['lat_lng'].'" class="onLatLngClick" id="'.$icon.'">Ver</a></td>';
  if($i<count($rpt)-1){
	  if($rpt[$i]['ignition']==0){
		$toff += getMinutes($rpt[$i]['date'],$rpt[$i+1]['date']);
	  }else{
		  $ton += getMinutes($rpt[$i]['date'],$rpt[$i+1]['date']);
	  }

  $bearing = (rad2deg(atan2(sin(deg2rad($rpt[$i+1]['lng']) - deg2rad($rpt[$i]['lng'])) * cos(deg2rad($rpt[$i+1]['lat'])), cos(deg2rad($rpt[$i]['lat'])) * sin(deg2rad($rpt[$i+1]['lat'])) - sin(deg2rad($rpt[$i]['lat'])) * cos(deg2rad($rpt[$i+1]['lat'])) * cos(deg2rad($rpt[$i+1]['lng']) - deg2rad($rpt[$i]['lng'])))) + 360) % 360;

  }

  echo '</tr>';

  $recarga += $rpt[$i]["carga"] +  $rpt_t2[$i]["carga"] +  $rpt_t3[$i]["carga"];
  $descarga += $rpt[$i]["descarga"] +  $rpt_t2[$i]["descarga"] +  $rpt_t3[$i]["descarga"];
}
echo '</tbody>';
echo '</table>';


function getMinutes($t1,$t2){
  $to_time = strtotime($t1);
  $from_time = strtotime($t2);
  return round(abs($to_time - $from_time) / 60,2);

}


function getCompassDirection($bearing) {
     $tmp = round($bearing / 22.5);
     switch($tmp) {
          case 1:
               $direction = "NNE";
               break;
          case 2:
               $direction = "NE";
               break;
          case 3:
               $direction = "ENE";
               break;
          case 4:
               $direction = "E";
               break;
          case 5:
               $direction = "ESE";
               break;
          case 6:
               $direction = "SE";
               break;
          case 7:
               $direction = "SSE";
               break;
          case 8:
               $direction = "S";
               break;
          case 9:
               $direction = "SSW";
               break;
          case 10:
               $direction = "SW";
               break;
          case 11:
               $direction = "WSW";
               break;
          case 12:
               $direction = "W";
               break;
          case 13:
               $direction = "WNW";
               break;
          case 14:
               $direction = "NW";
               break;
          case 15:
               $direction = "NNW";
               break;
          default:
               $direction = "N";
     }
     return $direction;
}



function getDistance($lat1, $lng1, $lat2, $lng2) {
 if(!$lat2 || !$lng2){ return 0; }
 $pi80 = M_PI / 180;
	$lat1 *= $pi80;
	$lng1 *= $pi80;
	$lat2 *= $pi80;
	$lng2 *= $pi80;

	$r = 6372.797; // mean radius of Earth in km
	$dlat = $lat2 - $lat1;
	$dlng = $lng2 - $lng1;
	$a = sin($dlat / 2) * sin($dlat / 2) + cos($lat1) * cos($lat2) * sin($dlng / 2) * sin($dlng / 2);
	$c = 2 * atan2(sqrt($a), sqrt(1 - $a));
	$km = $r * $c;

	return $km;
}
?>
    </div>
    <div id="tab_2" class="tab"><div class="datarpt">
    <table border="0" width="500" cellpadding="0" cellspacing="0">
     <tr class="top">
      <td colspan="1" valign="middle"><img src="_img/logo.png" alt="" width="114" height="36" class="logo" style="margin-left:10px;"></td>
      <td colspan="3" valign="middle"><span style="color:#fff;">RENDIMIENTO DE UNIDAD</span></td>
    </tr>
    <tr class="head">
      <td height="44" colspan="4" align="center" valign="middle"><strong>VEHICULO: <?php echo $asset[0]['alias']; ?> (<?php echo number_format($odo,2) ?> kms)</strong></td>
    </tr>
    <tr>
      <td>Ruta</td>
      <td colspan="3"> Sin Asignar</td>
    </tr>
    <tr>
      <td>Inicio</td>
      <td colspan="3"><a href="javascript:void(0)" class="onLatLngClick" rel="<?php echo $rpt[0]['lat_lng']; ?>" id="<?php echo $rpt[0]['direction']; ?>"><?php echo $objAsset->formatDateTime($rpt[0]['date'],"max"); ?></a></td>
    </tr>
    <tr>
      <td>Fin</td>
      <td colspan="3"><a href="javascript:void(0)" class="onLatLngClick" rel="<?php echo $rpt[count($rpt)-1]['lat_lng']; ?>" id="<?php echo $rpt[count($rpt)-1]['direction']; ?>"><?php echo $objAsset->formatDateTime($rpt[count($rpt)-1]['date'],"max"); ?>
      </a></td>
    </tr>

    <tr class="head">
    <td colspan="4"><strong>Distancia Recorrida Apr&oacute;x.</strong></td>
    </tr>
    <tr>
      <td>Distancia</td>
      <td colspan="3"><?php echo number_format($distance,2); ?> km</td>
    </tr>


    <tr class="head">
    <td colspan="4"><strong>Motor</strong></td>
    </tr>
    <tr>
      <td>Prendido</td>
      <td colspan="3"><?php echo secondsToTime($ton*60); ?></td>
    </tr>
     <tr>
      <td>Apagado</td>
      <td colspan="3"><?php echo secondsToTime($toff*60); ?></td>
    </tr>

    <?php
	function secondsToTime($inputSeconds) {

    $secondsInAMinute = 60;
    $secondsInAnHour  = 60 * $secondsInAMinute;
    $secondsInADay    = 24 * $secondsInAnHour;

    // extract days
    $days = floor($inputSeconds / $secondsInADay);

    // extract hours
    $hourSeconds = $inputSeconds % $secondsInADay;
    $hours = floor($hourSeconds / $secondsInAnHour);

    // extract minutes
    $minuteSeconds = $hourSeconds % $secondsInAnHour;
    $minutes = floor($minuteSeconds / $secondsInAMinute);

    // extract the remaining seconds
    $remainingSeconds = $minuteSeconds % $secondsInAMinute;
    $seconds = ceil($remainingSeconds);

    // return the final array
    $obj = array(
        'd' => (int) $days,
        'h' => (int) $hours,
        'm' => (int) $minutes,
        's' => (int) $seconds,
    );
	$buffer = '';
	$dias = '';
	$horas = '';
	$minutos = '';
	$segundos = '';
	if($obj["d"]>0) { $dias = $obj["d"]; }
	if($obj["d"]==1) { $dias = $obj["d"] ." d&iacute;a"; }
	if($obj["d"]>1) { $dias = $obj["d"] ." d&iacute;as"; }

	if($obj["h"]>0) { $horas = $obj["h"]; }
	if($obj["h"]==1) { $horas = $obj["h"] ." hora"; }
	if($obj["h"]>1) { $horas = $obj["h"] ." horas"; }

	if($obj["m"]>0) { $minutos = $obj["m"]; }
	if($obj["m"]==1) { $minutos = $obj["m"] ." minuto"; }
	if($obj["m"]>1) { $minutos = $obj["m"] ." minutos"; }

	if($obj["s"]>0) { $segundos = $obj["s"]; }
	if($obj["s"]==1) { $segundos = $obj["s"] ." segundo"; }
	if($obj["s"]>1) { $segundos = $obj["s"] ." segundos"; }

    return  $dias.' '.$horas.' '.$minutos.' '.$segundos.' ';
}
	?>


    <tr class="head">
    <td><strong>Temperatura <?php echo $tunit; ?></strong></td>
    <td><strong>Global</strong></td>
    <td><strong>Sobre Cero</strong></td>
    <td><strong>Bajo Cero</strong></td>
    </tr>
    <tr>
      <td>Promedio</td>
      <td><?php echo number_format((array_sum($ttemp) / count($ttemp)),1); ?><?php $tunit = 'C&deg'; if($_SESSION['logged']['temp']=="f"){ $tunit = 'F&deg';  }?> </td>
      <td>
        <?php
		  $aux_a;
		  $aux_b;
		  $cont_a = 0;
		  $cont_b = 0;
		  for($i=0;$i<count($ttemp);$i++){
		    if($ttemp[$i]>0){ $aux_a[$cont_a] = $ttemp[$i];  }else{ $aux_b[$cont_b] = $ttemp[$i]; }
		  }
		  echo number_format((array_sum($aux_a) / count($aux_a)),1);
		 ?>
      </td>
      <td><?php echo number_format((array_sum($aux_b) / count($aux_b)),1); ?></td>
    </tr>
    <tr>
      <td>Maxima</td>
      <td colspan="3"><?php echo max($ttemp);?>
	  <a href="javascript:void(0)" class="onLatLngClick" rel="<?php echo $rpt[array_search(max($ttemp), $ttemp)]['lat_lng']; ?>" id="<?php echo $rpt[array_search(max($ttemp), $ttemp)]['direction']; ?>"><?php if(max($ttemp)!=NULL) echo '('.$objAsset->formatDateTime($rpt[array_search(max($ttemp), $ttemp)]['date'],"max").')'; ?></a></td>
    </tr>
    <tr>
      <td>Minima</td>
      <td colspan="3">
      <?php echo min($ttemp);?>
	  <a href="javascript:void(0)" class="onLatLngClick" rel="<?php echo $rpt[array_search(min($ttemp), $ttemp)]['lat_lng']; ?>" id="<?php echo $rpt[array_search(min($ttemp), $ttemp)]['direction']; ?>">
	   <?php if(max($ttemp)!=NULL) echo '('.$objAsset->formatDateTime($rpt[array_search(min($ttemp), $ttemp)]['date'],"max").')'; ?></a></td>
    </tr>


    <tr class="head">
    <td colspan="4"><strong>Velocidades</strong></td>
    </tr>
    <tr>
      <td>Promedio</td>
      <td colspan="3"><?php echo number_format((array_sum($tspeed) / count($tspeed)),2); ?></td>
    </tr>
     <tr>
      <td>Maxima</td>
      <td colspan="3">
		<?php echo max($tspeed);?> km/h <a href="javascript:void(0)" class="onLatLngClick" rel="<?php echo $rpt[array_search(max($tspeed), $tspeed)]['lat_lng']; ?>" id="<?php echo $rpt[array_search(max($tspeed), $tspeed)]['direction']; ?>">(<?php echo $objAsset->formatDateTime($rpt[array_search(max($tspeed), $tspeed)]['date'],"max"); ?>)</a>
      </td>
    </tr>



     <tr class="head" >
    <td colspan="4"><strong>Historico de Combustible</strong></td>
    </tr>
    <tr>
      <td>Inicial</td>
      <td colspan="3"><a href="javascript:void(0)" class="onLatLngClick" rel="<?php echo $rpt[0]['lat_lng']; ?>" id="<?php echo $rpt[0]['direction']; ?>"><?php echo $rpt_tt[0]['t1']; ?> L</a></td>
    </tr>
     <tr>
      <td>Final</td>
      <td colspan="3"><a href="javascript:void(0)" class="onLatLngClick" rel="<?php echo $rpt[count($rpt)-1]['lat_lng']; ?>" id="<?php echo $rpt[count($rpt)-1]['direction']; ?>"><?php echo $rpt_tt[count($rpt)-1]['t1']; ?> L</a></td>
    </tr>
     <tr>
      <td>Minimo</td>
      <td colspan="3">
	  <?php echo min($tt_raw); ?>
      <a href="javascript:void(0)" class="onLatLngClick" rel="<?php echo $rpt[array_search(min($tt_raw), $tt_raw)]['lat_lng']; ?>" id="<?php echo $rpt[array_search(min($tt_raw), $tt_raw)]['direction']; ?>">
	  <?php if(min($tt_raw)!=NULL) echo '('.$objAsset->formatDateTime($rpt[array_search(min($tt_raw), $tt_raw)]['date'],"max").')'; ?>
      </a>
      </td>
    </tr>
     <tr>
      <td>Maximo</td>
      <td colspan="3">
	  <?php echo max($tt_raw); ?>
	  <a href="javascript:void(0)" class="onLatLngClick" rel="<?php echo $rpt[array_search(max($tt_raw), $tt_raw)]['lat_lng']; ?>" id="<?php echo $rpt[array_search(max($tt_raw), $tt_raw)]['direction']; ?>">
	  <?php if(max($tt_raw)!=NULL) echo '('.$objAsset->formatDateTime($rpt[array_search(max($tt_raw), $tt_raw)]['date'],"max").')'; ?>
      </a>
      </td>
    </tr>
    <tr>
      <td>Volumen Recargado</td>
      <td colspan="3"><?php echo $recarga; ?> lts</td>
    </tr>

    <tr>
      <td>Volumen Descargado</td>
      <td colspan="3"><?php echo $descarga; ?> lts</td>
    </tr>


    <?php
	$comb_consumido = ($rpt_tt[0]['t1']+$recarga)-($rpt_tt[count($rpt)-1]['t1']-$descarga);
	?>
    <tr class="head">
    <td colspan="4"><strong>Rendimiento de Unidad</strong></td>
    </tr>
    <tr>
      <td>Combustible Consumido</td>
      <td colspan="3"><?php echo $comb_consumido; ?> lts</td>
    </tr>
     <tr>
      <td>Consumo x 100KM</td>
      <td colspan="3"><?php echo number_format(($comb_consumido/$distance)*100,2); ?> lts</td>
    </tr>
     <tr>
      <td>Rendimiento X LITRO</td>
      <td colspan="3"><?php echo number_format(($distance/$comb_consumido),2); ?> km/lt</td>
    </tr>
     <tr>
      <td>Consumo x Hora</td>
      <td colspan="3"><?php echo number_format(($comb_consumido/($ton/60)),2); ?> lts/hr</td>
    </tr>

    </table>
    </div></div>
    </div>

  </div>
  <div id="fixed" class="right"><div id="map-canvas" style="width:100%; height:500px; display:block; position:relative;"></div></div>
</div>




<script>
var objMap = null;

$(window).load(function(){ objMap = new Map('map-canvas'); });

var Map = function(map_id){
    var t = this;

	this.div = map_id;
	this.map;
	this.units = [];
	this.marker;

	function onLoad(){ t.addEventListeners(); }
	this.addEventListeners = function(){  google.maps.event.addDomListener(window, 'load', t.init()); }



	this.init = function(){
	  var mapOptions = { zoom: 7, center: new google.maps.LatLng(<?php echo $route[0]['lat'].','.$route[0]['lng']; ?>), mapTypeId: google.maps.MapTypeId.ROADMAP,
	  	 panControlOptions: { position: google.maps.ControlPosition.TOP_LEFT},
	 	 zoomControlOptions: { style: google.maps.ZoomControlStyle.LARGE, position: google.maps.ControlPosition.TOP_LEFT },
	  	 mapTypeControlOptions: { position: google.maps.ControlPosition.TOP_LEFT }
	  };
      t.map = new google.maps.Map(document.getElementById(t.div),mapOptions);

	  t.go_to(<?php echo $route[0]['lat'].','.$route[0]['lng']; ?>);
	  google.maps.event.addListenerOnce(t.map, 'idle', function() {
	  google.maps.event.trigger(t.map, 'resize');
	  t.map.setCenter(new google.maps.LatLng(<?php echo $route[0]['lat'].','.$route[0]['lng']; ?>));
	  });
	  //t.get_gprs();


	  $('.onLatLngClick').live('click',function(e){
	    point = $(e.target).attr("rel").split(",");
	    t.go_to(point[0],point[1],$(e.target).attr("id"));
	  })


	}

	this.go_to = function(lat,lon,id){
	  if(t.marker!=null){ t.marker.setMap(null); }
	  t.marker = new google.maps.Marker({ position: new google.maps.LatLng(lat,lon),map: t.map, icon : "_icons/"+id });
      t.map.setZoom(15);
      t.map.panTo(new google.maps.LatLng(lat, lon));
	}


  onLoad();
}

</script>

<script>
$(document).ready(function(){
  $('.onMover').hover(function(){
    $(this).children().fadeIn().toggleClass('top');
  }, function(){
    $(this).children().fadeOut().toggleClass('top');
  })
   /* TABS */
  $("a.onClickTab").on("click",function(e){
    $(".tab").hide();
	$(".tabs ul li").removeClass('active');
	$(this).parent().addClass('active');
	$("#"+$(this).attr("rel")).fadeIn();
	google.maps.event.trigger(objMap.map, 'resize');
  });
  /* EOF TABS*/


});


$(document).ready(function() {
var stickyNavTop = 520;

var stickyNav = function(){
  var scrollTop = $(window).scrollTop();
  console.log(scrollTop);

  if (scrollTop > stickyNavTop) {
    $('#fixed').addClass('sticky');
} else {
    $('#fixed').removeClass('sticky');
}
};

stickyNav();

$(window).scroll(function() {
	stickyNav();
});
});

</script>

</body>
</html>
