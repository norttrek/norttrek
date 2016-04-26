<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 'On');
$id_client = $_SESSION['logged']['id_client'];
require_once('_firephp/FirePHP.class.php');
 $mifirePHP = FirePHP::getInstance(true);
 
// aad ago 2015
if($id_client == null){ 
  exit(1);
}

require_once("../_class/class.client.php");
require_once("../_class/class.asset.php");
require_once("../_class/class.gprs.php");
require_once("../_functions/eventCode.status.php");
 $footer_content = array();
$objAsset = new Asset();
$objGPRS = new GPRS();
$objClient = new Client();
function getDistance($latitude1, $longitude1, $latitude2, $longitude2) {
    $earth_radius = 6371;

    $dLat = deg2rad($latitude2 - $latitude1);
    $dLon = deg2rad($longitude2 - $longitude1);

    $a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * sin($dLon/2) * sin($dLon/2);
    $c = 2 * asin(sqrt($a));
    $d = $earth_radius * $c;

    return $d;

}
$pois = $objClient->set_id_client($id_client)->getPois();
if($_SESSION['logged']['type']==2){ $id_user = $_SESSION['logged']['id_user']; }
$assets = $objAsset->set_id_client($id_client)->set_order('sort,client_group.group ASC')->set_id_user($id_user)->set_id_device('0')->getAsset();
$assets_groups = $objAsset->set_order('client_group.group ASC')->getAssetGroups($id_client);

$pois = $objClient->set_id_client($id_client)->getPois();
$result = NULL;
$client_info = $objClient->getClientInfo($_SESSION['logged']['id_client']);
$gprs = NULL; 
$gprs_reports = array();
for($i=0;$i<count($assets);$i++){
  $data = json_decode($assets[$i]['data'],true);
 
  $alerts = $objGPRS->getLastAlerts($assets[$i]['imei']);

 
  }
for($i=0;$i<count($assets_groups);$i++){
    echo "<div class='col-md-12 grupo'>" . $assets_groups[$i]['group'] . "</div>";
    for($k=0;$k<count($assets);$k++){

      $gprs_data = $objGPRS->set_status(NULL)->getLastReport($assets[$k]['imei']);
      
      
      if($gprs_data[0]['v2_eng_status']==1 && $gprs_data[0]['v2_speed'] >= 2){
        $gprs_data[0]['icon_color'] = "#2079EC";
        }elseif ($gprs_data[0]['v2_eng_status']==0) {
         $gprs_data[0]['icon_color'] = "#818181";
        }elseif ($gprs_data[0]['v2_eng_status']==1 && $gprs_data[0]['v2_speed'] < 2) {
          $gprs_data[0]['icon_color'] = "#EA7C06";
      }
      $gprs_data[0]['name'] = $assets[$k]['alias'];
       
      
      // $alert = $objGPRS->getLastAlert($gprs_data[0]['id']);
      $event = eventCodeStatus($gprs_data[0]['v2_eventCode'],$gprs_data[0]['v2_sos']);
      $eventN = $event['icono'] . " ". $event['nombre'];
      $alert = $event['alert'];
       $gprs_data[0]['alert'] =$event['alert'];
      if($assets_groups[$i]['id']==$assets[$k]['id_group']){
        $power = 'Apagado';
        $color = '#818181'; 
        if($gprs_data[0]['v2_eng_status']==1){ $color = "#1cf100"; $power= 'Encendido'; }
          //PREPARA VARIABLES POR UNIDAD 
          include("unidades/variablesunidades.php");
          $motor_icono = '<i class="fa fa-power-off " style="color:'. $color .'"></i>';
        ?>
      <div class="col-md-12 unidad" id="unidad<?php echo $assets[$k]['imei']; ?>">
        <div class="row headerRow" >
        <div class="col-md-6 headUnidad"> <i class="fa fa-power-off " style="color:<?php echo $color ?>"></i>
            <a  title="<?php echo $assets[$k]['alias'] ?>" href="javascript:objTrack.go_to(<?php echo $latLang ?>)"
              rel="<?php echo $assets[$k]['id'] ?>" class="onClickOpenOverlaye" style=" color:#fff"><strong><?php echo $assets[$k]['alias'] ?></strong>
            </a></div>
        <div class="col-md-6 text-right headUnidad"> <?php 
              if($assets[$k]['engine'] == 1){
              echo '<i class="icon-engine" style="color:'.$eng_block_color.'"></i> ';
              
              }
              if($assets[$k]['marcha'] == 1){
              echo '<i class="icon-key" style="  color:'.$starter_block_color.'"></i>';
              }
              if($assets[$k]['elock'] == 1){
              echo '<i class="fa fa-'.$e_lock_class.' " style="  color:'.$e_lock_color.'"></i>';
              }        
              ?>
              <?php echo $alert ?>
                <i class=" <?php echo $lb_class ?>" style="cursor:pointer; color: <?php echo $lb ?>" data-toggle="tooltip" data-placement="top" title=" <?php echo $battery ?>"></i>
                <i class="icon-volt" style="cursor:pointer;  color: <?php echo $ps ?>" data-toggle="tooltip" data-placement="top" title="<?php echo $volt ?>"></i>
                <i class="icon-signal<?php echo $rssi ?>"></i>
              </div>
              </div>
        <div class="row">
        <div class="col-md-4"><?php echo $profile_image ?></div>
        <div class="col-md-8 text-right">
                <i class="fa fa-clock-o "  ></i>   </span> <?php echo $timeago ?>
                <i class="fa fa-tachometer " style="margin-left:10px;"></i> <?php echo round($gprs_data[0]['v2_speed']) ?> km 
                <i style="margin-left: 8px; font-size: 30px; color: #2079EC; margin-top: 2px; color:<?php  echo $icon_color ?>" class="fa fa-arrow-circle-o-up   fa-rotate-<?php echo $grados ?>"></i>
              </div>
            </div>
            <div class="row">
        <div class="col-md-9"><?php echo $tanques ?></div>
        <div class="col-md-3" style="padding:0"><?php echo $temp ?></div>
        </div>
        <div class="row">
        <div class="col-md-12 tabsUnidad">
            <ul class="nav tabnav nav-tabs" role="tablist">
    <li role="presentation"><a data-collapse-group="myDivs"  href="#home<?php echo $assets[$k]['imei'];?>" aria-controls="home" role="tab" data-toggle="tab"><i class="fa fa-cogs"  ></i></a></li>
    <li role="presentation"><a data-collapse-group="myDivs" href="#profile<?php echo $assets[$k]['imei'];?>" imei="<?php echo $assets[$k]['imei'];?>"  class="alertTabs" aria-controls="profile" role="tab" data-toggle="tab"><i class="glyphicon glyphicon-list"  ></i></a></li>
    <li role="presentation"><a data-collapse-group="myDivs"  href="#messages<?php echo $assets[$k]['imei'];?>" aria-controls="messages" role="tab" data-toggle="tab"><i class="fa fa-truck"  ></i></a></li>
    <li role="presentation"><a data-collapse-group="myDivs"  href="#settings<?php echo $assets[$k]['imei'];?>" aria-controls="settings" role="tab" data-toggle="tab"><i class="icon-engine"></i></a></li>
    <li role="presentation"><a data-collapse-group="myDivs"  href="#credenciales<?php echo $assets[$k]['imei'];?>" aria-controls="settings" role="tab" data-toggle="tab"><i class="fa fa-credit-card"  ></i></a></li>
    <li role="presentation"><a data-collapse-group="myDivs"  href="#alertas<?php echo $assets[$k]['imei'];?>" aria-controls="settings" role="tab" data-toggle="tab"><i class="fa fa-warning"  ></i></a></li>
    <li role="presentation"><a data-collapse-group="myDivs"  imei="<?php echo $assets[$k]['imei'];?>" class="geocercastab" href="#geocercastab<?php echo $assets[$k]['imei'];?>" aria-controls="settings" role="tab" data-toggle="tab"><i class="glyphicon glyphicon-record"  ></i></a></li>
    <li role="presentation"><a data-collapse-group="myDivs"  imei="<?php echo $assets[$k]['imei'];?>" class="tracksBtnTab" href="#georutastab<?php echo $assets[$k]['imei'];?>" aria-controls="settings" role="tab" data-toggle="tab"><i class="glyphicon glyphicon-road"  ></i></a></li>

  </ul>

 
  <!-- Tab panes -->
  <div class="tab-content">
    <div role="tabpanel" class="tab-pane" id="home<?php echo $assets[$k]['imei'];?>"><?php include("unidades/tab1.php"); ?></div>
    <div role="tabpanel" class="tab-pane"  id="profile<?php echo $assets[$k]['imei'];?>"><?php include("unidades/tab2.php"); ?></div>
    <div role="tabpanel" class="tab-pane"  id="messages<?php echo $assets[$k]['imei'];?>"><?php include("unidades/tab3.php"); ?></div>
    <div role="tabpanel" class="tab-pane"  id="settings<?php echo $assets[$k]['imei'];?>"><?php include("unidades/tab4.php"); ?></div>
    <div role="tabpanel" class="tab-pane"  id="credenciales<?php echo $assets[$k]['imei'];?>"><?php include("unidades/tab5.php"); ?></div>
    <div role="tabpanel" class="tab-pane alerts"  id="alertas<?php echo $assets[$k]['imei'];?>"><?php include("unidades/tab6.php"); ?></div>
    <div role="tabpanel" class="tab-pane alerts"  id="geocercastab<?php echo $assets[$k]['imei'];?>"><?php include("unidades/tab7.php"); ?></div>
    <div role="tabpanel" class="tab-pane alerts"  id="georutastab<?php echo $assets[$k]['imei'];?>"><?php include("unidades/tab8.php"); ?></div>
  </div>
        </div>
        </div>
      </div>

        <?php
        
         $coordenadas = $gprs_data[0]['lat'] . "," .$gprs_data[0]['lng'];
        

         $poi_distance = NULL;

         foreach ($pois as $poi) {
           $poi_distance = number_format(getDistance($poi['lat'],$poi['lng'],$gprs_data[0]['lat'],$gprs_data[0]['lng']),2);
           $referencia = $poi['poi'] . ' &rarr; ' . $poi_distance ." km";
         }
         asort($poi_distance);
         $close_idx = key($poi_distance);
         $footer_unidad = array('nombre'=>$assets[$k]['alias'],
          'motor'=> $motor_icono,
          'evento'=>$eventN,'velocidad'=>$gprs_data[0]['v2_speed'],
          'fecha'=>$gprs_data[0]['date'],
          'coordenadas'=>$coordenadas,
          'referencia'=>$referencia,
          'orientacion'=>'<i style="margin-left: 8px; font-size: 15px; color: #2079EC; margin-top: 2px; color:'. $icon_color .'" class="fa fa-arrow-circle-o-up   fa-rotate-'.$grados.' "></i>
');
        array_push($footer_content, $footer_unidad);
      }
    }

  
}

?>
