<?php

require_once("class.helper.php");

class Number extends Helper {
  var $id;
  var $no;
  var $serial_no;
  var $account;
  var $date_reg;
  var $status;
  

      
  public function __construct(){ $this->sql = new db(); }
  
  public function db($key){
    switch($key){
      case "insert": $query = "INSERT INTO number (no,serial_no,account,date_reg,status) VALUES ('".$this->no."','".$this->serial_no."','".$this->account."','".$this->date_reg."','".$this->status."')";
	  break;
      case "update": $query = "UPDATE number SET no = '".$this->no."', serial_no = '".$this->serial_no."' , account = '".$this->account."',date_reg = '".$this->date_reg."' WHERE id='".$this->id."'"; 
      break;
      case "delete": $query = "UPDATE number SET status=3 WHERE id='".$this->id."'";
      break;	  
    }
	$this->execute($query); 
    if($key=="insert"){ $this->lastInserted = mysql_insert_id(); }
  }  
  
  public function getLastInserted(){ return $this->lastInserted; }
  

  public function getNumber($id = NULL){
    $query = 'SELECT number.*,imei FROM number LEFT JOIN client_asset ON (number.no=client_asset.no) WHERE number.id > 0';
    if($id!=NULL) $query.=" AND number.id=".$id."";
	if($this->no!=NULL) $query .= " AND number.no=".$this->no; 
    if($this->status!=NULL) $query .= " AND number.status=".$this->status; 
	if($this->search!=NULL) $query .= " AND number.no LIKE '".$this->search."%' OR number.serial_no LIKE '%".$this->search."%'";
	if($this->order!=NULL) $query .= " ORDER BY ".$this->order; 
	if($this->limit!=NULL) $query .= " LIMIT ".$this->limit;
	return $this->execute($query); 
  } 
  
  public function getNumberNotUsed($id = NULL){
    $query = 'SELECT * FROM number WHERE status=1 AND no NOT IN (SELECT no FROM client_asset)';
	return $this->execute($query); 
  } 
  
    
}
?>