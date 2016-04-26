<?php
date_default_timezone_set('America/Mexico_City');
require_once("class.db.php");

abstract class Helper {
  public $arrayCollection;
  public $lastInserted;
  public $limit;
  public $order;
  public $in;
  public $between;
  public $search;
  public $search_field;
  public $sql;
  
  public function __construct(){ }

  public function __call($name, $arguments) {
    if(substr($name,0,4) == 'set_'){
      $property = substr($name,4);
      $this->$property = $arguments[0];	
    }
    return $this;
  }
  
  public function execute($query){
    $this->arrayCollection = NULL;
    $this->sql->CONNECT();
    $result = mysql_query($query) or die(mysql_error());
	if(!is_bool($result)){
      while($row = mysql_fetch_assoc($result)) { $this->arrayCollection[] = $row; }
	  return $this->arrayCollection;   	  
	}else{
	  return true;	
	}
  }
  
  public function db_close(){ mysql_close(); }
  
  public function __get($field) {
    if($field == 'name') {
      return $this->username;
    }
  }  
  
  public function __set($field, $value) {
    if($field == 'name') {
      $this->username = $value;
    }
  }  
  
  function formatDate($date,$format){
	
  $aux = explode("-",$date);
  $mes = NULL;
  switch($aux[1]){
    case "01": $mes = "Enero"; 
	break;
    case "02": $mes = "Febrero"; 
	break;
    case "03": $mes = "Marzo"; 
	break;
    case "04": $mes = "Abril"; 
	break;
    case "05": $mes = "Mayo"; 
	break;
    case "06": $mes = "Junio";
	break;	
    case "07": $mes = "Julio";
	break;  
    case "08": $mes = "Agosto";
	break;
    case "09":  $mes = "Septiembre";
	break;
    case "10":  $mes = "Octubre";
	break;
    case "11":  $mes = "Noviembre";
	break;
    case "12":  $mes = "Diciembre";
	break;
  }
  switch($format){
    case "min": return $aux[2]."-".substr(strtoupper($mes),0,3)."-".$aux[0]; 
	break;
	case "med": return $aux[2]."-".$mes."-".$aux[0]; 
	break;
	case "max": return $aux[2]." de ".$mes." del ".$aux[0]; 
	break;
	case "alt": return $aux[2]." ".substr($mes,0,3);
	break;
  }
  
 
}

function formatDateTime($date,$format){
  $temp = explode(" ",$date);
  $aux = explode("-",$temp[0]);
  $mes = NULL;
  switch($aux[1]){
    case "01": $mes = "Enero"; 
	break;
    case "02": $mes = "Febrero"; 
	break;
    case "03": $mes = "Marzo"; 
	break;
    case "04": $mes = "Abril"; 
	break;
    case "05": $mes = "Mayo"; 
	break;
    case "06": $mes = "Junio";
	break;	
    case "07": $mes = "Julio";
	break;  
    case "08": $mes = "Agosto";
	break;
    case "09":  $mes = "Septiembre";
	break;
    case "10":  $mes = "Octubre";
	break;
    case "11":  $mes = "Noviembre";
	break;
    case "12":  $mes = "Diciembre";
	break;
  }
  switch($format){
    case "min": return $aux[2]."-".substr(strtoupper($mes),0,3)."-".$aux[0]." ".$temp[1]; 
	break;
	case "med": return $aux[2]."-".$mes."-".$aux[0]." ".$temp[1];
	break;
	case "max": return $aux[2]." de ".$mes." del ".$aux[0]." ".$temp[1];
	break;
	case "alt": return $aux[2]." ".substr($mes,0,3);
	break;
  }
  
 
}

  
}
?>