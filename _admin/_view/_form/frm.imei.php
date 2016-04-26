<?php
require_once("../../../_class/class.device.php");
require_once("../../../_class/class.imei.php");

$objIMEI = new IMEI();
$objDevice = new Device();

$data = NULL;
$value = (isset($_GET['id'])) ? "update" : "save";
$label = (isset($_GET['id'])) ? "Modificar" : "Guardar";

if(isset($_GET['id'])){ 
  $data = $objIMEI->getIMEI($_GET['id']); 
}

$devices = $objDevice->set_status(1)->getDevice();
$buffer_devices = '<option value="NULL">(Seleccione una Opci&oacute;n)</option>';
for($i=0;$i<count($devices);$i++){
  $selected = '';
  if($data[0]['id_device']==$devices[$i]['id']){ $selected = 'selected="selected"'; }
  $buffer_devices .= '<option value="'.$devices[$i]['id'].'" '.$selected.'>'.$devices[$i]['device'].'</option>';
}	


?>

<div id="modal">
  <h1>Registro de IMEI</h1>
  <form id="frm_imei" name="frm_imei">
    <fieldset>
      <p>
        <label class="inline">IMEI</label>
        <input type="text" id="txt_imei" name="txt_imei" value="<?php echo $data[0]['imei'] ?>" class="inline"/>
        <br class="clear" />
      </p>
      <p>
        <label class="inline">Equipo GPS <em>*</em></label>
        <select id="lst_id_device" name="lst_id_device"><?php echo $buffer_devices; ?></select>
        <br class="clear" />
      </p>

      <p class="save"><a href="#" class="btn_save onClickSaveImei">Guardar</a></p>
        <input id="id" name="id" type="hidden"  value="<?php echo $_GET['id']; ?>"/>
        <input id="ctrl" name="ctrl" type="hidden" value="imei" />
        <input id="exec" name="exec" type="hidden" value="<?php echo $value; ?>" /> 
        <input type="hidden" id="back" name="back" value="index.php?call=imei" />
      </fieldset>
    </form>
  
</div>