<?php
require_once("../../../_class/class.client.php");
$objClient = new Client();

$data = NULL;
$value = (isset($_GET['id'])) ? "update" : "save";
$label = (isset($_GET['id'])) ? "Modificar" : "Guardar";
$no_client = ($objClient->getMaxNoClient()+1);
$contacto = NULL;
if(isset($_GET['id'])){ 
  $data = $objClient->getClient($_GET['id']); 
  $data_user = $objClient->getUsersClient($_GET['id']);
  $security = json_decode($data[0]['security'],true);
  $no_client = $data[0]['no_client'];
}


function NlToBr($inString){ return str_replace("<br />", "",$inString); }


?>

<div id="modal">
  <h1>Registro de Cliente</h1>
    <form id="frm_client" name="frm_client">
      <fieldset>
        <p>
          <label class="inline">Nombre Comercial  <em>*</em></label>
          <input type="text" id="txt_client" name="txt_client" value="<?php echo $data[0]['client'] ?>" class="inline"/>
          <br class="clear" />
        </p>
          <p>
          <label class="inline">Usuario<em>*</em></label>
          <input type="text" id="txt_user" name="txt_user" value="<?php echo $data_user[0]['user'] ?>" class="inline"/>
          <br class="clear" />
        </p>
        
         <p>
          <label class="inline">Contrase&ntilde;a<em>*</em></label>
          <input type="password" id="txt_password" name="txt_password" value="<?php echo $data_user[0]['password'] ?>" class="inline"/>
          <br class="clear" />
        </p>
        
        <p>
          <label class="inline">No. Cliente Interno<em></em></label>
          <input type="text" id="txt_no_client" name="txt_no_client" value="<?php echo $no_client; ?>" class="inline" />
          <br class="clear" />
       
        </p>
         <p>
          <label class="inline">Prueba </label>
          <input id="chk_test" name="chk_test" type="checkbox" value="<?php echo $no_client; ?>" class="onTestChange" />
          <br class="clear" />
        </p>
        <p>
          <label class="inline">Privado </label>
          <input id="chk_private" name="chk_private" type="checkbox" value="1" <?php if($data[0]['private']==1){ echo 'checked="checked"';}   ?> />
          <br class="clear" />
        </p>
       
        <p class="save"><a href="#" class="btn_save onClickSave">Guardar</a></p>
        <input id="id" name="id" type="hidden"  value="<?php if(isset($_GET['id'])) echo $_GET['id']; ?>"/>
        <input id="ctrl" name="ctrl" type="hidden" value="client" />
        <input id="exec" name="exec" type="hidden" value="<?php echo $value; ?>" /> 
        <input type="hidden" id="back" name="back" value="index.php?call=clientes" />
      </fieldset>
    </form>
    <script>
	$(document).ready(function(){ 
	  $(".onTestChange").change(function(){ 
	    if($(this).is(':checked')){ $("#txt_no_client").val(0); }else{ $("#txt_no_client").val($("#chk_test").val()); }
	  });
	   
	});
	</script>
  
</div>