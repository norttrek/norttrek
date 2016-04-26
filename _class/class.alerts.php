<?php   
require_once("class.helper.php");
require_once("class.poly.php");
date_default_timezone_set('Etc/UTC');

require 'mail/PHPMailerAutoload.php';
echo "no 5005";
//Create a new PHPMailer instance
$mail = new PHPMailer;
//Tell PHPMailer to use SMTP
$mail->isSMTP();
//Enable SMTP debugging
// 0 = off (for production use)
// 1 = client messages
// 2 = client and server messages
$mail->SMTPDebug = 2;
//Ask for HTML-friendly debug output
$mail->Debugoutput = 'html';
//Set the hostname of the mail server
$mail->Host = "mail.norttrek.com";
//Set the SMTP port number - likely to be 25, 465 or 587
$mail->Port = 587;
//Whether to use SMTP authentication
$mail->SMTPAuth = true;
//Username to use for SMTP authentication
$mail->Username = "alertas@norttrek.com";
//Password to use for SMTP authentication
$mail->Password = "NALERTAS123*";
//Set who the message is to be sent from
$mail->setFrom('alertas@norttrek.com', 'Norttrek');
//Set an alternative reply-to address
$mail->addReplyTo('noreply@noreply.com', 'Norttrek');
//Set who the message is to be sent to

//Set the subject line

//Read an HTML message body from an external file, convert referenced images to embedded,
//convert HTML into a basic plain-text alternative body
$mail->msgHTML('<b>hola</b>');
//Replace the plain text body with one created manually
$mail->AltBody = 'This is a plain-text message body';

echo "<pre>";

class ALERTS extends Helper {

 public function distanceCalculation($point1_lat, $point1_long, $point2_lat, $point2_long, $unit = 'km', $decimals = 2) {
  // Cálculo de la distancia en grados
  $degrees = rad2deg(acos((sin(deg2rad($point1_lat))*sin(deg2rad($point2_lat))) + (cos(deg2rad($point1_lat))*cos(deg2rad($point2_lat))*cos(deg2rad($point1_long-$point2_long)))));
 
  // Conversión de la distancia en grados a la unidad escogida (kilómetros, millas o millas naúticas)
  switch($unit) {
    case 'km':
      $distance = $degrees * 111.13384; // 1 grado = 111.13384 km, basándose en el diametro promedio de la Tierra (12.735 km)
      break;
    case 'mi':
      $distance = $degrees * 69.05482; // 1 grado = 69.05482 millas, basándose en el diametro promedio de la Tierra (7.913,1 millas)
      break;
    case 'nmi':
      $distance =  $degrees * 59.97662; // 1 grado = 59.97662 millas naúticas, basándose en el diametro promedio de la Tierra (6,876.3 millas naúticas)
  }
  return round($distance, $decimals);
}
   
  public function getEmail($id_client){
     $query = "SELECT email FROM client_info WHERE id_client=" . $id_client;
     return $this->execute($query); 
  }

  public function getDistance($latitude1, $longitude1, $latitude2, $longitude2) {  
    $earth_radius = 6371;  
      
    $dLat = deg2rad($latitude2 - $latitude1);  
    $dLon = deg2rad($longitude2 - $longitude1);  
      
    $a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * sin($dLon/2) * sin($dLon/2);  
    $c = 2 * asin(sqrt($a));  
    $d = $earth_radius * $c;  
      
    return $d;  
  
}  
  public function __construct(){ $this->sql = new db(); }
  
  public function getLastReport( ){
    $sendAlert = array();
    $query = 'SELECT * FROM reports  order by id desc limit 1';
    $reports = $this->execute($query); 
    $lastid =  $reports[0]['report'];


    $query2 = "SELECT * FROM `gprs` WHERE id >= ". $lastid ." order by id desc";
    $reportsToReview = $this->execute($query2); 
    $last = $reportsToReview[0]['id'];
    

    $query3 = "INSERT into reports set report=". $last;
    $this->execute($query3); 

    $reportesrevisados = $last - $lastid +1;
    echo "Reviso id del " . $lastid . " al " . $last . " revisados: " . $reportesrevisados;
 
    foreach ($reportsToReview as $report) {
      
 
      $lat = $report['lat'];
      $lng = $report['lng'];
      $imei = $report['imei'];
      $date = $report['date'];

      $en = $report['v2_eng_status'];
      if($en==0){
        $motor = 'Apagado';
      }elseif($en==1){
        $motor = "Encendido";
      }else{
        $motor = "Sin Información";
      }


      $velocidad =  $report['v2_speed'];
      $coordenadas = "http://maps.google.es/?q=".$report['v2_latitude']."%20".$report['v2_longitude'];
      $report_id = $report['id'];
 



      //revisar pistas activas
       $queryAlarms = "SELECT id_client,alias,route_alarms,geo_alarms,route_status,route_history,sos,battery_low,eng_on,eng_off,device_off,device_on,1min,3min,10min,30min,60min,90min,2hr,3hr FROM client_asset WHERE imei= ". $report['imei'];
      $alarmas = $this->execute($queryAlarms);

       
      $tracks = $alarmas[0]['route_alarms'];
     $route_status = $alarmas[0]['route_status'];
      $route_history = $alarmas[0]['route_history'];
      $geo_alarms = $alarmas[0]['geo_alarms'];
      $unidad = $alarmas[0]['alias'];
      $id_client = $alarmas[0]['id_client'];
     
      $geo_alarms = json_decode($alarmas[0]['geo_alarms'],true);
      $tracks = json_decode($alarmas[0]['route_alarms'],true);
      

      //revisar event code
      echo $event_code = $report['v2_eventCode'];
      echo " <- eventcode";
       switch ($event_code) {
         case '1':
          $event_code_active = $alarmas[0]['sos'];
          echo "xsosx";
          if ($event_code_active==1) {
             $alert = array('unidad'=>$unidad, "mensaje"=>'SOS (boton de pánico)',"date"=>$date, "id_client" =>$id_client,"coordenadas"=>$coordenadas,"velocidad"=>$velocidad,"motor"=>$motor);
             array_push($sendAlert,$alert);
          }
          break;
          case '40':
          $event_code_active = $alarmas[0]['battery_low'];
          if ($event_code_active==1) {
             $alert = array('unidad'=>$unidad, "mensaje"=>'Bateria baja ',"date"=>$date, "id_client" =>$id_client,"coordenadas"=>$coordenadas,"velocidad"=>$velocidad,"motor"=>$motor);
             array_push($sendAlert,$alert);
          } 
          break;
          case '2':
          $event_code_active = $alarmas[0]['eng_on'];
          if ($event_code_active==2) {
             $alert = array('unidad'=>$unidad, "mensaje"=>' Evento: Encendido de Motor ',"date"=>$date, "id_client" =>$id_client,"coordenadas"=>$coordenadas,"velocidad"=>$velocidad,"motor"=>$motor);
             array_push($sendAlert,$alert);
          } 
          break;
          case '3':
          $event_code_active = $alarmas[0]['eng_off'];
          if ($event_code_active==1) {
             $alert = array('unidad'=>$unidad, "mensaje"=>' Evento: Apagado de Motor ',"date"=>$date, "id_client" =>$id_client,"coordenadas"=>$coordenadas,"velocidad"=>$velocidad,"motor"=>$motor);
             array_push($sendAlert,$alert);
          } 
          break;
          case '6':
          $event_code_active = $alarmas[0]['device_off'];
          if ($event_code_active==1) {
             $alert = array('unidad'=>$unidad, "mensaje"=>' Evento: Apagado de equipo ',"date"=>$date, "id_client" =>$id_client,"coordenadas"=>$coordenadas,"velocidad"=>$velocidad,"motor"=>$motor);
             array_push($sendAlert,$alert);
          } 
          break;
          case '7':
          $event_code_active = $alarmas[0]['device_on'];
          if ($event_code_active==1) {
             $alert = array('unidad'=>$unidad, "mensaje"=>' Evento: Encendido de equipo ',"date"=>$date, "id_client" =>$id_client,"coordenadas"=>$coordenadas,"velocidad"=>$velocidad,"motor"=>$motor);
             array_push($sendAlert,$alert);
          } 
          break;
          case '31':
          $event_code_active = $alarmas[0]['1min'];
          if ($event_code_active==1) {
             $alert = array('unidad'=>$unidad, "mensaje"=>' Evento: Unidad detenida por 1 minuto ',"date"=>$date, "id_client" =>$id_client,"coordenadas"=>$coordenadas,"velocidad"=>$velocidad,"motor"=>$motor);
             array_push($sendAlert,$alert);
          } 
          break;
          case '32':
          $event_code_active = $alarmas[0]['3min'];
          if ($event_code_active==1) {
             $alert = array('unidad'=>$unidad, "mensaje"=>' Evento: Unidad detenida por 3 minutos ',"date"=>$date, "id_client" =>$id_client,"coordenadas"=>$coordenadas,"velocidad"=>$velocidad,"motor"=>$motor);
             array_push($sendAlert,$alert);
          } 
          break;
          case '33':
          $event_code_active = $alarmas[0]['10min'];
          if ($event_code_active==1) {
             $alert = array('unidad'=>$unidad, "mensaje"=>' Evento: Unidad detenida por 10 minutos ',"date"=>$date, "id_client" =>$id_client,"coordenadas"=>$coordenadas,"velocidad"=>$velocidad,"motor"=>$motor);
             array_push($sendAlert,$alert);
          } 
          break;
          case '35':
          $event_code_active = $alarmas[0]['30min'];
          if ($event_code_active==1) {
             $alert = array('unidad'=>$unidad, "mensaje"=>' Evento: Unidad detenida por 30 minutos ',"date"=>$date, "id_client" =>$id_client,"coordenadas"=>$coordenadas,"velocidad"=>$velocidad,"motor"=>$motor);
             array_push($sendAlert,$alert);
          } 
          break;
          case '36':
          $event_code_active = $alarmas[0]['60min'];
          if ($event_code_active==1) {
             $alert = array('unidad'=>$unidad, "mensaje"=>' Evento: Unidad detenida por 60 minutos ',"date"=>$date, "id_client" =>$id_client,"coordenadas"=>$coordenadas,"velocidad"=>$velocidad,"motor"=>$motor);
             array_push($sendAlert,$alert);
          } 
          break;
          case '37':
          $event_code_active = $alarmas[0]['90min'];
          if ($event_code_active==1) {
             $alert = array('unidad'=>$unidad, "mensaje"=>' Evento: Unidad detenida por 90 minutos ',"date"=>$date, "id_client" =>$id_client,"coordenadas"=>$coordenadas,"velocidad"=>$velocidad,"motor"=>$motor);
             array_push($sendAlert,$alert);
          } 
          break;
          case '38':
          $event_code_active = $alarmas[0]['2hr'];
          if ($event_code_active==1) {
             $alert = array('unidad'=>$unidad, "mensaje"=>' Evento: Unidad detenida por 2 horas',"date"=>$date, "id_client" =>$id_client,"coordenadas"=>$coordenadas,"velocidad"=>$velocidad,"motor"=>$motor);
             array_push($sendAlert,$alert);
          } 
          break;
          case '39':
          $event_code_active = $alarmas[0]['3hr'];
          if ($event_code_active==1) {
             $alert = array('unidad'=>$unidad, "mensaje"=>' Evento: Unidad detenida por 3 horas',"date"=>$date, "id_client" =>$id_client,"coordenadas"=>$coordenadas,"velocidad"=>$velocidad,"motor"=>$motor);
             array_push($sendAlert,$alert);
          } 
          break;
      } 




      foreach ($geo_alarms as $key => $values) {
       
        $geocerca_info = "SELECT * FROM client_geofence WHERE id=" . $key;
        $geocerca_info = $this->execute($geocerca_info);
        //echo "GEO INFO";
         
        $tipo = $geocerca_info[0]['type'];

        if($tipo == 'circle'){   
          $geocerca_info[0]['data'];
          
          //print_r($geocerca_info[0]['data']);
          $geocerca_circle = json_decode($geocerca_info[0]['data'],true);
          print_r($geocerca_circle);
          $geofoto = $geocerca_circle['preview'];
          echo $geofoto;
          echo "dentrodecricle";
          //echo "nombre:" . $geocerca_circle['name'];
          //echo "<br> variables: " . "lat: ". $lat . "lat: ".$lng ."lat: ".$geocerca_circle['lat']."lat: ".$geocerca_circle['lng'] ."lat: ". $geocerca_circle['radius'];

          $distance = $this->getDistance($lat,$lng,$geocerca_circle['lat'],$geocerca_circle['lng']);
          //echo "<br>ditancia: " . $distance;
          $distance = $distance * 1000;
           $georcercaName = $geocerca_circle['name'];
          if($distance<=$geocerca_circle['radius']){ $status = 'inside'; }else{ $status = 'outside'; }
          $in = $values['in'];
          $out = $values['out'];
         if($values['status']==''){
       
            $geo_alarms[$key]['status']=$status;
             
            $updateAlarm = json_encode($geo_alarms);
            
            $queryNoStatus = "UPDATE client_asset SET geo_alarms='".$updateAlarm ."' WHERE imei=".$imei;
           
            $this->execute($queryNoStatus);
            if($status == 'inside'){
               $alert = array('unidad'=>$unidad, "geofoto"=>"geofoto".$geofoto,"mensaje"=>' dentro de la geocerca ' . $georcercaName,"date"=>$date, "id_client" =>$id_client,"coordenadas"=>$coordenadas,"velocidad"=>$velocidad,"motor"=>$motor);
               array_push($sendAlert,$alert);
            }
          }elseif ($values['status'] == 'outside' AND $status == 'inside') {
            
            if($in ==1){
               //UPDATE GEOCERCA INFO
               $queryUpdateGeo ="INSERT INTO  client_geofence_history (id_geocerca,status,date,imei)values(".$key.",'in','".$date."','".$imei."')";
               $this->execute($queryUpdateGeo);
               $alert = array('unidad'=>$unidad,"geofoto"=>$geofoto, "mensaje"=>' entro a la geocerca' . $georcercaName,"date"=>$date, "id_client" =>$id_client,"coordenadas"=>$coordenadas,"velocidad"=>$velocidad,"motor"=>$motor);
               array_push($sendAlert,$alert);
            }

            $geo_alarms[$key]['status']=$status;
            $updateAlarm = json_encode($geo_alarms);
            $queryNoStatus = "UPDATE client_asset SET geo_alarms='".$updateAlarm ."' WHERE imei=".$imei;
            $this->execute($queryNoStatus);
          }elseif ($values['status']=='inside' AND $status =='outside') {
            if($out ==1){
              //UPDATE GEOCERCA INFO
               $queryUpdateGeo ="INSERT INTO  client_geofence_history (id_geocerca,status,date,imei)values(".$key.",'out','".$date."','".$imei."')";
               $this->execute($queryUpdateGeo);
               $alert = array('unidad'=>$unidad,"geofoto"=>$geofoto, "mensaje"=>' salio de la geocerca' . $georcercaName,"date"=>$date, "id_client" =>$id_client,"coordenadas"=>$coordenadas,"velocidad"=>$velocidad,"motor"=>$motor);
               array_push($sendAlert,$alert);
            }
            $geo_alarms[$key]['status']=$status;
            $updateAlarm = json_encode($geo_alarms);
            $queryNoStatus = "UPDATE client_asset SET geo_alarms='".$updateAlarm ."' WHERE imei=".$imei;
            $this->execute($queryNoStatus);
          }
          elseif ($values['status'] == $status) {
            $unidad . " sigue en geocerca " . $georcercaName;
          }  
          
           

        }elseif($tipo == 'poly'){

           
          $geocerca_info[0]['data'];
          $geocerca = json_decode($geocerca_info[0]['data'],true);
          print_r($geocerca);
          $geofoto = $geocerca['preview'];
          echo "<img src='" . $geofoto ."'>";
          //print_r($geocerca); 
          $points = $geocerca['vars'];
          $georcercaName = $geocerca['name'];

          $points = split(':', $points);
          //print_r($points);
          array_pop($points);
          //print_r($points);
          $pointsF ="";
          $polygon = array();
          foreach ($points as $keyP => $value) {
            $value = split(',', $value);
            $pointsF .= "'". $value[0].' '. $value[1] ."',";
            $pt = $value[0].' '. $value[1];
            array_push($polygon, $pt);
          }
          $firstPoint = split(',', $points[0]);
          $pointsF .= "'".$firstPoint[0]. ' ' . $firstPoint[1]."'";
          $fp = $firstPoint[0]. ' ' . $firstPoint[1];
          array_push($polygon, $fp);
          //echo "<br>points finales<br>";
          $pointsF;

          //echo " in: " . $in . " out: " . $out;
          $pointLocation = new pointLocation();
          

          $points = $lat . " " . $lng;
          

          $status =  $pointLocation->pointInPolygon($points, $polygon);
          
          
          $in = $values['in'];
          
          $out = $values['out'];
           

          if($values['status']==''){
            $geo_alarms[$key]['status']=$status;
            $updateAlarm = json_encode($geo_alarms);
            $queryNoStatus = "UPDATE client_asset SET geo_alarms='".$updateAlarm ."' WHERE imei=".$imei;
            $this->execute($queryNoStatus);
            if($status == 'inside'){
               $alert = array('unidad'=>$unidad,"geofoto"=>$geofoto, "mensaje"=>' dentro de la geocerca ' . $georcercaName,"date"=>$date, "id_client" =>$id_client,"coordenadas"=>$coordenadas,"velocidad"=>$velocidad,"motor"=>$motor);
               array_push($sendAlert,$alert);
            }
          }elseif ($values['status'] == 'outside' AND $status == 'inside') {
            
            if($in ==1){
               $alert = array('unidad'=>$unidad,"geofoto"=>$geofoto, "mensaje"=>'entro a la geocerca' . $georcercaName,"date"=>$date, "id_client" =>$id_client,"coordenadas"=>$coordenadas,"velocidad"=>$velocidad,"motor"=>$motor);
               array_push($sendAlert,$alert);
            }

            $geo_alarms[$key]['status']=$status;
            $updateAlarm = json_encode($geo_alarms);
            $queryNoStatus = "UPDATE client_asset SET geo_alarms='".$updateAlarm ."' WHERE imei=".$imei;
            $this->execute($queryNoStatus);
          }elseif ($values['status']=='inside' AND $status =='outside') {
            if($out ==1){
               $alert = array('unidad'=>$unidad,"geofoto"=>$geofoto, "mensaje"=>' Salio de la geocerca ' . $georcercaName,"date"=>$date, "id_client" =>$id_client,"coordenadas"=>$coordenadas,"velocidad"=>$velocidad,"motor"=>$motor);
               array_push($sendAlert,$alert);
            }
            $geo_alarms[$key]['status']=$status;
            $updateAlarm = json_encode($geo_alarms);
            $queryNoStatus = "UPDATE client_asset SET geo_alarms='".$updateAlarm ."' WHERE imei=".$imei;
            $this->execute($queryNoStatus);
          }
          elseif ($values['status'] == $status) {
             $unidad . " sigue en geocerca " . $georcercaName;
          }
          $updateAlarm = json_encode($geo_alarms);
        } //termi
      }

      //COMIENZA A REVISAR GEORUTA
       echo "<br>----------------<br>revoisando " . $unidad . " con tracks activas".$tracks ."<br>";
        
       //CONSULTAR LAS PISTAS ACTIVAS POR CADA REPORTE
       foreach ($tracks as $track_id => $active) {
        echo "track_id: ". $track_id . "<br>";
        echo "actives: ". $active . "<br>";
         $id_track =$track_id;
         if($active==1){
          $query_track = "SELECT * FROM client_track WHERE id=" . $track_id;
          $track = $this->execute($query_track);
          
 

          $track_name = $track[0]['track_name'];
          echo "<br><b>Pista" . $track_name . "</b><br>";
          $track = json_decode($track[0]['track_points'],true);
           

          //INICIA COMPROBACION DE RUTA
          //print_r($track);
          foreach ($track as $key => $value) {
            $puntoReporte = $lat. "," . $lng;
      
      $next = $key +1;
      
      $punto1Next = split(',', $track[$next][0]);
      $punto1 = split(',', $value[0]);
       
      $lat1 = $punto1[0];
      $lat1Next = $punto1Next[0];
      
      $lang1 = $punto1[1];
      $lang1Next = $punto1Next[1];

      $punto2 = split(',', $value[1]);
      $punto2Next = split(',', $track[$next][1]);
      
      $lat2 = $punto2[0];
      $lat2Next = $punto2Next[0];
       
      $lang2 = $punto2[1];
      $lang2Next = $punto2Next[1];
      
      // echo "comparando punto reporte: " . $puntoReporte . "con punto ruta " . $lat1 . ",".$lang1;

      $a = $this->distanceCalculation($lat, $lng, $lat1, $lang1, $unit = 'km', $decimals = 8);
      $a = $a * 1000;
       
      $aNext = $this->distanceCalculation($lat, $lng, $lat1Next, $lang1Next, $unit = 'km', $decimals = 8);
      $aNext = $aNext * 1000;  
      

      $b = $this->distanceCalculation($lat, $lng, $lat2, $lang2, $unit = 'km', $decimals = 8);
      $b = $b *1000;

      $bNext = $this->distanceCalculation($lat, $lng, $lat2Next, $lang2Next, $unit = 'km', $decimals = 8);
      $bNext = $bNext *1000;


      $c = $value[3];
      $cNext = $track[$next][3];
      
      $c1 = $c * $c;
      $b1 = 150 * 150;
      $bc = $c1 + $b1;
      $distanMax =  sqrt($bc);
      $distanMax = $distanMax ;
      //$mifirePHP->log($distanMax, "distanMax");

      $c1Next = $cNext * $cNext;
      $b1Next = 150 * 150;
      $bcNext = $c1Next + $b1Next;
      $distanMaxNext =  sqrt($bcNext);
      $distanMaxNext = $distanMaxNext ;
      //$mifirePHP->log($distanMaxNext,"distanMaxNext");

      //1era comprobacion

       // echo "<br>a=".$a." distanMax=".$distanMax." b=".$b."<br>";
      if($a <= $distanMax and $b <= $distanMax){

         $s1 = $a + $b + $c;
         $s = $s1 * .5;

         $ax = $s-$a;
         $bx = $s-$b;
         $cx = $s - $c;

         $gt = $s * $ax * $bx * $cx;

         $area = sqrt($gt);

         $alturax = 2 * $area;
         $alturaTotal = $alturax / $c;

         if($alturaTotal <=150){
                  
                  $report_track_status = 'inside';
                  echo "Coordenadas:" .  $puntoReporte . " CoordenadaRuta: " . $lat1 . ",".$lang1 . "REsultado: EN RUTA <br>";
                  echo "<br>a=".$a." distanMax=".$distanMax." b=".$b."<br>";
                  echo $alturaTotal."<br>"; 
                   echo $c . " <c " . $a ." <a " . $b ."<b". $distanMax ."<-max".$report_id."*".$i."*" ."<--Reporte callo en ". $key . " id del punto en pista EN RUTA <br>";
                  break;
         }else{
          $report_track_status = 'outside';
          //echo $report_id."*".$i."* FUERA DE RUTA";
                  echo "Coordenadas:" .  $puntoReporte . " CoordenadaRuta: " . $lat1 . ",".$lang1 . "REsultado: FUERA RUTA<br>"; 
         }
         $alerta = 0;
         $x++;
         //break;
      }

      //Segunda comprobacion
      if($aNext <= $distanMaxNext and $bNext <= $distanMaxNext){
         $alerta = 0;
         echo "Coordenadas:" .  $puntoReporte . " CoordenadaRuta: " . $lat1 . ",".$lang1 . "REsultado: SIGUIENTE PUNTO<br>"; 
        // $mifirePHP->log($i . " - " . $x ."-".$puntoReporte,"esta en el siguiente Next");
         $x++;
         continue;
      }


      //Tercera comprobacion
      $radio = $b;
      if ($radio <= 100){
        $alerta = 0;
        $report_track_status = 'inside';
        // echo $report_id ."*".$i."* EN RUTA CIRCULO";
           echo "Coordenadas:" .  $puntoReporte . " CoordenadaRuta: " . $lat1 . ",".$lang1 . "REsultado: EN RUTA CIRCULO<br>"; 
         
        //$mifirePHP->log($i . " - " . $x ."-".$puntoReporte ,"esta dentro radio pactual");
        $x++;
        break;
      }else{
        $report_track_status = 'outside';
        $x++;
         $alerta = 1;
      } 



   } //TERMINA COMPROBACION DE REPORTE EN RUTA$route_status[$track_id]['status']
   
   $route_status = json_decode($route_status,true);
   echo "ReporteGPS: <b> " . $report_track_status . " STATUS DB: ".$route_status[$track_id]['status']."</b><br>";
   //echo "uuu" . $track_id;
   // print_r($route_status);
   // print_r($route_status[$track_id]);
if($report_track_status == 'inside'){
        echo "ESTAMOS INSIDE<br>";
        echo "track_id" . $track_id . "<br>";
        echo "resultado por el reporte: " . $report_track_status . "<br>"; 
        echo "route_status track_id status:" . $route_status[$track_id]['status']. "<br>";
        //NOT FOUND
        if($route_status==""  || $route_status[$track_id] =='' ){
          echo "<b>inside not found</b>";
           $alert = array('unidad'=>$unidad, "mensaje"=>' entro a la ruta ' . $track_name ."Coordenadas:" .  $puntoReporte . " CoordenadaRuta: " . $lat1 . ",".$lang1,"date"=>$date, "id_client" =>$id_client,"coordenadas"=>$coordenadas,"velocidad"=>$velocidad,"motor"=>$motor);
           array_push($sendAlert,$alert);

           $route_status_update = array($track_id=>array('status'=>'inside','out'=>0));
           $route_status_update = json_encode($route_status_update);
           echo $query_route_status_update = "UPDATE client_asset set route_status='".$route_status_update."' WHERE imei =" . $imei;
           
           echo "<br> funciono";
           $this->execute($query_route_status_update); 
        }
        //DB OUTSIDE
        if($route_status[$track_id]['status'] =='outside' ){
          echo "<b>inside outside</b>";
           $alert = array('unidad'=>$unidad, "mensaje"=>' entro a la ruta ' . $track_name ." Coordenadas:" .  $puntoReporte . " CoordenadaRuta: " . $lat1 . ",".$lang1,"date"=>$date, "id_client" =>$id_client,"coordenadas"=>$coordenadas,"velocidad"=>$velocidad,"motor"=>$motor);
           array_push($sendAlert,$alert);

           $route_status_update = array($track_id=>array('status'=>'inside','out'=>0));
           $route_status_update = json_encode($route_status_update);
           echo $query_route_status_update = "UPDATE client_asset set route_status='".$route_status_update."' WHERE imei =" . $imei;
           
           echo "<br> funciono";
           $this->execute($query_route_status_update); 
        }
        //DB INSIDE
        if($route_status[$track_id]['status'] =='inside' ){
          echo "<b>inside inside</b>";
           $in_db = $route_status[$track_id]['in'];
           $in_db = $in_db+1;
           $route_status[$track_id]['in']=$in_db;
           $route_status[$track_id]['out']=0;
           $route_status_update = json_encode($route_status);
           echo $route_status_update;
           echo $query_route_status_update = "UPDATE client_asset set route_status='".$route_status_update."' WHERE imei =" . $imei;
            
            //$alert = array('unidad'=>$unidad, "mensaje"=>' sigue en la ruta ' . $track_name . " Coordenadas:" .  $puntoReporte . " CoordenadaRuta: " . $lat1 . ",".$lang1,"date"=>$date, "id_client" =>$id_client);
           //array_push($sendAlert,$alert);
           echo "<br> funciono incremento";
           $this->execute($query_route_status_update); 

        }
      }elseif ($report_track_status=='outside') {
        echo "ESTAMOS OUTSIDE<br>";
        echo "coodenadas:" . $lat . "," . $lng;
        echo "track_id" . $track_id . "<br>";
        echo "resultado por el reporte: " . $report_track_status . "<br>"; 
        echo "route_status track_id status:" . $route_status[$track_id]['status']. "<br>";
        //NOT FOUND
        if($route_status==""  || $route_status[$track_id] =='' ){
          echo "<b>outside not found</b>";
           echo "FUEEEEEEEEERA Y SIN RUTA";
        }
        //DB INSIDE
        if($route_status[$track_id]['status'] =='inside' ){
          echo "<b>outside inside</b>";
           $out_db = $route_status[$track_id]['out'];
           $out_db = $out_db+1;
           $route_status[$track_id]['out']=$out_db;
           $route_status_update = json_encode($route_status);
           echo $route_status_update;
           echo $query_route_status_update = "UPDATE client_asset set route_status='".$route_status_update."' WHERE imei =" . $imei;
           $this->execute($query_route_status_update); 
           echo "<br> salio menos de tres"; 
           //LA UNIDAD ACUMULO 3 REPORTES FUERA
           if($route_status[$track_id]['out'] >= 3){
            echo "salio definitivo";
                $route_status[$track_id]['status'] = 'outside';
                $route_status_update = json_encode($route_status); 
                echo $query_route_status_update = "UPDATE client_asset set route_status='".$route_status_update."' WHERE imei =" . $imei; 
                $this->execute($query_route_status_update); 
                $alert = array('unidad'=>$unidad, "mensaje"=>' salio de la ruta ' . $track_name . " Coordenadas:" .  $puntoReporte . " CoordenadaRuta: " . $lat1 . ",".$lang1,"date"=>$date, "id_client" =>$id_client,"coordenadas"=>$coordenadas,"velocidad"=>$velocidad,"motor"=>$motor);
                array_push($sendAlert,$alert);
           }
           
        }
        //DB OUTSIDE
        if($route_status[$track_id]['status'] =='outside' ){
          echo "<b>outside outside</b>";
                unset($route_status[$track_id]); 
                $route_status_update = json_encode($route_status); 
                echo $query_route_status_update = "UPDATE client_asset set route_status='".$route_status_update."' WHERE imei =" . $imei; 
                $this->execute($query_route_status_update);

           // $alert = array('unidad'=>$unidad, "mensaje"=>' eliminar ruta ' . $track_name . "Coordenadas:" .  $puntoReporte . " CoordenadaRuta: " . $lat1 . ",".$lang1,"date"=>$date, "id_client" =>$id_client);
           //array_push($sendAlert,$alert);
        }
      } 
         }
        

       } // termina pistas activas por reporte
    }
    return $sendAlert;
  } 
}

$alert = new ALERTS();
 
$reports = $alert->getLastReport();

if(!empty($reports)){
  
  foreach ($reports as $report => $content) {
    print_r($content);
   $email = $alert->getEmail($content['id_client']);
  
   print_r($email);
   $email = $email[0]['email'];
   $msg = '<table>
  <tr>
      <td>Unidad:</td>
      <td> '. $content['unidad'] . '</td>
  </tr>
  <tr>
      <td>Evento:</td>
      <td> '. $content['mensaje'] . '</td>
  </tr>
  <tr>
      <td>Velocidad</td>
      <td> '. $content['velocidad'] . '</td>
  </tr>
  <tr>
      <td>Motor</td>
      <td> '. $content['motor'] . '</td>
  </tr>
  <tr>
      <td>Fecha</td>
      <td> '. $content['date'] . '</td>
  </tr>
  <tr>
      <td>Coordenadas</td>
      <td> <a href="'. $content['coordenadas'] . '">Ver</a></td>
  </tr>
</table>
 ';
 if($content['geofoto']!=''){
  $msg .= '<img src="'.$content['geofoto'].'">';
 } 
 echo $msg;
   $mail->addAddress($email, 'Norttrek');
   $mail->Subject = 'Norttrek - Notificacion de unidad ' . $content['unidad'] ;
   $mail->msgHTML($msg);
   $mail->send();
  }
}else{
  echo "no gprs";
}



//Attach an image file 
//$mail->addAttachment('images/phpmailer_mini.png');

/*send the message, check for errors
if (!$mail->send()) {
    echo "Mailer Error: " . $mail->ErrorInfo;
} else {
    echo "Message sent!";
}

$mail->msgHTML('<b>hola 2</b>');

if (!$mail->send()) {
    echo "Mailer Error: " . $mail->ErrorInfo;
} else {
    echo "Message sent!";
}
*/

?> 