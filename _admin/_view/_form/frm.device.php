<?php
require_once("../../../_class/class.device.php");
$objDevice = new Device();
$data = NULL;
$value = (isset($_GET['id'])) ? "update" : "save";
$label = (isset($_GET['id'])) ? "Modificar" : "Guardar";
if(isset($_GET['id'])){ $data = $objDevice->getDevice($_GET['id']); }
?>

<div id="modal">
<h1>Registro de Equipo GPS</h1>
<form id="frm_device" name="frm_device" class="theme">
  <fieldset>
    <p>
      <label>Nombre </label>
      <input type="text" id="txt_device" name="txt_device" value="<?php echo $data[0]['device'] ?>" />
      <br class="clear" />
    </p>
    <p>
      <label>Descripci&oacute;n </label>
      <input type="text" id="txt_description" name="txt_description" value="<?php echo $data[0]['description'] ?>" />
      <br class="clear" />
    </p>

    <p class="submit" align="right">
      <input type="button" name="btnSave" id="btnSave" value="Guardar" class="onClickSave" />
    </p>
    <input id="id" name="id" type="hidden"  value="<?php if(isset($_GET['id'])) echo $_GET['id']; ?>"/>
    <input id="ctrl" name="ctrl" type="hidden" value="device" />
    <input id="exec" name="exec" type="hidden" value="<?php echo $value; ?>" /> 
    <input type="hidden" id="back" name="back" value="index.php?call=equipos" />
  </fieldset>
</form>
</div>
