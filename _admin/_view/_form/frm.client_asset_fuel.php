<?php
require_once("../../../_class/class.client.php");
require_once("../../../_class/class.device.php");
require_once("../../../_class/class.imei.php");
require_once("../../../_class/class.number.php");
$objDevice = new Device();
$objClient = new Client();
$objNumber = new Number();
$objIMEI = new IMEI();

$id_client = $_GET['id_client'];
$id_asset = $_GET['id_asset'];

$data = NULL;
$value = (isset($id_asset)) ? "save_asset_fuel" : "save_asset_fuel";
$label = (isset($id_asset)) ? "Modificar" : "Guardar";

$contacto = NULL;
if(isset($id_asset)){ 
  $data = $objClient->set_id_asset($id_asset)->getClientAssets(); 
  $info = json_decode($data[0]['data'],true);
  $value = (isset($id_asset)) ? "update_asset" : "update_asset";
  $label = (isset($id_asset)) ? "Modificar" : "Guardar";
  $sensor = json_decode($data[0]['sensor'],true);
}

$devices = $objDevice->getDevice();
$buffer_devices = '<option value="NULL">(Seleccione una Opci&oacute;n)</option>';
for($i=0;$i<count($devices);$i++){
  $selected = '';
  if($data[0]['id_device']==$devices[$i]['id']){ $selected = 'selected="selected"'; }
  $buffer_devices .= '<option value="'.$devices[$i]['id'].'" '.$selected.'>'.$devices[$i]['device'].'</option>';
}	

$numbers = $objNumber->getNumberNotUsed();
if($data[0]['no']!=NULL){ 
  $sn = $objNumber->set_no($data[0]['no'])->getNumber();
  $buffer_numbers = '<option value="'.$data[0]['no'].'">'.$sn[0]['serial_no'].' ('.$data[0]['no'].')</option>'; 
}else{ 
  $buffer_numbers = '<option value="NULL">(Seleccione una Opci&oacute;n)</option>'; 
}

for($i=0;$i<count($numbers);$i++){
  $selected = '';
  if($data[0]['no']==$numbers[$i]['no']){ $selected = 'selected="selected"'; }
  $buffer_numbers .= '<option value="'.$numbers[$i]['no'].'" '.$selected.'>'.$numbers[$i]['serial_no'].' ('.$numbers[$i]['no'].')</option>';
}

$imeis = $objIMEI->getIMEINotUsed();
if($data[0]['imei']!=NULL){ $buffer_imeis = '<option value="'.$data[0]['imei'].'">'.$data[0]['imei'].'</option>'; }else{ $buffer_imeis = '<option value="NULL">(Seleccione una Opci&oacute;n)</option>'; }

for($i=0;$i<count($imeis);$i++){
  $selected = '';
  if($data[0]['imei']==$imeis[$i]['imei']){ $selected = 'selected="selected"'; }
  $buffer_imeis .= '<option value="'.$imeis[$i]['imei'].'" '.$selected.'>'.$imeis[$i]['imei'].'</option>';
}




?>

<div id="modal">
  <h1>Registro de Eq.Combustible</h1>
  <form id="frm_client" name="frm_client">
    <fieldset>
      <p style="display:none;">
        <label class="inline">Nombre Eq. Combustible</label>
        <input type="text" id="txt_alias" name="txt_alias" value="<?php echo $data[0]['alias'] ?>" class="inline"/>
        <br class="clear" />
      </p>
       
      <p>
        <label class="inline">IMEI  <em>*</em></label>
        <select id="lst_imei" name="lst_imei"><?php echo $buffer_imeis; ?></select>
        <br class="clear" />
      </p>
      
      <p>
        <label class="inline">IMEI Unidad  <em>*</em></label>
        <input type="text" id="parent" name="parent" value="<?php echo $info[1]['value'] ?>" class="inline"/>
        <br class="clear" />
      </p>
     
      <p>
        <label class="inline">No. Celular  <em>*</em></label>
        <select id="lst_no" name="lst_no"><?php echo $buffer_numbers; ?></select>
        <br class="clear" />
      </p>
        
      <p>
        <label class="inline">Nombre *</em></label>
        <input type="text" id="info[]" name="info[]" value="<?php echo $info[1]['value'] ?>" class="inline"/>
        <input id="lbl_info[]" name="lbl_info[]" type="hidden" value="name" />
        <br class="clear" />
      </p>
        
        
        <br />
        <p class="save"><a href="#" class="btn_save onClickSave">Guardar</a></p>
        <input id="id_client" name="id_client" type="hidden"  value="<?php echo $id_client; ?>"/>
        <input id="id_asset" name="id_asset" type="hidden"  value="<?php echo $id_asset; ?>"/>
         
        <input id="ctrl" name="ctrl" type="hidden" value="client" />
        <input id="exec" name="exec" type="hidden" value="<?php echo $value; ?>" /> 
        <input type="hidden" id="back" name="back" value="index.php?call=cliente&id=<?php echo $id_client; ?>" />
      </fieldset>
    </form>
    
  
</div>
