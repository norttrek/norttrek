<?php

require_once("class.helper.php");

class Unidad extends Helper {
  var $id;
  var $id_cliente;
  var $id_equipo;
  var $id_staff;
  var $id_grupo;
  var $imei;
  var $alias;
  var $info;
  var $icono;
  var $fecha_registro;
  var $status;
  
    
  public function __construct(){ $this->sql = new db(); }
  
  public function db($key){
    switch($key){
  	  case "insert": 
	    $query = "INSERT INTO cliente_unidad (id_cliente,id_equipo,id_grupo,id_staff,imei,alias,info,icono,fecha_registro,status) 
		          VALUES ('".$this->id_cliente."','".$this->id_equipo."','".$this->id_grupo."','".$this->id_staff."','".$this->imei."','".$this->alias."','".$this->info."','".$this->icono."','".$this->fecha_registro."','".$this->status."')";
	  break;
	  case "update": $query = "UPDATE unidad SET 
	                                             id_equipo = '".$this->id_equipo."',
												 id_staff = '".$this->id_staff."',
												 imei = '".$this->imei."',
												 alias = '".$this->alias."',
												 info = '".$this->info."', 
												 WHERE id='".$this->id."'"; 
      break;
	  case "update_icon": $query = "UPDATE cliente_unidad SET icono = '".$this->icono."' WHERE imei ='".$this->imei."'"; 
      break;
      case "delete": $query = "DELETE FROM unidad WHERE id='".$this->id."'";
      break;
    }
	echo $query;
	$this->execute($query); 
    if($key=="insert"){ $this->lastInserted = mysql_insert_id(); }
  }  
  
  public function getUnidad($id = NULL){
    $query = 'SELECT cliente_unidad.*
              FROM cliente_unidad
              LEFT JOIN cliente ON (cliente_unidad.id_cliente = cliente.id)
			  LEFT JOIN equipo ON (cliente_unidad.id_equipo = equipo.id)
			  WHERE cliente_unidad.id > 0';
    if($id!=NULL) $query.=" AND cliente_unidad.id=".$id."";
	if($this->id_cliente!=NULL) $query .= " AND cliente_unidad.id_cliente=".$this->id_cliente; 
    if($this->status!=NULL) $query .= " AND unidad.status=".$this->status; 
	if($this->search!=NULL) $query .= " AND ".$this->search_field." LIKE '%".$this->search."%'";
	if($this->order!=NULL) $query .= " ORDER BY ".$this->order; 
	if($this->limit!=NULL) $query .= " LIMIT ".$this->limit;
	return $this->execute($query); 
  }
  
  public function getRegistros($imei = NULL){
	  $query = 'SELECT * FROM serv WHERE id > 0';
    if($imei!=NULL) $query.=" AND imei =".$imei."";
    if($this->status!=NULL) $query .= " AND unidad.status=".$this->status; 
	if($this->search!=NULL) $query .= " AND ".$this->search_field." LIKE '%".$this->search."%'";
	if($this->order!=NULL) $query .= " ORDER BY ".$this->order; 
	if($this->limit!=NULL) $query .= " LIMIT ".$this->limit;
	return $this->execute($query); 
  }
    
  function search($string){
      $this->arrayCollection = NULL;
      $this->sql->CONNECT();
      $query = "SELECT id,nombre FROM cupon WHERE nombre LIKE '%".$string."%' AND status='1'";
      $result = mysql_query($query) or die(mysql_error());
      while($row = mysql_fetch_assoc($result)) { $this->arrayCollection[] = $row; }
      return $this->arrayCollection;
  }  
  
  /* NUEVO */
  public function getUltimoRegistro($imei = NULL){
    $query = 'SELECT * FROM serv WHERE imei = "'.$imei.'" ORDER BY id DESC LIMIT 1 ';
    if($this->status!=NULL) $query .= " AND unidad.status=".$this->status; 
	return $this->execute($query); 
  }
  
   public function getRptTray($imei = NULL){
    $query = 'SELECT DISTINCT lat, id,imei,lon,status FROM serv WHERE imei = "'.$imei.'" ORDER BY id DESC LIMIT 100';
	return $this->execute($query); 
  }
  
}
?>