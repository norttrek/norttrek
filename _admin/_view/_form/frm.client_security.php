<?php
require_once("../../../_class/class.client.php");
require_once("../../../_class/class.device.php");
$objDevice = new Device();
$objClient = new Client();

$id_client = $_GET['id'];

$data = NULL;
$value = (isset($_GET['id'])) ? "update_client_security" : "update_client_security";
$label = (isset($_GET['id'])) ? "Modificar" : "Guardar";

$contacto = NULL;
if(isset($_GET['id'])){ 
  $data = $objClient->getClient($_GET['id']); 
  $data_user = $objClient->getUsersClient($_GET['id']);
  $security = json_decode($data[0]['security'],true);
}

?>

<div id="modal">
    <h1>Control de Seguridad</h1>
    <form id="frm_client" name="frm_client">
      <fieldset>
        <p>
          <label class="inline">PIN de Seguridad</label>
          <input type="text" id="pin_seguridad" name="pin_seguridad" value="<?php echo $security['pin_seguridad']; ?>" class="inline"/>
          <br class="clear" />
        </p>
       
        <p>
          <label class="inline">PIN (Bloqueos)</label>
          <input type="text" id="pin_bloqueos" name="pin_bloqueos" value="<?php echo $security['pin_bloqueos']; ?>" class="inline"/>
          <br class="clear" />
        </p>
        <p>
          <label class="inline">Usuario Administrador<em>*</em></label>
          <input type="text" id="txt_user" name="txt_user" value="<?php echo $data_user[0]['user'] ?>" class="inline"/>
          <br class="clear" />
        </p>
        
         <p>
          <label class="inline">Contrase&ntilde;a Administrador<em>*</em></label>
          <input type="password" id="txt_password" name="txt_password" value="<?php echo $data_user[0]['password'] ?>" class="inline"/>
          <br class="clear" />
        </p>
   
         
        <p class="save"><a href="#" class="btn_save onClickSave">Guardar</a></p>
        <input id="id_client" name="id_client" type="hidden"  value="<?php echo $_GET['id']; ?>"/>
        <input id="ctrl" name="ctrl" type="hidden" value="client" />
        <input id="exec" name="exec" type="hidden" value="<?php echo $value; ?>" /> 
        <input type="hidden" id="back" name="back" value="index.php?call=cliente&id=<?php echo $id_client; ?>" />
      </fieldset>
    </form>
  
</div>