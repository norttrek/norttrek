<?php
require_once("../_class/class.gprs.php");
require_once("../_class/class.asset.php");
require_once("../_class/class.mail.php");
$objGPRS  = new GPRS();
$objAsset  = new Asset();
$objMail  = new Mail();
$asset = $objAsset->getAssetByIMEI($_REQUEST['i']);
$gprs_data = $objGPRS->set_limit(4)->set_order("date DESC")->getGprsReport($asset[0]['imei']);
$fsensors = json_decode($asset[0]['sensor'],true);

$asset = $objAsset->set_imei($imei)->getAsset(NULL);
$fuel_calib = json_decode($asset[0]['fuel'],true);
$t1 = $fuel_calib['t1'];
$t2 = $fuel_calib['t2'];
$t3 = $fuel_calib['t3'];



$actual = 10;
#1st

$fuels = NULL;

$fuel_a_sum = 0;
$fuel_b_sum = 0;
$fuel_c_sum = 0;


for($i=0;$i<count($gprs_data);$i++){
  $data[$i]['date'] = $gprs_data[$i]['date'];
  $data[$i]['speed'] = $gprs_data[$i]['gps_speed'];
    
  $fuel_a = substr($gprs_data[$i]['ada_v'],0,4)/100;
  $fuel_b = substr($gprs_data[$i]['ada_v'],4,8)/100;
  $fuel_c = substr($gprs_data[$i]['fuel'],0,4)/100;
	
  if($fsensors['fuel_a']==1){ 
    $lts = 0;
    $lts = number_format($objGPRS->get_fuel_alt($fuel_a,$t1),2);
    $fuel_a_sum += $lts;
	$data[$i]['fuel_a'] = $lts;
	$cont++;
  }
  
  if($fsensors['fuel_b']==1){ 
    $lts = 0;
    $lts = number_format($objGPRS->get_fuel_alt($fuel_b,$t2),2);
    $fuel_a_sum += $lts;
	$data[$i]['fuel_b'] = $lts;
	$cont++;
  }
  
  if($fsensors['fuel_c']==1){ 
    $lts = 0;
    $lts = number_format($objGPRS->get_fuel_alt($fuel_c,$t3),2);
    $fuel_a_sum += $lts;
	$data[$i]['fuel_c'] = $lts;
	$cont++;
  }
	  
 	
}
echo '<pre>';
print_r($data);
$prom_actual = ($fuel_a_sum-$data[3]['fuel_a'])/3;
$prom_anterior = ($fuel_a_sum-$data[0]['fuel_a'])/3;
echo "Promedio Actual [A]: ".$prom_actual; 
echo "<br>";
echo "Promedio Anterior [A]: ".$prom_anterior; 
echo "<br>";
$total = $prom_actual-$prom_anterior;
if($total<0){ echo "Posible Extraccion ".$total;  }else{ echo "Posible Recarga ".$total;  }




/* PROMEDIO */




return false;

$notify = json_decode($asset[0]['notification'],true);
$data = json_decode($asset[0]['data'],true);
$buffer_html = '
<font face="Arial">
<table width="500" border="0" cellpadding="8" style="border:#e4e4e4 solid 1px;">
  <tr bgcolor="#1b1e25">
    <td height="47" colspan="2"><img src="http://norttrek.com/site/wp-content/uploads/2014/05/logo-1.png" /></td>
  </tr>
  <tr>
    <td>Unidad:</td>
    <td>'.$data[1]['value'].'</td>
  </tr>
  <tr>
    <td>Alerta:</td>
    <td>'.$objAlert->getAlert($_REQUEST['c'],$_REQUEST['v']).'</td>
  </tr>
  <tr>
    <td>Fecha y Hora</td>
    <td>'.$objAsset->formatDateTime($_REQUEST['d'],"max").'</td>
  </tr>
  <tr>
    <td>Ubicacion</td>
    <td><a href="https://www.google.com.mx/maps/?q='.$_REQUEST['p'].'">'.$_REQUEST['p'].'</a></td>
  </tr>
</table>
</font>';

//$objMail->send($notify[$_GET['t']],$bcc,'Alerta - Norttrek',$buffer_html);



?>