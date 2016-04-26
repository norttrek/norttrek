<?php

require_once("class.helper.php");

class Alert extends Helper {
  var $id;
 
  public function __construct(){ $this->sql = new db(); }

  public function getAlert($code,$value){
	$lbl = NULL;
	switch($code){
	  case "0x5": $lbl = "Boton de Panico"; break;
	  case "0xA": $lbl = "Motor Apagado"; break;
	  case "0xF": $lbl = "Motor Encendido"; break;
	  case "0x14": $lbl = "Bateria Baja"; break;
	  case "0x19": $lbl = "Conexion Antena GPS"; break;
	  case "0x1E": $lbl = "Desconexion Antena GPS"; break;
	  case "0x23": $lbl = "Reporte de Movimiento "; break;
	  case "0x28": $lbl = "Sin Movimiento (IDLE)"; break;
	  case "0x2D": $lbl = "Posible Extraccion (".$value." L)"; break;
	  case "0x32": $lbl = "Posible Carga de Combustible (".$value." L)"; break;
	  case "0x37": $lbl = "Sensor de Temperatura Fuera de Rango (".$value." C)"; break;
	  case "0x55": $lbl = "Exceso de Velocidad (".$value." kms)"; break;
	  case "0xGI": $lbl = "Dentro de GeoCerca ".$value.""; break;
	  case "0xGO": $lbl = "Fuera de GeoCerca (".$value.")"; break;
	}
	return $lbl;
  }
 
  
}
?>
