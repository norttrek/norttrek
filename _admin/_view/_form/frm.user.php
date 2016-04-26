<?php
require_once("../../../_class/class.user.php");
$objUser = new User();
$data = NULL;
$value = (isset($_GET['id'])) ? "update" : "save";
$label = (isset($_GET['id'])) ? "Modificar" : "Guardar";
if(isset($_GET['id'])){ $data = $objUser->getUser($_GET['id']); }
$permissions = json_decode($data[0]['permissions'],true);
?>

<div id="modal" style="width:800px">
  <h1>Registro de Usuarios</h1>
  <form id="frm_user" name="frm_user">
    <fieldset>
      <p>
        <label class="inline">Tipo de usuario <em>*</em></label>
        <select id="lst_type" name="lst_type" class="left">
          <option value="NULL">(Seleccione una Opci&oacute;n)</option>
          <option value="1" <?php if($data[0]['type'] == "1") {echo 'selected="selected"'; } ?>>Administrador</option>
          <option value="2" <?php if($data[0]['type'] == "2") {echo 'selected="selected"'; } ?>>General</option>
        </select>
        <br class="clear" />
      </p>
      <p>
        <label class="inline">Usuario  <em>*</em></label>
        <input type="text" id="txt_user" name="txt_user" value="<?php echo $data[0]['user'] ?>" class="inline"/>
        <br class="clear" />
      </p>
      <p>
        <label class="inline">Contrase&ntilde;a  <em>*</em></label>
        <input type="password" id="txt_password" name="txt_password" value="<?php echo $data[0]['password'] ?>" class="inline"/>
        <br class="clear" />
      </p>
      <p>
        <label class="inline">Contrase&ntilde;a Operaciones  <em>*</em></label>
        <input type="password" id="txt_op_password" name="txt_op_password" value="<?php echo $data[0]['op_password'] ?>" class="inline"/>
        <br class="clear" />
      </p>
      <p>
      <table class="result" cellpadding="0" cellspacing="0">
      <thead>
  <tr>
    <th>Menu</th> 
    <th>Submenu</th> 
    <th align="center">Visible</th> 
    <th align="center">Crear</th> 
    <th align="center">Editar</th> 
    <th align="center">Eliminar</th> 
    <th align="center">Clave</th> 
    <th align="center">Plataforma</th> 
    <th align="center">Reset</th> 
  </tr>
  </thead>
  <tbody>
  <tr>
    <td>Clientes</td>
    <td></td> 
    <td align="center"><input type="checkbox" name="cliente_vis" id="cliente_vis" <?php if($permissions['cliente_vis']=="on"){ echo 'checked="checked"'; } ?> /></td>
    <td align="center"><input type="checkbox" name="cliente_cre" id="cliente_cre" <?php if($permissions['cliente_cre']=="on"){ echo 'checked="checked"'; } ?> /></td>
    <td align="center"><input type="checkbox" name="cliente_edi" id="cliente_edi" <?php if($permissions['cliente_edi']=="on"){ echo 'checked="checked"'; } ?> /></td>
    <td align="center"><input type="checkbox" name="cliente_eli" id="cliente_eli" <?php if($permissions['cliente_eli']=="on"){ echo 'checked="checked"'; } ?> /></td>
    <td align="center"><input type="checkbox" name="cliente_cla" id="cliente_cla" <?php if($permissions['cliente_cla']=="on"){ echo 'checked="checked"'; } ?> /></td>
    <td align="center"><input type="checkbox" name="cliente_pla" id="cliente_pla" <?php if($permissions['cliente_pla']=="on"){ echo 'checked="checked"'; } ?> /></td> 
    <td align="center"></td> 
  </tr>
  <tr class="odd">
    <td>Clientes</td>
    <td>Informaci&oacute;n</td> 
    <td align="center"></td>
     <td align="center"></td>
    <td align="center"><input type="checkbox" name="cliente_inf_edi" id="cliente_inf_edi" <?php if($permissions['cliente_inf_edi']=="on"){ echo 'checked="checked"'; } ?> /></td>
    <td align="center"></td>
    <td align="center"><input type="checkbox" name="cliente_inf_cla" id="cliente_inf_cla" <?php if($permissions['cliente_inf_cla']=="on"){ echo 'checked="checked"'; } ?> /></td>
    <td align="center"></td> 
    <td align="center"></td> 
  </tr>
  <tr>
   <td>Clientes</td>
    <td>Unidades</td> 
  <td align="center"></td>
    <td align="center"><input type="checkbox" name="cliente_uni_cre" id="cliente_uni_cre" <?php if($permissions['cliente_uni_cre']=="on"){ echo 'checked="checked"'; } ?> /></td>
    <td align="center"><input type="checkbox" name="cliente_uni_edi" id="cliente_uni_edi" <?php if($permissions['cliente_uni_edi']=="on"){ echo 'checked="checked"'; } ?> /></td>
    <td align="center"><input type="checkbox" name="cliente_uni_eli" id="cliente_uni_eli" <?php if($permissions['cliente_uni_eli']=="on"){ echo 'checked="checked"'; } ?> /></td>
    <td align="center"><input type="checkbox" name="cliente_uni_cla" id="cliente_uni_cla" <?php if($permissions['cliente_uni_cla']=="on"){ echo 'checked="checked"'; } ?> /></td>
    <td align="center"></td>
    <td align="center"></td>  
  </tr>
  <tr class="odd">
    <td>Catalogos</td>
    <td></td> 
    <td align="center"><input type="checkbox" name="catalogo_vis" id="catalogo_vis" <?php if($permissions['catalogo_vis']=="on"){ echo 'checked="checked"'; } ?> /></td>
     <td align="center"></td>
    <td align="center"></td>
    <td align="center"></td>
    <td align="center"></td>
    <td align="center"></td> 
    <td align="center"></td> 
  </tr>
  <tr>
    <td>Catalogos</td>
    <td>Equipos</td> 
    <td align="center"><input type="checkbox" name="catalogo_eq_vis" id="catalogo_eq_vis" <?php if($permissions['catalogo_eq_vis']=="on"){ echo 'checked="checked"'; } ?> /></td>
    <td align="center"><input type="checkbox" name="catalogo_eq_cre" id="catalogo_eq_cre" <?php if($permissions['catalogo_eq_cre']=="on"){ echo 'checked="checked"'; } ?> /></td>
    <td align="center"><input type="checkbox" name="catalogo_eq_edi" id="catalogo_eq_edi" <?php if($permissions['catalogo_eq_edi']=="on"){ echo 'checked="checked"'; } ?> /></td>
    <td align="center"><input type="checkbox" name="catalogo_eq_eli" id="catalogo_eq_eli" <?php if($permissions['catalogo_eq_eli']=="on"){ echo 'checked="checked"'; } ?> /></td>
    <td align="center"><input type="checkbox" name="catalogo_eq_cla" id="catalogo_eq_cla" <?php if($permissions['catalogo_eq_cla']=="on"){ echo 'checked="checked"'; } ?> /></td>
    <td align="center"></td>
    <td align="center"></td>  
  </tr>
  <tr class="odd">
    <td>Catalogos</td>
    <td>Staff</td> 
    <td align="center"><input type="checkbox" name="catalogo_staff_vis" id="catalogo_staff_vis" <?php if($permissions['catalogo_staff_vis']=="on"){ echo 'checked="checked"'; } ?> /></td>
    <td align="center"><input type="checkbox" name="catalogo_staff_cre" id="catalogo_staff_cre" <?php if($permissions['catalogo_staff_cre']=="on"){ echo 'checked="checked"'; } ?> /></td>
    <td align="center"><input type="checkbox" name="catalogo_staff_edi" id="catalogo_staff_edi" <?php if($permissions['catalogo_staff_edi']=="on"){ echo 'checked="checked"'; } ?> /></td>
    <td align="center"><input type="checkbox" name="catalogo_staff_eli" id="catalogo_staff_eli" <?php if($permissions['catalogo_staff_eli']=="on"){ echo 'checked="checked"'; } ?> /></td>
    <td align="center"><input type="checkbox" name="catalogo_staff_cla" id="catalogo_staff_cla" <?php if($permissions['catalogo_staff_cla']=="on"){ echo 'checked="checked"'; } ?> /></td>
    <td align="center"></td> 
    <td align="center"></td> 
  </tr>
  <tr>
    <td>Catalogos</td>
    <td>Imeis</td> 
    <td align="center"><input type="checkbox" name="catalogo_imei_vis" id="catalogo_imei_vis" <?php if($permissions['catalogo_imei_vis']=="on"){ echo 'checked="checked"'; } ?> /></td>
    <td align="center"><input type="checkbox" name="catalogo_imei_cre" id="catalogo_imei_cre" <?php if($permissions['catalogo_imei_cre']=="on"){ echo 'checked="checked"'; } ?> /></td>
    <td align="center"><input type="checkbox" name="catalogo_imei_edi" id="catalogo_imei_edi" <?php if($permissions['catalogo_imei_edi']=="on"){ echo 'checked="checked"'; } ?> /></td>
    <td align="center"><input type="checkbox" name="catalogo_imei_eli" id="catalogo_imei_eli" <?php if($permissions['catalogo_imei_eli']=="on"){ echo 'checked="checked"'; } ?> /></td>
    <td align="center"><input type="checkbox" name="catalogo_imei_cla" id="catalogo_imei_cla" <?php if($permissions['catalogo_imei_cla']=="on"){ echo 'checked="checked"'; } ?> /></td>
    <td align="center"></td> 
    <td align="center"></td> 
  </tr>
   <tr class="odd">
    <td>Catalogos</td>
    <td>Numeros</td> 
    <td align="center"><input type="checkbox" name="catalogo_num_vis" id="catalogo_num_vis" <?php if($permissions['catalogo_num_vis']=="on"){ echo 'checked="checked"'; } ?> /></td>
    <td align="center"><input type="checkbox" name="catalogo_num_cre" id="catalogo_num_cre" <?php if($permissions['catalogo_num_cre']=="on"){ echo 'checked="checked"'; } ?> /></td>
    <td align="center"><input type="checkbox" name="catalogo_num_edi" id="catalogo_num_edi" <?php if($permissions['catalogo_num_edi']=="on"){ echo 'checked="checked"'; } ?> /></td>
    <td align="center"><input type="checkbox" name="catalogo_num_eli" id="catalogo_num_eli" <?php if($permissions['catalogo_num_eli']=="on"){ echo 'checked="checked"'; } ?> /></td>
    <td align="center"><input type="checkbox" name="catalogo_num_cla" id="catalogo_num_cla" <?php if($permissions['catalogo_num_cla']=="on"){ echo 'checked="checked"'; } ?> /></td>
    <td align="center"></td> 
    <td align="center"></td> 
  </tr>
  <tr>
    <td>Reportes</td>
    <td></td>
    <td align="center"><input type="checkbox" name="reportes_vis" id="reportes_vis" <?php if($permissions['reportes_vis']=="on"){ echo 'checked="checked"'; } ?> /></td>
     <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center"></td> 
    <td align="center"></td> 
  </tr>
  <tr class="odd"> 
    <td>Reportes</td>
    <td>Reporte GPRS</td>
    <td align="center"><input type="checkbox" name="reportes_gprs_vis" id="reportes_gprs_vis" <?php if($permissions['reportes_gprs_vis']=="on"){ echo 'checked="checked"'; } ?> /></td>
     <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td> 
    <td align="center"><input type="checkbox" name="reportes_gprs_res" id="reportes_gprs_res" <?php if($permissions['reportes_gprs_res']=="on"){ echo 'checked="checked"'; } ?> /></td> 
  </tr>
  <tr>  
    <td>Reportes</td>
    <td>Reporte General de Equipos</td>
    <td align="center"><input type="checkbox" name="reportes_gen_vis" id="reportes_gen_vis" <?php if($permissions['reportes_gen_vis']=="on"){ echo 'checked="checked"'; } ?> /></td>
     <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center"></td> 
    <td align="center"></td> 
  </tr>
  <tr>  
    <td>Papelera</td>
    <td></td>
    <td align="center"><input type="checkbox" name="papelera_vis" id="papelera_vis" <?php if($permissions['papelera_vis']=="on"){ echo 'checked="checked"'; } ?> /></td>
     <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center">&nbsp;</td>
    <td align="center"></td> 
    <td align="center"></td> 
  </tr>
  </tbody>
  
</table>
      </p>
        
      <p class="save"><a href="#" class="btn_save onClickSave">Guardar</a></p>
      <input id="id" name="id" type="hidden"  value="<?php if(isset($_GET['id'])) echo $_GET['id']; ?>"/>
      <input id="ctrl" name="ctrl" type="hidden" value="user" />
      <input id="exec" name="exec" type="hidden" value="<?php echo $value; ?>" /> 
      <input type="hidden" id="back" name="back" value="index.php?call=usuarios" />

    </fieldset>
  </form>
</div>