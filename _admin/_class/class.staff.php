<?php

require_once("class.helper.php");

class Staff extends Helper {
  var $id;
  var $tipo;
  var $nombre;
  var $correo;
  var $contacto;
  var $usuario;
  var $contrasena;
  var $status;
    
  public function __construct(){ $this->sql = new db(); }
  
  public function db($key){
    switch($key){
  	  case "insert": 
	    $query = "INSERT INTO staff (tipo,nombre,correo,contacto,usuario,contrasena,status) 
		          VALUES ('".$this->tipo."',
						  '".$this->nombre."',
						  '".$this->correo."',
						  '".$this->contacto."',
						  '".$this->usuario."',
						  '".$this->contrasena."',
						  '".$this->status."')";
	  break;
	  case "update": $query = "UPDATE staff SET nombre = '".$this->nombre."',
	                                              nombre_comercial = '".$this->nombre_comercial."',
												  calle_num = '".$this->calle_num."',
												  colonia = '".$this->colonia."',
												  telefono = '".$this->telefono."',
												  municipio = '".$this->municipio."',
												  estado = '".$this->estado."',
												  cp = '".$this->cp."',
												  rfc = '".$this->rfc."',
												  correo_facturas = '".$this->correo_facturas."',
												  banco = '".$this->banco."',
												  cuatro_dig = '".$this->cuatro_dig."',
												  giro_empresa = '".$this->giro_empresa."',
												  contacto_pagos = '".$this->contacto_pagos."' WHERE id='".$this->id."'"; 
      break;
      case "delete": $query = "DELETE FROM staff WHERE id='".$this->id."'";
    }
	$this->execute($query); 
	echo $query;
    if($key=="insert"){ $this->lastInserted = mysql_insert_id(); }
  }  
  
  public function getStaff($id = NULL){
    $query = 'SELECT * FROM staff WHERE id > 0';
    if($id!=NULL) $query.=" AND id=".$id."";
	if($this->tipo!=NULL) $query .= " AND tipo='".$this->tipo."'"; 
    if($this->status!=NULL) $query .= " AND status=".$this->status; 
	if($this->search!=NULL) $query .= " AND ".$this->search_field." LIKE '%".$this->search."%'";
	if($this->order!=NULL) $query .= " ORDER BY ".$this->order; 
	if($this->limit!=NULL) $query .= " LIMIT ".$this->limit;
	return $this->execute($query); 
  }
  
  public function auth(){
    $query = "SELECT * FROM staff WHERE usuario ='".$this->usuario."' AND contrasena ='".$this->contrasena."' LIMIT 1";
	return $this->execute($query); 
  } 
  
}
?>