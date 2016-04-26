<?php

require_once("class.helper.php");

class User extends Helper {
  var $id;
  var $type;
  var $user;
  var $password;
  var $op_password;
  var $permissions;

      
  public function __construct(){ $this->sql = new db(); }
  
  public function db($key){
    switch($key){
      case "insert": $query = "INSERT INTO user (type,user,password,op_password,permissions) VALUES ('".$this->type."','".$this->user."','".$this->password."','".$this->op_password."','".$this->permissions."')";
	  break;
      case "update": $query = "UPDATE user SET type = '".$this->type."',user = '".$this->user."', password = '".$this->password."' , op_password = '".$this->op_password."' 
	  , permissions = '".$this->permissions."'  WHERE id='".$this->id."'"; 
      break;
      case "delete": $query = "DELETE from user WHERE id='".$this->id."'";
      break;	  
    }
	$this->execute($query); 
    if($key=="insert"){ $this->lastInserted = mysql_insert_id(); }
  }  
  
  public function getLastInserted(){ return $this->lastInserted; }
  
  public function verify($id,$hash){
    //$query = 'UPDATE usuario SET status="1" WHERE id="'.$id.'" AND hash="'.$hash.'"';
	return $this->execute($query); 
  }

  public function getUser($id = NULL){
    $query = 'SELECT * FROM user WHERE id > 0';
    if($id!=NULL) $query.=" AND id=".$id."";
	if($this->type!=NULL) $query .= " AND type='".$this->type."'"; 
    if($this->status!=NULL) $query .= " AND estatus=".$this->estatus; 
	if($this->search!=NULL) $query .= " AND ".$this->search_field." LIKE '".$this->search."%'";
	if($this->order!=NULL) $query .= " ORDER BY ".$this->order; 
	if($this->limit!=NULL) $query .= " LIMIT ".$this->limit;
	return $this->execute($query); 
  }  
 
  public function auth(){
    $query = "SELECT * FROM user WHERE user ='".$this->user."' AND password ='".$this->password."' LIMIT 1";
	return $this->execute($query); 
  } 
  
  
  
  public function get_type($type){
    $label = NULL;
    switch($type){
      case 1: $label ="Administrador";
	  break;
	  case 2: $label ="General";
	  break;	 
    }
    return $label;
  }
 
}
?>