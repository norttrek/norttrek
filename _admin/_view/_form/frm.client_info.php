<?php
require_once("../../../_class/class.client.php");
require_once("../../../_class/class.device.php");
$objDevice = new Device();
$objClient = new Client();

$id_client = $_GET['id'];

$data = NULL;
$value = (isset($_GET['id'])) ? "update_client_info" : "update_client_info";
$label = (isset($_GET['id'])) ? "Modificar" : "Guardar";

$contacto = NULL;
if(isset($_GET['id'])){ 
  $data = $objClient->getClient($_GET['id']); 
  $info = json_decode($data[0]['info'],true);
}

function NlToBr($inString){
  return str_replace("<br />", "",$inString);
}

$groups = $objClient->getClientGroups($id_client);
$buffer_groups = '<option value="NULL">(Seleccione una Opci&oacute;n)</option>';
for($i=0;$i<count($groups);$i++){
  $selected = '';
  if($data[0]['id_group']==$groups[$i]['id']){ $selected = 'selected="selected"'; }
  $buffer_groups .= '<option value="'.$groups[$i]['id'].'" '.$selected.'>'.$groups[$i]['group'].'</option>';
}	


$devices = $objDevice->getDevice();
$buffer_devices = '<option value="NULL">(Seleccione una Opci&oacute;n)</option>';
for($i=0;$i<count($devices);$i++){
  $selected = '';
  if($data[0]['id_equipo']==$devices[$i]['id']){ $selected = 'selected="selected"'; }
  $buffer_devices .= '<option value="'.$devices[$i]['id'].'" '.$selected.'>'.$devices[$i]['device'].'</option>';
}	


?>

<div id="modal">
    <h1>Informaci&oacute;n General</h1>
    <form id="frm_client" name="frm_client">
      <fieldset>
          <p><label class="inline">Raz&oacute;n Social  <em>*</em></label><input type="text" id="razon_social" name="razon_social" value="<?php echo html_entity_decode($info['razon_social']); ?>" class="inline"/><br class="clear" /></p>
          <p><label class="inline">RFC  <em>*</em></label><input type="text" id="rfc" name="rfc" value="<?php echo $info['rfc'] ?>" class="inline"/><br class="clear" /></p>
          <p><label class="inline">E-mail</label><input type="text" id="correo" name="correo" value="<?php echo $info['correo'] ?>" class="inline"/><br class="clear" /></p>
          <p><label class="inline">Giro de la Empresa  <em>*</em></label><input type="text" id="giro_empresa" name="giro_empresa" value="<?php echo html_entity_decode($info['giro_empresa']); ?>" class="inline"/><br class="clear" /></p>
          <p><strong>Direcci&oacute;n</strong></p>
          <p><label class="inline">Calle y No.</label><input type="text" id="calle_no" name="calle_no" value="<?php echo html_entity_decode($info['calle_no']); ?>" class="inline"/><br class="clear" /></p>
          <p><label class="inline">No. Exterior</label><input type="text" id="no_ext" name="no_ext" value="<?php echo $info['no_ext'] ?>" class="inline medium"/><br class="clear" /></p>
          <p><label class="inline">No. Interior</label><input type="text" id="no_int" name="no_int" value="<?php echo $info['no_int'] ?>" class="inline"/><br class="clear" /></p>
          <p><label class="inline">Colonia</label><input type="text" id="colonia" name="colonia" value="<?php echo html_entity_decode($info['colonia']); ?>" class="inline"/><br class="clear" /></p>
          <p><label class="inline">Delegacion / Municipio</label><input type="text" id="dele_mun" name="dele_mun" value="<?php echo html_entity_decode($info['dele_mun']); ?>" class="inline"/><br class="clear" /></p>
          <p><label class="inline">Ciudad</label><input type="text" id="ciudad" name="ciudad" value="<?php echo html_entity_decode($info['ciudad']); ?>" class="inline"/><br class="clear" /></p>
          <p><label class="inline">Estado</label><input type="text" id="estado" name="estado" value="<?php echo html_entity_decode($info['estado']); ?>" class="inline"/><br class="clear" /></p>
          <p><label class="inline">CP</label><input type="text" id="cp" name="cp" value="<?php echo $info['cp'] ?>" class="inline"/><br class="clear" /></p>
		  <p><strong>Contacto</strong></p>	 
          <p>
		    <select id="contacto_1_medio" name="contacto_1_medio" class="left contacto inline">
		      <option value="Casa" <?php if($info['contacto_1_medio'] == "Casa") {echo 'selected="selected"'; } ?>>Casa</option>
		      <option value="Movil" <?php if($info['contacto_1_medio'] == "Movil") {echo 'selected="selected"'; } ?>>M&oacute;vil</option>
		      <option value="Oficina" <?php if($info['contacto_1_medio'] == "Oficina") {echo 'selected="selected"'; } ?>>Oficina</option>
              <option value="Otro" <?php if($info['contacto_1_medio'] == "Otro") {echo 'selected="selected"'; } ?>>Otro</option>
            </select>
            <input type="text" id="contacto_1" name="contacto_1" value="<?php echo $info['contacto_1'] ?>" class="inline medium"/>
            <br class="clear" />
		  </p>
		  <p>
		    <select id="contacto_2_medio" name="contacto_2_medio" class="left contacto inline">
              <option value="Movil" <?php if($info['contacto_2_medio'] == "Movil") {echo 'selected="selected"'; } ?>>M&oacute;vil</option>
              <option value="Casa" <?php if($info['contacto_2_medio'] == "Casa") {echo 'selected="selected"'; } ?>>Casa</option>
              <option value="Oficina" <?php if($info['contacto_2_medio'] == "Oficina") {echo 'selected="selected"'; } ?>>Oficina</option>
              <option value="Otro" <?php if($info['contacto_2_medio'] == "Otro") {echo 'selected="selected"'; } ?>>Otro</option>
		    </select>
		    <input type="text" id="contacto_2" name="contacto_2" value="<?php echo $info['contacto_2'] ?>" class="inline medium"/>
		    <br class="clear" />
		  </p>
		  <p>
            <select id="contacto_3_medio" name="contacto_3_medio" class="left contacto inline">
              <option value="Oficina" <?php if($info['contacto_3_medio'] == "Oficina") {echo 'selected="selected"'; } ?>>Oficina</option>
              <option value="Casa" <?php if($info['contacto_3_medio'] == "Casa") {echo 'selected="selected"'; } ?>>Casa</option>
              <option value="Movil" <?php if($info['contacto_3_medio'] == "Movil") {echo 'selected="selected"'; } ?>>M&oacute;vil</option>
              <option value="Otro" <?php if($info['contacto_3_medio'] == "Otro") {echo 'selected="selected"'; } ?>>Otro</option>
            </select>
		    <input type="text" id="contacto_3" name="contacto_3" value="<?php echo $info['contacto_3'] ?>" class="inline medium"/>
		    <br class="clear" />
		  </p>
          <p><label class="inline">No. de Cliente</label><input type="text" id="clave_interna" name="clave_interna" value="<?php echo $info['clave_interna'] ?>" class="inline"/><br class="clear" /></p>
          <p><label class="inline">Digito</label><input type="text" id="digito" name="digito" value="<?php echo $info['digito'] ?>" class="inline"/><br class="clear" /></p>

          
          
            <p class="save"><a href="#" class="btn_save onClickSave">Guardar</a></p>
        <input id="id_client" name="id_client" type="hidden"  value="<?php echo $_GET['id']; ?>"/>
        <input id="ctrl" name="ctrl" type="hidden" value="client" />
        <input id="exec" name="exec" type="hidden" value="<?php echo $value; ?>" /> 
        <input type="hidden" id="back" name="back" value="index.php?call=cliente&id=<?php echo $id_client; ?>" />
      </fieldset>
    </form>
  
</div>