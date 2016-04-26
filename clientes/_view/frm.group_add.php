<?php
session_start();
require_once("../../_class/class.asset.php");
require_once("../../_class/class.client.php");
$objAsset = new Asset();
$objClient = new Client();

$value = (isset($_GET['id'])) ? "update_group" : "save_group";
$label = (isset($_GET['id'])) ? "Modificar" : "Guardar";
if(isset($_GET['id'])){ $data_group = $objClient->getClientGroup($_GET['id']); }

$assets = $objAsset->set_id_client($_SESSION['logged']['id_client'])->getAsset();
/* ASSETS TBODY HTML */
 $buffer = NULL;
for($i=0;$i<count($assets);$i++){
  $checked = NULL;
  if($assets[$i]['id_group']==$_GET['id']){ $checked = 'checked="checked"';}
  $data = json_decode($assets[$i]['data'],true);
  
  $buffer .= '<tr>';
  $buffer .= '<td><input type="checkbox" id="chk_asset[]" name="chk_asset[]" class="chk_asset" value="'.$assets[$i]['id'].'" '.$checked.'/></td>';
  $buffer .= '<td>'.$assets[$i]['alias'].'</td>';
  $buffer .= '</tr>';
}
?>
<style>
.datagrid { height:220px; overflow-y:scroll; }
.datagrid table thead tr th { color:#333; }
</style>
<div id="modal" class="small darkblue">
  <h1>Registro de Nuevo Grupo</h1>
  <form id="frm_group_add" name="frm_group_add">
    <fieldset>
      <p><label>Nombre del Grupo</label><input type="text" id="txt_group" name="txt_group" value="<?php echo $data_group[0]['group'] ?>" /></p> 
      <div class="datagrid">
        <table id="tbl_asset_user" width="100%" cellpadding="1" cellspacing="1" border="0">
          <thead>
          <tr>
            <th width="20">*</th>
            <th>Unidad</th>
          </tr>
          </thead>
          <tbody>
          <?php echo $buffer;  ?>
          </tbody>
        </table>
        
       
      </div>
      <br />
      <a href="javascript:void(0)" class="onClickGroupAdd btn_save" rel="<?php echo $value; ?>|<?php echo $_GET['id']; ?>">Guardar</a>
    </fieldset>  
  </form>  
</div>  


