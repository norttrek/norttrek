<?php 
 if($assets[$k]['act']==1){
    $active_d = "checked";
  } 

?>
<div class="row">
<div class="col-md-12 headtabs"  >
 <form method="post" class="formUnidad" id="formUnidad<?php echo $assets[$k]['imei'] ?>" method="post" enctype="multipart/form-data">
              
               <div class="row">
             <div class="col-lg-6">
                <label  class="labelForm">Nombre</label>
                <input type="text" name="nombre_u" value="<?php echo $assets[$k]['nombre_u'] ?>">
             </div>

              <div class="col-lg-6">
              <label class="labelForm">Imagen</label>
             <input type="file" name="imagen_u" style="height: 22px; width: 116px; padding: 0; margin: 0;">
             </div></div> 


               <div class="row">
              <div class="col-lg-6" style="padding-top: 8px;" >
              <label class="labelSwitch labelForm radioForm">Activo</label>
              <span class="onoffswitch" style="float:right;     margin: 2px 0px 1px 0px;">
                  <input type="checkbox" name="active"  imei="<?php echo $assets[$k]['imei'] ?>" class="onoffswitch-checkbox" id="active<?php echo $assets[$k]['imei'] ?>"  <?php echo $active_d ?> >
                  <label class="onoffswitch-label" for="active<?php echo $assets[$k]['imei'] ?>">
                      <span class="onoffswitch-inner"></span>
                      <span class="onoffswitch-switch"></span>
                  </label>
              </span> 
             </div>

              <div class="col-lg-6">
              <label class="labelForm">Odometro:</label>
             <input type="text" name="odometro" value="<?php echo $assets[$k]['odometro'] ?>">
             </div></div> 


              <div class="row">
            <div class="col-lg-6">
            <label class="labelForm">Marca</label>
             <input type="text" name="marca" value="<?php echo $assets[$k]['marca'] ?>">
             </div>

              <div class="col-lg-6">
              <label class="labelForm">Modelo</label>
              <select name="modelo">
                <option value="1" <?php if($assets[$k]['modelo']==1){ echo 'selected';} ?> >Sedan</option>
                <option value="2" <?php if($assets[$k]['modelo']==2){  echo 'selected';} ?> >SUV</option>
                <option value="3" <?php if($assets[$k]['modelo']==3){  echo 'selected';} ?> >Pick Up</option>
                <option value="4" <?php if($assets[$k]['modelo']==4){ echo 'selected';} ?> >Camión de reparto</option>
                <option value="5"<?php if($assets[$k]['modelo']==5){  echo 'selected';} ?> >Chasis</option>
                <option value="6"<?php if($assets[$k]['modelo']==6){  echo 'selected';} ?> >Torton</option>
                <option value="7"<?php if($assets[$k]['modelo']==7){  echo 'selected';} ?> >Motocicleta</option>
                <option value="8"<?php if($assets[$k]['modelo']==8){  echo 'selected';} ?> >ATV</option>
                <option value="9"<?php if($assets[$k]['modelo']==9){  echo 'selected';} ?> >Jetski</option>
                <option value="10"<?php if($assets[$k]['modelo']==10){  echo 'selected';} ?> >Lancha</option>
                <option value="11"<?php if($assets[$k]['modelo']==11){  echo 'selected';} ?> >Yate</option>
                <option value="12"<?php if($assets[$k]['modelo']==12){  echo 'selected';} ?> >Taxi</option>
                <option value="13"<?php if($assets[$k]['modelo']==13){  echo 'selected';} ?> >Autobus</option>
                <option value="14"<?php if($assets[$k]['modelo']==14){  echo 'selected';} ?> >Tracto-Camión 5 rueda</option>
                <option value="15"<?php if($assets[$k]['modelo']==15){  echo 'selected';} ?> >Mula</option>
                <option value="16"<?php if($assets[$k]['modelo']==16){  echo 'selected';} ?> >Caja seca</option>
                <option value="17"<?php if($assets[$k]['modelo']==17){  echo 'selected';} ?> >Caja refrigerada</option>
                <option value="18"<?php if($assets[$k]['modelo']==18){  echo 'selected';} ?> >Lowboy</option>
                <option value="19"<?php if($assets[$k]['modelo']==19){  echo 'selected';} ?> >Plataforma</option>
                <option value="20"<?php if($assets[$k]['modelo']==20){  echo 'selected';} ?> >Redilas</option>
                <option value="21"<?php if($assets[$k]['modelo']==21){  echo 'selected';} ?> >Tolva</option>
                <option value="22"<?php if($assets[$k]['modelo']==22){  echo 'selected';} ?> >Pipa</option>
                <option value="0"<?php if($assets[$k]['modelo']==0){  echo 'selected';} ?> >Otro</option>
              </select>
             </div></div> 

              <div class="row">
              <div class="col-lg-6">
              <label class="labelForm">Año:</label>
             <input type="text" name="anio" value="<?php echo $assets[$k]['anio'] ?>">
             </div>

             <div class="col-lg-6">
              <label class="labelForm">Color:</label>
             <input type="text" name="color" value="<?php echo $assets[$k]['color'] ?>" >
             </div></div> 


              <div class="row">
             <div class="col-lg-6">
              <label class="labelForm">Placas:</label>
             <input type="text" name="placas" value="<?php echo $assets[$k]['placa'] ?>">
             </div>

             <div class="col-lg-6">
              <label class="labelForm">Pasajeros:</label>
             <input type="text" name="pasajeros" value="<?php echo $assets[$k]['pasajeros'] ?>">
             </div></div> 


              <div class="row">
             <div class="col-lg-6">
              <label class="labelForm">Rendimiento km/l:</label>
             <input type="text" name="rendimiento" value="<?php echo $assets[$k]['rendimiento'] ?>">
             </div>

             <div class="col-lg-6">
              <label class="labelForm">Peso Tara:</label>
             <input type="text" name="ptara" value="<?php echo $assets[$k]['pesotara'] ?>">
             </div></div> 


              <div class="row">
             <div class="col-lg-6">
              <label class="labelForm">Ejes:</label>
             <input type="text" name="ejes" value="<?php echo $assets[$k]['ejes'] ?>">
             </div>

             <div class="col-lg-6">
              <label class="labelForm">No. Serie Chasis:</label>
             <input type="text" name="chasis" value="<?php echo $assets[$k]['chasis'] ?>">
             </div></div> 


              <div class="row">
             <div class="col-lg-6">
              <label class="labelForm">Cap. de carga:</label>
             <input type="text" name="ccarga" value="<?php echo $assets[$k]['capcarga'] ?>">
             </div>
 
             <div class="col-lg-6">
              <label class="labelForm">Cap. de arrastre:</label>
             <input type="text" name="carrastre" value="<?php echo $assets[$k]['caparrastre'] ?>">
             </div></div> 


              <div class="row">
             <div class="col-lg-6">
              <label class="labelForm">Tipo de carga:</label>
             <input type="text" name="tcarga" value="<?php echo $assets[$k]['tdcarga'] ?>">
             </div>

             <div class="col-lg-6" >
             <input type="hidden" name="imei" value="<?php echo $assets[$k]['imei'] ?>">
             <input type="hidden" name="action" value="saveUnidad">
             <input type="submit" class="saveUnidad savebtn"  imei="<?php echo $assets[$k]['imei'] ?>" id="saveUnidad<?php echo $assets[$k]['imei'] ?>" value="Guardar">
             </div> </div> 
              </form>
              </div>        </div>