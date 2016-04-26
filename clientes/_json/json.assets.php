<?php

session_start();
error_reporting(E_ALL);
ini_set('display_errors', 'On');
$id_client = $_SESSION['logged']['id_client'];
require_once('../_firephp/FirePHP.class.php');
 $mifirePHP = FirePHP::getInstance(true);
 
// aad ago 2015
if($id_client == null){
  exit(1);
}

require_once("../../_class/class.client.php");
require_once("../../_class/class.asset.php");
require_once("../../_class/class.gprs.php");

 
$objAsset = new Asset();
$objGPRS = new GPRS();
$objClient = new Client();
  $tutorial=1;
$id_user = NULL;
if($_SESSION['logged']['type']==2){ $id_user = $_SESSION['logged']['id_user']; }
$assets = $objAsset->set_id_client($id_client)->set_order('sort,client_group.group ASC')->set_id_user($id_user)->set_id_device('0')->getAsset();
$assets_groups = $objAsset->set_order('client_group.group ASC')->getAssetGroups($id_client);

$pois = $objClient->set_id_client($id_client)->getPois();
$result = NULL;
$client_info = $objClient->getClientInfo($_SESSION['logged']['id_client']);
 
/* GPRS DATA */
$gprs = NULL; 
for($i=0;$i<count($assets);$i++){
  $data = json_decode($assets[$i]['data'],true);
   
  $gprs_data = $objGPRS->set_status(NULL)->getLastReport($assets[$i]['imei']);
  $alerts = $objGPRS->getLastAlerts($assets[$i]['imei']);
   
  //$resetStatus = $objGPRS->getResetStatus()
  $alert = $objGPRS->getLastAlert($gprs_data[0]['id']);
  $lastReportTime = strtotime($gprs_data[0]['date']);
  $LastResetTime = $objAsset->getLastResetTime($assets[$i]['imei']);
  $lastClickReset = strtotime($LastResetTime[0]['LastResetTime']);
 
  if($lastClickReset == '0000-00-00 00:00:00'){
    $lastClickReset = "no click";
  }
 
  $statusResetButton = $objGPRS->getResetStatus($assets[$i]['imei'], $lastReportTime,$lastClickReset);
 

  $iostatus = $objGPRS->get_iostatus($gprs_data[0]['iostatus']);
  
  //$iostatus['ignition'] = $gprs_data[0]['v2_eng_status'];
  
  $speed = 0;
  if($gprs_data[0]['gps_speed']=='000'){ $speed = '0'; }else{ $speed = intval($gprs_data[0]['gps_speed']); }

  $gprs[$i]['name'] = $assets[$i]['alias']; 
  $gprs[$i]['id_group'] =  $assets[$i]['id_group'];
  $gprs[$i]['group'] = $assets[$i]['group'];
  $gprs[$i]['temp'] = substr($gprs_data[0]['temp'],0,4)/10;
  // aad ago 2015 temp 2
  // TODO aad validar si en la parte de admin tiene el checkbox de mostrar o no la temp 2
  $gprs[$i]['temp2'] = substr($gprs_data[0]['temp'],4,8)/10;
  //$mifirePHP->log($gprs_data[0],'$gprs_data[0]');
  // PIN 3 AT06
  $gprs[$i]['fuel_a'] = substr($gprs_data[0]['ada_v'],0,4)/100;
  $gprs[$i]['fuel_b'] = substr($gprs_data[0]['ada_v'],4,8)/100;
  $gprs[$i]['fuel_c'] = substr($gprs_data[0]['fuel'],0,4)/100;

  $gprs[$i]['imei'] =  $assets[$i]['imei'];
  $gprs[$i]['speed'] = $speed;

  // aad
  //$gprs[$i]['ignition'] = $iostatus['ignition'];
  $gprs[$i]['ignition'] = $gprs_data[0]['v2_eng_status'];
 
  $gprs[$i]['ignition_cut'] = $iostatus['ignition_cut'];
  $gprs[$i]['ignition_blocked'] = $iostatus['ignition_blocked'];

  // aad nov 2015
  $gprs[$i]['v2_eng_status'] = $gprs_data[0]['v2_eng_status'];

  //add dic 2015
  $gprs[$i]['sat'] = $gprs_data[0]['v2_sat'];
  $gprs[$i]['hdop'] = $gprs_data[0]['v2_HDOP'];
  $gprs[$i]['power_status'] = $gprs_data[0]['v2_power_status'];
  // power_status 1 azul
  // power status 0 gris
  // 
  $gprs[$i]['sms_status'] = $client_info[0]['sms'];

  $gprs[$i]['low_bat'] = $gprs_data[0]['v2_low_bat'];
  $gprs[$i]['eng_block'] = $gprs_data[0]['v2_eng_block'];
  $gprs[$i]['starter_block'] = $gprs_data[0]['v2_starter_block'];
  $gprs[$i]['e_lock'] = $gprs_data[0]['v2_e_lock'];
  $gprs[$i]['volt'] = $gprs_data[0]['v2_volt'];
  $gprs[$i]['battery'] = $gprs_data[0]['v2_battery'];
  $gprs[$i]['altitude'] = $gprs_data[0]['v2_altitude'];
  $gprs[$i]['cof'] = 'cof';
  $gprs[$i]['grados'] = $gprs_data[0]['v2_heading'];
  $gprs[$i]['speedAlarm'] = $assets[$i]['speedAlarm'];
  $gprs[$i]['speedLimit'] = $assets[$i]['speedLimit'];
  $gprs[$i]['reportTime'] = $assets[$i]['reportTime'];
  $gprs[$i]['formula'] = $assets[$i]['formula'];

  $gprs[$i]['v2_fuel1'] = $gprs_data[0]['v2_fuel1'];
  $gprs[$i]['v2_fuel2'] = $gprs_data[0]['v2_fuel2'];
  $gprs[$i]['v2_fuel3'] = $gprs_data[0]['v2_fuel3'];
  $gprs[$i]['date'] = $gprs_data[0]['date']; 
  $gprs[$i]['pos1'] = $assets[$i]['pos1'];
  $gprs[$i]['pos2'] = $assets[$i]['pos2'];
  $gprs[$i]['pos3'] = $assets[$i]['pos3'];

  $gprs[$i]['lt1'] = $assets[$i]['lt1'];
  $gprs[$i]['lt2'] = $assets[$i]['lt2'];
  $gprs[$i]['lt3'] = $assets[$i]['lt3'];

  $gprs[$i]['da1'] = $assets[$i]['da1'];
  $gprs[$i]['da2'] = $assets[$i]['da2'];
  $gprs[$i]['da3'] = $assets[$i]['da3'];

  $gprs[$i]['lg1'] = $assets[$i]['lg1'];
  $gprs[$i]['lg2'] = $assets[$i]['lg2'];
  $gprs[$i]['lg3'] = $assets[$i]['lg3'];

  $gprs[$i]['da1'] = $assets[$i]['da1'];
  $gprs[$i]['da2'] = $assets[$i]['da2'];
  $gprs[$i]['da3'] = $assets[$i]['da3'];

  $gprs[$i]['lg1'] = $assets[$i]['lg1'];
  $gprs[$i]['lg2'] = $assets[$i]['lg2'];
  $gprs[$i]['lg3'] = $assets[$i]['lg3'];

  $gprs[$i]['var1'] = $assets[$i]['var1'];
  $gprs[$i]['var2'] = $assets[$i]['var2'];
  $gprs[$i]['var3'] = $assets[$i]['var3'];

  $gprs[$i]['as1'] = $assets[$i]['as1'];
  $gprs[$i]['as2'] = $assets[$i]['as2'];
  $gprs[$i]['as3'] = $assets[$i]['as3'];

  $gprs[$i]['vl1'] = $assets[$i]['vl1'];
  $gprs[$i]['vl2'] = $assets[$i]['vl2'];
  $gprs[$i]['vl3'] = $assets[$i]['vl3'];

  $gprs[$i]['elock_status'] = $assets[$i]['elock'];
  $gprs[$i]['temp1_status'] = $assets[$i]['temp1'];
  $gprs[$i]['temp2_status'] = $assets[$i]['temp2'];
  $gprs[$i]['engine_status'] = $assets[$i]['engine'];
  $gprs[$i]['speed_status'] = $assets[$i]['speed'];
  $gprs[$i]['marcha_status'] = $assets[$i]['marcha'];
  $gprs[$i]['formula_status'] = $assets[$i]['formula'];
  $gprs[$i]['nombre'] = $assets[$i]['nombre_u'];
   
  if($assets[$i]['act']==1){
    $gprs[$i]['active'] = "checked";
  } 
  $gprs[$i]['profile'] = $assets[$i]['imagen_u'];
  $gprs[$i]['odometro'] = $assets[$i]['odometro'];
  $gprs[$i]['marca'] = $assets[$i]['marca'];
  $gprs[$i]['modelo'] = $assets[$i]['modelo'];
  $gprs[$i]['anio'] = $assets[$i]['anio'];
  $gprs[$i]['color'] = $assets[$i]['color'];
  $gprs[$i]['placa'] = $assets[$i]['placa'];
  $gprs[$i]['rendimiento'] = $assets[$i]['rendimiento'];
  $gprs[$i]['pasajeros'] = $assets[$i]['pasajeros']; 
  $gprs[$i]['pesotara'] = $assets[$i]['pesotara'];
  $gprs[$i]['ejes'] = $assets[$i]['ejes']; 
  $gprs[$i]['chasis'] = $assets[$i]['chasis'];
  $gprs[$i]['capcarga'] = $assets[$i]['capcarga'];
  $gprs[$i]['caparrastre'] = $assets[$i]['caparrastre'];
  $gprs[$i]['tdcarga'] = $assets[$i]['tdcarga'];

  $gprs[$i]['rendimientokl'] = $assets[$i]['rendimientokl'];
  $gprs[$i]['cilindros'] = $assets[$i]['cilindros'];
  $gprs[$i]['transmision'] = $assets[$i]['transmision'];
  $gprs[$i]['velocidades'] = $assets[$i]['velocidades'];
  $gprs[$i]['diferencial'] = $assets[$i]['diferencial'];
  $gprs[$i]['seriemotor'] = $assets[$i]['seriemotor'];

  if($assets[$i]['tecnomecanica']==1){
    $gprs[$i]['tecnomecanica'] = "checked";
  } 
  if($assets[$i]['ambiental']==1){
    $gprs[$i]['ambiental'] = "checked";
  } 
  if($assets[$i]['neec']==1){
    $gprs[$i]['neec'] = "checked";
  } 
  if($assets[$i]['fisicomecanica']==1){
    $gprs[$i]['fisicomecanica'] = "checked";
  } 
  if($assets[$i]['tpat']==1){
    $gprs[$i]['tpat'] = "checked";
  }

  $gprs[$i]['seguro'] = $assets[$i]['seguro'];
  $gprs[$i]['vencimiento'] = $assets[$i]['vencimiento'];
  $gprs[$i]['poliza'] = $assets[$i]['poliza'];
  $gprs[$i]['dof'] = $assets[$i]['dof'];
  $gprs[$i]['fmDate'] = $assets[$i]['fmDate'];

  $gprs[$i]['tcDate'] = $assets[$i]['tcDate'];
  $gprs[$i]['vaDate'] = $assets[$i]['vaDate'];
  $gprs[$i]['ctDate'] = $assets[$i]['ctDate'];
  $gprs[$i]['usdot'] = $assets[$i]['usdot'];
  $gprs[$i]['txdot'] = $assets[$i]['txdot'];
  $gprs[$i]['neDate'] = $assets[$i]['neDate'];

  if($statusResetButton == 0){
     $gprs[$i]['ResetButtonClass'] = "btn-inactive";
     $gprs[$i]['ResetButtonFunction'] = "null";
  }elseif($statusResetButton == 1){
     $gprs[$i]['ResetButtonClass'] = "btn-active";
     $gprs[$i]['ResetButtonFunction'] = "setLastResetTime";
  }
 
  $gprs[$i]['statusResetButton'] = $statusResetButton;
  
  $gprs[$i]['speedAlarmActive'] = $assets[$i]['speedAlarmActive'];

  if($assets[$i]['speedAlarmActive'] == 1 ){
    $gprs[$i]['speedAlarmActive'] = 'checked';
  }else{
    $gprs[$i]['speedAlarmActive'] = '';
  }
 
  $gprs[$i]['speedLimitActive'] = $assets[$i]['speed_limitActive'];
  
  if($assets[$i]['speed_limitActive'] == 1 ){
 
    $gprs[$i]['speedLimitActive'] = 'checked';
  }else{ 
    $gprs[$i]['speedLimitActive'] = 'rr';
  }
   
  $gprs[$i]['alerts'] .= "<table id='tableAlerts'>";
  foreach ($alerts as $key => $value) {
    if($value['description'] == '¡ALERTA! SOS BOTÓN DE PÁNICO'){
     $value['description']= 'SOS BOTÓN DE PÁNICO';
    }
    $gprs[$i]['alerts'] .= "
    
      <tr>
        <td>" . ucfirst(mb_strtolower($value['description'], "UTF-8")) . "</td>
        <td style='width:20px; text-align:center'><a target='_blank' href='http://www.google.com/maps/place/".$value['latitude'].",".$value['longitude']."'> <i   class='fa fa-map-marker'></i></a></td>
        <td>". substr($value['date'], 5) . "</td>
      </tr>
   ";
  }
   $gprs[$i]['alerts'] .= " </table>"; 

    if($gprs[$i]['v2_eng_status']==1 && $gprs[$i]['speed'] >= 2){
        $gprs[$i]['icon_color'] = "#2079EC";
        }elseif ($gprs[$i]['v2_eng_status']==0) {
         $gprs[$i]['icon_color'] = "#818181";
        }elseif ($gprs[$i]['v2_eng_status']==1 && $gprs[$i]['speed'] < 2) {
          $gprs[$i]['icon_color'] = "#EA7C06";
      }

  if($alert != null){
     switch($alert[0]['description']) {
          case "¡ALERTA! SOS BOTÓN DE PÁNICO": 
                $colorW ="#F33";
                $gprs[$i]['icon_color'] = "#F33";
          break;
          case 'DESCONEXIÓN DE EQUIPO':
                $colorW ="#F33";
                $gprs[$i]['icon_color']  = "#F33";
          break;
          case 'BATERIA DE EQUIPO BAJA':
                $colorW ="#F33";
                if($gprs[$i]['power_status'] = 0){

                }elseif ($gprs[$i]['power_status'] == 1) {
                  # code...
                }
          break;
          case 'EXCESO DE VELOCIDAD':
                $colorW ="#F57709";
          break;
          case 'APERTURA DE E-LOCK':
                $colorW ="#F57709";
          break;
          case 'BLOQUEO DE MOTOR':
                $colorW ="#F57709";
          break;
          case 'BLOQUEO DE MARCHA':
                $colorW ="#F57709";
          break;
          case 'ENCENDIDO DE MOTOR':
                $colorW ="rgb(239, 224, 44)";
          break;
          case 'APAGADO DE MOTOR':
                $colorW ="rgb(239, 224, 44)";
          break;
          case 'UNIDAD ENCENDIDA SIN MOVIMIENTO':
                $colorW ="rgb(206, 194, 51)";
          break;
          case 'CERRADO DE E-LOCK':
                $colorW ="rgb(239, 224, 44)";
          break;
          case 'DESBLOQUEO DE MOTOR':
                $colorW ="rgb(239, 224, 44)";
          break;
          case 'BLOQUEO DE MARCHA':
                $colorW ="rgb(239, 224, 44)";
          break;
          case 'CONEXIÓN DE EQUIPO':
                $colorW ="#1cf100";
          break;       
     }
      $alert =  ' <i class="fa fa-warning  tooltip"  style="font-size:12px; color:'.$colorW. '" title="'.$alert[0]['description'].'"></i>';
  }
  $gprs[$i]['alert'] = $alert;

  if($gprs[$i]['starter_block'] == 1){
    $gprs[$i]['starter_block_color'] = '#F33';
    $gprs[$i]['starter_block_class'] = 'btn-alert-red';
    $gprs[$i]['starter_block_function'] = 'desbloquearMarcha';
  }elseif ($gprs[$i]['starter_block'] == 0) {
    $gprs[$i]['starter_block_class'] = 'btn-active';
    $gprs[$i]['starter_block_function'] = 'bloquearMarcha';
  }

  if($gprs[$i]['e_lock'] == 1){
    $gprs[$i]['e_lock_color'] = '#1cf100';
    $gprs[$i]['e_lock_class_btn'] = 'btn-alert-green';
    $gprs[$i]['e_lock_class'] = 'lock';
    $gprs[$i]['e_lock_function'] = 'openElock';
  }else{
    $gprs[$i]['e_lock_class'] = 'unlock';
    $gprs[$i]['e_lock_class_btn'] = 'btn-alert-red';
    $gprs[$i]['e_lock_function'] = 'closeElock';
  }


  if($gprs[$i]['eng_block'] == 1){
    $gprs[$i]['eng_block_color'] = '#F33';
    $gprs[$i]['eng_block_class'] = 'btn-alert-red';
    $gprs[$i]['eng_block_function'] = 'unlockEngine';
  }elseif($gprs[$i]['eng_block'] == 0){
    $gprs[$i]['eng_block_class'] = 'btn-active';
    $gprs[$i]['eng_block_function'] = 'lockEngine';

  }

  //Si el voltaje es mayor a 5000, el voltaje debe ser igual al ultimo litraje en la calibracion
  if($gprs_data[0]['v2_fuel1'] > 5000){
     $f1 = $gprs_data[0]['v2_fuel1'] = 5000;
  }
  $gas1 =  $gprs_data[0]['v2_fuel1'] / 50;
  $gprs[$i]['gas1'] =  $gas1;
   
  if($gprs_data[0]['v2_fuel2'] > 5000){
    $gprs_data[0]['v2_fuel2'] = 5000;
  }
  $gas2 =  $gprs_data[0]['v2_fuel2'] / 50;
  $gprs[$i]['gas2'] =  $gas2;

  if($gprs_data[0]['v2_fuel3'] > 5000){
    $gprs_data[0]['v2_fuel3'] = 5000;
  }
  $gas3 =  $gprs_data[0]['v2_fuel3'] / 50;
  $gprs[$i]['gas3'] =  $gas3;


  $gprs[$i]['rssi'] = $objGPRS->get_rssi_value($gprs_data[$i]['v2_RSSI']) ;
 
  $bearing = (rad2deg(atan2(sin(deg2rad($gprs_data[1]['lng']) - deg2rad($gprs_data[0]['lng'])) * cos(deg2rad($gprs_data[1]['lat'])), cos(deg2rad($gprs_data[0]['lat'])) * sin(deg2rad($gprs_data[1]['lat'])) - sin(deg2rad($gprs_data[0]['lat'])) * cos(deg2rad($gprs_data[1]['lat'])) * cos(deg2rad($gprs_data[1]['lng']) - deg2rad($gprs_data[0]['lng'])))) + 360) % 360;

  $direction = getCompassDirection($bearing);
  $direction_class = '';
  if($gprs[$i]['ignition']==1 && $gprs[$i]['speed']>0){ $icon = "0_".$direction.".png"; $direction_class = 'm_'.$direction; }
  if($gprs[$i]['ignition']==1 && $gprs[$i]['speed']<=0){ $icon = "1_".$direction.".png"; $direction_class = 'i_'.$direction; }
  if($gprs[$i]['ignition']==0){ $icon = "2_".$direction.".png"; $direction_class = 's_'.$direction; }

  $gprs[$i]['bearing'] = $direction_class;

  $gprs[$i]['icon'] = $icon;

  $gprs[$i]['datetime_ago'] = $objGPRS->get_time_ago(date("Y-m-d H:i:s"),$gprs_data[0]['date']);
  $gprs[$i]['datetime'] = $objGPRS->formatDateTime($gprs_data[0]['date'],"min");
  $gprs[$i]['status'] = $objGPRS->get_status($gprs_data[0]['status']);
  $gprs[$i]['lat'] = number_format($gprs_data[0]['lat'],5);
  $gprs[$i]['lng'] = number_format($gprs_data[0]['lng'],5);
  $gprs[$i]['lat_lng'] = $gprs_data[0]['lat'].','.$gprs_data[0]['lng'];
  $assets[$i]['tipo'] = $data[3]['value'];
    
}

function getCompassDirection($bearing) {
     $tmp = round($bearing / 22.5);
     switch($tmp) {
          case 1: $direction = "NNE"; 
                  $grados = 22;
          break;
          case 2: 
            $direction = "NE";
             $grados = 45;
          break;
          case 3: $direction = "ENE";
           $grados = 67;
          break;
          case 4:
               $direction = "E";
                $grados = 90;
               break;
          case 5:
               $direction = "ESE";
                $grados = 112;
               break;
          case 6:
               $direction = "SE";
                $grados = 135;
               break;
          case 7:
               $direction = "SSE";
                $grados = 157;
               break;
          case 8:
               $direction = "S";
                $grados = 180;
               break;
          case 9:
               $direction = "SSW";
                $grados = 202;
               break;
          case 10:
               $direction = "SW";
                $grados = 225;
               break;
          case 11:
               $direction = "WSW";
                $grados = 247;
               break;
          case 12:
               $direction = "W";
                $grados = 270;
               break;
          case 13:
               $direction = "WNW";
                $grados = 292;
               break;
          case 14:
               $direction = "NW";
               break;
          case 15:
               $direction = "NNW";
                $grados = 315;
               break;
          default:
               $direction = "N";
                $grados = 0;
     }
     return $direction;

}
 
$points = NULL;
/* HTML BOTTOM */
$html_bottom = NULL;

for($i=0;$i<count($assets);$i++){
 
  $poi_distance = NULL;
  for($k=0;$k<count($pois);$k++){
    $poi_distance[$k] = number_format(getDistance($pois[$k]['lat'],$pois[$k]['lng'],$gprs[$i]['lat'],$gprs[$i]['lng']),2);
  $pois[$k]['distance'] = $poi_distance[$k];
  }
  asort($poi_distance);
  $close_idx = key($poi_distance);
  if($gprs[$i]['lat_lng']!=","){


  $idx_points = 0;
  $is_duplicate = is_duplicate($gprs[$i]['lat'],$gprs[$i]['lng'],$points);
  $address = '';
  if($is_duplicate>=0){
    $geocoding = $points[$is_duplicate];
  $address = $geocoding['address'];
  }else{
    $geocoding = get_position($gprs[$i]['lat'],$gprs[$i]['lng']);
    $points[$idx_points]['lat'] = $gprs[$i]['lat'];
    $points[$idx_points]['lng'] = $gprs[$i]['lng'];
  $points[$idx_points]['address'] = $geocoding['address'];
  $address = $geocoding['address'];
  $idx_points++;

  }
 
    if($pois[$close_idx]['poi'] == null){

      $referencia = 'Sin referencia';
    
    }

    $power = 'Apagado';
    $color = '#c80000';
    
    // aad nov 2015
    //if($gprs[$i]['ignition']==1){ $color = "#22b400"; $power= 'Encendido'; }
    if($gprs[$i]['v2_eng_status']==1){ $color = "#22b400"; $power= 'Encendido'; }
    
  if($assets[$i]['tipo']=="Caja Seca" || $assets[$i]['tipo']=="Caja Refrigerada" || $assets[$i]['tipo']=="Plataforma" || $assets[$i]['tipo']=="Plataforma Cortina"){ if($power=="Apagado"){  $power = 'Desconectada'; }else{ $power = 'Conectada'; }}
    $html_bottom .= '<tr>';
    $html_bottom .= '<td><strong>'.$gprs[$i]['name'].'</strong></td>';
    $html_bottom .= '<td><span style=" padding:0px 3px; background-color:'.$color.'; color:#fff;">'.$power.'</span></td>';
    $html_bottom .= '<td>'.$gprs[$i]['status'].'</td>';
    $html_bottom .= '<td>'.$gprs[$i]['speed'].' km</td>';
    $html_bottom .= '<td>'.$gprs[$i]['datetime'].'</td>';
    $html_bottom .= '<td>('.$gprs[$i]['lat_lng'].')</td>';
    $html_bottom .= '<td>'.$pois[$close_idx]['poi'].' &rarr; '.$pois[$close_idx]['distance'].' km</td>';
    $html_bottom .= '</tr>';
  }
}

/* HTML LEFT */
$temp = NULL;
 
for($i=0;$i<count($assets_groups);$i++){
 
 
  $html_left  .= '<table border="0" width="100%" cellpadding="0" cellspacing="0" id="units">';
  $html_left  .= '<tr><td colspan="2"><h1>'.$assets_groups[$i]['group'].'</h1></td></tr>';

  for($k=0;$k<count($assets);$k++){
    

    if($tutorial==1){ 
      $step1 = "data-step='1' data-intro='En esta barra se muestra el estado de encendido del motor y el nombre de la unidad.<br><i class=".'icon-battery-full'." ></i> Batería del equipo.<br><i class=".'icon-volt'." ></i> Batería del vehículo<br><i class=".'icon-signal5'." ></i> Señal de la red celular.<br> '";
      $step2 = 'data-step="2" data-intro="Tiempo del ultimo reporte"'; 
      $step3 = 'data-step="3" data-intro="Velocidad de la unidad en Km/hora"';
      $step4 = 'data-step="4" data-intro="Orientación"';
      $step5 = 'data-step="5" imei="'.$gprs[$k]['imei'].'" data-intro="Configuraciones para el equipo"';
      $step6 = 'data-step="6" data-intro="Obtener dirección donde se encentra la unidad y enviarla vía SMS o E-mail"';
      $step7 = 'data-step="7" data-intro="Activar alerta de velocidad"';
      $step8 = 'data-step="8" data-intro="Ingresar la cantidad de km/hora "';
      $step9 = 'data-step="9" data-intro="Tiempo de reporte"';
      $step10 = 'data-step="10" data-intro="Activar el limite velocidad"';
      $step11 = 'data-step="11" imei="'.$gprs[$k]['imei'].'" data-intro="Ingresar la cantidad de km/hora "';
      $step12 = 'data-step="12" imei="'.$gprs[$k]['imei'].'" data-intro="Muestra las ultimas 15 alertas del día"';
      $step13 = 'data-step="13" imei="'.$gprs[$k]['imei'].'" data-intro="Aqui puedes cargar los datos del vehículo, asi como subir una foto o imagen en formato jpg"';


      $step14 = "data-step='14'  data-intro='Al terminar de ingresar los datos presiona el boton Guardar<input type=".'submit'." class=".'savebtn'."  value=".'Guardar'.">'";
      $step15 = 'data-step="15" imei="'.$gprs[$k]['imei'].'" data-intro="Aqui puedes ingresar información del motor, transmisión, No. de serie etc."';
      $step16 = 'data-step="16" imei="'.$gprs[$k]['imei'].'" data-intro="Al terminar de ingresar los datos presiona el boton Guardar"';
      $step17 = 'data-step="17" imei="'.$gprs[$k]['imei'].'" data-intro="Aqui puedes ingresar credenciales tales como seguro, inspecciones y mas"';
      $step18 = 'data-step="18" data-intro="Al terminar de ingresar los datos presiona el boton Guardar"';
    }else{
      $step1="";$step2="";$step3="";$step4="";$step5="";$step6="";$step7=""; $step8="";$step9="";$step10="";$step11="";$step12="";$step13="";$step14="";$step15="";$step16="";$step17="";$step18="";$step19="";
    } 
  $fsensors = NULL;
  $fsensors = json_decode($assets[$k]['sensor'],true);
  $fuel_calib = json_decode($assets[$k]['fuel'],true);
  $fuel_a = $gprs[$k]['fuel_a'];
  $fuel_b = $gprs[$k]['fuel_b'];
  $fuel_c = $gprs[$k]['fuel_c'];

  $temp_c = 0;
  $temp_c2 = 0;

  $temp_c = $gprs[$k]['temp'];
  $temp_c2 = $gprs[$k]['temp2'];

  $tunit = 'C&deg';
  #FARENHEIT
  if($_SESSION['logged']['temp']=="f"){
  $temp_c  = ($gprs[$k]['temp']*1.8+32);
  $temp_c2  = ($gprs[$k]['temp2']*1.8+32);
  $tunit = 'F&deg';
  }

  $fuel_a_html = '';
  $fuel_b_html = '';
  $fuel_c_html = '';

  $fuel_total = 0;
 
  if($fuel_a<1){ $fuel_a_html = '<li></li>'; }
  if($fuel_a>=1 && $fuel_a<2){ $fuel_a_html = '<li></li><li></li>'; }
  if($fuel_a>=2 && $fuel_a<3){ $fuel_a_html = '<li></li><li></li><li></li>'; }
  if($fuel_a>=3 && $fuel_a<4.5){  $fuel_a_html = '<li></li><li></li><li></li><li></li>';}
  if($fuel_a>=4.5){ $fuel_a_html = '<li></li><li></li><li></li><li></li><li></li>'; }


  if($fuel_b<1){ $fuel_b_html = '<li></li>'; }
  if($fuel_b>=1 && $fuel_b<2){ $fuel_b_html = '<li></li><li></li>'; }
  if($fuel_b>=2 && $fuel_b<3){ $fuel_b_html = '<li></li><li></li><li></li>'; }
  if($fuel_b>=3 && $fuel_b<4.5){  $fuel_b_html = '<li></li><li></li><li></li><li></li>';}
  if($fuel_b>=4.5){ $fuel_b_html = '<li></li><li></li><li></li><li></li><li></li>'; }
  
  if($fuel_c<1){ $fuel_c_html = '<li></li>'; }
  if($fuel_c>=1 && $fuel_c<2){ $fuel_c_html = '<li></li><li></li>'; }
  if($fuel_c>=2 && $fuel_c<3){ $fuel_c_html = '<li></li><li></li><li></li>'; }
  if($fuel_c>=3 && $fuel_c<4.5){  $fuel_c_html = '<li></li><li></li><li></li><li></li>';}
  if($fuel_c>=4.5){ $fuel_c_html = '<li></li><li></li><li></li><li></li><li></li>'; }
    
    $power = 'Apagado';
    $color = '#818181';
   
    // aad 
    //if($gprs[$k]['ignition']==1){ $color = "#1cf100"; $power= 'Encendido'; }
    if($gprs[$k]['v2_eng_status']==1){ $color = "#1cf100"; $power= 'Encendido'; }
  
 
    if($assets_groups[$i]['id']==$assets[$k]['id_group']){
 
    $html_left .= '<tr  class=""  id="'.$gprs[$k]['imei'].'">';
    if($gprs[$k]['hdop'] > 20){
      $hdop = '#F33';
    }else{
      $hdop = '#818181';
    }
    if($gprs[$k]['power_status'] == 0){
      $ps = '#F33';
    }else{
      $ps = '#818181';
    }

    if($gprs[$k]['low_bat'] == 1){
      $lb = '#F33';
      $lb_class = 'icon-battery-low';
    }else{
      $lb = '#818181';
      $lb_class = 'icon-battery-full';
    }

    
  $poi_distance = NULL;
  $poi_distance[$i] = number_format(getDistance($pois[$i]['lat'],$pois[$i]['lng'],$gprs[$k]['lat'],$gprs[$k]['lng']),2);
  $pois[$i]['distance'] = $poi_distance[$i];

  asort($poi_distance);
  $close_idx = key($poi_distance);

    $volt = $gprs[$k]['volt'] / 1000;
    $volt = substr($volt, 0, 2);
    $battery = $gprs[$k]['battery'] / 1000;
    $battery = substr($battery, 0, 3);
 
    $altitude = substr($gprs[$k]['altitude'], 0, 3);
     
      $html_left .= '
      <td  valign="top">
       <div class="headVehicle"  '.$step1.'>
      <div class="vehicleName">

        <i class="fa fa-power-off " style=" color:'.$color.';"></i>
             <a class="tooltip" title="'.$gprs[$k]['name'].'" href="javascript:objTrack.go_to('.$gprs[$k]['lat_lng'].')"
              rel="'.$assets[$k]['id'].'" class="onClickOpenOverlay" style=" color:#fff"><strong>'.substr($gprs[$k]['name'], 0, 15).'</strong></a>
         
      </div>
      <div class="vehicleNot">';

      if($gprs[$k]['engine_status'] == 1){
        $html_left .= '<i class="icon-engine" style="color:'.$gprs[$k]['eng_block_color'].'"></i> ';
      }
      if($gprs[$k]['marcha_status'] == 1){
        $html_left .= '<i class="icon-key" style="  color:'.$gprs[$k]['starter_block_color'].'"></i>';
      }
      if($gprs[$k]['elock_status'] == 1){
        $html_left .= '<i class="fa fa-'.$gprs[$k]['e_lock_class'].' " style="  color:'.$gprs[$k]['e_lock_color'].'"></i>';
      }            
              
                
            $html_left .=''.$gprs[$k]['alert'].'
        <div id="not">
          <i class="tooltip '.$lb_class.'" style="cursor:pointer; color: '.$lb.'" title="'.$battery .'"></i>
          <div class="not">'.$battery .'</div>
        </div>
         
        <div id="not">
          <i class="tooltip icon-volt" style="cursor:pointer;  color:'. $ps .'" title="'.$volt .'"></i>
          <div class="not">'.$volt.'</div>
        </div>
         
        <div id="not">
          <i class="icon-signal'.$gprs[$k]['rssi'].'"></i>
        </div>
      </div>
      <div class="clear"></div>
    </div>
    <div class="BodyVehicle "  onClick="ClickOpenUnitDetails('.$gprs[$k]['imei'].')">
      <div id="user">';

      if($gprs[$k]['profile'] != ''){
        $html_left.='<img src="/clientes/_img/profile/'.$gprs[$k]['profile'].'" style="width: 35px; border-radius: 18px;">';
      }else{
        $html_left.=' <img src="_img/user.png">';
      }
       
      $html_left.='</div>
      <div id="engineInfo1"> 
        <div id="iconsengine" style="text-align:right; padding-right:24px;">
          <span '.$step2.'><i class="fa fa-clock-o " '.$step2.'></i> '.$gprs[$k]['datetime_ago'].' </span>
           
          <span '.$step3.'><i class="fa fa-tachometer " style="margin-left:10px;"></i> '.$gprs[$k]['speed'].' km </span><i style="margin-left: 8px;
    font-size: 30px;
    color: #2079EC;
    margin-top: 2px; color:'.$gprs[$k]['icon_color'].'" '.$step4.' class="fa fa-arrow-circle-o-up   fa-rotate-'.$gprs[$k]['grados'].'"></i>
        </div>
        
        <div class="clear"></div>
      </div>
      <div id="iconseng">
        <div id="gasGraphics">
          
          ';
        $total_fuel=0;
      $total_fuel_active=0;
            if($gprs[$k]['formula']==2){
      //obtener la calibracion del equipo
      $lts = $objAsset->get_lts($gprs[$k]['imei']);
      //decodificar los litros que estan guardados en formato json en la base de datos
      $lts = json_decode($lts[0]['fuel'],true);  
      //Recorrer los 3 tanques
      
      foreach ($lts as $key => $value) {  
        if($value==null){
          continue;
        }
         
        $total_fuel_active++;
          $key =  substr($key,1); 
          $volt_report = $gprs[$k]['v2_fuel'.$key];
          $volt_report = $volt_report/1000;  
          if ($volt_report > 6) {
             $html_left .= '
          <div class="containerGas">
            <div class="">
              Error de lectura en tanque '.$key .'
            </div>
            <div class="gas1Q">  </div>
            <div class="clear"></div>
          </div>';
          }else{
             $litros = $objGPRS->get_fuel_by_calibracion($volt_report,$value,$gprs[$k]['date'],$key);
             $total_fuel = $total_fuel + $litros;
          $html_left .= '
          <div class="containerGas">
            <div class="gas1">
              <div class="bloquegas bloque1"></div>
              <div class="bloquegas bloque2"></div>
              <div class="bloquegas bloque3"></div>
              <div class="bloquegas bloque4"></div>
              <div class="gas1fill" style="width:'.$gprs[$k]['gas'.$key].'%"  per="'.$gprs[$k]['gas'.$key].'"></div>
            </div>
            <div class="gas1Q">'.round($litros).' L </div>
            <div class="clear"></div>
          </div>';
          
          }
      }
      
    }elseif ($gprs[$k]['formula']==1) {
      //Formula tradicional
      
      $lts = $objAsset->get_lts($gprs[$k]['imei']);
      $volt_report = $gprs[$k]['v2_fuel1'];
      $volt_report = $volt_report/1000; 
           
      for ($d=1; $d <= 3 ; $d++) {
        if($d == 1){ $a = "a";}if($d == 2){ $a = "b";}if($d == 3){ $a = "c";} 
 
          if($fsensors['fuel_'.$a]==1){
             $volt_report = $gprs[$k]['v2_fuel'.$d];
             $volt_report = $volt_report/1000; 
             $lts = number_format($objGPRS->get_fuel_lt_v2($gprs[$k]['da'.$d],$gprs[$k]['lg'.$d],$gprs[$k]['as'.$d],$gprs[$k]['var'.$d],$gprs[$k]['vl'.$d],$volt_report),2);
           $total_fuel = $total_fuel + $lts;
            
           $total_fuel_active++;
          $html_left .= '
            <div class="containerGas">
              <div class="gas1">
              <div class="bloquegas bloque1"></div>
              <div class="bloquegas bloque2"></div>
              <div class="bloquegas bloque3"></div>
              <div class="bloquegas bloque4"></div>
                <div class="gas1fill" style="width:'.$gprs[$k]['gas'.$d ].'%"  per="'.$gprs[$k]['gas'.$d].'"></div>
              </div>
              <div class="gas1Q"> '.round($lts).' L </div>
              <div class="clear"></div>
            </div>';
        }
      } 
    }
 
    if($total_fuel_active==1 ||$total_fuel_active==0 ){
        $total_fuel_active =0;
      }else{
        $total_fuel_active=1;
      }
if($fsensors['fuel_a']==0 && $fsensors['fuel_b']==0 && $fsensors['fuel_c']==0){
  $fuelClass="iconsEngineNF";
}
else{
  $fuelClass="iconsEngine";
}
        $html_left.='</div>
        <div id="'.$fuelClass.'">
          <div class="containerEicons">
            <div class="icons3"> 
                ';
                
                if($total_fuel_active==1){
                  $html_left.='<span style="font-size:10px;">Total:'.round($total_fuel).' L</span>';
                }
               $total_fuel_active =0;
            $html_left.='</div>
            <div class="tempIcons">';
            if($fsensors['temp']==1){
       $html_left  .= '&nbsp;<i class="icon-snow" style="margin-right:4px;"></i><span style="font-size:10px">'.number_format($temp_c,1).' '.$tunit.' </span>';

       if($fsensors['temp2']==1){
         $html_left .= ' &nbsp; <span style="font-size:10px">'.number_format($temp_c2,1).' '.$tunit.' </span>';
       }

       $html_left .= " ";
    }
              $html_left .= ' 
            </div>

            <div class="clear"></div>
          </div>
        </div>
        <div class="clear"></div>
      </div>
      <div class="clear"></div>
    </div>
    </div>'; 

    $html_left .= '';

     $html_left .= '  
    <div id="tabs" imei='.$gprs[$k]['imei'].'" class="tabs'.$gprs[$k]['imei'].' tabstoDo">
    <ul>
      <li class="tabli tabli'.$gprs[$k]['imei'].'1" onClick="setTabs('.$gprs[$k]['imei'].',1)" '.$step5.'><a href="#tabs-1" title=""><i class="fa fa-cogs"  ></i></a></li>
      <li class="tabli tabli'.$gprs[$k]['imei'].'2" onClick="setTabs('.$gprs[$k]['imei'].',2)" '.$step12.'><a href="#tabs-2" title=""><i class="fa fa-warning"  ></i></a></li>
      <li class="tabli tabli'.$gprs[$k]['imei'].'3" onClick="setTabs('.$gprs[$k]['imei'].',3)" '.$step13.'><a href="#tabs-3" title=""><i class="fa fa-truck"  ></i></a></li>
      <li class="tabli tabli'.$gprs[$k]['imei'].'4" onClick="setTabs('.$gprs[$k]['imei'].',4)" '.$step15.'><a href="#tabs-2" title=""><i class="icon-engine"></i></a></li>
      <li class="tabli tabli'.$gprs[$k]['imei'].'5" onClick="setTabs('.$gprs[$k]['imei'].',5)" '.$step17.'><a href="#tabs-3" title=""><i class="fa fa-credit-card"  ></i></a></li>
    </ul>
 
   
    <div id="tabs_container" class="tabs_container container'.$gprs[$k]['imei'].'" style="display:none">
        <div id="tabs-1'.$gprs[$k]['imei'].'" class="opentabs"  style="display:none">
        <a class="closeButton" onclick="closeTabs('.$gprs[$k]['imei'].')"><i  class="fa fa-times"></i></a> 
        
            <p class="ptabs"> 
                <button onclick="'.$gprs[$k]['ResetButtonFunction']. '('.$gprs[$k]['imei']. ')" type="submit" class="btnt '. $gprs[$k]['ResetButtonClass']. '">RESET </button>
            </p>';
            if($gprs[$k]['engine_status'] == 1){
              //                                  <button onclick="'.$gprs[$k]['eng_block_function']. '('.$gprs[$k]['imei']. ')" imei="'.$gprs[$k]['imei']. '" function="'.$gprs[$k]['eng_block_function']. '" type="submit" class="blockengine btnt '. $gprs[$k]['eng_block_class']. '"><i class="icon-engine"  ></i>  </button>

                $html_left .= '<p class="ptabs"> 
                                  <button  name="'.$gprs[$k]['name']. '" imei="'.$gprs[$k]['imei']. '" function="'.$gprs[$k]['eng_block_function']. '" type="submit" class="blockengine btnt '. $gprs[$k]['eng_block_class']. '"><i class="icon-engine"  ></i>  </button>
                                </p>';
                            }
            if($gprs[$k]['marcha_status'] == 1){
                $html_left .= '<p class="ptabs"> 
                                  <button onclick="'.$gprs[$k]['starter_block_function']. '('.$gprs[$k]['imei']. ')" type="submit" class="btnt '. $gprs[$k]['starter_block_class']. '"><i class="icon-key"></i> </button>
                                </p>';
                            }
            if($gprs[$k]['elock_status'] == 1){
                $html_left .= '<p class="ptabs"> 
                <button onclick="'.$gprs[$k]['e_lock_function']. '('.$gprs[$k]['imei']. ')" type="submit" class="btnt '. $gprs[$k]['e_lock_class_btn']. '"><i class="fa fa-'.$gprs[$k]['e_lock_class'].' fa-lg" style="  color:'.$gprs[$k]['e_lock_color'].'"></i>  </button>
            </p>';
                            }
            
            
             
             $html_left .= '<hr class="hr">
            <p class="ptabs" >
              <p class="ptabs" '.$step6.'><button ><i onclick="get_direction('.$gprs[$k]['lat_lng']. ','.$gprs[$k]['imei'].')" class="fa fa-map-marker"></i></button><input class="  address'.$gprs[$k]['imei'].'" style="    width: 151px;
    padding: 3px 0px 1px 3px; 
    height: 13px;
    font-size: 11px;" placeholder="Obtener Dirección" type="text">
              ';
              if($gprs[$k]['sms_status'] == 0){
                              $html_left .=' <button   class="dirB bdir'.$gprs[$k]['imei'].'  tooltip" title="Esta funcion necesita contrato de sms"><i class="fa fa-mobile"></i></button>';

          }elseif ($gprs[$k]['sms_status']==1) {
                $html_left .=' <button onclick="openSmsBox('.$gprs[$k]['imei'].')" class="dirB bdir'.$gprs[$k]['imei'].'"><i class="fa fa-mobile"></i></button>';

          }


               //onclick="openEmailBox('.$gprs[$k]['imei'].')" 
             $html_left .='<button  class="dirB bdir'.$gprs[$k]['imei'].'"><i class="fa fa-envelope-o"></i></button><button class="dirB bdir'.$gprs[$k]['imei'].'"><i onclick="get_direction('.$gprs[$k]['lat_lng']. ','.$gprs[$k]['imei'].')" class="fa fa-refresh"></i></button></p>';
       
             $html_left .='<p class="ptabs sendBySms sendByEmail'.$gprs[$k]['imei'].'" style="display:none">
              <input class="smsinput coordenadas'.$gprs[$k]['imei'].'" type="text" placeholder="Ingresa un correo electrónico">
               <button onclick="sendCordsByEmail('.$gprs[$k]['imei'].','.$gprs[$k]['lat_lng']. ')" type="submit" class="submitB">
                  <i class="fa fa-caret-right"></i>  
                </button>
             </p>
      



';
$html_left .= '


<p class="ptabsSwicht"> 
<span class="alarmttitle">Alarma de Velocidad<br></span>
  <span class="onoffswitch" style="float:left">
    <input type="checkbox" name="onoffswitch"   imei="'.$gprs[$k]['imei'].'" class="onoffswitch-checkbox speedAlarmActive" id="myonoffswitch'.$gprs[$k]['imei'].'"  '.$gprs[$k]['speedAlarmActive'].'>
    <label class="onoffswitch-label" '.$step7.' for="myonoffswitch'.$gprs[$k]['imei'].'">
        <span class="onoffswitch-inner"></span>
        <span class="onoffswitch-switch"></span>
    </label>
</span> 
 
<input class="inputtabsSM" id="speed'.$gprs[$k]['imei'].'" '.$step8.'  placeholder="Alarma de Velocidad" type="text" value="'.$gprs[$k]['speedAlarm'].'">
<button style="margin-right:7px" type="submit" onClick="setSpeed('.$gprs[$k]['imei'].')" class="submitB">
  <i class="fa fa-caret-right"></i>  
</button>
</p>';
if($gprs[$k]['speed_status'] == 1){
 $html_left .='<p class="ptabsSwicht" style="border-left: 1px solid #333333;
    padding-left: 5px;"> 
    <span class="alarmttitle">Limite de Velocidad<br></span>
<span  class="onoffswitch" style="float:left">
    <input type="checkbox" name="onoffswitch"  imei="'.$gprs[$k]['imei'].'" class="onoffswitch-checkbox speedLimitActive" id="myonoffswitchspeed'.$gprs[$k]['imei'].'" '.$gprs[$k]['speedLimitActive'].'>
     <label class="onoffswitch-label" '.$step9.' for="myonoffswitchspeed'.$gprs[$k]['imei'].'">
        <span class="onoffswitch-inner"></span>
        <span class="onoffswitch-switch"></span>
    </label>
</span>
 
<input class="inputtabsSM" id="speedLimit'.$gprs[$k]['imei'].'" '.$step11.'  placeholder="Limite de Velocidad" type="text" value="'.$gprs[$k]['speedLimit'].'">
<button type="submit" onClick="setSpeedLimit('.$gprs[$k]['imei'].')" class="submitB">
  <i class="fa fa-caret-right"></i>  
</button>
</p>';
}
 $html_left .='<hr class="hr"> 
<p class="ptabs" '.$step9.'>';
$html_left .='
 <span class="alarmttitle">Reporte de Tiempo<br></span> 
  <select class="inputtabsSMtime setReportTime'.$gprs[$k]['imei'].'">
  <option value="1">1</option>
  <option value="2">2</option>
  <option value="3">3</option>
  <option value="4">4</option>
  <option selected="selected" value="5">5</option>
</select>
  <button onclick="setReportTime('.$gprs[$k]['imei'].')" type="submit" class="submitB">
    <i class="fa fa-caret-right"></i>  
  </button>
</p>
      </div> 
      <div id="tabs-2'.$gprs[$k]['imei'].'" style="display:none" class="alertsTab">
      <a class="closeButton" onclick="closeTabs('.$gprs[$k]['imei'].')"><i  class="fa fa-times"></i></a> 
          '.$gprs[$k]['alerts'].' 
      </div>

      <div id="tabs-3'.$gprs[$k]['imei'].'"  style="display:none">
      <a class="closeButton" onclick="closeTabs('.$gprs[$k]['imei'].')"><i  class="fa fa-times"></i></a> 
             
             <form method="post" class="formUnidad" id="formUnidad'.$gprs[$k]['imei'].'" method="post" enctype="multipart/form-data">
             <span '.$step14.'> </span>
             <div class="input50">
                <label  class="labelForm">Nombre</label>
                <input type="text" name="nombre_u" value="'.$gprs[$k]['nombre'].'">
             </div>

              <div class="input50">
              <label class="labelForm">Imagen</label>
             <input type="file" name="imagen_u" style="height: 22px; width: 116px; padding: 0; margin: 0;">
             </div>

              <div class="input50" style="height:35px">
              <label class="labelSwitch labelForm ">Activo</label>
              <span class="onoffswitch" style="float:left;     margin: 2px 0px 1px 0px;">
                  <input type="checkbox" name="active"  imei="'.$gprs[$k]['imei'].'" class="onoffswitch-checkbox" id="active'.$gprs[$k]['imei'].'"  '.$gprs[$k]['active'].' >
                  <label class="onoffswitch-label" for="active'.$gprs[$k]['imei'].'">
                      <span class="onoffswitch-inner"></span>
                      <span class="onoffswitch-switch"></span>
                  </label>
              </span> 
             </div>

              <div class="input50">
              <label class="labelForm">Odometro:</label>
             <input type="text" name="odometro" value="'.$gprs[$k]['odometro'].'">
             </div>

            <div class="input50">
            <label class="labelForm">Marca</label>
             <input type="text" name="marca" value="'.$gprs[$k]['marca'].'">
             </div>

              <div class="input50">
              <label class="labelForm">Modelo</label>
              <select name="modelo">
                <option value="1" '; if($gprs[$k]['modelo']==1){ $html_left.='selected';} $html_left.='>Sedan</option>
                <option value="2" '; if($gprs[$k]['modelo']==2){ $html_left.='selected';} $html_left.='>SUV</option>
                <option value="3" '; if($gprs[$k]['modelo']==3){ $html_left.='selected';} $html_left.='>Pick Up</option>
                <option value="4"'; if($gprs[$k]['modelo']==4){ $html_left.='selected';} $html_left.='>Camión de reparto</option>
                <option value="5"'; if($gprs[$k]['modelo']==5){ $html_left.='selected';} $html_left.='>Chasis</option>
                <option value="6"'; if($gprs[$k]['modelo']==6){ $html_left.='selected';} $html_left.='>Torton</option>
                <option value="7"'; if($gprs[$k]['modelo']==7){ $html_left.='selected';} $html_left.='>Motocicleta</option>
                <option value="8"'; if($gprs[$k]['modelo']==8){ $html_left.='selected';} $html_left.='>ATV</option>
                <option value="9"'; if($gprs[$k]['modelo']==9){ $html_left.='selected';} $html_left.='>Jetski</option>
                <option value="10"'; if($gprs[$k]['modelo']==10){ $html_left.='selected';} $html_left.='>Lancha</option>
                <option value="11"'; if($gprs[$k]['modelo']==11){ $html_left.='selected';} $html_left.='>Yate</option>
                <option value="12"'; if($gprs[$k]['modelo']==12){ $html_left.='selected';} $html_left.='>Taxi</option>
                <option value="13"'; if($gprs[$k]['modelo']==13){ $html_left.='selected';} $html_left.='>Autobus</option>
                <option value="14"'; if($gprs[$k]['modelo']==14){ $html_left.='selected';} $html_left.='>Tracto-Camión 5 rueda</option>
                <option value="15"'; if($gprs[$k]['modelo']==15){ $html_left.='selected';} $html_left.='>Mula</option>
                <option value="16"'; if($gprs[$k]['modelo']==16){ $html_left.='selected';} $html_left.='>Caja seca</option>
                <option value="17"'; if($gprs[$k]['modelo']==17){ $html_left.='selected';} $html_left.='>Caja refrigerada</option>
                <option value="18"'; if($gprs[$k]['modelo']==18){ $html_left.='selected';} $html_left.='>Lowboy</option>
                <option value="19"'; if($gprs[$k]['modelo']==19){ $html_left.='selected';} $html_left.='>Plataforma</option>
                <option value="20"'; if($gprs[$k]['modelo']==20){ $html_left.='selected';} $html_left.='>Redilas</option>
                <option value="21"'; if($gprs[$k]['modelo']==21){ $html_left.='selected';} $html_left.='>Tolva</option>
                <option value="22"'; if($gprs[$k]['modelo']==22){ $html_left.='selected';} $html_left.='>Pipa</option>
                <option value="0"'; if($gprs[$k]['modelo']==0){ $html_left.='selected';} $html_left.='>Otro</option>
              </select>
             </div>

              <div class="input50">
              <label class="labelForm">Año:</label>
             <input type="text" name="anio" value="'.$gprs[$k]['anio'].'">
             </div>

             <div class="input50">
              <label class="labelForm">Color:</label>
             <input type="text" name="color" value="'.$gprs[$k]['color'].'" >
             </div>

             <div class="input50">
              <label class="labelForm">Placas:</label>
             <input type="text" name="placas" value="'.$gprs[$k]['placa'].'">
             </div>

             <div class="input50">
              <label class="labelForm">Pasajeros:</label>
             <input type="text" name="pasajeros" value="'.$gprs[$k]['pasajeros'].'">
             </div>

             <div class="input50">
              <label class="labelForm">Rendimiento km/l:</label>
             <input type="text" name="rendimiento" value="'.$gprs[$k]['rendimiento'].'">
             </div>

             <div class="input50">
              <label class="labelForm">Peso Tara:</label>
             <input type="text" name="ptara" value="'.$gprs[$k]['pesotara'].'">
             </div>

             <div class="input50">
              <label class="labelForm">Ejes:</label>
             <input type="text" name="ejes" value="'.$gprs[$k]['ejes'].'">
             </div>

             <div class="input50">
              <label class="labelForm">No. Serie Chasis:</label>
             <input type="text" name="chasis" value="'.$gprs[$k]['chasis'].'">
             </div>

             <div class="input50">
              <label class="labelForm">Cap. de carga:</label>
             <input type="text" name="ccarga" value="'.$gprs[$k]['capcarga'].'">
             </div>
 
             <div class="input50">
              <label class="labelForm">Cap. de arrastre:</label>
             <input type="text" name="carrastre" value="'.$gprs[$k]['caparrastre'].'">
             </div>

             <div class="input50">
              <label class="labelForm">Tipo de carga:</label>
             <input type="text" name="tcarga" value="'.$gprs[$k]['tdcarga'].'">
             </div>

             <div class="input50" >
             <input type="hidden" name="imei" value="'.$gprs[$k]['imei'].'">
             <input type="hidden" name="action" value="saveUnidad">
             <input type="submit" class="saveUnidad savebtn"  imei="'.$gprs[$k]['imei'].'" id="saveUnidad'.$gprs[$k]['imei'].'" value="Guardar">
             </div>


             <div class="clear"></div>
              </form>
             
      </div>
      <div id="tabs-4'.$gprs[$k]['imei'].'"  style="display:none">
      <a class="closeButton" onclick="closeTabs('.$gprs[$k]['imei'].')"><i  class="fa fa-times"></i></a> 
         <form class="formEngine" id="formEngine'.$gprs[$k]['imei'].'">
             <div class="input50">
                <label>Rendimiento km/l</label>
                <input type="text" name="rendimientokl" value="'.$gprs[$k]['rendimientokl'].'">
             </div>

              
              <div class="input50">
              <label>Cilindros:</label>
             <input type="text" name="cilindros" value="'.$gprs[$k]['cilindros'].'">
             </div>

            <div class="input50">
            <label>Transmisión:</label>
             <input type="text" name="transmision" value="'.$gprs[$k]['transmision'].'">
             </div>

              

              <div class="input50">
              <label>Velocidades:</label>
             <input type="text" name="velocidades" value="'.$gprs[$k]['velocidades'].'">
             </div>

             <div class="input50">
              <label>Diferencial:</label>
             <input type="text" name="diferencial" value="'.$gprs[$k]['diferencial'].'">
             </div>

             <div class="input50">
              <label>No. Serie Motor:</label>
             <input type="text" name="seriemotor" value="'.$gprs[$k]['seriemotor'].'">
             </div> 
             <div class="input50" '.$step16.'>
             <input type="hidden" name="imei" value="'.$gprs[$k]['imei'].'">
             <input type="hidden" name="action" value="saveEngine">
             <input type="submit" class="saveEngine savebtn"  imei="'.$gprs[$k]['imei'].'" id="saveEngine'.$gprs[$k]['imei'].'" value="Guardar">
             </div>
             <div class="clear"></div>
              </form>
      </div>
      <div id="tabs-5'.$gprs[$k]['imei'].'"  style="display:none">
      <a class="closeButton" onclick="closeTabs('.$gprs[$k]['imei'].')"><i  class="fa fa-times"></i></a> 
        <form class="formMecanic" id="formMecanic'.$gprs[$k]['imei'].'">
             <div class="input50">
                <label class="labelForm">Seguro:</label>
                <input type="text" name="seguro" value="'.$gprs[$k]['seguro'].'">
             </div>

              
              <div class="input50">
              <label class="labelForm">Vencimiento:</label>
                <input style="width: 111px !important;" type="text" name="vencimiento" value="'.$gprs[$k]['vencimiento'].'" id="vencimiento'.$gprs[$k]['imei'].'"  imei="'.$gprs[$k]['imei'].'" placeholder="Vencimiento" class="fmDate" >
                 
             </div>

            <div class="input50">
            <label class="labelForm">Póliza de Seguro:</label>
             <input type="text" name="poliza" value="'.$gprs[$k]['poliza'].'">
             </div>

              <div class="input50">
            <label class="labelSwitch labelForm">Fisicomecánica</label>
              <span class="onoffswitch" style="float:left">
                  <input type="checkbox" name="onoffswitch"  imei="'.$gprs[$k]['imei'].'" class="onoffswitch-checkbox " id="fisicomecanica'.$gprs[$k]['imei'].'"  '.$gprs[$k]['fisicomecanica'].' >
                  <label class="onoffswitch-label" for="fisicomecanica'.$gprs[$k]['imei'].'">
                      <span class="onoffswitch-inner"></span>
                      <span class="onoffswitch-switch"></span>
                  </label>
              </span> 
              <input type="text"  name="fmDate" value="'.$gprs[$k]['fmDate'].'" id="fmDate'.$gprs[$k]['imei'].'"  imei="'.$gprs[$k]['imei'].'" placeholder="Vencimiento" class="fmDate" >
             </div>

             
             <div class="input50">
            <label class="labelSwitch labelForm">Tecnomecánica:</label>
              <span class="onoffswitch" style="float:left">
                  <input type="checkbox" name="onoffswitch"  imei="'.$gprs[$k]['imei'].'" class="onoffswitch-checkbox " id="tecnomecanica'.$gprs[$k]['imei'].'"  '.$gprs[$k]['tecnomecanica'].' >
                  <label class="onoffswitch-label" for="tecnomecanica'.$gprs[$k]['imei'].'">
                      <span class="onoffswitch-inner"></span>
                      <span class="onoffswitch-switch"></span>
                  </label>
              </span> 
              <input type="text" name="tcDate" value="'.$gprs[$k]['tcDate'].'" id="tcDate'.$gprs[$k]['imei'].'"  imei="'.$gprs[$k]['imei'].'" placeholder="Vencimiento" class="fmDate" >
             </div>

             <div class="input50">
            <label class="labelSwitch labelForm">Verificación ambiental:</label>
              <span class="onoffswitch" style="float:left">
                  <input type="checkbox" name="onoffswitch"  imei="'.$gprs[$k]['imei'].'" class="onoffswitch-checkbox " id="ambiental'.$gprs[$k]['imei'].'"   '.$gprs[$k]['ambiental'].'>
                  <label class="onoffswitch-label" for="ambiental'.$gprs[$k]['imei'].'">
                      <span class="onoffswitch-inner"></span>
                      <span class="onoffswitch-switch"></span>
                  </label>
              </span> 
              <input type="text" name="vaDate" value="'.$gprs[$k]['vaDate'].'" id="vaDate'.$gprs[$k]['imei'].'"  imei="'.$gprs[$k]['imei'].'" placeholder="Vencimiento" class="fmDate" >
             </div>

             <div class="input50">
            <label class="labelSwitch labelForm">C-TPAT:</label>
              <span class="onoffswitch" style="float:left">
                  <input type="checkbox" name="onoffswitch"  imei="'.$gprs[$k]['imei'].'" class="onoffswitch-checkbox " id="tpat'.$gprs[$k]['imei'].'"  '.$gprs[$k]['tpat'].' >
                  <label class="onoffswitch-label" for="tpat'.$gprs[$k]['imei'].'">
                      <span class="onoffswitch-inner"></span>
                      <span class="onoffswitch-switch"></span>
                  </label>
              </span> 
              <input type="text" name="ctDate" value="'.$gprs[$k]['ctDate'].'" id="ctDate'.$gprs[$k]['imei'].'"  imei="'.$gprs[$k]['imei'].'" placeholder="Vencimiento" class="fmDate" >
             </div>

             <div class="input50">
            <label class="labelSwitch labelForm">NEEC:</label>
              <span class="onoffswitch" style="float:left">
                  <input type="checkbox" name="onoffswitch"  imei="'.$gprs[$k]['imei'].'" class="onoffswitch-checkbox " id="neec'.$gprs[$k]['imei'].'"  '.$gprs[$k]['neec'].' >
                  <label class="onoffswitch-label" for="neec'.$gprs[$k]['imei'].'">
                      <span class="onoffswitch-inner"></span>
                      <span class="onoffswitch-switch"></span>
                  </label>
              </span> 
              <input type="text" name="neDate" value="'.$gprs[$k]['neDate'].'" id="neDate'.$gprs[$k]['imei'].'"  imei="'.$gprs[$k]['imei'].'" placeholder="Vencimiento" class="fmDate" >
             </div>
 
               <div class="input50">
                <label class="labelForm">DOF:</label>
                <input type="text" name="dof" value="'.$gprs[$k]['dof'].'">
             </div>

              
              <div class="input50">
              <label class="labelForm">US DOT:</label>
             <input type="text" name="usdot" value="'.$gprs[$k]['usdot'].'">
             </div>

            <div class="input50">
            <label class="labelForm">TXDOT:</label>
             <input type="text" name="txdot" value="'.$gprs[$k]['txdot'].'">
             </div>

             <div class="input50" '.$step18.'>
             <input type="hidden" name="imei" value="'.$gprs[$k]['imei'].'">
             <input type="hidden" name="action" value="saveMecanic">
             <input type="submit" class="saveMecanic savebtn"  imei="'.$gprs[$k]['imei'].'" id="saveMecanic'.$gprs[$k]['imei'].'" value="Guardar">
             </div>
             <div class="clear"></div>
              </form>
      </div>
    </div><!--End tabs container-->
    
  </div><!--End tabs-->
  </div><!--more info-->
 </td>

  </tr>';
  $tutorial=2;
   
  $html_left = str_replace("{L}",number_format($fuel_total,2)." L",$html_left);

  }
  }
}


$html_left  .= '</table>';




$result['gprs'] = $gprs;
$result['html_bottom'] = $html_bottom;
$result['html_left'] = $html_left;

echo json_encode($result);

function get_position($lat,$lng){
  return 0;
  $json_string = file_get_contents("http://www.geocodefarm.com/api/reverse/json/e609ccad34060a43a59a4351a8d28f1c2588c5e8/".$lat."/".$lng."/");
  $parsed_json = json_decode($json_string,true);
  return $parsed_json['geocoding_results']['ADDRESS'];

}

function is_duplicate($lat,$lng,$data){
  $idx = -1;
  for($i=0;$i<count($data);$i++){
    if($data[$i]['lat'] == $lat && $data[$i]['lng']==$lng){ $idx = $i; break; }
  }
  return $idx;
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
