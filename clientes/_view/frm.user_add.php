<?php
session_start();
require_once("../../_class/class.asset.php");
require_once("../../_class/class.client.php");
$objAsset = new Asset();
$objClient = new Client();

$data = NULL;
$value = (isset($_GET['id'])) ? "update_user" : "save_user";
$label = (isset($_GET['id'])) ? "Modificar" : "Guardar";
if(isset($_GET['id'])){ $data_user = $objClient->set_type(2)->getUserClient($_GET['id']); }


$date_exp = explode(" ",$data_user[0]['date_exp']);

$assets = $objAsset->set_id_client($_SESSION['logged']['id_client'])->getAsset();
$assets_user = $objAsset->getUserAssets($_GET['id']);
/* ASSETS TBODY HTML */
 $buffer = NULL;
for($i=0;$i<count($assets);$i++){
  $checked = '';
  if(in_array($assets[$i]['id'],$assets_user)){ $checked = 'checked="checked"'; }
  $data = json_decode($assets[$i]['data'],true);
  $buffer .= '<tr>';
  $buffer .= '<td><input type="checkbox" id="chk_asset[]" name="chk_asset[]" class="chk_asset" value="'.$assets[$i]['id'].'" '.$checked.'/></td>';
  $buffer .= '<td>'.$assets[$i]['alias'].'</td>';
  $buffer .= '</tr>';
}

?>
<style>
.datagrid { height:180px; overflow-y:scroll; }
.datagrid table thead tr th { color:#333; }
</style>
<div id="modal" class="small darkblue">
  <h1>Registro de Nuevo Usuarios</h1>
  <form id="frm_user_add" name="frm_user_add">
    <fieldset>
      <p><label>Usuario</label><input type="text" id="txt_user" name="txt_user" value="<?php echo $data_user[0]['user']; ?>"></p>
      <p><label>Contrase&ntilde;a</label><input type="text" id="txt_password" name="txt_password" value="<?php echo $data_user[0]['password']; ?>"></p>
      <p>
        <label>Fecha de Expiraci&oacute;n</label><input type="text" id="txt_date_exp" name="txt_date_exp" value="<?php if($date_exp[0] != "" && $date_exp[0] != '0000-00-00'){ echo $objAsset->formatDate($date_exp[0],"max"); } ?>" class="isDate">
        <select id="hour" name="hour">
            <option value="00:00:00" <?php if ($date_exp[1]=="00:00:00"){ echo 'selected="selected"'; }?>>00:00</option>
            <option value="01:00:00" <?php if ($date_exp[1]=="01:00:00"){ echo 'selected="selected"'; }?>>01:00</option>
            <option value="02:00:00" <?php if ($date_exp[1]=="02:00:00"){ echo 'selected="selected"'; }?>>02:00</option>
            <option value="03:00:00" <?php if ($date_exp[1]=="03:00:00"){ echo 'selected="selected"'; }?>>03:00</option>
            <option value="04:00:00" <?php if ($date_exp[1]=="04:00:00"){ echo 'selected="selected"'; }?>>04:00</option>
            <option value="05:00:00" <?php if ($date_exp[1]=="05:00:00"){ echo 'selected="selected"'; }?>>05:00</option>
            <option value="06:00:00" <?php if ($date_exp[1]=="06:00:00"){ echo 'selected="selected"'; }?>>06:00</option>
            <option value="07:00:00" <?php if ($date_exp[1]=="07:00:00"){ echo 'selected="selected"'; }?>>07:00</option>
            <option value="08:00:00" <?php if ($date_exp[1]=="08:00:00"){ echo 'selected="selected"'; }?>>08:00</option>
            <option value="09:00:00" <?php if ($date_exp[1]=="09:00:00"){ echo 'selected="selected"'; }?>>09:00</option>
            <option value="10:00:00" <?php if ($date_exp[1]=="10:00:00"){ echo 'selected="selected"'; }?>>10:00</option>
            <option value="11:00:00" <?php if ($date_exp[1]=="11:00:00"){ echo 'selected="selected"'; }?>>11:00</option>
            <option value="12:00:00" <?php if ($date_exp[1]=="12:00:00"){ echo 'selected="selected"'; }?>>12:00</option>
            <option value="13:00:00" <?php if ($date_exp[1]=="13:00:00"){ echo 'selected="selected"'; }?>>13:00</option>
            <option value="14:00:00" <?php if ($date_exp[1]=="14:00:00"){ echo 'selected="selected"'; }?>>14:00</option>
            <option value="15:00:00" <?php if ($date_exp[1]=="15:00:00"){ echo 'selected="selected"'; }?>>15:00</option>
            <option value="16:00:00" <?php if ($date_exp[1]=="16:00:00"){ echo 'selected="selected"'; }?>>16:00</option>
            <option value="17:00:00" <?php if ($date_exp[1]=="17:00:00"){ echo 'selected="selected"'; }?>>17:00</option>
            <option value="18:00:00" <?php if ($date_exp[1]=="18:00:00"){ echo 'selected="selected"'; }?>>18:00</option>
            <option value="19:00:00" <?php if ($date_exp[1]=="19:00:00"){ echo 'selected="selected"'; }?>>19:00</option>
            <option value="20:00:00" <?php if ($date_exp[1]=="20:00:00"){ echo 'selected="selected"'; }?>>20:00</option>
            <option value="21:00:00" <?php if ($date_exp[1]=="21:00:00"){ echo 'selected="selected"'; }?>>21:00</option>
            <option value="22:00:00" <?php if ($date_exp[1]=="22:00:00"){ echo 'selected="selected"'; }?>>22:00</option>
            <option value="23:00:00" <?php if ($date_exp[1]=="23:00:00"){ echo 'selected="selected"'; }?>>23:00</option>
            <option value="23:59:59" <?php if ($date_exp[1]=="23:59:59"){ echo 'selected="selected"'; }?>>23:59:59</option>
          </select>
      </p>
      
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
      <a href="javascript:void(0)" class="onClickUserAdd btn_save" rel="<?php echo $value; ?>|<?php echo $_GET['id']; ?>">Guardar</a>
      <input type="hidden" id="date_exp" name="date_exp" value="<?php echo $date_exp[0]; ?>" />
    </fieldset>  
             
  </form>  
</div>  
<script>
  $(document).ready(function(){
   $('.isDate').datepicker({
    altFormat: 'yy-mm-dd', 
    altField: "#date_exp",
    dateFormat: 'd \'de\' MM \'del\' yy',
    changeMonth: true,
    changeYear: true,
    yearRange: '1981:2020',
    monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'],
    monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
    dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
    dayNames: ['Domingo', 'Lunes', 'Martes', 'MiÃ©rcoles', 'Jueves', 'Viernes', 'SÃ¡bado']
    });

    
      
  });
  
  </script>

