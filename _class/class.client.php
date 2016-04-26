<?php

require_once("class.helper.php"); 
class Client extends Helper {
  var $id;
  var $no_client;
  var $client;
  var $pin;
  var $private;
  var $date_reg;
  var $status;
  var $sort;
  var $parent;
  
  var $park;
  var $payment;
  var $security;
  var $info;
  
  /* ASSET */
  var $alias;
  var $id_device;
  var $id_group;
  var $icon;
  var $no;
  var $sensor;
  

  /* GEOFENCE */
  var $id_geofence;
  var $id_client;
  var $name; 
  var $type;
  var $category;
  var $data;
  
  /* GEOFENCE ASSET */
  var $imei;
  var $gf_enter;
  var $gf_exit;
  
  /* GEOROUTE */
  var $time;
  var $dist;
  
  /* POI */
  var $lat;
  var $poi;
   var $lng;
  
  /* USER / */
  var $user;
  var $password;
  
  var $group;
  
  /* ASSET_USER */
  var $id_user;
  var $id_asset;
  var $date_exp;
  
  /* ASSET CMDS */
  var $id_client_user;
  var $cmd;
  var $date_send;
  var $date_recv;
  
  /* ALARM */
  var $code;
  var $value;
  var $email;
  
  var $fuel;
  
  var $settings;
    
  public function __construct(){ $this->sql = new db(); }
  
  public function db($key){
    switch($key){
  	  case "insert": $query = "INSERT INTO client (client,pin,private,date_reg,no_client,status) VALUES ('".$this->client."', '".$this->pin."', '".$this->private."',  '".$this->date_reg."', '".$this->no_client."', '".$this->status."')";
	  break;
	  
	  case "insert_info": $query = "INSERT INTO client_info (id_client) VALUES ('".$this->id_client."')";
	  break;
	  
	  case "insert_settings": $query = "INSERT INTO client_conf (id_client,settings) VALUES ('".$this->id_client."','".$this->settings."') ON DUPLICATE KEY UPDATE settings='".$this->settings."'";
	  break;
	  
	  case "update_client_pin": $query = "UPDATE client SET pin = '".$this->pin."' WHERE id='".$this->id_client."'"; 
      break;
	  
	  case "update_client_park": $query = "UPDATE client_info SET park = '".$this->park."' WHERE id_client='".$this->id_client."'"; 
      break;
	  
	  case "update_client_security": $query = "UPDATE client_info SET security = '".$this->security."' WHERE id_client='".$this->id_client."'"; 
      break;
	  
	  case "update_client_user": $query = "UPDATE client_user SET user = '".$this->user."', password = '".$this->password."', date_exp = '".$this->date_exp."'  WHERE id_client='".$this->id_client."' AND type=1"; 
      break;
	  
	  case "update_client_payment": $query = "UPDATE client_info SET payment = '".$this->payment."' WHERE id_client='".$this->id_client."'"; 
      break;
	  
	  case "update_client_info": $query = "UPDATE client_info SET info = '".$this->info."' WHERE id_client='".$this->id_client."'"; 
      break;
	  
	  case "update_client_settings": $query = "UPDATE client_conf SET settings = '".$this->settings."' WHERE id_client='".$this->id_client."'"; 
      break;
	  
	  case "update_no_client": $query = "UPDATE client SET no_client = '".$this->no_client."' WHERE id = '".$this->id_client."'"; 
      break;
	  
	  case "insert_fuel": $query = "UPDATE client_asset SET fuel = '".$this->fuel."' WHERE id = '".$this->id_asset."'"; 
	  break;
	 
	  /* CLIENT ASSET */
	  case "insert_asset": 
	    $query = "INSERT INTO client_asset (id_client,id_device,id_group,imei,no,alias,data,date_reg,icon,sensor,status) 
				  VALUES ('".$this->id_client."', '".$this->id_device."', '".$this->id_group."',  '".$this->imei."', '".$this->no."', '".$this->alias."', '".$this->data."', '".$this->date_reg."', '".$this->icon."', '".$this->sensor."', '".$this->status."')";
	  break;
	  
	  case "insert_asset_fuel": 
	    $query = "INSERT INTO client_asset (id_client,id_device,id_group,imei,no,data,date_reg,status,parent) 
				  VALUES ('".$this->id_client."', 
				  		  '".$this->id_device."', 
						  '".$this->id_group."',  
						  '".$this->imei."', 
						  '".$this->no."', 
						  '".$this->data."', 
						  '".$this->date_reg."', 
						  '".$this->status."',
						  '".$this->parent."')";
	  break;
	  
	  
	  
	  
	  case "update_asset": 
	   $query = "UPDATE client_asset SET id_device = '".$this->id_device."', id_group = '".$this->id_group."', imei = '".$this->imei."', no = '".$this->no."', alias = '".$this->alias."', data = '".$this->data."', sensor = '".$this->sensor."' WHERE id ='".$this->id_asset."'"; 
      break;
	  
	  case "update_asset_name": $query = "UPDATE client_asset SET alias = '".$this->alias."' WHERE id ='".$this->id_asset."'"; 
      break;
	  
	  case "update_asset_info": $query = "UPDATE client_asset SET info = '".$this->info."' WHERE id ='".$this->id_asset."'"; 
      break;
	  
	   case "delete_asset": $query = "DELETE FROM client_asset WHERE id='".$this->id_asset."'";
      break;
	  
	  case "inset_asset_info": $query = "INSERT INTO client_info (id_asset,info) VALUES ('".$this->id_asset."','".$this->info."')";
	  break;
	  
	  /* GROUP */
	  case "insert_group": $query = "INSERT INTO client_group (id_client,client_group.group) VALUES ('".$this->id_client."','".$this->group."')";
	  break;
	  
	  case "update_group": $query = "UPDATE client_group SET client_group.group = '".$this->group."' WHERE id ='".$this->id_group."'";
      break;
	  
	  case "update_asset_group": $query = "UPDATE client_asset SET id_group= '".$this->id_group."' WHERE id = '".$this->id_asset."'";
	  break;
	  
	  /* GEOFENCE */
	  case "insert_geofence": $query = "INSERT INTO client_geofence (id_client,name,type,category,data) VALUES ('".$this->id_client."','".$this->name."','".$this->type."','".$this->category."','".$this->data."')";
	  break;
	  
	  case "delete_geofence": $query = "DELETE FROM client_geofence WHERE id='".$this->id_geofence."'";
      break;
	  
	  case "delete_asset_geofence": $query = "DELETE FROM client_asset_geofence WHERE id_geofence='".$this->id_geofence."'";
      break;
	  
	  case "delete_asset_geofence_event": $query = "DELETE FROM client_asset_geofence_event WHERE id_geofence='".$this->id_geofence."'";
      break;
	  
	  /* GEOFENCE ASSET */
	  case "insert_asset_geofence": 
	    $query = "INSERT INTO client_asset_geofence (id_client,id_geofence,imei,gf_enter,gf_exit) 
				  VALUES ('".$this->id_client."','".$this->id_geofence."','".$this->imei."','".$this->gf_enter."','".$this->gf_exit."') ON DUPLICATE KEY UPDATE gf_enter='".$this->gf_enter."', gf_exit='".$this->gf_exit."'";
				  echo $query;
	  break;
	  
	  case "delete_asset_geofence": $query = "DELETE FROM client_asset_geofence WHERE imei='".$this->imei."' AND id_geofence='".$this->id_geofence."'";
      break;
	  
	   /* GEOFENCE ASSET */
	  case "insert_georoute": $query = "INSERT INTO client_georoute (id_client,name,dist,time,data) VALUES ('".$this->id_client."','".$this->name."','".$this->dist."','".$this->time."','".$this->data."')";
	  break;
	  
	  case "delete_georoute": $query = "DELETE FROM client_georoute WHERE id='".$this->id."'";
      break;
	  
	  
	  /* USER */
	  
	  case "insert_user": $query = "INSERT INTO client_user (id_client,type,user,password,date_exp) VALUES ('".$this->id_client."','".$this->type."','".$this->user."','".$this->password."','".$this->date_exp."')";
	  break;
	  
	   case "insert_poi": $query = "INSERT INTO client_poi (id_client,poi,lat,lng,status) VALUES ('".$this->id_client."','".$this->poi."','".$this->lat."','".$this->lng."','".$this->status."')";
	  break;

	  
	  
	  case "update_user": $query = "UPDATE client_user SET type = '".$this->type."',user = '".$this->user."', password = '".$this->password."', date_exp = '".$this->date_exp."' WHERE id='".$this->id_user."'"; 
      break;
	  
	  case "update_pass": $query = "UPDATE client_user SET password = '".$this->password."' WHERE id ='".$this->id."'"; 
      break;
	  
	  case "delete_user": $query = "DELETE FROM client_user WHERE id ='".$this->id_user."'";
      break;
	  case "delete_asset_user": $query = "DELETE FROM client_asset_user WHERE id_user ='".$this->id_user."'";
      break;
	  
	  case "insert_asset_user": $query = "INSERT INTO client_asset_user (id_user,id_asset) VALUES ('".$this->id_user."','".$this->id_asset."')";
	  break;
	  
	  case "delete_group": $query = "DELETE FROM client_group WHERE id='".$this->id_group."'";
      break;
	  
	   case "delete_poi": $query = "DELETE FROM client_poi WHERE id='".$this->id."'";
      break;
	  
	  case "update_sort": $query = "UPDATE client_asset SET sort = '".$this->sort."' WHERE imei ='".$this->imei."'";
      break;

	  case "update": $query = "UPDATE client SET client = '".$this->client."', pin = '".$this->pin."',no_client = '".$this->no_client."', private = '".$this->private."' WHERE id='".$this->id."'"; 
      break;
	  case "update_icon": $query = "UPDATE client_asset SET icon = '".$this->icon."' WHERE id ='".$this->id."'"; 
      break;
      case "delete": $query = "UPDATE client SET status = 3 WHERE id='".$this->id."'";
      break;
	  
	  /* ALARMS */
	  case "reset_asset_alarm": $query = "DELETE FROM client_asset_alarm WHERE imei ='".$this->imei."'";
      break;
	  case "insert_asset_alarm": $query = "INSERT INTO client_asset_alarm (id_client,imei,code,value,email) VALUES ('".$this->id_client."','".$this->imei."','".$this->code."','".$this->value."','".$this->email."')";
	  break;
	  
	  
      case "insert_asset_cmd": 
	   	$query = "INSERT INTO client_asset_cmd (id_client_user,imei,cmd,date_send,status) 
				  VALUES ('".$this->id_client_user."','".$this->imei."','".$this->cmd."','".$this->date_send."','".$this->status."')";
      break;
	  
    }
	$this->execute($query); 
    if($key=="insert"){ $this->lastInserted = mysql_insert_id(); }
	if($key=="insert_user"){ $this->lastInserted = mysql_insert_id(); }
  }  
  
  public function getLastInserted(){ return $this->lastInserted; }
  public function getReports($imei = NULL, $de = null, $a = null){ 
  	$query = 'SELECT *
FROM `gprs`
WHERE `imei` ='.$imei.' ';
if($de !=null){
	$query .=" AND date BETWEEN '". $de . "' and '".$a."'  ORDER BY `gprs`.`date` DESC LIMIT 0 , 200 "; 
}else {
	$query .="   ORDER BY `gprs`.`date` DESC LIMIT 0 , 200 "; 
}

$query = $this->execute($query); 
$name = "SELECT alias FROM client_asset WHERE imei=" . $imei;
$name = $this->execute($name); 

$return = array($name,$query);
return $return;
  	//return $this->execute($query); 
  	 }
  
  public function getClient($id = NULL){
    $query = 'SELECT client.*, info,park,payment,security,sms,email FROM client INNER JOIN client_info ON client_info.id_client=client.id WHERE client.status != 3';
    if($id!=NULL) $query.=" AND client.id=".$id."";
	if($this->search!=NULL) $query .= " AND ".$this->search_field." LIKE '%".$this->search."%'";
	if($this->order!=NULL) $query .= " ORDER BY ".$this->order; 
	if($this->limit!=NULL) $query .= " LIMIT ".$this->limit;
	
	return $this->execute($query); 
  }

  public function getClientInfo($id = NULL){
    $query = 'SELECT  * FROM  client_info   WHERE id_client='.$id;
	//return $query;
	return $this->execute($query); 
  }
  
  public function getClientAssets(){
    $query = 'SELECT client_asset.*, client_group.group  
              FROM client_asset 
              LEFT JOIN client ON (client_asset.id_client = client.id) 
			  LEFT JOIN device ON (client_asset.id_device = device.id) 
			  LEFT JOIN client_group ON (client_asset.id_group = client_group.id) 
			  WHERE client.status != 3';
    if($this->id_asset!=NULL) $query .= " AND client_asset.id=".$this->id_asset; 
	if($this->id_client!=NULL) $query .= " AND client_asset.id_client=".$this->id_client;
	if($this->id_device!=NULL) $query .= " AND client_asset.id_device=".$this->id_device; 
    if($this->status!=NULL) $query .= " AND unidad.status=".$this->status; 
	if($this->search!=NULL) $query .= " AND ".$this->search_field." LIKE '%".$this->search."%'";
	if($this->order!=NULL) $query .= " ORDER BY ".$this->order; 
	if($this->limit!=NULL) $query .= " LIMIT ".$this->limit;
	return $this->execute($query); 
  }
  
  public function getClientImeis(){
    $query = 'SELECT imei,data 
              FROM client_asset 
              LEFT JOIN client ON (client_asset.id_client = client.id) 
			  WHERE client.status != 3';
    if($this->id_asset!=NULL) $query .= " AND client_asset.id=".$this->id_asset; 
	if($this->id_client!=NULL) $query .= " AND client_asset.id_client=".$this->id_client; 
    if($this->status!=NULL) $query .= " AND unidad.status=".$this->status; 
	if($this->search!=NULL) $query .= " AND ".$this->search_field." LIKE '%".$this->search."%'";
	if($this->order!=NULL) $query .= " ORDER BY ".$this->order; 
	if($this->limit!=NULL) $query .= " LIMIT ".$this->limit;
	return $this->execute($query); 
  }
  
  public function getTotalClientAssets($id_client = NULL){
    $query = 'SELECT count(*) AS TOTAL FROM client_asset WHERE id_client="'.$id_client.'"';
	return $this->execute($query); 
  }
  
  public function getClientGeofence($id = NULL){
    $query = 'SELECT * FROM client_geofence WHERE id > 0';
    if($id!=NULL) $query.=" AND id=".$id."";
	if($this->id_client!=NULL) $query .= " AND id_client=".$this->id_client; 
	if($this->in!=NULL) $query .= " AND id IN (".$this->in.")";
	if($this->order!=NULL) $query .= " ORDER BY ".$this->order; 
	if($this->limit!=NULL) $query .= " LIMIT ".$this->limit;
	//return $query;
	return $this->execute($query); 
  }
  
  public function getClientGeofenceOpt($id = NULL){
    $query = 'SELECT * FROM client_geofence WHERE id_client = ' . $id;
   
	if($this->id_client!=NULL) $query .= " AND id_client=".$this->id_client; 
	if($this->in!=NULL) $query .= " AND id IN (".$this->in.")";
	if($this->order!=NULL) $query .= " ORDER BY ".$this->order; 
	if($this->limit!=NULL) $query .= " LIMIT ".$this->limit;
	//return $query;
	return $this->execute($query); 
  }

 public function getClientGeofenceOpt2($imei = NULL, $de = null, $a = null){
    $query = 'SELECT * FROM client_geofence_history WHERE imei = ' . $imei ;
    if($this->in!=NULL) $query .= " AND id_geocerca IN (".$this->in.")";

     $query .=" AND date BETWEEN '". $de . "' and '".$a."'"; 
	//return $query;
	return $this->execute($query); 
  }


  public function getAssetGeofence($imei = NULL){
    $query = 'SELECT * FROM client_asset_geofence WHERE id > 0';
	if($this->imei!=NULL) $query .= " AND imei=".$this->imei; 
	if($this->id_client!=NULL) $query .= " AND id_client=".$this->id_client; 
	if($this->in!=NULL) $query .= " AND id IN (".$this->in.")";
	if($this->order!=NULL) $query .= " ORDER BY ".$this->order; 
	if($this->limit!=NULL) $query .= " LIMIT ".$this->limit;
	return $this->execute($query); 
  }
  
  public function getClientGeoroute($id = NULL){
    $query = 'SELECT * FROM client_georoute WHERE id > 0';
    if($id!=NULL) $query.=" AND id=".$id."";
	if($this->id_client!=NULL) $query .= " AND id_client=".$this->id_client; 
	if($this->order!=NULL) $query .= " ORDER BY ".$this->order; 
	if($this->limit!=NULL) $query .= " LIMIT ".$this->limit;
	return $this->execute($query); 
  }
  
  public function getClientAlarms($id = NULL){
    $query = 'SELECT * FROM client_asset_alarm WHERE id > 0';
    if($id!=NULL) $query.=" AND id=".$id."";
	if($this->id_client!=NULL) $query .= " AND id_client=".$this->id_client; 
	if($this->order!=NULL) $query .= " ORDER BY ".$this->order; 
	if($this->limit!=NULL) $query .= " LIMIT ".$this->limit;
	return $this->execute($query); 
  }
  
  public function getPois($id = NULL){
    $query = 'SELECT * FROM client_poi WHERE id > 0';
    if($id!=NULL) $query.=" AND id=".$id."";
	if($this->id_client!=NULL) $query .= " AND id_client=".$this->id_client; 
	if($this->order!=NULL) $query .= " ORDER BY ".$this->order; 
	if($this->limit!=NULL) $query .= " LIMIT ".$this->limit;
	return $this->execute($query); 
  }

  public function getTracks($id = NULL){
    $query = 'SELECT * FROM client_track WHERE id_client ='.$this->id_client;
	//return $query;
	return $this->execute($query); 
  }
  
  
   public function getSettings($id = NULL){
    $query = "SELECT * FROM client_conf WHERE id_client=".$id."";
	$result = $this->execute($query); 
	return $result[0]['settings'];
  }
  
  public function getMaxNoClient(){
    $query = 'SELECT MAX(no_client) AS max FROM client LIMIT 1';
    $result = $this->execute($query); 
	return $result[0]['max'];
  
  }
  
  
  
  
   public function getUsersClient($id_client = NULL){
     $query = 'SELECT * FROM client_user WHERE id > 0';
    if($id_client!=NULL) $query.=" AND id_client =".$id_client."";
    if($this->type!=NULL) $query.=" AND type =".$this->type."";
	if($this->order!=NULL) $query .= " ORDER BY ".$this->order; 
	if($this->limit!=NULL) $query .= " LIMIT ".$this->limit;
	 

 
	return $this->execute($query); 
  }

  public function getUserMaster($id_client = NULL){
     $query = 'SELECT * FROM client_user WHERE id > 0';
    if($id_client!=NULL) $query.=" AND id_client =".$id_client."";
    if($this->type!=NULL) $query.=" AND type =".$this->type."";
	if($this->order!=NULL) $query .= " ORDER BY ".$this->order; 
	if($this->limit!=NULL) $query .= " LIMIT ".$this->limit;
	$query .= " AND type=1";

	 
	return $this->execute($query); 
  }
  
   public function getClientGroups($id_client = NULL){
    $query = 'SELECT * FROM client_group WHERE id > 0';
    if($id_client!=NULL) $query.=" AND id_client =".$id_client."";
	if($this->order!=NULL) $query .= " ORDER BY ".$this->order; 
	if($this->limit!=NULL) $query .= " LIMIT ".$this->limit;
	return $this->execute($query); 
  }
  
  public function getUserClient($id = NULL){
    $query = 'SELECT * FROM client_user WHERE id > 0';
    if($id!=NULL) $query.=" AND id=".$id."";
	return $this->execute($query); 
  }
  
  public function getActiveGeoRoute($imei = NULL){
    $query = 'SELECT * FROM client_asset_georoute WHERE NOW() BETWEEN date_init AND date_end';
	return $this->execute($query); 
  }
  
  
    public function getGeoRoute($id = NULL){
    $query = 'SELECT * FROM client_georoute WHERE id > 0';
	if($id!=NULL) $query.=" AND id=".$id."";
	return $this->execute($query); 
  }
  
  
  
  public function getDevicesReport(){
    $query = 'SELECT client,ca.alias,device,i.imei, ca.no, n.serial_no, n.account, data 
			  FROM imei i 
			  LEFT JOIN client_asset ca ON (ca.imei = i.imei) 
			  INNER JOIN device d ON (i.id_device = d.id) 
			  INNER JOIN client c ON (ca.id_client=c.id) 
			  INNER JOIN number n ON (ca.no=n.no) 
			  WHERE i.imei LIKE "%'.$this->search.'%" OR ca.no LIKE "%'.$this->search.'%" OR client LIKE "%'.$this->search.'%" OR ca.alias LIKE "%'.$this->search.'%" OR device LIKE "%'.$this->search.'%" OR n.serial_no LIKE "%'.$this->search.'%" OR n.account LIKE "%'.$this->search.'%" ORDER BY ca.id ';
	return $this->execute($query); 
  }
  
  public function getClientGroup($id = NULL){
    $query = 'SELECT * FROM client_group WHERE id > 0';
    if($id!=NULL) $query.=" AND id=".$id."";
	return $this->execute($query); 
  }
  
   public function auth_client_user(){
     $query = "SELECT * FROM client_user WHERE user ='".$this->user."' AND password ='".$this->password."' LIMIT 1";
	return $this->execute($query); 
  } 
    public function user_exists(){
    $query = "SELECT * FROM client_user WHERE user ='".$this->user."'  LIMIT 1";
	return $this->execute($query); 
  } 

   public function update_active_client($id,$onff)
  {
  	  $query ="UPDATE client SET activo='".$onff."' WHERE id=".$id;
  	  $query2 ="UPDATE client_user SET activo ='".$onff."' WHERE `id_client` =".$id;
  	  $this->execute($query); 
 	  return $this->execute($query2); 
  }
}
?>