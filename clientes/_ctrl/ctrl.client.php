<?php
session_start();
require_once('../_class/class.client.php');
require_once('../_class/class.asset.php');
$obj = new Client();
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
			  <td class="drop">**********</td>
			  <td class="drop">'.$date_exp.' '.$date_aux[1].'</td>';
	  if($_SESSION['logged']['type']==1){
	    $buffer .= '<td><a href="_view/frm.user_add.php?id='.$result[$i]['id'].'" class="modal fancybox.ajax">editar</a> <a href="javascript:void(0)" class="onRemoveUser" rel="'.$result[$i]['id'].'|'.$result[$i]['user'].'">eliminar</a></td>';
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
	  
	  <a href="http://maps.googleapis.com/maps/api/staticmap?center='.$pois[$i]['lat'].','.$pois[$i]['lng'].'&size=640x640&maptype=roadmap&sensor=false&scale=1&zoom=15&markers=icon:http://dev.norttrek.com/clientes/_img/poi.png|'.$pois[$i]['lat'].','.$pois[$i]['lng'].'" class="fancybox.image modal onGeoFenceMouseOver">
	  <img src="http://maps.googleapis.com/maps/api/staticmap?center='.$pois[$i]['lat'].','.$pois[$i]['lng'].'&size=122x72&maptype=roadmap&sensor=false&scale=1&zoom=7&markers=icon:http://dev.norttrek.com/clientes/_img/poi_s.png|'.$pois[$i]['lat'].','.$pois[$i]['lng'].'" style=" max-width:122px; border:#ccc solid 1px"/>
	  </a></td><td>'.$pois[$i]['poi'].'</td><td><a href="javascript:void(0)" class="onRemovePoi" rel="'.$pois[$i]['id'].'">eliminar</a></td></tr>';
	}
	$result = NULL;
	$result['poi'] = $pois;
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
	  $data = json_decode($result[$i]['data'],true);
		    $buffer .= '<tr>
            <td>
			  <a href="'.str_replace("430x250","640x640",$data['preview']).'" class="fancybox.image modal onGeoFenceMouseOver" rel="'.$data['lat'].'|'.$data['lng'].'|'.$data['radius'].'|'.$data['zoom'].'">
			    <img src="'.$data['preview'].'" style=" max-width:120px; border:#ccc solid 1px;"/>
			  </a>
			 </td>
			 <td>'.$result[$i]['name'].' <br /><strong>'.$cat.'</strong></td>
			 <td><a href="javascript:void(0)" rel="'.$result[$i]['id'].'" class="onRemoveGeofence">eliminar</a></td>
			</tr>';	  
	}
	echo $buffer;
  break;
  case "cmd":
    $cmd = NULL;
    switch($_POST['data']['option']){
	  case "A0": $cmd = "@@19A*615011,300,A,0#"; break;
	  case "A1": $cmd = "@@19A*615011,300,A,1#"; break;
	  case "B0": $cmd = "@@19A*615011,300,B,0#"; break;
	  case "B1": $cmd = "@@19A*615011,300,B,1#"; break;
	  case "R": $cmd = ""; break;
	}
    $obj->set_cmd($cmd)->set_id_client_user($_SESSION['logged']['id_user'])->set_imei($_POST['data']['imei'])->set_date_send(date("Y-m-d H:i:s"))->set_status(0)->db('insert_asset_cmd');
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
	  case "A0": $cmd = "@@19A*615011,300,A,0#"; break;
	  case "A1": $cmd = "@@19A*615011,300,A,1#"; break;
	  case "B0": $cmd = "@@19A*615011,300,B,0#"; break;
	  case "B1": $cmd = "@@19A*615011,300,B,1#"; break;
	  case "R": $cmd = ""; break;
	}
    $obj->set_cmd($cmd)->set_id_client_user($_SESSION['logged']['id_user'])->set_imei($_POST['data']['imei'])->set_date_send(date("Y-m-d H:i:s"))->set_status(0)->db('insert_asset_cmd');
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
	  print_r($notifications);
	  $objAsset->set_imei($settings[$i]['imei'])->set_notification(json_encode($notifications))->db('update_notifications');
	  
	  for($k=0;$k<count($codes);$k++){
	    $alarm = explode("|",$codes[$k]);
		$obj->set_id_client($_SESSION['logged']['id_client'])->set_imei($settings[$i]['imei'])->set_code($alarm[0])->set_value($alarm[1])->set_email($settings[$i]['email'])->db('insert_asset_alarm');
	  }
	}
	echo 123;
  break;
  
  
   case "update_asset_name": 
     $data = $obj->set_id_asset($_POST['id_asset'])->getClientAssets(); 
     $info = json_decode($data[0]['data'],true);
	 for($i=0;$i<count($info);$i++){ 
	   if($info[$i]['label']=="name"){ $info[$i]['value'] = $_POST['name']; }
	 } 	
     $obj->set_id_asset($_POST['id'])->set_data(json_encode($info))->db('update_asset_name');
  break;
  
  
  
  }
?>