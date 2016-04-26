<div class="row">
   <div class="col-md-12 headtabs"  >
  <form class="formMecanic" id="formMecanic<?php echo $assets[$k]['imei'] ?>">
    <div class="row">
             <div class="col-lg-6">
                <label class="labelForm">Seguro:</label>
                <input type="text" name="seguro" value="<?php echo $assets[$k]['seguro'] ?>">
             </div> 

               
              <div class="col-lg-6">
              <label class="labelForm">Vencimiento:</label>
                <input style="width: 111px !important;" type="text" name="vencimiento" value="<?php echo $assets[$k]['vencimiento'] ?>" id="vencimiento<?php echo $assets[$k]['imei'] ?>"  imei="<?php echo $assets[$k]['imei'] ?>" placeholder="Vencimiento" class="fmDate" >
                 
             </div></div>

 <div class="row">
            <div class="col-lg-6">
            <label class="labelForm">Póliza de Seguro:</label>
             <input type="text" name="poliza" value="<?php echo $assets[$k]['poliza'] ?>">
             </div>

              <div class="col-lg-6">
            <label class="labelSwitch labelForm">Fisicomecánica <?php if($assets[$k]['fisicomecanica'] ==1){ $fisicomecanica = "checked"; } ?></label>
              <span class="onoffswitch" style="float:left">
                  <input type="checkbox" name="onoffswitch"  imei="<?php echo $assets[$k]['imei'] ?>" class="onoffswitch-checkbox " id="fisicomecanica<?php echo $assets[$k]['imei'] ?>"  <?php  echo $fisicomecanica ?> >
                  <label class="onoffswitch-label" for="fisicomecanica<?php echo $assets[$k]['imei'] ?>">
                      <span class="onoffswitch-inner"></span>
                      <span class="onoffswitch-switch"></span>
                  </label>
              </span> 
              <input type="text"  name="fmDate" value="<?php echo $assets[$k]['fmDate'] ?>" id="fmDate<?php echo $assets[$k]['imei'] ?>"  imei="<?php echo $assets[$k]['imei'] ?>" placeholder="Vencimiento" class="fmDate" >
             </div></div>

              <div class="row">
             <div class="col-lg-6">
            <label class="labelSwitch labelForm">Tecnomecánica: <?php if($assets[$k]['tecnomecanica'] ==1){ $tecnomecanica = "checked"; } ?></label>
              <span class="onoffswitch" style="float:left">
                  <input type="checkbox" name="onoffswitch"  imei="<?php echo $assets[$k]['imei'] ?>" class="onoffswitch-checkbox " id="tecnomecanica<?php echo $assets[$k]['imei'] ?>"  <?php  echo $tecnomecanica ?>>
                  <label class="onoffswitch-label" for="tecnomecanica<?php echo $assets[$k]['imei'] ?>">
                      <span class="onoffswitch-inner"></span>
                      <span class="onoffswitch-switch"></span>
                  </label>
              </span> 
              <input type="text" name="tcDate" value="<?php echo $assets[$k]['tcDate'] ?>" id="tcDate<?php echo $assets[$k]['imei'] ?>"  imei="<?php echo $assets[$k]['imei'] ?>" placeholder="Vencimiento" class="fmDate" >
             </div>

             <div class="col-lg-6">
            <label class="labelSwitch labelForm">Ver. ambiental: <?php if($assets[$k]['ambiental'] ==1){ $ambiental = "checked"; } ?></label>
              <span class="onoffswitch" style="float:left">
                  <input type="checkbox" name="onoffswitch"  imei="<?php echo $assets[$k]['imei'] ?>" class="onoffswitch-checkbox " id="ambiental<?php echo $assets[$k]['imei'] ?>"   <?php  echo $ambiental ?>>
                  <label class="onoffswitch-label" for="ambiental<?php echo $assets[$k]['imei'] ?>">
                      <span class="onoffswitch-inner"></span>
                      <span class="onoffswitch-switch"></span>
                  </label>
              </span> 
              <input type="text" name="vaDate" value="<?php echo $assets[$k]['vaDate'] ?>" id="vaDate<?php echo $assets[$k]['imei'] ?>"  imei="<?php echo $assets[$k]['imei'] ?>" placeholder="Vencimiento" class="fmDate" >
             </div></div>

               <div class="row">
             <div class="col-lg-6">
            <label class="labelSwitch labelForm">C-TPAT: <?php if($assets[$k]['tpat'] ==1){ $tpat = "checked"; } ?></label>
              <span class="onoffswitch" style="float:left">
                  <input type="checkbox" name="onoffswitch"  imei="<?php echo $assets[$k]['imei'] ?>" class="onoffswitch-checkbox " id="tpat<?php echo $assets[$k]['imei'] ?>"  <?php  echo $tpat ?> >
                  <label class="onoffswitch-label" for="tpat<?php echo $assets[$k]['imei'] ?>">
                      <span class="onoffswitch-inner"></span>
                      <span class="onoffswitch-switch"></span>
                  </label>
              </span> 
              <input type="text" name="ctDate" value="<?php echo $assets[$k]['ctDate'] ?>" id="ctDate<?php echo $assets[$k]['imei'] ?>"  imei="<?php echo $assets[$k]['imei'] ?>" placeholder="Vencimiento" class="fmDate" >
             </div>

             <div class="col-lg-6">
            <label class="labelSwitch labelForm">NEEC: <?php if($assets[$k]['neec'] ==1){ $neec = "checked"; } ?></label>
              <span class="onoffswitch" style="float:left">
                  <input type="checkbox" name="onoffswitch"  imei="<?php echo $assets[$k]['imei'] ?>" class="onoffswitch-checkbox " id="neec<?php echo $assets[$k]['imei'] ?>" <?php  echo $neec ?>  >
                  <label class="onoffswitch-label" for="neec<?php echo $assets[$k]['imei'] ?>">
                      <span class="onoffswitch-inner"></span>
                      <span class="onoffswitch-switch"></span>
                  </label>
              </span> 
              <input type="text" name="neDate" value="<?php echo $assets[$k]['neDate'] ?>" id="neDate<?php echo $assets[$k]['imei'] ?>"  imei="<?php echo $assets[$k]['imei'] ?>" placeholder="Vencimiento" class="fmDate" >
             </div></div>
 

 <div class="row">
               <div class="col-lg-6">
                <label class="labelForm">DOF:</label>
                <input type="text" name="dof" value="<?php echo $assets[$k]['dof'] ?>">
             </div>

              
              <div class="col-lg-6">
              <label class="labelForm">US DOT:</label>
             <input type="text" name="usdot" value="<?php echo $assets[$k]['usdot'] ?>">
             </div></div>


             <div class="row">
            <div class="col-lg-6">
            <label class="labelForm">TXDOT:</label>
             <input type="text" name="txdot" value="<?php echo $assets[$k]['txdot'] ?>">
             </div>

             <div class="col-lg-6" '.$step18.'>
             <input type="hidden" name="imei" value="<?php echo $assets[$k]['imei'] ?>">
             <input type="hidden" name="action" value="saveMecanic">
             <input type="submit" class="saveMecanic savebtn"  imei="<?php echo $assets[$k]['imei'] ?>" id="saveMecanic<?php echo $assets[$k]['imei'] ?>" value="Guardar">
             </div></div>
             
              </form>


 </div> </div>