<?php
$statusResetButton = $objGPRS->getResetStatus($assets[$k]['imei'], $lastReportTime,$lastClickReset);
 if($statusResetButton == 0){
     $ResetButtonClass = "btn-inactive";
     $ResetButtonFunction = "null";
  }elseif($statusResetButton == 1){
     $ResetButtonClass = "btn-active";
     $ResetButtonFunction = "setLastResetTime";
  }
$elock_status = $assets[$k]['elock'];

$marcha_status = $assets[$k]['marcha'];
 
$engine_status = $assets[$k]['engine'];
  if($gprs_data[0]['v2_eng_block'] == 1){
    $eng_block_color = '#F33';
    $eng_block_class = 'btn-alert-red';
    $eng_block_function = 'unlockEngine';
  }elseif($gprs_data[0]['v2_eng_block'] == 0){
    $eng_block_class = 'btn-active';
    $eng_block_function = 'lockEngine';

  }

   $gprs[$i]['starter_block'] = $gprs_data[0]['v2_starter_block'];

   if($gprs_data[0]['v2_starter_block'] == 1){
    $starter_block_color = '#F33';
    $starter_block_class = 'btn-alert-red';
    $starter_block_function = 'desbloquearMarcha';
  }elseif ($gprs_data[0]['v2_starter_block'] == 0) {
    $starter_block_class = 'btn-active';
    $starter_block_function = 'bloquearMarcha';
  }

 if($gprs_data[0]['v2_e_lock'] == 1){
    $e_lock_color = '#1cf100';
    $e_lock_class_btn = 'btn-alert-green';
    $e_lock_class = 'lock';
    $e_lock_function = 'openElock';
  }else{
    $e_lock_class = 'unlock';
    $e_lock_class_btn = 'btn-alert-red';
    $e_lock_function = 'closeElock';
  }
 

  if($assets[$k]['speedAlarmActive'] == 1 ){
    $speedAlarmActive = 'checked';
  }else{
    $speedAlarmActive = '';
  }

?>
<div class="row"> 
<div class="col-md-12 headtabs"  >
<p class="ptabs">  
                <button onclick="<?php echo $ResetButtonFunction ?>(<?php echo $assets[$k]['imei'] ?>)" type="submit" class="btnt <?php echo $ResetButtonClass ?>">RESET </button>
            </p> 
            <?php if($engine_status == 1){
              //<button onclick="'.$gprs[$k]['eng_block_function']. '('.$gprs[$k]['imei']. ')" imei="'.$gprs[$k]['imei']. '" function="'.$gprs[$k]['eng_block_function']. '" type="submit" class="blockengine btnt '. $gprs[$k]['eng_block_class']. '"><i class="icon-engine"  ></i>  </button>
             ?><p class="ptabs"> 
                   <button  name="<?php echo $assets[$k]['alias']; ?>" imei="<?php echo $assets[$k]['imei'] ?>" function="<?php echo $eng_block_function?>" type="submit" class="blockengine btnt <?php echo $eng_block_class ?>"><i class="icon-engine"  ></i>  </button>
               </p>
            <?php      }
            if($assets[$k]['marcha'] == 1){ ?>
              <p class="ptabs"> 
                                  <button onclick="<?php echo $starter_block_function ?>(<?php echo $assets[$k]['imei'] ?>)" type="submit" class="btnt <?php echo $starter_block_class ?>"><i class="icon-key"></i> </button>
                                </p>
                          <?php   }
            if($assets[$k]['elock']== 1){
                ?><p class="ptabs"> 
                <button onclick="<?php echo $e_lock_function ?>(<?php echo $assets[$k]['imei'] ?>)" type="submit" class="btnt <?php echo $e_lock_class_btn ?>"><i class="fa fa-<?php echo $e_lock_class ?> fa-lg" style="color:<?php echo $e_lock_color ?>"></i>  </button>
            </p>
                  <?php } ?>
            <hr class="hr">
            <p class="ptabs" >
              <p class="ptabs" ><button ><i latlang="<?php echo $latLang ?>" imei="<?php echo $assets[$k]['imei'] ?>"  class="fa fa-map-marker getDir">
              </i></button><input class="address<?php echo $assets[$k]['imei'] ?>" style="    width: 151px; " placeholder="Obtener Dirección" type="text">
               
              <?php if($client_info[0]['sms'] == 0){
                  ?><button   class="dirB bdir<?php echo $assets[$k]['imei'] ?> " title="Esta funcion necesita contrato de sms"><i class="fa fa-mobile"></i></button> 

          <?php }elseif ($client_info[0]['sms']==1) {
                ?> <button onclick="openSmsBox(<?php echo $assets[$k]['imei'] ?>)" class="dirB bdir<?php echo $assets[$k]['imei'] ?>"><i class="fa fa-mobile"></i></button> 

          <?php } 


               //onclick="openEmailBox('.$gprs[$k]['imei'].')" ?>
             <button  class="dirB bdir<?php echo $assets[$k]['imei'] ?>"><i class="fa fa-envelope-o"></i></button><button class="dirB bdir<?php echo $assets[$k]['imei'] ?>"><i onclick="get_direction(<?php echo $latLang ?>,<?php echo $assets[$k]['imei'] ?>)" class="fa fa-refresh"></i></button></p>
       
             <p class="ptabs sendBySms sendByEmail<?php echo $assets[$k]['imei'] ?>" style="display:none">
              <input class="smsinput coordenadas<?php echo $assets[$k]['imei'] ?>" type="text" placeholder="Ingresa un correo electrónico">
               <button onclick="sendCordsByEmail(<?php echo $assets[$k]['imei'] ?>,<?php echo $latLang ?>)" type="submit" class="submitB">
                  <i class="fa fa-caret-right"></i>  
                </button>
             </p> 


<p class="ptabsSwicht"> 
<span class="alarmttitle">Alarma de Velocidad<br></span>
  <span class="onoffswitch" style="float:left">
    <input type="checkbox" name="onoffswitch"   imei="<?php echo $assets[$k]['imei'] ?>" class="onoffswitch-checkbox speedAlarmActive" id="myonoffswitch<?php echo $assets[$k]['imei'] ?>"  <?php echo $speedAlarmActive ?>>
    <label class="onoffswitch-label"   for="myonoffswitch<?php echo $assets[$k]['imei'] ?>">
        <span class="onoffswitch-inner"></span>
        <span class="onoffswitch-switch"></span>
    </label>
</span> 
 
<input class="inputtabsSM" id="speed<?php echo $assets[$k]['imei'] ?>"   placeholder="Alarma de Velocidad" type="text" value="<?php echo $assets[$k]['speedAlarm'] ?>">
<button style="margin-right:7px" type="submit" onClick="setSpeed(<?php echo $assets[$k]['imei'] ?>)" class="submitB">
  <i class="fa fa-caret-right"></i>  
</button>
</p>
<?php 
if($assets[$k]['speed'] == 1){
 ?><p class="ptabsSwicht" style="border-left: 1px solid #333333;
    padding-left: 5px;"> 
    <span class="alarmttitle">Limite de Velocidad<br></span>
<span  class="onoffswitch" style="float:left">
    <input type="checkbox" name="onoffswitch"  imei="<?php echo $assets[$k]['imei'] ?>" class="onoffswitch-checkbox speedLimitActive" id="myonoffswitchspeed<?php echo $assets[$k]['imei'] ?>" <?php echo $assets[$k]['speed_limitActive'] ?>>
     <label class="onoffswitch-label"   for="myonoffswitchspeed<?php echo $assets[$k]['imei'] ?>">
        <span class="onoffswitch-inner"></span>
        <span class="onoffswitch-switch"></span>
    </label>
</span>
 
<input class="inputtabsSM" id="speedLimit<?php echo $assets[$k]['imei'] ?>"    placeholder="Limite de Velocidad" type="text" value="<?php echo $assets[$k]['speedLimit'] ?>">
<button type="submit" onClick="setSpeedLimit(<?php echo $assets[$k]['imei'] ?>)" class="submitB">
  <i class="fa fa-caret-right"></i>  
</button>
</p>
<?php } ?>
  <hr class="hr"> 
<p class="ptabs"  > 
 
 <span class="alarmttitle">Reporte de Tiempo<br></span> 
  <select class="inputtabsSMtime setReportTime<?php echo $assets[$k]['imei'] ?>">
  <option value="1">1</option>
  <option value="2">2</option>
  <option value="3">3</option>
  <option value="4">4</option>
  <option selected="selected" value="5">5</option>
</select>
  <button onclick="setReportTime(<?php echo $assets[$k]['imei'] ?>)" type="submit" class="submitB">
    <i class="fa fa-caret-right"></i>  
  </button>
</p></div></div>