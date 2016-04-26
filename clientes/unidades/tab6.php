 <style type="text/css">
.unidad{
  font-size: 10px;
} 
.alertsfields div{
  margin-bottom: 10px;
}
 </style>
 <div class="row alertsfields">
  <div class="col-lg-12 text-center" style="border-top:1px solid #131313; padding-top: 8px;">ALERTAS DE UNIDAD</div>
    <div class="col-lg-6">
  SOS
            <span class="onoffswitch" style="float:right">
                  <input type="checkbox"    name="sos" active="<?php echo $assets[$k]['sos']?>" imei="<?php echo $assets[$k]['imei'] ?>" class="alarmSwitch onoffswitch-checkbox " id="sos<?php echo $assets[$k]['imei'] ?>"  <?php if($assets[$k]['sos']==1){ echo "checked"; } ?>  >
                  <label class="onoffswitch-label"   for="sos<?php echo $assets[$k]['imei'] ?>">
                      <span class="onoffswitch-inner"></span>
                      <span class="onoffswitch-switch"></span>
                  </label>
            </span> 
    </div>
    <div class="col-lg-6">
  Bateria Baja
            <span class="onoffswitch" style="float:right">
                  <input type="checkbox"    name="battery_low" active="<?php echo $assets[$k]['battery_low']?>" imei="<?php echo $assets[$k]['imei'] ?>" class="alarmSwitch onoffswitch-checkbox " id="battery_low<?php echo $assets[$k]['imei'] ?>"  <?php if($assets[$k]['battery_low']==1){ echo "checked"; } ?>  >
                  <label class="onoffswitch-label"   for="battery_low<?php echo $assets[$k]['imei'] ?>">
                      <span class="onoffswitch-inner"></span>
                      <span class="onoffswitch-switch"></span>
                  </label>
            </span> 
    </div>
    <div class="col-lg-6">Motor ON
            <span class="onoffswitch" style="float:right">
                  <input type="checkbox"   name="eng_on" active="<?php echo $assets[$k]['eng_on']?>" imei="<?php echo $assets[$k]['imei'] ?>" class="alarmSwitch onoffswitch-checkbox " id="eng_on<?php echo $assets[$k]['imei'] ?>"  <?php if($assets[$k]['eng_on']==1){ echo "checked"; } ?>  >
                  <label class="onoffswitch-label"   for="eng_on<?php echo $assets[$k]['imei'] ?>">
                      <span class="onoffswitch-inner"></span>
                      <span class="onoffswitch-switch"></span>
                  </label>
            </span> 
    </div>
    <div class="col-lg-6">Motor OFF 
            <span class="onoffswitch" style="float:right">
                  <input type="checkbox"    name="eng_off" active="<?php echo $assets[$k]['eng_off']?>" imei="<?php echo $assets[$k]['imei'] ?>" class="alarmSwitch onoffswitch-checkbox " id="eng_off<?php echo $assets[$k]['imei'] ?>"  <?php if($assets[$k]['eng_off']==1){ echo "checked"; } ?>  >
                  <label class="onoffswitch-label"   for="eng_off<?php echo $assets[$k]['imei'] ?>">
                      <span class="onoffswitch-inner"></span>
                      <span class="onoffswitch-switch"></span>
                  </label>
            </span> 
    </div>
    <div class="col-lg-6">Desc Equipo 
            <span class="onoffswitch" style="float:right">
                  <input type="checkbox"    name="device_off" active="<?php echo $assets[$k]['device_off']?>" imei="<?php echo $assets[$k]['imei'] ?>" class="alarmSwitch onoffswitch-checkbox " id="device_off<?php echo $assets[$k]['imei'] ?>"  <?php if($assets[$k]['device_off']==1){ echo "checked"; } ?>  >
                  <label class="onoffswitch-label"   for="device_off<?php echo $assets[$k]['imei'] ?>">
                      <span class="onoffswitch-inner"></span>
                      <span class="onoffswitch-switch"></span>
                  </label>
            </span> 
    </div>
    <div class="col-lg-6">Conex Equipo 
            <span class="onoffswitch" style="float:right">
                  <input type="checkbox"    name="device_on" active="<?php echo $assets[$k]['device_on']?>" imei="<?php echo $assets[$k]['imei'] ?>" class="alarmSwitch onoffswitch-checkbox " id="device_on<?php echo $assets[$k]['imei'] ?>"  <?php if($assets[$k]['device_on']==1){ echo "checked"; } ?>  >
                  <label class="onoffswitch-label"   for="device_on<?php echo $assets[$k]['imei'] ?>">
                      <span class="onoffswitch-inner"></span>
                      <span class="onoffswitch-switch"></span>
                  </label>
            </span> 
    </div>
    <div class="col-lg-12 text-center" style="border-top:1px solid #131313; padding-top: 8px;">ALERTAS DE TIEMPOS DE UNIDAD DETENIDA</div>
    <div class="col-lg-6">1 minuto
            <span class="onoffswitch" style="float:right">
                  <input type="checkbox"    name="1min" active="<?php echo $assets[$k]['1min']?>" imei="<?php echo $assets[$k]['imei'] ?>" class="alarmSwitch onoffswitch-checkbox " id="1min<?php echo $assets[$k]['imei'] ?>"  <?php if($assets[$k]['1min']==1){ echo "checked"; } ?>  >
                  <label class="onoffswitch-label"   for="1min<?php echo $assets[$k]['imei'] ?>">
                      <span class="onoffswitch-inner"></span>
                      <span class="onoffswitch-switch"></span>
                  </label>
            </span> 
    </div>
    <div class="col-lg-6">3 minutos
            <span class="onoffswitch" style="float:right">
                  <input type="checkbox"    name="3min" active="<?php echo $assets[$k]['3min']?>" imei="<?php echo $assets[$k]['imei'] ?>" class="alarmSwitch onoffswitch-checkbox " id="3min<?php echo $assets[$k]['imei'] ?>"  <?php if($assets[$k]['3min']==1){ echo "checked"; } ?>  >
                  <label class="onoffswitch-label"   for="3min<?php echo $assets[$k]['imei'] ?>">
                      <span class="onoffswitch-inner"></span>
                      <span class="onoffswitch-switch"></span>
                  </label>
            </span> 
    </div>
    <div class="col-lg-6">10 minutos
            <span class="onoffswitch" style="float:right">
                  <input type="checkbox"    name="10min" active="<?php echo $assets[$k]['10min']?>" imei="<?php echo $assets[$k]['imei'] ?>" class="alarmSwitch onoffswitch-checkbox " id="10min<?php echo $assets[$k]['imei'] ?>"  <?php if($assets[$k]['10min']==1){ echo "checked"; } ?>  >
                  <label class="onoffswitch-label"   for="10min<?php echo $assets[$k]['imei'] ?>">
                      <span class="onoffswitch-inner"></span>
                      <span class="onoffswitch-switch"></span>
                  </label>
            </span> 
    </div>
    <div class="col-lg-6">30 minutos
            <span class="onoffswitch" style="float:right">
                  <input type="checkbox"    name="30min" active="<?php echo $assets[$k]['30min']?>" imei="<?php echo $assets[$k]['imei'] ?>" class="alarmSwitch onoffswitch-checkbox " id="30min<?php echo $assets[$k]['imei'] ?>"  <?php if($assets[$k]['30min']==1){ echo "checked"; } ?>  >
                  <label class="onoffswitch-label"   for="30min<?php echo $assets[$k]['imei'] ?>">
                      <span class="onoffswitch-inner"></span>
                      <span class="onoffswitch-switch"></span>
                  </label>
            </span> 
    </div>
    <div class="col-lg-6">60 minutos
            <span class="onoffswitch" style="float:right">
                  <input type="checkbox"    name="60min" active="<?php echo $assets[$k]['60min']?>" imei="<?php echo $assets[$k]['imei'] ?>" class="alarmSwitch onoffswitch-checkbox " id="60min<?php echo $assets[$k]['imei'] ?>"  <?php if($assets[$k]['60min']==1){ echo "checked"; } ?>  >
                  <label class="onoffswitch-label"   for="60min<?php echo $assets[$k]['imei'] ?>">
                      <span class="onoffswitch-inner"></span>
                      <span class="onoffswitch-switch"></span>
                  </label>
            </span> 
    </div>
    <div class="col-lg-6">90 minutos
            <span class="onoffswitch" style="float:right">
                  <input type="checkbox"    name="90min" active="<?php echo $assets[$k]['90min']?>" imei="<?php echo $assets[$k]['imei'] ?>" class="alarmSwitch onoffswitch-checkbox " id="90min<?php echo $assets[$k]['imei'] ?>"  <?php if($assets[$k]['90min']==1){ echo "checked"; } ?>  >
                  <label class="onoffswitch-label"   for="90min<?php echo $assets[$k]['imei'] ?>">
                      <span class="onoffswitch-inner"></span>
                      <span class="onoffswitch-switch"></span>
                  </label>
            </span> 
    </div>
    <div class="col-lg-6">2 horas
            <span class="onoffswitch" style="float:right">
                  <input type="checkbox"    name="2hr" active="<?php echo $assets[$k]['2hr']?>" imei="<?php echo $assets[$k]['imei'] ?>" class="alarmSwitch onoffswitch-checkbox " id="2hr<?php echo $assets[$k]['imei'] ?>"  <?php if($assets[$k]['2hr']==1){ echo "checked"; } ?>  >
                  <label class="onoffswitch-label"   for="2hr<?php echo $assets[$k]['imei'] ?>">
                      <span class="onoffswitch-inner"></span>
                      <span class="onoffswitch-switch"></span>
                  </label>
            </span> 
    </div>
    <div class="col-lg-6">3 horas
            <span class="onoffswitch" style="float:right">
                  <input type="checkbox"    name="3hr" active="<?php echo $assets[$k]['3hr']?>" imei="<?php echo $assets[$k]['imei'] ?>" class="alarmSwitch onoffswitch-checkbox " id="3hr<?php echo $assets[$k]['imei'] ?>"  <?php if($assets[$k]['3hr']==1){ echo "checked"; } ?>  >
                  <label class="onoffswitch-label"   for="3hr<?php echo $assets[$k]['imei'] ?>">
                      <span class="onoffswitch-inner"></span>
                      <span class="onoffswitch-switch"></span>
                  </label>
            </span> 
    </div>

</div>