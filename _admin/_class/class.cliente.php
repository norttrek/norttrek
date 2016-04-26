<?php

require_once("class.helper.php");

class Cliente extends Helper {
  var $id;
  var $razon_social;
  var $RFC;
  var $nombre_comercial;
  var $correo;
  var $giro;
  var $direccion;
  var $contacto;
  var $fecha_registro;
  var $status;
  
  /* NUEVO */
  var $id_cliente;
  var $id_cliente_usuario;
  var $imei;
  var $cmd;
  var $fecha_hora_reg;
  var $ffecha_hora_ok;
  var $privado;
  
  var $id_usuario;
  var $tipo;
  var $nombre;
  var $fecha_reg;
  var $points;
    
  public function __construct(){ $this->sql = new db(); }
  
  public function db($key){
    switch($key){
  	  case "insert": 
	    $query = "INSERT INTO cliente (razon_social,RFC,nombre_comercial,correo,giro,direccion,contacto,fecha_registro,privado,status) 
		          VALUES ('".$this->razon_social."',
						  '".$this->RFC."',
						  '".$this->nombre_comercial."',
						  '".$this->correo."',
						  '".$this->giro."',
						  '".$this->direccion."',
						  '".$this->contacto."',
						  '".$this->fecha_registro."',
						  '".$this->privado."',
						  '".$this->status."')";
	  break;
	  case "insert_cmd": 
	    $query = "INSERT INTO cliente_cmd (id_cliente_usuario,imei,cmd,fecha_hora_reg,fecha_hora_ok,status) 
		          VALUES ('".$this->id_cliente_usuario."',
						  '".$this->imei."',
						  '".$this->cmd."',
						  '".$this->fecha_hora_reg."',
						  '".$this->fecha_hora_ok."',
						  '".$this->status."')";
	  break;
	  case "insert_plan": 
	    $query = "INSERT INTO cliente_plan (id_cliente,fecha_limite,metodo_pago,mensualidad,plazo_min,comentario,status) 
		          VALUES ('".$this->id_cliente."',
						  '".$this->fecha_limite."',
						  '".$this->metodo_pago."',
						  '".$this->mensualidad."',
						  '".$this->plazo_min."',
						  '".$this->comentario."',
						  '".$this->status."')";
	  break;
	  case "update": $query = "UPDATE cliente SET nombre = '".$this->nombre."',
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
	  case "update_patio": $query = "UPDATE cliente_patio SET id_cliente = '".$this->id_cliente."',
												              calle_num = '".$this->calle_num."',
												              colonia = '".$this->colonia."',
												              telefono = '".$this->telefono."',
												              municipio = '".$this->municipio."',
												              estado = '".$this->estado."',
												              cp = '".$this->cp."',
												              entre_calles = '".$this->entre_calles."' WHERE id='".$this->id."'"; 
      break;
	  case "update_plan": $query = "UPDATE cliente_plan SET id_cliente = '".$this->id_cliente."',
	                                                        fecha_limite = '".$this->fecha_limite."',
												            metodo_pago = '".$this->metodo_pago."',
												            mensualidad = '".$this->mensualidad."',
												            plazo_min = '".$this->plazo_min."',
												            comentario = '".$this->comentario."'  WHERE id='".$this->id."'"; 
      break;

      case "delete": $query = "DELETE FROM cliente WHERE id='".$this->id."'";
      break;
	  case "delete_patio": $query = "DELETE FROM cliente_patio WHERE id='".$this->id."'";
      break;
	  case "delete_plan": $query = "DELETE FROM cliente_plan WHERE id='".$this->id."'";
      break;
	  case "insert_geozone": 
	    $query = "INSERT INTO cliente_geozona (id_cliente,id_usuario,nombre,tipo,points,fecha_reg) 
		          VALUES ('".$this->id_cliente."',
						  '".$this->id_usuario."',
						  '".$this->nombre."',
						  '".$this->tipo."',
						  '".$this->points."',
						  '".$this->fecha_reg."')";
	  break;

    }
	$this->execute($query); 
    if($key=="insert"){ $this->lastInserted = mysql_insert_id(); }
  }  
  
  public function getCliente($id = NULL){
    $query = 'SELECT * FROM cliente
			  WHERE id > 0';
    if($id!=NULL) $query.=" AND id=".$id."";
    if($this->status!=NULL) $query .= " AND status=".$this->status; 
	if($this->search!=NULL) $query .= " AND ".$this->search_field." LIKE '%".$this->search."%'";
	if($this->order!=NULL) $query .= " ORDER BY ".$this->order; 
	if($this->limit!=NULL) $query .= " LIMIT ".$this->limit;
	return $this->execute($query); 
  }
  
  public function getClientePatio($id = NULL){
    $query = 'SELECT cliente_patio.*
	          FROM cliente_patio
			  LEFT JOIN cliente ON (cliente_patio.id_cliente=cliente.id) 
			  WHERE cliente_patio.id > 0';
    if($id!=NULL) $query.=" AND cliente_patio.id_cliente=".$id."";
    if($this->status!=NULL) $query .= " AND status=".$this->status; 
	if($this->search!=NULL) $query .= " AND ".$this->search_field." LIKE '%".$this->search."%'";
	if($this->order!=NULL) $query .= " ORDER BY ".$this->order; 
	if($this->limit!=NULL) $query .= " LIMIT ".$this->limit;
	return $this->execute($query); 
  }
  
  public function getClientePlan($id = NULL){
    $query = 'SELECT cliente_plan.*
	          FROM cliente_plan
			  LEFT JOIN cliente ON (cliente_plan.id_cliente=cliente.id) 
			  WHERE cliente_plan.id > 0';
    if($id!=NULL) $query.=" AND cliente_plan.id_cliente=".$id."";
    if($this->status!=NULL) $query .= " AND status=".$this->status; 
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
  
   public function getUnidades($id = NULL){
    $query = 'SELECT * FROM cliente_unidad WHERE id > 0';
    if($id!=NULL) $query.=" AND cliente_plan.id_cliente=".$id."";
	if($this->id_cliente!=NULL) $query.=" AND id_cliente = '".$this->id_cliente."'";
    if($this->status!=NULL) $query .= " AND status=".$this->status; 
	if($this->search!=NULL) $query .= " AND ".$this->search_field." LIKE '%".$this->search."%'";
	if($this->order!=NULL) $query .= " ORDER BY ".$this->order; 
	if($this->limit!=NULL) $query .= " LIMIT ".$this->limit;
	return $this->execute($query); 
  }
  
  public function getGeoZonas(){
    $query = 'SELECT * FROM cliente_geozona WHERE id > 0';
	if($this->id_cliente!=NULL) $query.=" AND id_cliente = '".$this->id_cliente."'";
    if($this->status!=NULL) $query .= " AND status=".$this->status; 
	if($this->search!=NULL) $query .= " AND ".$this->search_field." LIKE '%".$this->search."%'";
	if($this->order!=NULL) $query .= " ORDER BY ".$this->order; 
	if($this->limit!=NULL) $query .= " LIMIT ".$this->limit;
	return $this->execute($query); 
  }
  
}
?>