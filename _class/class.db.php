<?php
if(!defined('SERVER_USER')){ require(dirname(__FILE__)."/../_config/config.php"); }

class db { 
  
  var $db_name = SERVER_DB;
  
function db(){ } 

function setDatabase($name = NULL){
  $this->db_name = $name;
}


function CONNECT(){
  $connect = mysql_connect(SERVER_HOST,SERVER_USER,SERVER_PASS) or die("Error connecting to Database! " . mysql_error());
  mysql_select_db(SERVER_DB, $connect) or die("Cannot select database! " . mysql_error());
  mysql_query ("SET NAMES 'utf8'");
}



function getColumns($table) {
  $arrayCollection = NULL;
  $arrayCollection = array();
  $this->CONNECT();
  $cont = 0;
  $query = "SHOW COLUMNS FROM ".$table;
  $result = mysql_query($query) or die(mysql_error());
  while($row = mysql_fetch_array($result)) {
    $arrayCollection[$cont] = $row['Field']; 
	$cont++;
  }
  return $arrayCollection;
}

function getTables($db) {
  $arrayCollection = array();
  $cont = 0;
  $query = "SHOW TABLES FROM ".$db;
  $result = mysql_query($query) or die(mysql_error());
  while($row = mysql_fetch_array($result)) {
    $arrayCollection[$cont] = $row[0]; 
	$cont++;
  }
  return $arrayCollection;
}

 

} 

?>