<?php

require_once("class.helper.php");

class IMEI extends Helper {
  var $id;
  var $id_device;
  var $imei;
  var $status;

      
  public function __construct(){ $this->sql = new db(); }
  
  public function db($key){
    switch($key){
      case "insert": $query = "INSERT INTO imei (id_device,imei,status) VALUES ('".$this->id_device."','".$this->imei."','".$this->status."')";
	  break;
      case "update": $query = "UPDATE imei SET id_device = '".$this->id_device."', imei = '".$this->imei."' WHERE id='".$this->id."'"; 
      break;
      case "delete": $query = "DELETE FROM imei WHERE id = '".$this->id."'";
      break;	  
    }
	$this->execute($query); 
    if($key=="insert"){ $this->lastInserted = mysql_insert_id(); }
  }  
  
  public function getLastInserted(){ return $this->lastInserted; }
  

  public function getIMEI($id = NULL){
    $query = 'SELECT imei.*, device, ca.id AS ca_id 
			  FROM imei 
			  LEFT JOIN device ON (imei.id_device=device.id)
			  LEFT JOIN client_asset ca ON (imei.imei = ca.imei)
			  WHERE imei.id > 0';
    if($id!=NULL) $query.=" AND imei.id=".$id."";
    if($this->status!=NULL) $query .= " AND status=".$this->estatus; 
	if($this->search!=NULL) $query .= " AND ".$this->search_field." LIKE '%".$this->search."%'";
	if($this->order!=NULL) $query .= " ORDER BY ".$this->order; 
	if($this->limit!=NULL) $query .= " LIMIT ".$this->limit;
	return $this->execute($query); 
  }   
  
  public function isImeiDuplicate($imei){
    $query = 'SELECT * FROM imei WHERE imei ="'.$imei.'"';
	$result = $this->execute($query); 
	if($result){ return true; }else{ return false; }
  }   
  
  

 public function getIMEINotUsed($id = NULL){
    $query = 'SELECT * FROM imei WHERE imei NOT IN (SELECT imei FROM client_asset)';
	return $this->execute($query); 
  }   
  
   public function getFuelIMEINotUsed($id = NULL){
    $query = 'SELECT * FROM imei WHERE imei NOT IN (SELECT imei FROM client_asset)';
	return $this->execute($query); 
  }  
  
}
?>