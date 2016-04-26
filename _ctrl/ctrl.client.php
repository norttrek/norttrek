<?php
session_start();
require_once('../_class/class.client.php');
require_once('../_class/class.asset.php');
require_once('../_class/class.gprs.php');
require_once('../clientes/_firephp/FirePHP.class.php'); 
$mifirePHP = FirePHP::getInstance(true); 
$obj = new Client();
$gprs = new GPRS();
$objAsset = new Asset();
switch($_POST['exec']) {
  case "save_geofence": 
    $obj->set_id_client($_SESSION['logged']['id_client'])->set_name($_POST['data']['name'])->set_type($_POST['data']['type'])->set_data(json_encode($_POST['data']))->set_preview($_POST['data']['preview'])->set_category($_POST['data']['category'])->db('insert_geofence');
  break;
  
   case "remove_user": 
     $obj->set_id_user($_POST['data']['id'])->db('delete_user');
     $obj->set_id_user($_POST['data']['id'])->db('delete_asset_user');
  break;
  
   case "remove_group": $obj->set_id_group($_POST['data']['id'])->db('delete_group');
   break;
   
    case "remove_poi": $obj->set_id($_POST['id'])->db('delete_poi');
   break;
  
  case "remove_geofence":
    /* Remove the geofence catalog - Remove the geofence set to asset / remove the table of cotnrol (enter/exit)*/
    $obj->set_id_geofence($_POST['data']['id'])->db('delete_geofence');
	$obj->set_id_geofence($_POST['data']['id'])->db('delete_asset_geofence');
	$obj->set_id_geofence($_POST['data']['id'])->db('delete_asset_geofence_event');
  break;
  case "save_asset_geofence": 
    $obj->set_id_client($_SESSION['logged']['id_client'])->set_id_geofence($_POST['data']['id_geofence'])->set_imei($_POST['data']['imei'])->set_gf_enter($_POST['data']['enter'])->set_gf_exit($_POST['data']['exit'])->db('insert_asset_geofence');
  break;
  case "remove_asset_geofence": 
    $obj->set_id_geofence($_POST['data']['id_geofence'])->set_imei($_POST['data']['imei'])->db('delete_asset_geofence');
  break;

  
  case "save_user":
	$assets = $_POST['data']['assets'];
	$user_exists = $obj->set_user($_POST['data']['user'])->set_password($_POST['data']['password'])->user_exists();
	if($user_exists){ echo "0"; return false; }
	$date_exp = $_POST['data']['date_exp'];
	if($_POST['data']['date_exp']==NULL){ $date_exp = NULL; }
    $obj->set_id_client($_SESSION['logged']['id_client'])->set_user($_POST['data']['user'])->set_password($_POST['data']['password'])->set_date_exp($date_exp)->set_type(2)->db('insert_user');
	$lastInserted = $obj->getLastInserted();
	for($i=0;$i<count($assets);$i++){ $obj->set_id_user($lastInserted)->set_id_asset($assets[$i]['value'])->db('insert_asset_user'); }
	echo 202;
  break;
  
  case "save_poi":
    $data = $_POST['data'];
    $obj->set_id_client($_SESSION['logged']['id_client'])->set_poi($_POST['data']['name'])->set_lat($_POST['data']['lat'])->set_lng($_POST['data']['lng'])->set_status(1)->db('insert_poi');
  break;
  
  case "save_georoute":
    $total_dist = 0;
	$total_time = 0;
	$data = json_decode($_POST['data'],true);
	$route = NULL;
	for($i=0;$i<count($data);$i++){
	  $route[$i]['name'] = $data[$i][0];
	  $route[$i]['dist'] = $data[$i][1];
	  $route[$i]['time'] = $data[$i][2];
	  $route[$i]['lat_lng'] = $data[$i][3];
	  $route[$i]['r'] = 10; //radius
	  $total_dist += $route[$i]['dist'];
	  $total_time += $route[$i]['time'];
	}
	$obj->set_id_client($_SESSION['logged']['id_client'])->set_name($_POST['route'])->set_dist($total_dist)->set_time($total_time)->set_data(json_encode($data))->db('insert_georoute');
  break;
  
  case "update_user":
	$assets = $_POST['data']['assets'];
	$date_exp = $_POST['data']['date_exp'];
    $obj->set_id_user($_POST['data']['id'])->set_id_client($_SESSION['logged']['id_client'])->set_type(2)->set_user($_POST['data']['user'])->set_password($_POST['data']['password'])->set_date_exp($date_exp)->db('update_user');
	$obj->set_id_user($_POST['data']['id'])->db('delete_asset_user'); 
	for($i=0;$i<count($assets);$i++){ $obj->set_id_user($_POST['data']['id'])->set_id_asset($assets[$i]['value'])->db('insert_asset_user'); }
  break;
  
  case "update_pass": $obj->set_id($_SESSION['logged']['id_user'])->set_password($_POST['data']['password'])->db('update_pass');
  break;
  
  case "get_user": 
    $result = $obj->set_type($_POST['type'])->getUsersClient($_SESSION['logged']['id_client']);
	$buffer = '';
	for($i=0;$i<count($result);$i++){
	  
	  if($result[$i]['date_exp']=='0000-00-00 00:00:00'){
		$date_exp = '';
	  }else{
	    $date_aux = explode(" ",$result[$i]['date_exp']);
	    $date_exp = $obj->formatDate($date_aux[0],"min");
	  }
	  
	  
	  
	  $buffer .= '<tr>
              <td class="drop">'.$result[$i]['user'].'</td>
			  <td class="drop">****</td>
			  <td class="drop">'.$date_exp.' '.$date_aux[1].'</td>';
	  if($_SESSION['logged']['type']==1){
	    $buffer .= '<td><a class="editUser" ide="'.$result[$i]['id'].'" class="fancybox.ajax">editar</a> <a href="javascript:void(0)" class="onRemoveUser" rel="'.$result[$i]['id'].'|'.$result[$i]['user'].'">eliminar</a></td>';
	  }else{
	    $buffer .= '<td>&nbsp;</td>';
	  }
	}
	$buffer .= '</tr>';
	echo $buffer;
  break;
  
  /* GROUP */
  
  case "save_group": 
    $obj->set_id_client($_SESSION['logged']['id_client'])->set_group($_POST['data']['group'])->db('insert_group');
	$assets = $_POST['data']['assets'];
	$lastInserted = 1;
	for($i=0;$i<count($assets);$i++){ $obj->set_id_asset($assets[$i]['value'])->set_id_group($lastInserted)->db('update_asset_group'); }
  break;
  
  case "update_group": 
    $obj->set_id_group($_POST['data']['id'])->set_group($_POST['data']['group'])->db('update_group');
	$assets = $_POST['data']['assets'];
	for($i=0;$i<count($assets);$i++){ $obj->set_id_asset($assets[$i]['value'])->set_id_group($_POST['data']['id'])->db('update_asset_group'); }
  break;
  case "save_units_order": 
	$order = json_decode($_POST['data'],true);
	for($i=0;$i<count($order);$i++){ $obj->set_sort($i)->set_imei($order[$i])->db('update_sort'); }
  break;
  
  case "get_group": 
    $result = $obj->getClientGroups($_SESSION['logged']['id_client']);
	$buffer = '';
	for($i=0;$i<count($result);$i++){
	  $buffer .= '<tr><td class="drop">'.$result[$i]['group'].'</td><td><a href="_view/frm.group_add.php?id='.$result[$i]['id'].'" class="modal fancybox.ajax">editar</a> | <a href="javascript:void(0)" class="onRemoveGroup" rel="'.$result[$i]['id'].'|'.$result[$i]['group'].'">eliminar</a></td></tr>';
	}
	echo $buffer;
  break;
  
  case "get_pois": 
    $pois = $obj->set_id_client($_SESSION['logged']['id_client'])->getPois();
	$buffer = '';
	for($i=0;$i<count($pois);$i++){
	  $buffer .= '<tr><td>
	  
	  <a href="http://maps.googleapis.com/maps/api/staticmap?center='.$pois[$i]['lat'].','.$pois[$i]['lng'].'&size=640x640&maptype=roadmap&sensor=false&scale=1&zoom=15&markers=icon:http://dev.norttrek.com/clientes/_img/poi.png|'.$pois[$i]['lat'].','.$pois[$i]['lng'].'" class="fancybox.image   onGeoFenceMouseOver">
	  <img src="http://maps.googleapis.com/maps/api/staticmap?center='.$pois[$i]['lat'].','.$pois[$i]['lng'].'&size=122x72&maptype=roadmap&sensor=false&scale=1&zoom=7&markers=icon:http://dev.norttrek.com/clientes/_img/poi_s.png|'.$pois[$i]['lat'].','.$pois[$i]['lng'].'" style=" max-width:122px; border:#ccc solid 1px"/>
	  </a></td><td>'.$pois[$i]['poi'].'</td><td><a href="javascript:void(0)" class="onRemovePoi" rel="'.$pois[$i]['id'].'">eliminar</a></td></tr>';
	}
	$result = NULL;
	$result['poi'] = $pois;
	$result['html'] = $buffer;
	echo json_encode($result);
  break;

  case "get_tracks": 
    $tracks = $obj->set_id_client($_SESSION['logged']['id_client'])->getTracks();
	$buffer = '';
	for($i=0;$i<count($tracks);$i++){ 
		$kms = $tracks[$i]['tolerancia'] / 1000;
		$buffer .= "<tr><td class='rowtrack'>";
	  	$buffer .= "<p>Nombre: ".$tracks[$i]['track_name']."</p>";
	  	$buffer .= "<p>Tolerancia: ".$tracks[$i]['tolerancia']."</p>";
	  	$buffer .= "<p>Distancia Total: ".$kms."</p>";
	  	$buffer .=" <img src='".$tracks[$i]['image']."'><button ide='".$tracks[$i]['id']."' id='deltrack'>Eliminar</button></td> </tr>";

 	}
	$result = NULL;
	$result['html'] = $buffer; 
	echo json_encode($result);
  break;


  
  case "get_geofences": 
	$result = $obj->set_id_client($_SESSION['logged']['id_client'])->getClientGeofence();
	$buffer = '';
	for($i=0;$i<count($result);$i++){
	  $cat = NULL;
	  switch($result[$i]['category']){
	    case "zr": $cat = 'Zona de Riesgo'; break;
		case "zs": $cat = 'Zona Segura'; break;
		case "cli": $cat = 'Cliente'; break;
		case "base": $cat = 'Base'; break;
	  }
	  //$mifirePHP->log($result[$i]['data'],'result i data');
	  $data = json_decode($result[$i]['data'],true);
	  $data2 = json_encode($result[$i]['data'],true);
	  //$mifirePHP->log($data,'$data'); 
	  //$mifirePHP->log($result[$i],'$result[$i]');
	  
		    $buffer .= '<tr>
            <td> 
			  <a href="'.str_replace("430x250","640x640",$data['preview']).'" class="fancybox.image   onGeoFenceMouseOver" rel="'.$data['lat'].'|'.$data['lng'].'|'.$data['radius'].'|'.$data['zoom'].'">
			    <img src="'.$data['preview'].'" style=" max-width:120px; border:#ccc solid 1px;"/>
			  </a>
			 </td>
			 <td>'.$result[$i]['name'].' <br /><strong>'.$cat.'</strong>
			 <br><a href="javascript:void(0)" rel="'.$result[$i]['id'].'" class="onRemoveGeofence"><i style="color:red" class="fa fa-times fa-lg"></i></a>
			 </td>
			 
			</tr>';	  
	}
	echo $buffer;
  break;

  case "get_geofences_tab": 
	$result = $obj->set_id_client($_SESSION['logged']['id_client'])->getClientGeofence();
	$buffer = '';
	
	
    $alarms = $gprs->getGeoAlerts($_POST['imei']); 
    
    $alarms = json_decode($alarms[0]['geo_alarms'],true);
    $mifirePHP->log($alarms,'aaa');
	for($i=0;$i<count($result);$i++){
	  $cat = NULL;
	  switch($result[$i]['category']){
	    case "zr": $cat = 'Zona de Riesgo'; break;
		case "zs": $cat = 'Zona Segura'; break;
		case "cli": $cat = 'Cliente'; break;
		case "base": $cat = 'Base'; break;
	  }
	  //$mifirePHP->log($result[$i]['data'],'result i data');
	  $data = json_decode($result[$i]['data'],true);
	  $data2 = json_encode($result[$i]['data'],true);
	  //$mifirePHP->log($data,'$data');
	  
	  //$mifirePHP->log($result[$i],'$result[$i]');
	  if($alarms[$result[$i]['id']]['in'] == 1){
	  	$chekin = "checked";
	  }else{
	  	$chekin = "";
	  }
	  if($alarms[$result[$i]['id']]['out'] == 1){
	  	$chekout = "checked";
	  }else{
	  	$chekout = "";
	  }
		    $buffer .= '<tr> 
			 <td>Nombre: '.$result[$i]['name'].' <br /><strong>Tipo: '.$cat.'</strong></td>
			  <td><input id="geotab" id_geo="'.$result[$i]['id'].'" '.$chekin.'  imei="'.$_POST['imei'].'" type="checkbox" name="entrada" value="in"> Entrada 
 		      <input id="geotab" id_geo="'.$result[$i]['id'].'" '.$chekout.' imei="'.$_POST['imei'].'" type="checkbox" name="salida" value="out"> Salida 
			 </td>
			 
			</tr>';	  
	}
	echo $buffer;
  break;
 
  case "get_tracks_tabs":

    $tracks = $obj->set_id_client($_SESSION['logged']['id_client'])->getTracks();
     
	$buffer = '';

	$Activetrack = $gprs->getActiveTracks($_POST['imei']); 
	 
	$mifirePHP->log($Activetrack,'activas');

	$Activas = json_decode($Activetrack[0]['route_alarms'],true);
	$mifirePHP->log($Activas,'ActivasActivasActivas');
	for($i=0;$i<count($tracks);$i++){ 
		if($Activas[$tracks[$i]['id']] == 1){
			$class="activeAlarm";
			$active=0;
			$text = "Desactivar";
		}else{
			$active = 1;
			$class="";
			$text = "Activar";
		}
		$kmstotal = round($tracks[$i]['total_mts'] / 1000);
		$buffer .= "<tr><td class='rowtrack'>";
	  	$buffer .= "<p>Nombre: ".$tracks[$i]['track_name']."</p>";
	  	$buffer .= "<p>Distancia Total: ".$kmstotal." kms</p></td>";
	  	$buffer .=" <td valign='middle' style='text-align:center'><button style='width:80px' class='".$class." track".$tracks[$i]['id']."' active='".$active."' ide='".$tracks[$i]['id']."' imei='".$_POST['imei']."' id='Activetrack'>".$text."</button></td> </tr>";

 	} 
	  
	$result = NULL;
	$result['html'] = $buffer; 
	echo json_encode($result);
  break;
  case "get_checkpoints": 
	$result = $obj->set_id_client($_SESSION['logged']['id_client'])->getClientGeoroute();
	$buffer = '';
	for($i=0;$i<count($result);$i++){
	  $data = json_decode($result[$i]['data'],true);
		    $buffer .= '<tr>
             <td>'.$result[$i]['name'].'</td>
			 <td>'.$result[$i]['dist'].'</td>
			 <td>'.$result[$i]['time'].'</td>
			 <td><a href="javascript:void(0)" rel="'.$result[$i]['id'].'" class="onRemoveGeoRoute">eliminar</a></td>
			</tr>';	  
	}
	echo $buffer;
  break;
  case "cmd":
    $cmd = NULL;
    switch($_POST['data']['option']){
	  case "A0": $cmd = "@@19A*615011,016,A,0#"; break;
	  case "A1": $cmd = "@@19A*615011,016,A,1#"; break;
	  case "B0": $cmd = "@@19A*615011,016,B,0#"; break;
	  case "B1": $cmd = "@@19A*615011,016,B,1#"; break;
	  case "C0": $cmd = "$$0024CF000000,016,C,06B"; break;
	  case "C1": $cmd = "$$0024CF000000,016,C,16A"; break;
	  case "R": $cmd = ""; break;
	}
    $obj->set_cmd($cmd)->set_id_client_user($_SESSION['logged']['id_user'])->set_imei($_POST['data']['imei'])->set_date_send(date("Y-m-d H:i:s"))->set_status(0)->db('insert_asset_cmd');
	echo $cmd;
  break;
  
  case "set_alarms":
	$settings = $_POST['data'];
	for($i=0;$i<count($settings);$i++){
	  $codes = explode(",",$settings[$i]['settings']);
	  array_pop($codes);
	  $obj->set_imei($settings[$i]['imei'])->db('reset_asset_alarm');
	  $asset = $objAsset->getAssetByIMEI($settings[$i]['imei']);
	  
	  $notifications[0] = NULL;
	  $notifications[1] = NULL;
	  $notifications[2] = NULL;
	  $notifications = json_decode($asset[0]['notification'],true);
	  
	  $notifications[$_POST['o']] = $settings[$i]['email'];
	  $objAsset->set_imei($settings[$i]['imei'])->set_notification(json_encode($notifications))->db('update_notifications');
	  
	  for($k=0;$k<count($codes);$k++){
	    $alarm = explode("|",$codes[$k]);
		$obj->set_id_client($_SESSION['logged']['id_client'])->set_imei($settings[$i]['imei'])->set_code($alarm[0])->set_value($alarm[1])->set_email($settings[$i]['email'])->db('insert_asset_alarm');
	  }
	}
  break;
  
  case "update_settings_temp":
    $settings = NULL;
    $settings["temp"] = $_POST['value'];
	$obj->set_id_client($_SESSION['logged']['id_client'])->set_settings(json_encode($settings))->db('insert_settings');
	$_SESSION['logged']['temp'] = $_POST['value'];
  break;

  case "set_geofences":
	$data = $_POST['data'];
	$gc = $_POST['gc'];
	$imeis = '';
	for($i=0;$i<count($data);$i++){ 
	  $imeis = $imeis.",".(string)$data[$i]['imei']; 
	  $asset = $objAsset->getAssetByIMEI($data[$i]['imei']);
	  $notifications[0] = NULL;
	  $notifications[1] = NULL;
	  $notifications[2] = NULL;
	  $notifications = json_decode($asset[0]['notification'],true);
	  $notifications[$_POST['o']] = $data[$i]['email'];
	  $objAsset->set_imei($data[$i]['imei'])->set_notification(json_encode($notifications))->db('update_notifications');
	}
	
	$imeis = substr($imeis, 1);
	$objAsset->set_in($imeis)->db('remove_geofences');
	// meter los nuevos
	for($i=0;$i<count($gc);$i++){ 
	  $imeis = $imeis.",".(string)$data[$i]['imei']; 
	  $obj->set_id_client($_SESSION['logged']['id_client'])->set_id_geofence($gc[$i]['id'])->set_imei($gc[$i]['imei'])->set_gf_enter($gc[$i]['enter'])->set_gf_exit($gc[$i]['exit'])->db('insert_asset_geofence');
	}
   break;
  
  
   case "update_asset_name": $obj->set_id_asset($_POST['id'])->set_alias($_POST['name'])->db('update_asset_name');
  break;
  
  case "update_asset_client": 
	 $details['marca'] = $_POST['data'][1]['value'];
	 $details['modelo'] = $_POST['data'][2]['value'];
	 $details['ano'] = $_POST['data'][3]['value'];
	 $details['no_serie'] = $_POST['data'][4]['value'];
	 $details['placas'] = $_POST['data'][5]['value'];
	 $details['color'] = $_POST['data'][6]['value'];
	 $details['odometro'] = $_POST['data'][7]['value'];	
     $obj->set_id_asset($_POST['id'])->set_alias($_POST['data'][0]['value'])->db('update_asset_name');
	 $obj->set_id_asset($_POST['id'])->set_info(json_encode($details))->db('update_asset_info');
  break;
  
  
  
  }
?>