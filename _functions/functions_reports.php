<?php 
function test($info,$name)
{ 
  $firephp = FirePHP::getInstance(true);
  $firephp->info($info,$name);
} 

function get_tank_fuel_report($asset, $route, $tank1, $tank2, $tank3, $imei,$formula){

  $out_data = null;
  $objGPRS = new GPRS();
  $objAsset = new Asset();
  $client_asset = $objGPRS->getCLientAssetByImei($imei);

  $fsensors = json_decode($client_asset[0]['sensor'],true);

  $fuel_a_sensor = $fsensors['fuel_a'];
  $fuel_b_sensor = $fsensors['fuel_b'];
  $fuel_c_sensor = $fsensors['fuel_c'];

  $temp1_sensor = $fsensors['temp'];
  $temp2_sensor = $fsensors['temp2'];
test($route,'ruta');
 
 
  // For $route
  for($i=0;$i<count($route);$i++){
 
    // Fecha
    $data[$i]['date'] = $route[$i]['date']; 

   

    //tabla con reporte

    // Columna C
    // Velocidad
    if($route[$i]['gps_speed']=='000'){

      $data[$i]['km'] = 0;
      $data[$i]['col_c'] = 0;

    }else{

      $data[$i]['km'] = intval($route[$i]['gps_speed']);
      $data[$i]['col_c'] = intval($route[$i]['gps_speed']);

    }
    // Fin Columna C

    // lat y long
    $data[$i]['lat_lng'] = $route[$i]['lat'].','.$route[$i]['lng'];
    // lat
    $data[$i]['lat'] = $route[$i]['lat'];
    // long
    $data[$i]['lng'] = $route[$i]['lng'];
    // Columna D
    $dateReport = $data[$i]['date'];
    // Tanque 1
    if($fuel_a_sensor ==1){
      $fuel_a_volt = $route[$i]['v2_fuel1']/1000;
      $data[$i]['volt_t1'] = $fuel_a_volt;
      if($formula==2){
        $data[$i]['t1'] = number_format($objGPRS->get_fuel_by_calibracion($fuel_a_volt,$tank1,$dateReport,1),2);
      }elseif ($formula==1) { 
        $data[$i]['t1'] = number_format($objGPRS->get_fuel_lt_v2($asset[0]['da1'],$asset[0]['lg1'],$asset[0]['as1'],$asset[0]['var1'],$asset[0]['vl1'],$fuel_a_volt),2);
      }
      
    }
    

    // Tanque 2
    if($fuel_b_sensor ==1){
       $fuel_b_volt = $route[$i]['v2_fuel2']/1000;
      $data[$i]['volt_t2'] = $fuel_b_volt;
      if($formula==2){
      $data[$i]['t2'] = number_format($objGPRS->get_fuel_by_calibracion($fuel_b_volt,$tank2,$dateReport,2),2);
      }elseif ($formula==1) { 
        $data[$i]['t2'] = number_format($objGPRS->get_fuel_lt_v2($asset[0]['da2'],$asset[0]['lg2'],$asset[0]['as2'],$asset[0]['var2'],$asset[0]['vl2'],$fuel_a_volt),2);
      }
    }

    // Tanque 3
    if($fuel_c_sensor ==1){
    $fuel_c_volt = $route[$i]['v2_fuel3']/1000;
      $data[$i]['volt_t3'] = $fuel_c_volt;
       if($formula==2){
      $data[$i]['t3'] = number_format($objGPRS->get_fuel_by_calibracion($fuel_c_volt,$tank3,$dateReport,3),2);
       }elseif ($formula==1) { 
        $data[$i]['t3'] = number_format($objGPRS->get_fuel_lt_v2($asset[0]['da3'],$asset[0]['lg3'],$asset[0]['as3'],$asset[0]['var3'],$asset[0]['vl3'],$fuel_a_volt),2);
      }
    }

    // Total Tanques
    $data[$i]['tt'] = $data[$i]['t1']+$data[$i]['t2']+$data[$i]['t3'];
    $data[$i]['col_d'] = $data[$i]['t1']+$data[$i]['t2']+$data[$i]['t3'];
    // Fin Columna D

    // ???
    $iostatus = $objGPRS->get_iostatus($route[$i]['iostatus']);
    $data[$i]['ignition'] =$route[$i]['v2_eng_status'];
    test($data[$i]['ignition'],'on');
    $data[$i]['event_code'] = $route[$i]['v2_eventCode'];
    // Temperatura 1
    if($temp1_sensor==1){
        $temp1 = 0;
        
     
      $temp1 =  substr($route[$i]['temp'],0,4)/10;
  
          $tunit1 = 'C&deg';
        if($_SESSION['logged']['temp']=="f"){
          $temp1  = ($temp1*1.8+32);
          $tunit1 = 'F&deg';
          }
        $data[$i]['temp_1'] = number_format($temp1,1);
        $data[$i]['temp_unit_1'] = $tunit;
     
      $ttemp[$i] = $data[$i]['temp_1'];
    }



    // Temperatura 2
    if($temp2_sensor==1){
        $temp2 = 0;
        
      
      $temp2 =   substr($route[$i]['temp'],4,8)/10;
     
          $tunit1 = 'C&deg';
        if($_SESSION['logged']['temp']=="f"){
          $temp2  = ($temp2*1.8+32);
          $tunit1 = 'F&deg';
          }
        $data[$i]['temp_2'] = number_format($temp2,1);
        $data[$i]['temp_unit_2'] = $tunit;
 
      $ttemp[$i] = $data[$i]['temp_2'];
    }




    
  $data[$i]['tbl_route'] .= '<tr id="tr_route_'.($i).'" class="onMarkerHover">';
  $data[$i]['tbl_route'] .= '<td align="center">'.($i).'</td>';
  $data[$i]['tbl_route'] .= '<td>'.$route[$i]['date'].'</td>';
  $data[$i]['tbl_route'] .= '<td>'.$route[$i]['gps_speed'].'</td>';
  if($fuel_a_sensor ==1){
    $data[$i]['tbl_route'] .= '<td>'.$data[$i]['t1'] .'</td>';
  }
  if($fuel_b_sensor ==1){
    $data[$i]['tbl_route'] .= '<td>'.$data[$i]['t2'] .'</td>';
  }
  if($fuel_c_sensor ==1){
    $data[$i]['tbl_route'] .= '<td>'.$data[$i]['t3'] .'</td>';
  }
  if($temp1_sensor ==1){
    $data[$i]['tbl_route'] .= '<td>'.$data[$i]['temp_1'] .'</td>';
  }
  if($temp2_sensor ==1){
    $data[$i]['tbl_route'] .= '<td>'.$data[$i]['temp_2'] .'</td>';
  }

  $data[$i]['tbl_route'] .= '<td>'.$route[$i]['v2_eventCode'].'</td>';
  $data[$i]['tbl_route'] .= '<td>'.$route[$i]['lat'].' , '.$route[$i]['lng'].'</td>';
  $data[$i]['tbl_route'] .= '<td align="center"><a href="javascript:void(0)" rel="sd" class="onLatLngOver">ver</a></td>';
  
  $data[$i]['tbl_route'] .= '</tr>';

    



    if($i<count($route)-2){
      $lts1 = ($data[$i]['t1']+$data[$i+1]['t1']+$data[$i+2]['t1'])/3;
      $lts2 = ($data[$i]['t2']+$data[$i+1]['t2']+$data[$i+2]['t2'])/3;
      $lts3 = ($data[$i]['t3']+$data[$i+1]['t3']+$data[$i+2]['t3'])/3;
      $data[$i]['t1_prom'] = $lts1;
      $data[$i]['t2_prom'] = $lts2;
      $data[$i]['t3_prom'] = $lts3;
    }else{
      $data[$i]['t1_prom'] = 0;
      $data[$i]['t2_prom'] = 0;
      $data[$i]['t3_prom'] = 0;
    }

  }
  // Fin For $route

  // For en $data
  for($i=0;$i<count($data);$i++){

      // Columna E
      if($i<=count($route)-1){

        $data[$i]['diftt'] = $data[$i]['tt'] - $data[$i+1]['tt'];

        $data[$i]['dif1'] = $data[$i]['t1'] - $data[$i+1]['t1'];
        $data[$i]['dif2'] = $data[$i]['t2'] - $data[$i+1]['t2'];
        $data[$i]['dif3'] = $data[$i]['t3'] - $data[$i+1]['t3'];

        $data[$i]['col_e'] = $data[$i]['col_d'] - $data[$i+1]['col_d'];

      }
      // Fin Columna E

      // Columna F
      $p4 = 10;
      $q4 = 5;
      $r4 = 10;
      $s4 = 10;
      
      // function formula detectar posible carga y descarga;

      if( ($i>=4) AND ($i<=count($route)-4) ){

        // =============== TANK1 ===============
        // Si -4
        if( abs($data[$i-4]['t1']-$data[$i+4]['t1']) > $p4 ){

          // Si -3
          if( abs($data[$i-3]['t1']-$data[$i+3]['t1']) > $p4 ){

            // Si -2
            if( abs($data[$i-2]['t1']-$data[$i+2]['t1']) > $p4 ){

              // Si -1
              if( ( $data[$i]['km'] < $q4 ) AND ( abs($data[$i]['dif1']) > $r4 ) AND ( abs($data[$i]['t1']-$data[$i+1]['t1']) > $s4 ) ){

                // escoger columna e
                $data[$i]['t1col_f'] = $data[$i]['dif1'];

              }else {

                // escoger 0
                $data[$i]['t1col_f'] = 0;

              }
              // Fin Si -1

            }else {

              // escoger 0
              $data[$i]['t1col_f'] = 0;

            }
            // Fin Si -2

          }else {

            // escoger 0
            $data[$i]['t1col_f'] = 0;

          }
          // Fin Si -3

        } else {

          // escoger 0
          $data[$i]['t1col_f'] = 0;

        }
        // Fin Si -4
        // =============== /TANK1 ===============

        // =============== TANK2 ===============
        // Si -4
        if( abs($data[$i-4]['t2']-$data[$i+4]['t2']) > $p4 ){

          // Si -3
          if( abs($data[$i-3]['t2']-$data[$i+3]['t2']) > $p4 ){

            // Si -2
            if( abs($data[$i-2]['t2']-$data[$i+2]['t2']) > $p4 ){

              // Si -1
              if( ( $data[$i]['km'] < $q4 ) AND ( abs($data[$i]['dif2']) > $r4 ) AND ( abs($data[$i]['t2']-$data[$i+1]['t2']) > $s4 ) ){

                // escoger columna e
                $data[$i]['t2col_f'] = $data[$i]['dif2'];

              }else {

                // escoger 0
                $data[$i]['t2col_f'] = 0;

              }
              // Fin Si -1

            }else {

              // escoger 0
              $data[$i]['t2col_f'] = 0;

            }
            // Fin Si -2

          }else {

            // escoger 0
            $data[$i]['t2col_f'] = 0;

          }
          // Fin Si -3

        } else {

          // escoger 0
          $data[$i]['t2col_f'] = 0;

        }
        // Fin Si -4
        // =============== /TANK2 ===============

        // =============== TANK3 ===============
        // Si -4
        if( abs($data[$i-4]['t3']-$data[$i+4]['t3']) > $p4 ){

          // Si -3
          if( abs($data[$i-3]['t3']-$data[$i+3]['t3']) > $p4 ){

            // Si -2
            if( abs($data[$i-2]['t3']-$data[$i+2]['t3']) > $p4 ){

              // Si -1
              if( ( $data[$i]['km'] < $q4 ) AND ( abs($data[$i]['dif3']) > $r4 ) AND ( abs($data[$i]['t3']-$data[$i+1]['t3']) > $s4 ) ){

                // escoger columna e
                $data[$i]['t3col_f'] = $data[$i]['dif3'];

              }else {

                // escoger 0
                $data[$i]['t3col_f'] = 0;

              }
              // Fin Si -1

            }else {

              // escoger 0
              $data[$i]['t3col_f'] = 0;

            }
            // Fin Si -2

          }else {

            // escoger 0
            $data[$i]['t3col_f'] = 0;

          }
          // Fin Si -3

        } else {

          // escoger 0
          $data[$i]['t3col_f'] = 0;

        }
        // Fin Si -4
        // =============== /TANK3 ===============

        // =============== TANK TOTAL ===============
        // Si -4
        if( abs($data[$i-4]['tt']-$data[$i+4]['tt']) > $p4 ){

          // Si -3
          if( abs($data[$i-3]['tt']-$data[$i+3]['tt']) > $p4 ){

            // Si -2
            if( abs($data[$i-2]['tt']-$data[$i+2]['tt']) > $p4 ){

              // Si -1
              if( ( $data[$i]['km'] < $q4 ) AND ( abs($data[$i]['diftt']) > $r4 ) AND ( abs($data[$i]['tt']-$data[$i+1]['tt']) > $s4 ) ){

                // escoger columna e
                $data[$i]['ttcol_f'] = $data[$i]['diftt'];

              }else {

                // escoger 0
                $data[$i]['ttcol_f'] = 0;

              }
              // Fin Si -1

            }else {

              // escoger 0
              $data[$i]['ttcol_f'] = 0;

            }
            // Fin Si -2

          }else {

            // escoger 0
            $data[$i]['ttcol_f'] = 0;

          }
          // Fin Si -3

        } else {

          // escoger 0
          $data[$i]['ttcol_f'] = 0;

        }
        // Fin Si -4
        // =============== /TANK TOTAL ===============

      }
      // Fin Columna F

      // Columna G
      if($data[$i]['col_f']>0){
        $data[$i]['col_g'] = $data[$i]['col_f'];
      }else{
        $data[$i]['col_g'] = '';
      }

      // TANK1
      if($data[$i]['t1col_f'] > 0){
        $data[$i]['t1col_g'] = $data[$i]['t1col_f'];
      } else {
        $data[$i]['t1col_g'] = '';
      }
      // TANK 2
      if($data[$i]['t2col_f'] > 0){
        $data[$i]['t2col_g'] = $data[$i]['t2col_f'];
      } else {
        $data[$i]['t2col_g'] = '';
      }
      // TANK 3
      if($data[$i]['t3col_f'] > 0){
        $data[$i]['t3col_g'] = $data[$i]['t3col_f'];
      } else {
        $data[$i]['t3col_g'] = '';
      }
      // TANK TOTAL
      if($data[$i]['ttcol_f'] > 0){
        $data[$i]['ttcol_g'] = $data[$i]['ttcol_f'];
      } else {
        $data[$i]['ttcol_g'] = '';
      }

      // Fin Columna G

      // Columna H
      if($data[$i]['col_f']<0){
        $data[$i]['col_h'] = $data[$i]['col_f'];
      }else{
        $data[$i]['col_h'] = '';
      }

      // TANK1
      if($data[$i]['t1col_f'] < 0){
        $data[$i]['t1col_h'] = $data[$i]['t1col_f'];
      } else {
        $data[$i]['t1col_h'] = '';
      }
      // TANK 2
      if($data[$i]['t2col_f'] < 0){
        $data[$i]['t2col_h'] = $data[$i]['t2col_f'];
      } else {
        $data[$i]['t2col_h'] = '';
      }
      // TANK 3
      if($data[$i]['t3col_f'] < 0){
        $data[$i]['t3col_h'] = $data[$i]['t3col_f'];
      } else {
        $data[$i]['t3col_h'] = '';
      }
      // TANK TOTAL
      if($data[$i]['ttcol_f'] < 0){
        $data[$i]['ttcol_h'] = $data[$i]['ttcol_f'];
      } else {
        $data[$i]['ttcol_h'] = '';
      }

      // Fin Columna H

      // CHART

      // TANK 1
      if($data[$i]['t1col_f'] != 0){
        $label = '';
        if($data[$i]['t1col_f']>0){
          $label = 'Posible Recarga';
          $data[$i]['t1_carga'] = number_format($data[$i]['t1'],2);
        } else {
          $label = 'Posible Extraccion';
          $data[$i]['t1_descarga'] = number_format($data[$i]['t1'],2);
        }
        $data[$i]['t1_chart'] = '<p><strong>'.$label.'</strong> <br />'.number_format($data[$i]['t1col_f'],2).' lts (total)<br />Total de Tanques: '.number_format($data[$i]['tt'],2).' lts<br />Velocidad '.number_format($route[$i]['speed'],2).'<br />Fecha: '.$data[$i]['date'].'</p>';

      }else {
        // do nothing
      }
      // FIN TANK 1

      // TANK 2
      if($data[$i]['t2col_f'] != 0){
        $label = '';
        if($data[$i]['t2col_f']>0){
          $label = 'Posible Recarga';
          $data[$i]['t2_carga'] = number_format($data[$i]['t2'],2);
        } else {
          $label = 'Posible Extraccion';
          $data[$i]['t2_descarga'] = number_format($data[$i]['t2'],2);
        }
        $data[$i]['t2_chart'] = '<p><strong>'.$label.'</strong> <br />'.number_format($data[$i]['t2col_f'],2).' lts (total)<br />Total de Tanques: '.number_format($data[$i]['tt'],2).' lts<br />Velocidad '.number_format($route[$i]['speed'],2).'<br />Fecha: '.$data[$i]['date'].'</p>';

      }else {
        // do nothing
      }
      // FIN TANK 2

      // TANK 3
      if($data[$i]['t3col_f'] != 0){
        $label = '';
        if($data[$i]['t3col_f']>0){
          $label = 'Posible Recarga';
          $data[$i]['t3_carga'] = number_format($data[$i]['t3'],2);
        } else {
          $label = 'Posible Extraccion';
          $data[$i]['t3_descarga'] = number_format($data[$i]['t3'],2);
        }
        $data[$i]['t3_chart'] = '<p><strong>'.$label.'</strong> <br />'.number_format($data[$i]['t3col_f'],2).' lts (total)<br />Total de Tanques: '.number_format($data[$i]['tt'],2).' lts<br />Velocidad '.number_format($route[$i]['speed'],2).'<br />Fecha: '.$data[$i]['date'].'</p>';

      }else {
        // do nothing
      }
      // FIN TANK 3

      // TANK TOTAL
      if($data[$i]['ttcol_f'] != 0){
        $label = '';
        if($data[$i]['ttcol_f']>0){
          $label = 'Posible Recarga';
          $data[$i]['tt_carga'] = number_format($data[$i]['tt'],2);
        } else {
          $label = 'Posible Extraccion';
          $data[$i]['tt_descarga'] = number_format($data[$i]['tt'],2);
        }
        $data[$i]['tt_chart'] = '<p><strong>'.$label.'</strong> <br />'.number_format($data[$i]['ttcol_f'],2).' lts (total)<br />Total de Tanques: '.number_format($data[$i]['tt'],2).' lts<br />Velocidad '.number_format($route[$i]['speed'],2).'<br />Fecha: '.$data[$i]['date'].'</p>';

      }else {
        // do nothing
      }
      // FIN TANK TOTAL

      // FIN Chart
      $data[$i]['sensor_fuel_a'] = $fsensors['fuel_a'];
      $data[$i]['sensor_fuel_b'] = $fsensors['fuel_b'];
      $data[$i]['sensor_fuel_c'] = $fsensors['fuel_c'];

      $data[$i]['sensor_temp1'] = $fsensors['temp'];
      $data[$i]['sensor_temp2'] = $fsensors['temp2']; 
      $data[$i]['assets'] = $client_asset;

  }
  // FIN for $route
  
  return $data;

}
// FIN function get_tank_fuel_report($asset, $route, $tank1, $tank2, $tank3)


function getTanksSensor($fields){
  $tanks=0;
  foreach ($fields as $key => $value) {
   
    if($key == "fuel_a"){
      if ($value ==1) {
        $tanks++;
      }
    }
    if($key == "fuel_b"){
      if ($value ==1) {
        $tanks++;
      }
    }
    if($key == "fuel_c"){
      if ($value ==1) {
        $tanks++;
      }
    }
    
  }
  return $tanks;
}

function getTempSensor($fields){
 
  $temps=0;
  foreach ($fields as $key => $value) {
 
    if($key == "temp"){
      if ($value ==1) {
        $temps++;
      }
    }
    if($key == "temp2"){
      if ($value ==1) {
        $temps++;
      }
    }
   
    
  }
  return $temps;
}
?>