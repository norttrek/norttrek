<?php
require_once("../../../_class/class.client.php");
require_once("../../../_class/class.device.php");
$objDevice = new Device();
$objClient = new Client();

$id_client = $_GET['id'];

$data = NULL;
$value = (isset($_GET['id'])) ? "update_client_park" : "update_client_park";
$label = (isset($_GET['id'])) ? "Modificar" : "Guardar";

if(isset($_GET['id'])){ 
  $data = $objClient->getClient($_GET['id']); 
  $park = json_decode($data[0]['park'],true);
}

function NlToBr($inString){ return str_replace("<br />", "",$inString); }

?>

<div id="modal">
  <h1>Informaci&oacute;n de Patio</h1>
  <form id="frm_client" name="frm_client">
    <fieldset>
    <p>
      <label class="inline">Direcci&oacute;n del Patio</label>
      <textarea id="direccion" name="direccion" placeholder="Calle y No, Colonia, Ciudad, Estado, C.P" rows="3"><?php echo NlToBr($park['direccion']); ?></textarea><br class="clear" />
    </p>
    <p>
      <select id="contacto_medio" name="contacto_medio" class="left contacto inline">
        <option value="Casa" <?php if($park['contacto_medio'] == "Casa") {echo 'selected="selected"'; } ?>>Casa</option>
        <option value="Movil" <?php if($park['contacto_medio'] == "Movil") {echo 'selected="selected"'; } ?>>M&oacute;vil</option>
        <option value="Oficina" <?php if($park['contacto_medio'] == "Oficina") {echo 'selected="selected"'; } ?>>Oficina</option>
        <option value="Otro" <?php if($park['contacto_medio'] == "Otro") {echo 'selected="selected"'; } ?>>Otro</option>
      </select>
      <input type="text" id="contacto" name="contacto" value="<?php echo $park['contacto'] ?>" class="inline medium"/>
      <br class="clear" />
    </p>
    <p>
      <label class="inline">Observaciones</label>
      <textarea id="observaciones" name="observaciones" placeholder="" rows="3"><?php echo html_entity_decode($park['observaciones']); ?></textarea>
    </p>
    <br />
    <p class="save"><a href="#" class="btn_save onClickSave">Guardar</a></p>
        <input id="id_client" name="id_client" type="hidden"  value="<?php echo $_GET['id']; ?>"/>
        <input id="ctrl" name="ctrl" type="hidden" value="client" />
        <input id="exec" name="exec" type="hidden" value="<?php echo $value; ?>" /> 
        <input type="hidden" id="back" name="back" value="index.php?call=cliente&id=<?php echo $id_client; ?>" />
    </fieldset>
  </form>
</div>