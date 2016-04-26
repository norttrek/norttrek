<?php

require_once("class.helper.php");

class Equipo extends Helper {
  var $id;
  var $equipo;
  var $descripcion;
  var $status;
    
  public function __construct(){ $this->sql = new db(); }
  
  public function db($key){
    switch($key){
  	  case "insert": $query = "INSERT INTO equipo (equipo,descripcion,status) VALUES ('".$this->equipo."','".$this->descripcion."','".$this->status."')";
	  break;
	  case "update": $query = "UPDATE equipo SET equipo = '".$this->equipo."', descripcion = '".$this->descripcion."' WHERE id='".$this->id."'"; 
      break;
      case "delete": $query = "UPDATE equipo SET status = 3 WHERE id='".$this->id."'"; 
      break;
    }
	$this->execute($query); 
    if($key=="insert"){ $this->lastInserted = mysql_insert_id(); }
  }  
  
  public function getEquipo($id = NULL){
    $query = 'SELECT * FROM equipo WHERE id > 0';
    if($id!=NULL) $query.=" AND id=".$id."";
    if($this->status!=NULL) $query .= " AND status=".$this->status; 
	if($this->search!=NULL) $query .= " AND ".$this->search_field." LIKE '%".$this->search."%'";
	if($this->order!=NULL) $query .= " ORDER BY ".$this->order; 
	if($this->limit!=NULL) $query .= " LIMIT ".$this->limit;
	return $this->execute($query); 
  }
  
}
?>