<?php

require_once("class.helper.php");

class Device extends Helper {
  var $id;
  var $device;
  var $description;
  var $status;
    
  public function __construct(){ $this->sql = new db(); }
  
  public function db($key){
    switch($key){
  	  case "insert": $query = "INSERT INTO device (device,description,status) VALUES ('".$this->device."','".$this->description."','".$this->status."')";
	  break;
	  case "update": $query = "UPDATE device SET device = '".$this->device."', description = '".$this->description."' WHERE id='".$this->id."'"; 
      break;
      case "delete": $query = "UPDATE device SET status = 3 WHERE id='".$this->id."'"; 
      break;
    }
	$this->execute($query); 
    if($key=="insert"){ $this->lastInserted = mysql_insert_id(); }
  }  
  
  public function getDevice($id = NULL){
    $query = 'SELECT * FROM device WHERE id > 0';
    if($id!=NULL) $query.=" AND id=".$id."";
    if($this->status!=NULL) $query .= " AND status=".$this->status; 
	if($this->search!=NULL) $query .= " AND ".$this->search_field." LIKE '%".$this->search."%'";
	if($this->order!=NULL) $query .= " ORDER BY ".$this->order; 
	if($this->limit!=NULL) $query .= " LIMIT ".$this->limit;
	return $this->execute($query); 
  }
  
}
?>