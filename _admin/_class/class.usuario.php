<?php

require_once("class.helper.php");

class Usuario extends Helper {
  var $id;
  var $tipo_usuario;
  var $nombre;
  var $correo;
  var $contrasena;
  var $estatus;
  var $e;
      
  public function __construct(){ $this->sql = new db(); }
  
  public function db($key){
    switch($key){
      case "insert": $query = "INSERT INTO usuario (tipo_usuario,nombre,correo,contrasena,estatus,e) 
				               VALUES ('".$this->tipo_usuario."','".$this->nombre."','".$this->correo."','".$this->contrasena."','".$this->estatus."','".$this->e."')";
	  break;
      case "update": $query = "UPDATE usuario SET tipo_usuario = '".$this->tipo_usuario."',
		                                          nombre = '".$this->nombre."',
		                                          correo = '".$this->correo."',
		                                          contrasena = '".$this->contrasena."' WHERE id='".$this->id."'"; 
      break;
      case "delete": $query = "UPDATE usuario SET e = '".$this->e."' WHERE id='".$this->id."'";
      break;	  
      case "estatus": $query = "UPDATE usuario SET estatus = '".$this->estatus."' WHERE id='".$this->id."'";
      break;	
    }
	$this->execute($query); 
    if($key=="insert"){ $this->lastInserted = mysql_insert_id(); }
  }  
  
  public function getLastInserted(){ return $this->lastInserted; }
  
  public function verify($id,$hash){
    $query = 'UPDATE usuario SET status="1" WHERE id="'.$id.'" AND hash="'.$hash.'"';
	return $this->execute($query); 
  }
  
  public function user_exists($correo){ 
    $query = 'SELECT up.nombre,up.apellido,u.correo 
			  FROM usuario u INNER JOIN usuario_perfil up ON (u.id=up.id_usuario)
			  WHERE correo = "'.$correo.'"';
	$result = $this->execute($query);
	if($result){ return $result; }else{ return false; }
  }
  
  
  public function getUsuario($id = NULL){
    $query = 'SELECT * FROM usuario
	          WHERE id > 0';
    if($id!=NULL) $query.=" AND id=".$id."";
	if($this->tipo!=NULL) $query .= " AND tipo='".$this->tipo."'"; 
    if($this->status!=NULL) $query .= " AND estatus=".$this->estatus; 
	if($this->search!=NULL) $query .= " AND ".$this->search_field." LIKE '".$this->search."%'";
	if($this->order!=NULL) $query .= " ORDER BY ".$this->order; 
	if($this->limit!=NULL) $query .= " LIMIT ".$this->limit;
	return $this->execute($query); 
  }  
  
  public function getUsuarioContactos($id = NULL){
    $query = 'SELECT * FROM usuario_contacto WHERE id > 0';
    if($id!=NULL) $query.=" AND id=".$id."";
    if($this->status!=NULL) $query .= " AND status=".$this->status; 
	if($this->tipo!=NULL) $query .= " AND tipo='".$this->status."'"; 
	if($this->search!=NULL) $query .= " AND ".$this->search_field." LIKE '".$this->search."%'";
	if($this->order!=NULL) $query .= " ORDER BY ".$this->order; 
	if($this->limit!=NULL) $query .= " LIMIT ".$this->limit;
	return $this->execute($query); 
  }  

  public function auth(){
    $query = "SELECT id,nombre,apellido 
			  FROM usuario u INNER JOIN usuario_perfil up ON (u.id=up.id_usuario)
			  WHERE correo ='".$this->correo."' AND contrasena ='".$this->contrasena."' LIMIT 1";
	return $this->execute($query); 
  } 
  
  
  public function getUsuariosReseravados($id = NULL){
    $query = 'SELECT * FROM cat_usuario_reservado';
    if($id!=NULL) $query.=" AND id=".$id."";
    if($this->status!=NULL) $query .= " AND status=".$this->status; 
	if($this->search!=NULL) $query .= " AND ".$this->search_field." LIKE '".$this->search."%'";
	if($this->order!=NULL) $query .= " ORDER BY ".$this->order; 
	if($this->limit!=NULL) $query .= " LIMIT ".$this->limit;
	return $this->execute($query); 
  }  
  
  public function getReporteUsuarios($id = NULL){
    $query = 'SELECT * FROM usuario INNER JOIN usuario_perfil ON (usuario.id=usuario_perfil.id_usuario)';
    if($id!=NULL) $query.=" AND id=".$id."";
    if($this->status!=NULL) $query .= " AND status=".$this->status; 
	if($this->search!=NULL) $query .= " AND ".$this->search_field." LIKE '".$this->search."%'";
	if($this->order!=NULL) $query .= " ORDER BY ".$this->order; 
	if($this->limit!=NULL) $query .= " LIMIT ".$this->limit;
	return $this->execute($query); 
  }  
}
?>