<div class="row">
<div class="col-md-12 headtabs"  >
<form class="formEngine" id="formEngine<?php echo $assets[$k]['imei'] ?>">

              <div class="row">
             <div class="col-lg-6">
                <label>Rendimiento km/l</label>
                <input type="text" name="rendimientokl" value="<?php echo $assets[$k]['rendimientokl'] ?>">
             </div>

              
              <div class="col-lg-6">
              <label>Cilindros:</label>
             <input type="text" name="cilindros" value="<?php echo $assets[$k]['cilindros'] ?>">
             </div></div> 


             <div class="row">
            <div class="col-lg-6">
            <label>Transmisión:</label>
             <input type="text" name="transmision" value="<?php echo $assets[$k]['transmision'] ?>">
             </div>
              <div class="col-lg-6">
              <label>Velocidades:</label>
             <input type="text" name="velocidades" value="<?php echo $assets[$k]['velocidades'] ?>">
             </div></div> 


             <div class="row">
             <div class="col-lg-6">
              <label>Diferencial:</label>
             <input type="text" name="diferencial" value="<?php echo $assets[$k]['diferencial'] ?>">
             </div>

             <div class="col-lg-6">
              <label>No. Serie Motor:</label>
             <input type="text" name="seriemotor" value="<?php echo $assets[$k]['seriemotor'] ?>">
             </div> </div> 

             <div class="row">
             <div class="col-lg-6"  >
             <input type="hidden" name="imei" value="<?php echo $assets[$k]['imei'] ?>">
             <input type="hidden" name="action" value="saveEngine">
             <input type="submit" class="saveEngine savebtn"  imei="<?php echo $assets[$k]['imei'] ?>" id="saveEngine<?php echo $assets[$k]['imei'] ?>" value="Guardar">
             </div> 
              </form></div>
            </div>  </div>