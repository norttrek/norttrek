<?php 
$latLang= $gprs_data[0]['lat'].','.$gprs_data[0]['lng'];
$timeago = $objGPRS->get_time_ago(date("Y-m-d H:i:s"),$gprs_data[0]['date']);
$client_info = $objClient->getClientInfo($_SESSION['logged']['id_client']);
  
 

//status motor
if($gprs_data[0]['v2_eng_block'] == 1){
  $eng_block_color = '#F33';
  $eng_block_class = 'btn-alert-red';
    $eng_block_function = 'unlockEngine';
    }
    elseif($gprs_data[0]['v2_eng_block'] == 0){
    $eng_block_color = 'grey';
    $eng_block_class = 'btn-active';
    $eng_block_function  = 'lockEngine';
}

//status e-lock
 if($gprs_data[0]['v2_e_lock'] == 1){
    $e_lock_color = '#1cf100';
    $e_lock_class_btn = 'btn-alert-green';
    $e_lock_class= 'lock';
    $e_lock_function = 'openElock';
  }else{
    $e_lock_class = 'unlock';
    $e_lock_class_btn = 'btn-alert-red';
    $e_lock_function = 'closeElock';
  }

//status marcha
if($gprs_data[0]['v2_starter_block'] == 1){
    $starter_block_color = '#F33';
    $starter_block_class = 'btn-alert-red';
    $starter_block_function = 'desbloquearMarcha';
  }elseif ($gprs_data[0]['v2_starter_block'] == 0) {
    $starter_block_class = 'btn-active';
    $starter_block_function = 'bloquearMarcha';
  }

//FLECHAS DE POSICION
 if($gprs_data[0]['v2_eng_status']==1 && $gprs_data[0]['v2_speed'] >= 2){
        $icon_color = "#2079EC";
        }elseif ($gprs_data[0]['v2_eng_status']==0) {
        $icon_color  = "#818181";
        }elseif ($gprs_data[0]['v2_eng_status']==1 && $gprs_data[0]['v2_speed'] < 2) {
         $icon_color  = "#EA7C06";
      }

//GRADOS
    $grados = $gprs_data[0]['v2_heading'];

 

 
 
 
  /*if($alert != null){
     switch($alert[0]['description']) {
          case "¡ALERTA! SOS BOTÓN DE PÁNICO": 
                $colorW ="#F33";
                $gprs[$i]['icon_color'] = "#F33";
          break;
          case 'DESCONEXIÓN DE EQUIPO':
                $colorW ="#F33";
                $gprs[$i]['icon_color']  = "#F33";
          break;
          case 'BATERIA DE EQUIPO BAJA':
                $colorW ="#F33";
                if($gprs[$i]['power_status'] = 0){

                }elseif ($gprs[$i]['power_status'] == 1) {
                  # code...
                }
          break;
          case 'EXCESO DE VELOCIDAD':
                $colorW ="#F57709";
          break;
          case 'APERTURA DE E-LOCK':
                $colorW ="#F57709";
          break;
          case 'BLOQUEO DE MOTOR':
                $colorW ="#F57709";
          break;
          case 'BLOQUEO DE MARCHA':
                $colorW ="#F57709";
          break;
          case 'ENCENDIDO DE MOTOR':
                $colorW ="rgb(239, 224, 44)";
          break;
          case 'APAGADO DE MOTOR':
                $colorW ="rgb(239, 224, 44)";
          break;
          case 'UNIDAD ENCENDIDA SIN MOVIMIENTO':
                $colorW ="rgb(206, 194, 51)";
          break;
          case 'CERRADO DE E-LOCK':
                $colorW ="rgb(239, 224, 44)";
          break;
          case 'DESBLOQUEO DE MOTOR':
                $colorW ="rgb(239, 224, 44)";
          break;
          case 'BLOQUEO DE MARCHA':
                $colorW ="rgb(239, 224, 44)";
          break;
          case 'CONEXIÓN DE EQUIPO':
                $colorW ="#1cf100";
          break;       
     }
      $alert =  ' <i class="fa fa-warning  "  style="font-size:12px; color:'.$colorW. '" title="'.$alert[0]['description'].'"></i>';
  }

  if($alert == ""){
    $gprs_data[0]['alert'] =' ';
  }else{
    $gprs_data[0]['alert'] =$alert;
  }
 */
 array_push($gprs_reports, $gprs_data);
 
 if($gprs_data[0]['v2_HDOP'] > 20){
      $hdop = '#F33';
    }else{
      $hdop = '#818181';
    }
    if($gprs_data[0]['v2_power_status'] == 0){
      $ps = '#F33';
    }else{
      $ps = '#818181';
    }

    if($gprs_data[0]['v2_low_bat'] == 1){
      $lb = '#F33';
      $lb_class = 'icon-battery-low';
    }else{
      $lb = '#818181';
      $lb_class = 'icon-battery-full';
    }
$volt = $gprs_data[0]['v2_volt'] / 1000;
    $volt = substr($volt, 0, 2);
    $battery = $gprs_data[0]['v2_battery'] / 1000;
    $battery = substr($battery, 0, 3);
 
    


 $rssi = $objGPRS->get_rssi_value($gprs_data[0]['v2_RSSI']) ;

 //PROFILE IMAGE
  if($assets[$i]['imagen_u'] != ''){
        $profile_image ='<img src="/clientes/_img/profile/'.$assets[$i]['imagen_u'].'" style="width: 35px; border-radius: 18px;">';
      }else{
        $profile_image = ' <img src="_img/user.png">';
      } 



//TEMPERATURA
       $temp_c = 0;
  $temp_c2 = 0;

  $temp_c = substr($gprs_data[0]['temp'],0,4)/10;
  $temp_c2 = substr($gprs_data[0]['temp'],4,8)/10;

  $tunit = 'C&deg';
  #FARENHEIT
  if($_SESSION['logged']['temp']=="f"){
  $temp_c  = ($temp_c*1.8+32);
  $temp_c2  = ($temp_c2*1.8+32);
  $tunit = 'F&deg';
  } 



// TANQUES DE GASOLINA
 //Si el voltaje es mayor a 5000, el voltaje debe ser igual al ultimo litraje en la calibracion
  if($gprs_data[0]['v2_fuel1'] > 5000){
     $f1 = $gprs_data[0]['v2_fuel1'] = 5000;
  }
  $gas1 =  $gprs_data[0]['v2_fuel1'] / 50;
  $gprs[$i]['gas1'] =  $gas1;
   
  if($gprs_data[0]['v2_fuel2'] > 5000){
    $gprs_data[0]['v2_fuel2'] = 5000;
  }
  $gas2 =  $gprs_data[0]['v2_fuel2'] / 50;
  $gprs[$i]['gas2'] =  $gas2;

  if($gprs_data[0]['v2_fuel3'] > 5000){
    $gprs_data[0]['v2_fuel3'] = 5000;
  }
  $gas3 =  $gprs_data[0]['v2_fuel3'] / 50;
  $gprs[$i]['gas3'] =  $gas3;


$tanques = '<div id="gasGraphics">';
       $total_fuel=0;
      $total_fuel_active=0;
       if($assets[$k]['formula']==2){
      //obtener la calibracion del equipo
      $lts = $objAsset->get_lts($assets[$k]['imei']);
      //decodificar los litros que estan guardados en formato json en la base de datos
      $lts = json_decode($lts[0]['fuel'],true);  
      //Recorrer los 3 tanques
      
      foreach ($lts as $key => $value) {  
        if($value==null){
          continue;
        }
         
        $total_fuel_active++;
          $key =  substr($key,1); 
          $volt_report = $gprs_data[0]['v2_fuel'.$key];
          $volt_report = $volt_report/1000;  
          if ($volt_report > 6) {
             $tanques .= '
          <div class="containerGas">
            <div class="">
              Error de lectura en tanque '.$key .'
            </div>
            <div class="gas1Q">  </div>
            <div class="clear"></div>
          </div>';
          }else{
             $litros = $objGPRS->get_fuel_by_calibracion($volt_report,$value,$gprs_data[0]['date'],$key);
             $total_fuel = $total_fuel + $litros;
          //$tanques .='<div>'.$assets[$k]['formula'].$assets[$k]['imei'].'</div>';
          $tanques .= '
          <div class="containerGas">
            <div class="gas1">
              <div class="bloquegas bloque1"></div>
              <div class="bloquegas bloque2"></div>
              <div class="bloquegas bloque3"></div>
              <div class="bloquegas bloque4"></div>
              <div class="gas1fill" style="width:'. $gprs[$i]['gas'.$key].'%"  per="'.$gprs[$i]['gas'.$key].'"></div>
            </div>
            <div class="gas1Q">'.round($litros).' L </div>
            <div class="clear"></div>
          </div>';
          
          }
      }
      
    }elseif ($assets[$k]['formula']==1) {
      //Formula tradicional
       
      $lts = $objAsset->get_lts($assets[$k]['imei']);

      $volt_report = $gprs[$i]['v2_fuel1'];
      $volt_report = $volt_report/1000; 
           
      for ($d=1; $d <= 3 ; $d++) {
        if($d == 1){ $a = "a";}if($d == 2){ $a = "b";}if($d == 3){ $a = "c";} 
           $fsensors = json_decode($assets[$k]['sensor'],true);
           
          if($fsensors['fuel_'.$a]==1){
             $volt_report = $gprs_data[0]['v2_fuel'.$d];
               ;
            
             $volt_report = $volt_report/1000; 
            // $tanques .='<div>voltaje '.$volt_report.'</div>';
             // $tanques .='<div>da '.$assets[$k]['da'.$d].'</div>';
             $lts = number_format($objGPRS->get_fuel_lt_v2($assets[$k]['da'.$d],$assets[$k]['lg'.$d],$assets[$k]['as'.$d],$assets[$k]['var'.$d],$assets[$k]['vl'.$d],$volt_report),2);
            //  $tanques .='<div>'.$lts.'</div>';
           $total_fuel = $total_fuel + $lts;
            
           $total_fuel_active++; 

         $tanques .= '

            <div class="containerGas">
              <div class="gas1">
              <div class="bloquegas bloque1"></div>
              <div class="bloquegas bloque2"></div>
              <div class="bloquegas bloque3"></div>
              <div class="bloquegas bloque4"></div>
                <div class="gas1fill" style="width:'.$gprs[$i]['gas'.$d ].'%"  per="'.$gprs[$i]['gas'.$d].'"></div>
              </div>
              <div class="gas1Q"> '.round($lts).' L </div>
              <div class="clear"></div>
            </div>';
        }
      } 
    }
 
    if($total_fuel_active==1 || $total_fuel_active==0 ){
        $total_fuel_active =0;
      }else{
        $total_fuel_active=1;
      }
 
if($fsensors['fuel_a']==0 && $fsensors['fuel_b']==0 && $fsensors['fuel_c']==0){
  $fuelClass="iconsEngineNF";
}
else{
  $fuelClass="iconsEngine";
}
         
$tanques .= '</div>';
 //TERMINA COMBUSTUBLE

$temp = '<div id="'.$fuelClass.'">
          <div class="containerEicons">
            <div class="icons3"> 
                ';
                
                if($total_fuel_active==1){
                $temp.='<span style="font-size:10px;">Total:'.round($total_fuel).' L</span>';
                }
               $total_fuel_active =0;
            $temp.='</div>
            <div class="tempIcons">';
            if($fsensors['temp']==1){
       $temp  .= '&nbsp;<i class="icon-snow" style="margin-right:4px;"></i><span style="font-size:10px">'.number_format($temp_c,1).' '.$tunit.' </span>';

       if($fsensors['temp2']==1){
        $temp .= ' &nbsp; <span style="font-size:10px">'.number_format($temp_c2,1).' '.$tunit.' </span>';
       }

      
    }
   $temp .= " </div></div></div>";

?>