 
<div id="cuenta" class="containerHeadSide navmenu navmenu-default navmenu-fixed-left offcanvas" role="navigation">
  <div class=" row headSide">
  <div class="col-lg-10"> Mi cuenta</div>
  <div class="col-lg-2">
    <a href="javascript:void(0)" side="cuenta" id="ClosegeocercasBtn" class="Closeside btn_close onClickCloseWindow"><i class="fa fa-times fa-lg"></i></a></a>
  </div>
 </div>
  
   <?php
session_start();
require_once("../_class/class.asset.php");
require_once("../_class/class.client.php");
$objAsset = new Asset();
$objClient = new Client();

$data = NULL;
$value = (isset($_GET['id'])) ? "update" : "save";
$label = (isset($_GET['id'])) ? "Modificar" : "Guardar";
if(isset($_GET['id'])){ $data_user = $objClient->getUserClient($_GET['id']); }
$user = $objClient->getClient($_SESSION['logged']['id_client']);
 
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
<style type="text/css">
.title2{
  font-size: 14px;
  font-weight: bold;
}

</style>
<div id="modal" class="col-md-12 small darkblue"> 
  <form id="frm_my_account" name="frm_my_account">
    <fieldset style="margin-bottom:20px">
      <p class="title2">Ingresa tu nueva contrase&ntilde;a</p>
      <p><label>Contrase&ntilde;a</label><input type="password" id="txt_password" name="txt_password" value="<?php echo $data_user[0]['password']; ?>"></p>
      <p><label>Verifica Contrase&ntilde;a</label><input type="password" id="txt_password_verify" name="txt_password_verify" value="<?php echo $data_user[0]['password']; ?>"></p>
   
      <a href="#" class="btn_save onClickChangePass">Guardar</a>
      <br />
    </fieldset>  
             
  </form>  
 
  <form id="frm_my_email" name="frm_my_email">
    <fieldset>
      <p class="title2">Ingresa tu correo electróniso</p>
      <p><label>Correo</label><input type="text" id="txt_mail" name="txt_mail" value="<?php echo $user[0]['email']; ?>"></p>
      <input type="hidden" id="id_user_email" value="<?php echo $_SESSION['logged']['id_client']; ?>">
      <a href="#" class="btn_save onClickSaveEmail">Guardar</a>
      <br />
    </fieldset>  
             
  </form> 
<a href="javascript:void(0)" class="onClickUpdateTemp temp <?php echo $_SESSION['logged']['temp']; ?>" rel="<?php echo $_SESSION['logged']['temp']; ?>"></a>
</div> 
</div> 
<script>
$("#cuenta").offcanvas({ autohide: false, toggle: false  });
 
</script>