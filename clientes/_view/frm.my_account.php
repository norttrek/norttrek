<?php
session_start();
require_once("../../_class/class.asset.php");
require_once("../../_class/class.client.php");
$objAsset = new Asset();
$objClient = new Client();

$data = NULL;
$value = (isset($_GET['id'])) ? "update" : "save";
$label = (isset($_GET['id'])) ? "Modificar" : "Guardar";
if(isset($_GET['id'])){ $data_user = $objClient->getUserClient($_GET['id']); }



$assets = $objAsset->set_id_client($_SESSION['logged']['id_client'])->getAsset();
/* ASSETS TBODY HTML */
 $buffer = NULL;
for($i=0;$i<count($assets);$i++){
  $data = json_decode($assets[$i]['data'],true);
  $buffer .= '<tr>';
  $buffer .= '<td><input type="checkbox" id="chk_asset[]" name="chk_asset[]" class="chk_asset" /></td>';
  $buffer .= '<td>'.$data['name'].'</td>';
  $buffer .= '</tr>';
}

?>
<div id="modal" class="small darkblue">
  <h1>Mi Cuenta</h1>
  <form id="frm_my_account" name="frm_my_account">
    <fieldset>
      <p>Ingresa tu nueva contrase&ntilde;a</p>
      <p><label>Contrase&ntilde;a</label><input type="password" id="txt_password" name="txt_password" value="<?php echo $data_user[0]['password']; ?>"></p>
      <p><label>Verifica Contrase&ntilde;a</label><input type="password" id="txt_password_verify" name="txt_password_verify" value="<?php echo $data_user[0]['password']; ?>"></p>
   
      <a href="#" class="btn_save onClickChangePass">Guardar</a>
      <br />
    </fieldset>  
         	   
  </form>  
</div>  


