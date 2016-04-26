<?php

require_once("class.helper.php");

class Asset extends Helper {
  var $id;
  var $id_user;
  var $id_client;
  var $id_device;
  var $id_group;
  var $imei;
  var $no;
  var $alias;
  var $date_reg;
  var $icon;
  var $data;
  var $notification;
  var $status;
  
  /* GROUP */
  var $group;
  
  /* USER */
  var $user;
  var $password;
  
    
  public function __construct(){ $this->sql = new db(); }
  
  public function db($key){
    switch($key){
  	  case "insert": 
	    $query = "INSERT INTO cliente_unidad (id_cliente,id_equipo,id_grupo,imei,alias,info,icono,fecha_registro,status) 
		          VALUES ('".$this->id_cliente."',
				  		  '".$this->id_equipo."',
						  '".$this->id_grupo."',
						  '".$this->id_staff."',
						  '".$this->imei."',
						  '".$this->alias."',
						  '".$this->info."',
						  '".$this->icono."',
						  '".$this->fecha_registro."',
						  '".$this->status."')";
	  break;
	 
	  /* GROUP */
	  case "insert_group": $query = "INSERT INTO client_group (id_client,group,status) VALUES ('".$this->id_client."','".$this->group."','".$this->status."')";
	  break;
	  case "update_group": $query = "UPDATE client_group SET group = '".$this->id_staff."' WHERE id='".$this->id."'"; 
      break;
	  
	  /* USER */
	  case "insert_user": $query = "INSERT INTO client_user (user,password,status) VALUES ('".$this->user."','".$this->password."','".$this->status."')";
	  break;
	  case "update_user": $query = "UPDATE client_user SET user = '".$this->user."', password = '".$this->password."' WHERE id='".$this->id."'"; 
      break;

	  
	  case "update": $query = "UPDATE unidad SET 
	                                             id_equipo = '".$this->id_equipo."',
												 id_staff = '".$this->id_staff."',
												 imei = '".$this->imei."',
												 alias = '".$this->alias."',
												 info = '".$this->info."', 
												 WHERE id='".$this->id."'"; 
      break;
	  case "update_icon": $query = "UPDATE client_asset SET icon = '".$this->icon."' WHERE id ='".$this->id."'"; 
      break;
	  
	  case "update_notifications":  $query = "UPDATE client_asset SET notification = '".$this->notification."' WHERE imei ='".$this->imei."'"; 
      break;
      case "delete": $query = "UPDATE client_asset SET status = 3 WHERE id='".$this->id."'";
      break;
	  case "remove_geofences":  $query = "DELETE FROM client_asset_geofence WHERE imei IN(".$this->in.")";
      break;
	  
	  
	  
    }
	$this->execute($query); 
    if($key=="insert"){ $this->lastInserted = mysql_insert_id(); }
  }  
  public function get_lts($imei){
  	$query ="SELECT fuel FROM client_asset WHERE imei = '".$imei."'";
  	return $this->execute($query);
  }
  public function getAsset($id = NULL){
    $query = 'SELECT client_asset.*, client_group.group  
              FROM client_asset 
              LEFT JOIN client ON (client_asset.id_client = client.id) 
			  LEFT JOIN device ON (client_asset.id_device = device.id) 
			  LEFT JOIN client_group ON (client_asset.id_group = client_group.id) 
			  WHERE client_asset.id > 0';
    if($id!=NULL) $query.=" AND client_asset.id=".$id."";
	if($this->imei!= NULL) $query.=" AND client_asset.imei=".$this->imei."";
	if($this->id_device!=NULL) $query .= " AND client_asset.id_device='".$this->id_device."'"; 
	if($this->id_client!=NULL) $query .= " AND client_asset.id_client=".$this->id_client; 
    if($this->status!=NULL) $query .= " AND unidad.status=".$this->status; 
	if($this->id_user!=NULL) $query .= ' AND client_asset.id IN (SELECT id_asset FROM client_asset_user WHERE id_user='.$this->id_user.')';
	if($this->search!=NULL) $query .= " AND ".$this->search_field." LIKE '%".$this->search."%'";
	if($this->order!=NULL) $query .= " ORDER BY ".$this->order; 
	if($this->limit!=NULL) $query .= " LIMIT ".$this->limit;
	return $this->execute($query); 
  }
  
   public function getAssetByIMEI($imei = NULL){
    $query = 'SELECT client_asset.*, client_group.group  
              FROM client_asset 
              LEFT JOIN client ON (client_asset.id_client = client.id) 
			  LEFT JOIN device ON (client_asset.id_device = device.id) 
			  LEFT JOIN client_group ON (client_asset.id_group = client_group.id) 
			  WHERE client_asset.id > 0';
    if($imei!=NULL) $query.=" AND client_asset.imei=".$imei."";
	if($this->id_client!=NULL) $query .= " AND client_asset.id_client=".$this->id_client; 
	if($this->id_device!=NULL) $query .= " AND client_asset.id_device=".$this->id_device; 
    if($this->status!=NULL) $query .= " AND unidad.status=".$this->status; 
	if($this->search!=NULL) $query .= " AND ".$this->search_field." LIKE '%".$this->search."%'";
	if($this->order!=NULL) $query .= " ORDER BY ".$this->order; 
	if($this->limit!=NULL) $query .= " LIMIT ".$this->limit;
	return $this->execute($query); 
  }
  
  public function getNameByImei($imei){
	$query = 'SELECT alias FROM client_asset WHERE imei = '.$imei.' LIMIT 1';
	$result = $this->execute($query); 
	return $result[0]['alias'];
  }

  public function getAssetsFieldsByUser($id,$fields){
  	$fields_query ='';
  	foreach ($fields as $key => $name) {
  		$fields_query .= $name . ',';
  	}
  	$fields_query = trim($fields_query, ',');
 
	$query = 'SELECT ' . $fields_query. ' FROM client_asset WHERE id_client = '.$id;
	$result = $this->execute($query); 
	return $result;
  }

  public function getLastResetTime($imei){
	$query = 'SELECT LastResetTime FROM client_asset WHERE imei = '.$imei.' LIMIT 1';
	$result = $this->execute($query); 
	return $result ;
  }
  
  public function getSensorByImei($imei){
	$query = 'SELECT sensor FROM client_asset WHERE imei = '.$imei.' LIMIT 1';
	$result = $this->execute($query); 
	return $result[0]['alias'];
  }
  
  
  
  
  public function getOdometer($imei,$sdate){
	$query = 'SELECT date,lat,lng FROM gprs WHERE lat != 0 AND lng != 0 AND date < "'.$sdate.'" AND  imei = '.$imei;
	$result = $this->execute($query);
	$km = 0;
	for($i=0;$i<count($result);$i++){ $km += $this->getDistance($result[$i]['lat'],$result[$i]['lng'],$result[$i+1]['lat'],$result[$i+1]['lng']); }
	return $km;
  }
  
  public function getDistance($lat1, $lng1, $lat2, $lng2) {  
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
  
  
   public function getUserAssets($id_user = NULL){
	$query = 'SELECT id_asset FROM client_asset_user WHERE id_user ="'.$id_user.'"';
	$result = $this->execute($query); 
	$buffer = NULL;
	for($i=0;$i<count($result);$i++){ $buffer[$i] = $result[$i]['id_asset']; }
	return $buffer; 
  }
  
  public function getAssetGroups($id_client = NULL){
	$query = 'SELECT * FROM client_group WHERE id_client="'.$id_client.'"';
    if($this->status!=NULL) $query .= " AND status=".$this->status; 
	if($this->order!=NULL) $query .= " ORDER BY ".$this->order; 
	return $this->execute($query); 
  }
 
  
}
?>