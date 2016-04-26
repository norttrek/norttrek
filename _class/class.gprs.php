<?php

require_once("class.helper.php");

class GPRS extends Helper {

  
    
  public function __construct(){ $this->sql = new db(); }
  
  public function getCLientAssetByImei($imei = NULL){
    $query = 'SELECT * FROM client_asset WHERE imei = "'.$imei.'"  LIMIT 1';
     
  return $this->execute($query); 
  } 


  public function getLastReport($imei = NULL){
    $query = 'SELECT * FROM gprs WHERE imei = "'.$imei.'" AND date!="0000-00-00 00:00:00" ORDER BY date DESC LIMIT 2';
    if($this->status!=NULL) $query .= " AND unidad.status=".$this->status; 
	return $this->execute($query); 
  } 
  public function getGeoAlerts($imei = NULL){
    $query = "SELECT geo_alarms FROM  client_asset WHERE imei = ".$imei;
    return $this->execute($query); 
  } 
 

  public function update_alarm($imei = NULL,$alarm =null,$alarmVal){
    $query = 'UPDATE client_asset set '.$alarm.'='.$alarmVal . " WHERE imei=".$imei;
    // return $query;
    return $this->execute($query); 
  } 

  public function saveTempAlarms($imei = NULL,$speed = NULL, $alarm_temp1a,$alarm_temp1b){
    $alarm_temp1 = $alarm_temp1a."/".$alarm_temp2;

    $query = "UPDATE client_asset SET alarm_temp2 =  '".$alarm_temp2."', alarm_temp1 =  '".$alarm_temp1."'  WHERE imei =  '".$imei."'";
  //return $query; 
  return $this->execute($query);
  }

  public function setSpeed($imei = NULL,$speed = NULL){

    $query = "UPDATE client_asset SET speedAlarm =  '".$speed."' WHERE imei =  '".$imei."'";
	//return $query; 
	return $this->execute($query);
  }

  public function saveTrack($id_client = NULL,$track = NULL,$image=null,$total_mts=null,$track_name=null,$tolerancia =null){
 
    $query = "INSERT into client_track SET id_client =  '".$id_client."' ,track_name = '".$track_name."' ,track_points =  '".$track."', tolerancia='".$tolerancia."', total_mts =  '".$total_mts."', image =  '".$image."' " ;
   //return $query; 
  return $this->execute($query); 
  }
  public function getTrack( ){

    $query = "SELECT * FROM client_track WHERE id = 10" ;
  //return $query; 
  return $this->execute($query);
  }
 

public function deltrack($id = NULL){

    $query = "DELETE FROM client_track WHERE id=" .$id;
   
  return $this->execute($query);
  }


public function setReportTime($imei = NULL,$time = NULL){

    $query = "UPDATE client_asset SET reportTime =  '".$time."' WHERE imei =  '".$imei."'";
	//return $query; 
	return $this->execute($query);
  }

  public function setLastResetTime($imei = NULL){
  	$query = "UPDATE client_asset SET lastResetTime =  '".date("Y-m-d H:i:s")."' WHERE imei =  '".$imei."'";
	//return $query; 
	return $this->execute($query);
  }
  public function setSpeedLimit($imei = NULL,$speed = NULL){

    $query = "UPDATE client_asset SET speedLimit =  '".$speed."' WHERE imei =  '".$imei."'";
	//return $query; 
	return $this->execute($query);
  }

  public function ActiveSpeedAlarm($imei = NULL){

    $query = "UPDATE client_asset SET speedAlarmActive = 1 WHERE imei =  '".$imei."'";
	//return $query; 
	return $this->execute($query);
  }

  public function ActiveSpeedLimit($imei = NULL){
     $query = "UPDATE client_asset SET speed_limitActive = 1 WHERE imei =  '".$imei."'";
	//return $query; 
	return $this->execute($query);
  }
  public function DisableSpeedAlarm($imei = NULL){

    $query = "UPDATE client_asset SET speedAlarmActive = 0 WHERE imei =  '".$imei."'";
	//return $query; 
	return $this->execute($query);
  }

  public function DisableSpeedLimit($imei = NULL){

    $query = "UPDATE client_asset SET speed_limitActive = 0 WHERE imei =  '".$imei."'";
	//return $query; 
	return $this->execute($query);
  } 
  public function getLastAlerts($imei = NULL){
    $query = 'SELECT * FROM alert WHERE imei = "'.$imei.'"  ORDER BY gprs_id DESC LIMIT 15';
	//return $query;
    $id_Array = array();
  $result =  $this->execute($query);
	 foreach ($result as $key => $value ) {
      
     $query2 = 'SELECT date FROM gprs WHERE id = "'.$value['gprs_id'].'"  LIMIT 1';
     $result2 =  $this->execute($query2);
    // $result[$key]['date'] =  $result2[$key]['date'];
     $result[$key]['date'] = $result2[0]['date'];
     
     //$result['date'] = $result_q['date'];
      
      //array_push($id_Array,  $result );*/
   }
   return $result;
  // return $id_Array;
  }

  public function getLastAlertsbyCode($imei = NULL){

    $query = 'SELECT * FROM alert WHERE imei = "'.$imei.'"  ORDER BY gprs_id DESC LIMIT 15';
  //return $query;
    $id_Array = array();
  $result =  $this->execute($query);
   foreach ($result as $key => $value ) {
      
     $query2 = 'SELECT date FROM gprs WHERE id = "'.$value['gprs_id'].'"  LIMIT 1';
     $result2 =  $this->execute($query2);
    // $result[$key]['date'] =  $result2[$key]['date'];
     $result[$key]['date'] = $result2[0]['date'];
     
     //$result['date'] = $result_q['date'];
      
      //array_push($id_Array,  $result );*/
   }
   return $result;
  // return $id_Array;
  }

  public function getLastAlert($gprs_id = NULL){
  	if($gprs_id != null || $gprs_id != 0){
  		$query = 'SELECT * FROM alert WHERE gprs_id = "'.$gprs_id.'"';
		return $this->execute($query);
	} 
  }
    public function get_rssi_value($value = NULL){
 	abs($value) ;
 	$value = $value*-1;
  	switch($value){
	  case $value <= 75: 
	  		$val = 5;
	  		break;
	  case $value >= 76 AND $value <= 83: 
	  		$val = 4;
	  		break;
	  case $value >= 84 AND $value <= 95: 
	  		$val = 3;
	  		break;
	  case $value >= 96 AND $value <= 105: 
	  		$val = 2;
	  		break;
	  case $value >= 106 AND $value <= 110: 
	  		$val = 1;
	  		break;
	  case $value >= 111: 
	  		$val = 0;
	  		break;
	}
	return $val;
  }
  public function getGprsReport($imei = NULL){
    $query = 'SELECT * FROM gprs WHERE id > 0 AND date != "0000-00-00 00:00:00" ';
    if($imei!=NULL) $query.=" AND imei =".$imei."";
	if($this->search!=NULL) $query .= " AND ".$this->search_field." LIKE '%".$this->search."%'";
	if($this->between!=NULL) $query .= " AND date BETWEEN ".$this->between; 
	if($this->in!=NULL) $query .= " AND imei IN (".$this->in.")";
	if($this->order!=NULL) $query .= " ORDER BY ".$this->order; 
	if($this->limit!=NULL) $query .= " LIMIT ".$this->limit;
	return $this->execute($query); 
  }
  
  public function getGprsGeoFenceReport($imei = NULL){
    $query = 'SELECT DISTINCT date,imei,lat,lng,v2_fuel1,v2_fuel2,v2_fuel3,v2_temp1,v2_temp2 FROM gprs WHERE id > 0 ';
    if($imei!=NULL) $query.=" AND imei =".$imei."";
	if($this->search!=NULL) $query .= " AND ".$this->search_field." LIKE '%".$this->search."%'";
	if($this->between!=NULL) $query .= " AND date BETWEEN ".$this->between; 
	if($this->in!=NULL) $query .= " AND imei IN (".$this->in.")";
	
	//$query .= ' GROUP BY DAY(date)';
	if($this->order!=NULL) $query .= " ORDER BY ".$this->order; 
	if($this->limit!=NULL) $query .= " LIMIT ".$this->limit;
	return $this->execute($query); 
  //return $query;
  }
  
 public function getAssetRoute($imei = NULL){
    $query = 'SELECT * FROM gprs WHERE imei = '.$imei;
	if($this->between!=NULL) $query .= " AND date BETWEEN ".$this->between; 
    if($this->status!=NULL) $query .= " AND gps_status='".$this->status."'"; 
	$query .= ' AND lat != 0 ORDER BY date DESC';
	return $this->execute($query); 
  }


public function updateEmail($id = NULL,$email=NULL){
    $query = 'UPDATE  client_info set email="'.$email.'" WHERE id_client = '.$id; 
    // return $query;
    return $this->execute($query); 
  }



  public function getAssetRouteOpt($imei = NULL,$fields){
    $fields_query ='';
    foreach ($fields as $key => $name) {
      $fields_query .= $name . ',';
    }
    $fields_query = trim($fields_query, ',');

    $query = 'SELECT ' . $fields_query. '  FROM gprs WHERE imei = '.$imei;
  if($this->between!=NULL) $query .= " AND date BETWEEN ".$this->between; 
    if($this->status!=NULL) $query .= " AND gps_status='".$this->status."' "; 
  //$query .= ' AND lat != 0 ORDER BY date DESC';
  $query .= '   ORDER BY date DESC';
  //return $query;

  return $this->execute($query); 
  }
  
  public function get_geo_info($id){
      $query ="SELECT * FROM client_geofence WHERE id=" . $id;
      return $this->execute($query); 
  }
  
  
  public function get_status($id){
    $status = NULL;
	switch($id){
	  case "01": $status = 'Alarma de P&aacute;nico (SOS)'; break;
	  case "02": $status = 'Alarma Motor Encendido (i3)'; break;
	  case "03": $status = 'Alarma Motor Apagado (i3)'; break;
	  case "04": $status = 'Exceso de Velocidad'; break;
	  case "05": $status = 'Resumen de Velocidad'; break;
	  case "06": $status = 'Desconexi&oacute;n de Fuente de Poder'; break;
	  case "07": $status = 'Conexi&oacute;n de Fuente de Poder'; break;
	  case "08": $status = 'Arm'; break;
	  case "09": $status = 'Disarm'; break;
	  case "10": $status = 'Geocerca (Salida)'; break;
	  case "11": $status = 'Geocerca (Entrada)'; break;
	  case "12": $status = 'Puerta Abierta (i1)'; break;
	  case "13": $status = 'Puerta Cerrada (i1)'; break;
	  case "14": $status = 'Alarma Desconexi&oacute;n Antena GPS'; break;
	  case "15": $status = 'Alarma Conexi&oacute;n Antena GPS'; break;
	  case "23": $status = 'Reporte de Posici&oacute;n'; break;
	  case "30": $status = 'Reporte de Movimiento (Tremble)'; break;
	  case "39": $status = 'Alarma (Sin Movimiento)'; break;
	  case "40": $status = 'Bater&iacute;a Baja'; break;
	}
    return $status;
  }
  
   function get_gprs_report($search){
	if($search!=NULL){
	$query = 'SELECT cu.*, (SELECT date_server FROM gprs WHERE gprs.imei = cu.imei AND gprs.date != "0000-00-00 00:00:00" ORDER BY gprs.date DESC LIMIT 1) AS fecha_servidor,
								(SELECT date FROM gprs WHERE gprs.imei = cu.imei AND gprs.date != "0000-00-00 00:00:00" ORDER BY gprs.date DESC LIMIT 1) AS fecha,
		 						(SELECT gps_status FROM gprs WHERE gprs.imei = cu.imei AND gprs.date != "0000-00-00 00:00:00" ORDER BY gprs.date DESC LIMIT 1 ) AS status, 
								(SELECT CONCAT(lat,",",lng) FROM gprs WHERE gprs.imei = cu.imei AND gprs.date != "0000-00-00 00:00:00" ORDER BY gprs.date DESC LIMIT 1) AS coor, client 
	FROM client_asset cu INNER JOIN client c ON c.id=cu.id_client  WHERE imei="'.$search.'" OR no LIKE "%'.$search.'%" OR data LIKE "%'.$search.'%" OR client LIKE "%'.$search.'%"  ORDER BY fecha ASC';
	}else{
		 $query = 'SELECT cu.*, (SELECT date_server FROM gprs WHERE gprs.imei = cu.imei AND gprs.date != "0000-00-00 00:00:00" ORDER BY gprs.date DESC LIMIT 1) AS fecha_servidor,
		 						(SELECT date FROM gprs WHERE gprs.imei = cu.imei AND gprs.date != "0000-00-00 00:00:00" ORDER BY gprs.date DESC LIMIT 1) AS fecha,
		 						(SELECT gps_status FROM gprs WHERE gprs.imei = cu.imei AND gprs.date != "0000-00-00 00:00:00" ORDER BY gprs.date DESC LIMIT 1 ) AS status, 
								(SELECT CONCAT(lat,",",lng) FROM gprs WHERE gprs.imei = cu.imei AND gprs.date != "0000-00-00 00:00:00" ORDER BY gprs.date DESC LIMIT 1) AS coor, client 
	FROM client_asset cu INNER JOIN client c ON c.id=cu.id_client ORDER BY fecha ASC';
	}
	return $this->execute($query); 
  }
  
  public function get_iostatus($hex){
    if($hex!=NULL){
      $bin = base_convert($hex, 16, 2);
      $iostatus = NULL;
      if($bin[4]==1){ $iostatus["ignition"] = 1;  } else{ $iostatus["ignition"] = 0; }
      if($bin[8]==1){ $iostatus["ignition_cut"] = 1;  } else{ $iostatus["ignition_cut"] = 0; } //cortado el motor
      if($bin[9]==1){ $iostatus["ignition_blocked"] = 1;  } else{ $iostatus["ignition_blocked"] = 0; } //bloqueo de marcha
	   return $iostatus;
	  }
	}
	

function get_fuel_alt($vol,$t){
  $gps_vol = $vol;
  $idx = NULL;
  for($i=0;$i<count($t);$i++){
    if($gps_vol >= $t[$i]['vol'] && $gps_vol <= $t[$i+1]['vol']){
	  $idx = $i;
	  break;
    }   
  }
  $dif_vol = $t[$idx+1]['vol']-$t[$idx]['vol'];
  $ind_comb = number_format((($t[$idx+1]['lts']-$t[$idx]['lts'])/($t[$idx+1]['vol']-$t[$idx]['vol'])),2);
  $lts = $t[$idx]['lts']+($ind_comb*($gps_vol-$t[$idx]['vol']));
  return number_format($lts,2);
}

function get_fuel_by_calibracion($vol,$t,$date,$tank){
          //Recorrer los 3 tanques
          //Preparar las variables para que guarden el ultimo valor menor de voltaje y litros
           //$mifirePHP->log('lhhhhos');
          


          $voltmenor = 0;
          $litrosmenor = 0;
          $key =  substr($key,1); 
          
          //Recorrer los voltajes para encontrar en que posicion se encuentra el voltaje enviado desde el equipo
          $LastCalibration = count($t); 
          $calibracion = 1;
          foreach ($t as $num => $linea) { 
           $volt_report = $vol;
           if($tank == "1"){
             $volt_report =  $volt_report - .06;
           }elseif($tank == "2"){
             $volt_report =  $volt_report - .03;
           }elseif ($tank == "3") {
             $volt_report =  $volt_report - .03;
           } 
          if($volt_report > 5 && $volt_report < 6){
             //test($calibracion,'contando calibracion');
            if($calibracion == $LastCalibration){
              $lastVolt = $linea['vol']; 
              $volt_report=$linea['vol']; 
            }
          } 
          $calibracion++;
          // Si el volt que manda el equipo es menor al volt se guarda el voltaje mayor
          if($volt_report <= $linea['vol']){
            
            // Formula para calcular los litros
            $voltmayor = $linea['vol'];
            $litrosmayor = $linea['lts'];
            $diferenciadevolts = $voltmayor - $voltmenor;
            $diferenciadelitros = $litrosmayor - $litrosmenor;
            $voltsporcalcular = $volt_report - $voltmenor;

            $a = $voltsporcalcular * $diferenciadelitros;

            $a = $a/$diferenciadevolts;

            $litros = $a + $litrosmenor; 
        
            //Se rompe el ciclo cuando se encuentra el voltaje mayor
           // test($vol." regreso " .$litros." hora: ".$date,'conversion');
          return $litros;
            break;
          } 
          // Si el voltaje no es mayor, se guarda como voltaje menor y continua con el ciclo
          $voltmenor = $linea['vol'];
          $litrosmenor = $linea['lts']; 
        } 
}
	
function get_fuel_lt($d,$l, $a,$v,$vll,$p){
  $diametro = $d;
  $largo = $l;
  $altura_sensor = $a;
  $variacion = $v;
  $voltaje_lleno = $vll;
  $radio = $diametro/2;
  $espacio = $diametro - $variacion - $altura_sensor;
  //manipulados
  $diametro_alt = $diametro - $variacion;
  $largo_alt = $largo - $variacion;
  $radio_alt = $diametro_alt/2;
		
  $parametro = $p;
		
  $fsensor = ((($parametro*100)/$voltaje_lleno)/100);
  $hsensor = $fsensor * $altura_sensor+$espacio;
  $r1 = pow($radio_alt,2) *(3.1416/2-asin(1-$hsensor/$radio_alt))-($radio_alt-$hsensor)*sqrt($hsensor*(2*+$radio_alt-$hsensor));
  $r2 = $r1*$largo_alt;
  $litros = $r2/1000;
  return $litros;
}	

function get_fuel_lt_v2($d,$l, $a,$v,$vll,$p){
  $diametro = $d;
  $largo = $l;
  $altura_sensor = $a;
  $variacion = $v;
  $voltaje_lleno = $vll;
  $radio = $diametro/2;
  $espacio = $diametro - $variacion - $altura_sensor;
  //manipulados
  $diametro_alt = $diametro - $variacion;
  $largo_alt = $largo - $variacion;
  $radio_alt = $diametro_alt/2;
    
  $parametro = $p - .03;
    
  $fsensor = ((($parametro)/$voltaje_lleno));
  $hsensor = $fsensor * $altura_sensor+$espacio;
  $r1 = pow($radio_alt,2) *(3.1416/2-asin(1-$hsensor/$radio_alt))-($radio_alt-$hsensor)*sqrt($hsensor*(2*+$radio_alt-$hsensor));
  $r2 = $r1*$largo_alt;
  $litros = $r2/1000;
  return $litros;
} 


function get_device($imei = NULL){
	 
	$query =  "SELECT id_device FROM imei WHERE imei=".$imei;
	$result = $this->execute($query); 
	$id =  $result[0]['id_device'];

	$query2 = "SELECT device FROM device WHERE id =".$id;
	$result2 = $this->execute($query2);
	$sql = "SELECT no FROM client_asset WHERE imei=".$imei;
	$number = $this->execute($sql);
	$number = $number[0]['no'];
	$info = array($result2[0]['device'],$number);
	return $info;
	
}
public function getLastResetTime($imei){
	$query = 'SELECT LastResetTime FROM client_asset WHERE imei = '.$imei.' LIMIT 1';
	$result = $this->execute($query); 
	return $result ;
  }
public function getAlarms($imei){
    $query = "SELECT v2_eventCode,v2_sos,lat,lng,date FROM  gprs WHERE imei = ".$imei . " ORDER BY date DESC LIMIT 50";
    $result = $this->execute($query);
    return $result;
}
public function getResetStatus($imei = NULL,$lastReportTime= NULL,$lastClickReset= NULL){
	$device = $this->get_device($imei);
	$device[0]; 
	switch($device[0]) {
          case "LMU-700": 
               $limitMinsLastClick = 15;
               $limitDaysLastReport = 2880;
               $limitMinsLastReport = 90;
          break;
          case "LMU-800": 
               $limitMinsLastClick = 15;
               $limitDaysLastReport = 2880;
               $limitMinsLastReport = 90;
          break;
          case "LMU-4200": 
                $limitMinsLastClick = 15;
               $limitDaysLastReport = 2880;
               $limitMinsLastReport = 90;
          break;
          case "LMU-1100": 
                $limitMinsLastClick = 15;
               $limitDaysLastReport = 2880;
               $limitMinsLastReport = 90;
          break;
          case "AT09": 
               /*$limitMinsLastClick = 40;
               $limitDaysLastReport = 2880;
               $limitMinsLastReport = 150;*/
               $limitMinsLastClick = 40;
               $limitDaysLastReport = 2880;
               $limitMinsLastReport = 120;
          break;
      };
    $date = strtotime(date('m/d/Y h:i:s a', time()));
    $diferencialastReportTime = round(abs($lastReportTime - $date) / 60,2). " minute";
    if($diferencialastReportTime > $limitDaysLastReport  ){
	  //NO SE ENVIA 
	  $timeConditionDays = 0;

	 }elseif($diferencialastReportTime < $limitDaysLastReport && $diferencialastReportTime > $limitMinsLastReport){
	  
	  $timeConditionDays = 1;
	 }
	 if($lastClickReset == 'no click'){
	 	$lastClickReset = $limitMinsLastClick +1;
	 }
	 $diferenciaLastClickReport = round(abs($lastClickReset - $date) / 60,2). " minute";
     
     if($diferenciaLastClickReport <= $limitMinsLastClick){
     	//NO SE ENVIA
     	$timeConditionMinutes = 0;
     }else{
     	$timeConditionMinutes = 1;
     }

     if($timeConditionMinutes == 0 && $timeConditionDays == 0){
     	$status = 0;
     }elseif($timeConditionMinutes == 1 && $timeConditionDays == 1){
     	$status = 1;
     }elseif ($timeConditionMinutes == 1 && $timeConditionDays == 0) {
     	$status = 0;
     }elseif ($timeConditionMinutes == 0 && $timeConditionDays == 1) {
     	$status = 0;
     }
	return $status;
}

function update_asset($imei,$elock,$temp1,$temp2,$engine,$marcha,$formula,$POS1,$POS2,$POS3,$LT1,$LT2,$LT3,$DA1,$DA2,$DA3,$LG1,$LG2,$LG3,$VAR1,$VAR2,$VAR3,$VA1,$VA2,$VA3,$AS1,$AS2,$AS3,$VL1,$VL2,$VL3){
 

 $query = 'UPDATE client_asset SET elock = "'.$elock.'",
                                   temp1 = "'.$temp1.'",
                                    temp2 = "'.$temp2.'",
                                     engine = "'.$engine.'",
                                      marcha = "'.$marcha.'",
                                      formula = "'.$formula.'",
                                       pos1 = "'.$POS1.'", 
                                       pos2 = "'.$POS2.'",
                                       pos3 = "'.$POS3.'",
                                       lt1 = "'.$LT1.'",
                                       lt2 = "'.$LT2.'",
                                       lt3 = "'.$LT3.'",
                                       da1 = "'.$DA1.'",
                                       da2 = "'.$DA2.'",
                                       da3 = "'.$DA3.'",
                                       lg1 = "'.$LG1.'",
                                       lg2 = "'.$LG2.'",
                                       lg3 = "'.$LG3.'",
                                       var1 = "'.$VAR1.'",
                                       var2 = "'.$VAR2.'",
                                       var3 = "'.$VAR3.'",
                                       va1 = "'.$VA1.'",
                                       va2 = "'.$VA2.'",
                                       va3 = "'.$VA3.'",
                                       as1 = "'.$AS1.'",
                                       as2 = "'.$AS2.'",
                                       as3 = "'.$AS3.'",
                                       vl1 = "'.$VL1.'",
                                       vl2 = "'.$VL2.'",
                                       vl3 = "'.$VL3.'" 

                                WHERE imei = "'.$imei.'"';
  $result = $this->execute($query); 
  return $result ;
                               
}

function saveUnidad($imei,$nombre_u,$odometro,$marca,$modelo,$anio,$color,$placas,$pasajeros,$rendimiento,$ptara,$ejes,$chasis,$ccarga,$carrastre,$tcarga,$active,$newname){
  
  if($newname !=''){
    $query = 'UPDATE client_asset SET nombre_u = "'.$nombre_u.'",
                                    odometro = "'.$odometro.'",
                                    marca = "'.$marca.'",
                                    modelo = "'.$modelo.'",
                                    anio = "'.$anio.'",
                                    color = "'.$color.'",
                                    placa = "'.$placas.'",
                                    pasajeros = "'.$pasajeros.'",
                                    rendimiento = "'.$rendimiento.'",
                                    pesotara = "'.$ptara.'",
                                    ejes = "'.$ejes.'",
                                    chasis = "'.$chasis.'",
                                    capcarga = "'.$ccarga.'",
                                    caparrastre = "'.$carrastre.'",
                                    tdcarga = "'.$tcarga.'",
                                    act = "'.$active.'",
                                    imagen_u = "'.$newname.'" 

                                WHERE imei = "'.$imei.'"';
                              }else{
                                $query = 'UPDATE client_asset SET nombre_u = "'.$nombre_u.'",
                                    odometro = "'.$odometro.'",
                                    marca = "'.$marca.'",
                                    modelo = "'.$modelo.'",
                                    anio = "'.$anio.'",
                                    color = "'.$color.'",
                                    placa = "'.$placas.'",
                                    pasajeros = "'.$pasajeros.'",
                                    rendimiento = "'.$rendimiento.'",
                                    pesotara = "'.$ptara.'",
                                    ejes = "'.$ejes.'",
                                    chasis = "'.$chasis.'",
                                    capcarga = "'.$ccarga.'",
                                    caparrastre = "'.$carrastre.'",
                                    tdcarga = "'.$tcarga.'",
                                    act = "'.$active.'"  

                                WHERE imei = "'.$imei.'"';
                              }
   
  $result = $this->execute($query); 
  return $result ;
}
function saveEngine($imei,$rendimientokl,$cilindros,$transmision,$velocidades,$diferencial,$seriemotor){
  
  
    $query = 'UPDATE client_asset SET rendimientokl = "'.$rendimientokl.'",
                                    cilindros = "'.$cilindros.'",
                                    transmision = "'.$transmision.'",
                                    velocidades = "'.$velocidades.'",
                                    diferencial = "'.$diferencial.'",
                                    seriemotor = "'.$seriemotor.'" 

                                WHERE imei = "'.$imei.'"';
                              
   
  $result = $this->execute($query); 
  return $result ;
}


function saveMecanic($imei,$tecnomecanica,$ambiental,$neec,$fisicomecanica,$tpat,$seguro,$vencimiento,$poliza,$dof,$fmDate,$tcDate,$vaDate,$ctDate,$neDate,$usdot,$txdot){
  
  
    $query = 'UPDATE client_asset SET tecnomecanica = "'.$tecnomecanica.'",
                                    ambiental = "'.$ambiental.'",
                                    neec = "'.$neec.'",
                                    fisicomecanica = "'.$fisicomecanica.'",
                                    tpat = "'.$tpat.'",
                                    seguro = "'.$seguro.'",
                                    vencimiento = "'.$vencimiento.'",
                                    poliza = "'.$poliza.'",
                                    dof = "'.$dof.'",
                                    fmDate = "'.$fmDate.'",
                                    tcDate = "'.$tcDate.'",
                                    vaDate = "'.$vaDate.'",
                                    ctDate = "'.$ctDate.'",
                                    usdot = "'.$usdot.'",
                                    txdot = "'.$txdot.'",
                                    neDate = "'.$neDate.'" 

                                WHERE imei = "'.$imei.'"';
                              
   
  $result = $this->execute($query); 
  return $result ;
}



function getActiveTracks($imei){
    $query = "SELECT route_alarms FROM  client_asset WHERE imei = ".$imei;
    $result = $this->execute($query);
    return $result;
}
public function activeGeoRoute($imei = NULL,$ide_track=null,$active=null){
    $query = "SELECT route_alarms FROM  client_asset WHERE imei = ".$imei;
    //echo $query;
    $route_alarms = $this->execute($query);  
    if($route_alarms[0]['route_alarms'] == ''){ 
      $newActiveRoute =  array($ide_track=>$active); 
      $ActiveRoute = json_encode($newActiveRoute);
        $query2 = "UPDATE client_asset SET route_alarms ='".$ActiveRoute . "' WHERE imei='" . $imei . "'";
      $this->execute($query2); 
    }
    else{
      /*UNA PISTA A LA VEZ 
       $route_alarms[0]['geo_alarms'];
       print_r($route_alarms[0]['geo_alarms']);
      $json = $route_alarms[0]['geo_alarms'];
      $route_alarms = json_decode($json,true);
      $route_alarms[$ide_track] = $active;
      ;

      $alarm_push2 = json_encode($route_alarms);
      print_r($alarm_push2);
       $query2 = "UPDATE client_asset SET route_alarms ='".$alarm_push2 . "' WHERE imei='" . $imei . "'";
      $this->execute($query2); */ 
      // echo $route_alarms[0]['geo_alarms'] ; 
      $json = $route_alarms[0]['route_alarms'];
      $route_alarms = json_decode($json,true); 
      $route_alarms[$ide_track] = $active; 
      //print_r($route_alarms);
      echo $alarm_push2 = json_encode($route_alarms);
      // print_r($alarm_push2);
       $query2 = "UPDATE client_asset SET route_alarms ='".$alarm_push2 . "' WHERE imei='" . $imei . "'";
      $this->execute($query2);
    }
} 
function updateGeoAlarm($imei,$id_geo,$active,$pos){
  $newAlarms = array();
  $query = "SELECT geo_alarms FROM  client_asset WHERE imei = ".$imei;
  $alarms = $this->execute($query); 
  
 if($alarms == ''){
    $newAlarm =  array($id_geo=>array($pos=>$active));
    $alarm_push = json_encode($newAlarm);
    //echo $alarm_push;
    $query2 = "UPDATE client_asset SET geo_alarms ='".$alarm_push . "' WHERE imei='" . $imei . "'";
    echo $query2;
    $push = $this->execute($query2); 
    echo $push;
    //print_r($newAlarm);
    return $newAlarm ;
 }else{
 

  $json = $alarms[0]['geo_alarms'];
 
  
  $alarms = json_decode($json,true);

  if($alarms[$id_geo][$pos] == ''){
    $alarms[$id_geo][$pos] = $active;
  }else{
     $alarms[$id_geo][$pos] = $active;
  }
  $alarm_push2 = json_encode($alarms);
  $query2 = "UPDATE client_asset SET geo_alarms ='".$alarm_push2 . "' WHERE imei='" . $imei . "'";
 
    $push = $this->execute($query2); 

   return $push;
 }
  
}
function update_general_info($sms,$id){
   $query = "UPDATE client_info SET sms=".$sms." WHERE id=".$id;
   $result = $this->execute($query); 
  return $result ;
}
function set_regions($dates){
  
 
  $dates = trim($dates, ',');
$dates = explode(",", $dates);
$dmh=array();
foreach($dates as $key => $date){
  $date = trim($date,"'");
  $date_time  = strtotime($date);
  $date_and_hour =  explode(" ",$date);
  array_push($dmh, $date_and_hour);
   
}

 $regions = array();
 $dia_anterior = 0;
 $hora_anterior = 0;
 $len = count($dmh);
 $i=0;
foreach ($dmh as $key => $value) {
  $dia = explode( "-",$value[0]);

  $hora_reporte = $value[1];

  $dia_reporte = $dia[2];
  
  $mes_anterior = $dia[1];
  $anio_anterior = $dia[0];
  $hora_reporte = $value[1];

  if($dia_anterior == $dia_reporte){
    $hora_reporte ."<br>";
    $new = $hora_reporte;
  }else{
    // echo $dia_inicial . " no es igual a " . $dia_reporte;
    $start_hour = $hora_reporte;
    $start_date = $dia_reporte;

    $end_date = $dia_reporte;
    $end_hour = $hora_reporte;
   // echo "reporte: " . $value[0] . " " . $value [1] . "<br>";
    if($dia_anterior!=0){
       $ini = "'".$anio_anterior."-".$mes_anterior."-".$dia_anterior." ".$hora_anterior."'";
      
      $end = "'".$anio_anterior."-".$mes_anterior."-".$dia_anterior." ".$new."'";
      array_push($regions, $ini.",".$end);
    }
    $dia_anterior = $dia_reporte;
    $hora_anterior = $hora_reporte;
  }
   if ($i == $len - 1){
    $ini =  "'".$anio_anterior."-".$mes_anterior."-".$dia_anterior." ".$end_hour."'"; 
    $end =  "'".$anio_anterior."-".$mes_anterior."-".$dia_anterior." ".$hora_reporte."'";
    array_push($regions, $ini.",".$end);
 
    }
     $i++; 
}
return $regions;

}
}
?>