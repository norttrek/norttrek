<?php
require_once("../../../_class/class.client.php");
require_once("../../../_class/class.device.php");
require_once("../../../_class/class.imei.php");
require_once("../../../_class/class.number.php");
$objDevice = new Device();
$objClient = new Client();
$objNumber = new Number();
$objIMEI = new IMEI();

$id_client = $_GET['id_client'];
$id_asset = $_GET['id_asset'];

$data = NULL;
$value = (isset($id_asset)) ? "save_asset" : "save_asset";
$label = (isset($id_asset)) ? "Modificar" : "Guardar";

$contacto = NULL;
if(isset($id_asset)){
  $data = $objClient->set_id_asset($id_asset)->getClientAssets();
  $info = json_decode($data[0]['data'],true);
  $value = (isset($id_asset)) ? "update_asset" : "update_asset";
  $label = (isset($id_asset)) ? "Modificar" : "Guardar";
  $sensor = json_decode($data[0]['sensor'],true);
}

// aad debug
//print_r($sensor);

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
  if($data[0]['id_device']==$devices[$i]['id']){ $selected = 'selected="selected"'; }
  $buffer_devices .= '<option value="'.$devices[$i]['id'].'" '.$selected.'>'.$devices[$i]['device'].'</option>';
}

$numbers = $objNumber->getNumberNotUsed();
if($data[0]['no']!=NULL){
  $sn = $objNumber->set_no($data[0]['no'])->getNumber();
  $buffer_numbers = '<option value="'.$data[0]['no'].'">'.$sn[0]['serial_no'].' ('.$data[0]['no'].')</option>';
}else{
  $buffer_numbers = '<option value="NULL">(Seleccione una Opci&oacute;n)</option>';
}

for($i=0;$i<count($numbers);$i++){
  $selected = '';
  if($data[0]['no']==$numbers[$i]['no']){ $selected = 'selected="selected"'; }
  $buffer_numbers .= '<option value="'.$numbers[$i]['no'].'" '.$selected.'>'.$numbers[$i]['serial_no'].' ('.$numbers[$i]['no'].')</option>';
}

$imeis = $objIMEI->getIMEINotUsed();
if($data[0]['imei']!=NULL){ $buffer_imeis = '<option value="'.$data[0]['imei'].'">'.$data[0]['imei'].'</option>'; }else{ $buffer_imeis = '<option value="NULL">(Seleccione una Opci&oacute;n)</option>'; }

for($i=0;$i<count($imeis);$i++){
  $selected = '';
  if($data[0]['imei']==$imeis[$i]['imei']){ $selected = 'selected="selected"'; }
  $buffer_imeis .= '<option value="'.$imeis[$i]['imei'].'" '.$selected.'>'.$imeis[$i]['imei'].'</option>';
}




?>

<div id="modal">
    <h1>Registro de Unidad</h1>
    <form id="frm_client" name="frm_client">
      <fieldset>
        <p style="display:none;">
          <label class="inline">Alias (Interno)</label>
          <input type="text" id="txt_alias" name="txt_alias" value="<?php echo $data[0]['alias'] ?>" class="inline"/>
          <br class="clear" />
        </p>

       <p>
          <label class="inline">IMEI  <em>*</em></label>
          <select id="lst_imei" name="lst_imei"><?php echo $buffer_imeis; ?></select>
          <br class="clear" />
        </p>
        <p>
          <label class="inline">No. Celular  <em>*</em></label>
          <select id="lst_no" name="lst_no"><?php echo $buffer_numbers; ?></select>
          <br class="clear" />
        </p>
        <p>
          <label class="inline">Grupo  <em>*</em></label>
          <select id="lst_id_group" name="lst_id_group"><?php echo $buffer_groups; ?></select>
          <br class="clear" />
        </p>



        <p>
          <label class="inline">Nombre *</em></label>
          <input type="text" id="txt_alias" name="txt_alias" value="<?php echo $data[0]['alias'] ?>" class="inline"/>
          <br class="clear" />
        </p>

         <p>
          <label class="inline">Observaciones</label>
          <input type="text" id="observaciones" name="observaciones" value="<?php echo $info['observaciones'] ?>" class="inline"/>
          <br class="clear" />
        </p>

        <p>
          <label class="inline">Tipo de Vehiculo <em>*</em></label>
          <select id="tipo_vehiculo" name="tipo_vehiculo">
            <option value="Automovil" <?php if($info['tipo_vehiculo']=='Automovil') echo 'selected="selected"'; ?>>Automovil</option>
            <option value="Camioneta de Reparto" <?php if($info['tipo_vehiculo']=='Camioneta de Reparto') echo 'selected="selected"'; ?>>Camioneta de Reparto</option>
            <option value="Caja Seca" <?php if($info['tipo_vehiculo']=='Caja Seca') echo 'selected="selected"'; ?>>Caja Seca</option>
            <option value="Caja Refrigerada" <?php if($info['tipo_vehiculo']=='Caja Refrigerada') echo 'selected="selected"'; ?>>Caja Refrigerada</option>
            <option value="Moto" <?php if($info['tipo_vehiculo']=='Moto') echo 'selected="selected"'; ?>>Moto</option>
            <option value="Plataforma" <?php if($info['tipo_vehiculo']=='Plataforma') echo 'selected="selected"'; ?>>Plataforma</option>
            <option value="Plataforma Cortina" <?php if($info['tipo_vehiculo']=='Plataforma Cortina') echo 'selected="selected"'; ?>>Plataforma Cortina</option>
            <option value="Pickup" <?php if($info['tipo_vehiculo']=='Pickup') echo 'selected="selected"'; ?>>Pickup</option>
            <option value="Tracto 5 Rueda" <?php if($info['tipo_vehiculo']=='Tracto 5 Rueda') echo 'selected="selected"'; ?>>Tracto 5 Rueda</option>
            <option value="Torton" <?php if($info['tipo_vehiculo']=='Torton') echo 'selected="selected"'; ?>>Torton</option>
            <option value="Utilitarios" <?php if($info['tipo_vehiculo']=='Utilitarios') echo 'selected="selected"'; ?>>Utilitarios</option>
          </select>
          <br class="clear" />
        </p>


          <p>

          <label class="inline">Fecha de Instalacion<em>*</em></label>
          <input type="hidden" id="fecha_instalacion" name="fecha_instalacion" value="<?php echo $info['fecha_instalacion']; ?>" class="date_alt" />
          <input type="text" id="date" name="date" value="<?php echo $objClient->formatDate($info['fecha_instalacion'],"max"); ?>" class="inline isDate"/>
          <br class="clear" />
        </p>
         <p>
          <label class="inline">Ubicaci&oacute;n del Equipo<em>*</em></label>
          <input type="text" id="ubicacion_equipo" name="ubicacion_equipo" value="<?php echo $info['ubicacion_equipo']; ?>" class="inline"/>
          <br class="clear" />
        </p>

        <p>
        <table border="0" width="100%" cellpadding="1" cellspacing="0">
          <tr>
            <td align="center" valign="middle"><input type="checkbox" id="chk_sensor_elock" name="chk_sensor_elock" value="1" <?php if($sensor['elock']==1){ echo 'checked="checked"'; } ?> /></td>
            <td width="100" valign="middle"><strong>E-Lock</strong></td>
            <td valign="middle">&nbsp;</td>
            <td valign="middle">&nbsp;</td>
            <td valign="middle">&nbsp;</td>
            <td valign="middle">&nbsp;</td>
            <td valign="middle">&nbsp;</td>
            <td valign="middle">&nbsp;</td>
          </tr>
          <tr>
            <td align="center" valign="middle"><input type="checkbox" id="chk_sensor_temp" name="chk_sensor_temp" value="1" <?php if($sensor['temp']==1){ echo 'checked="checked"'; } ?> /></td>
            <td width="100" valign="middle"><strong>Temperatura</strong></td>
            <td valign="middle">&nbsp;</td>
            <td valign="middle">&nbsp;</td>
            <td valign="middle">&nbsp;</td>
            <td valign="middle">&nbsp;</td>
            <td valign="middle">&nbsp;</td>
            <td valign="middle">&nbsp;</td>
          </tr>
          <tr>
            <td align="center" valign="middle"><input type="checkbox" id="chk_sensor_temp2" name="chk_sensor_temp2" value="1" <?php if($sensor['temp2']==1){ echo 'checked="checked"'; } ?> /></td>
            <td width="100" valign="middle"><strong>Temperatura 2</strong></td>
            <td valign="middle">&nbsp;</td>
            <td valign="middle">&nbsp;</td>
            <td valign="middle">&nbsp;</td>
            <td valign="middle">&nbsp;</td>
            <td valign="middle">&nbsp;</td>
            <td valign="middle">&nbsp;</td>
          </tr>
          <tr>
            <td align="center" valign="middle"></td>
            <td valign="middle"></td>
            <td align="center" valign="middle"><span style=" font-size:8px;">Diametro</span></td>
            <td align="center" valign="middle"><span style=" font-size:8px;">Largo</span></td>
            <td align="center" valign="middle"><span style=" font-size:8px;">Altura Sen.</span></td>
            <td align="center" valign="middle"><span style=" font-size:8px;">Variación</span></td>
            <td align="center" valign="middle"><span style=" font-size:8px;">Volta. Lleno</span></td>
            <td align="center" valign="middle"><span style=" font-size:8px;">Vol. Apróx.</span></td>
          </tr>
          <tr>
            <td align="center" valign="middle"><input type="checkbox" id="chk_sensor_fuel_a" name="chk_sensor_fuel_a" value="1" <?php if($sensor['fuel_a']==1){ echo 'checked="checked"'; } ?> /></td>
            <td valign="middle"><strong>Combustible 1</strong></td>
            <td align="center" valign="middle"><input type="text" id="fuel_a_d" name="fuel_a_d" style="border: #ccc solid 1px; width: 40px;padding: 5px;" value="<?php echo $sensor['fuel_a_d']; ?>" class="fuel_a" /></td>
            <td align="center" valign="middle"><input type="text" id="fuel_a_l" name="fuel_a_l" style="border: #ccc solid 1px; width: 40px;padding: 5px;" value="<?php echo $sensor['fuel_a_l']; ?>" class="fuel_a" /></td>
            <td align="center" valign="middle"><input type="text" id="fuel_a_as" name="fuel_a_as" style="border: #ccc solid 1px; width: 40px;padding: 5px;" value="<?php echo $sensor['fuel_a_as']; ?>" class="fuel_a" /></td>
            <td align="center" valign="middle"><input type="text" id="fuel_a_v" name="fuel_a_v" style="border: #ccc solid 1px; width: 40px;padding: 5px;" value="<?php echo $sensor['fuel_a_v']; ?>" class="fuel_a" /></td>
            <td align="center" valign="middle"><input type="text" id="fuel_a_vl" name="fuel_a_vl" style="border: #ccc solid 1px; width: 40px;padding: 5px;" value="<?php echo $sensor['fuel_a_vl']; ?>" class="fuel_a" /></td>
            <td align="center" valign="middle"><input type="text" id="fuel_a_r" name="fuel_a_r" style="border: #ccc solid 1px; width: 40px;padding: 5px;" readonly="readonly" value="<?php echo $sensor['fuel_a_r']; ?>" class="fuel_a"/></td>
          </tr>
          <tr>
            <td align="center" valign="middle"><input type="checkbox" id="chk_sensor_fuel_b" name="chk_sensor_fuel_b" value="1" <?php if($sensor['fuel_b']==1){ echo 'checked="checked"'; } ?> /></td>
            <td valign="middle"><strong>Combustible 2</strong></td>
            <td align="center" valign="middle"><input type="text" id="fuel_b_d" name="fuel_b_d" style="border: #ccc solid 1px; width: 40px;padding: 5px;" value="<?php echo $sensor['fuel_b_d']; ?>" class="fuel_b"/></td>
            <td align="center" valign="middle"><input type="text" id="fuel_b_l" name="fuel_b_l" style="border: #ccc solid 1px; width: 40px;padding: 5px;" value="<?php echo $sensor['fuel_b_l']; ?>" class="fuel_b"/></td>
            <td align="center" valign="middle"><input type="text" id="fuel_b_as" name="fuel_b_as" style="border: #ccc solid 1px; width: 40px;padding: 5px;" value="<?php echo $sensor['fuel_b_as']; ?>" class="fuel_b"/></td>
            <td align="center" valign="middle"><input type="text" id="fuel_b_v" name="fuel_b_v" style="border: #ccc solid 1px; width: 40px;padding: 5px;" value="<?php echo $sensor['fuel_b_v']; ?>" class="fuel_b"/></td>
            <td align="center" valign="middle"><input type="text" id="fuel_b_vl" name="fuel_b_vl" style="border: #ccc solid 1px; width: 40px;padding: 5px;" value="<?php echo $sensor['fuel_b_vl']; ?>" class="fuel_b"/></td>
            <td align="center" valign="middle"><input type="text" id="fuel_b_r" name="fuel_b_r" style="border: #ccc solid 1px; width: 40px;padding: 5px;" readonly="readonly" value="<?php echo $sensor['fuel_b_r']; ?>" class="fuel_b"/></td>
          </tr>
          <tr>
            <td align="center" valign="middle"><input type="checkbox" id="chk_sensor_fuel_c" name="chk_sensor_fuel_c" value="1" <?php if($sensor['fuel_c']==1){ echo 'checked="checked"'; } ?> /></td>
            <td valign="middle"><strong>Combustible 3</strong></td>
            <td align="center" valign="middle"><input type="text" id="fuel_c_d" name="fuel_c_d" style="border: #ccc solid 1px; width: 40px;padding: 5px;" value="<?php echo $sensor['fuel_c_d']; ?>" class="fuel_c"/></td>
            <td align="center" valign="middle"><input type="text" id="fuel_c_l" name="fuel_c_l" style="border: #ccc solid 1px; width: 40px;padding: 5px;" value="<?php echo $sensor['fuel_c_l']; ?>" class="fuel_c"/></td>
            <td align="center" valign="middle"><input type="text" id="fuel_c_as" name="fuel_c_as" style="border: #ccc solid 1px; width: 40px;padding: 5px;" value="<?php echo $sensor['fuel_c_as']; ?>" class="fuel_c"/></td>
            <td align="center" valign="middle"><input type="text" id="fuel_c_v" name="fuel_c_v" style="border: #ccc solid 1px; width: 40px;padding: 5px;" value="<?php echo $sensor['fuel_c_v']; ?>" class="fuel_c"/></td>
            <td align="center" valign="middle"><input type="text" id="fuel_c_vl" name="fuel_c_vl" style="border: #ccc solid 1px; width: 40px;padding: 5px;" value="<?php echo $sensor['fuel_c_vl']; ?>" class="fuel_c"/></td>
            <td align="center" valign="middle"><input type="text" id="fuel_c_r" name="fuel_c_r" style="border: #ccc solid 1px; width: 40px;padding: 5px;" readonly="readonly" value="<?php echo $sensor['fuel_c_r']; ?>" class="fuel_c"/></td>
          </tr>

        </table>
        </p>
        <p>
          <label class="inline">F&oacute;rmula Combustible</label>
          <select id="lst_formula" name="lst_formula">
            <option value="1" <?php if($sensor['formula']=="1") echo 'selected="selected"'; ?>>Formula Tradicional</option>
            <option value="2" <?php if($sensor['formula']=="2") echo 'selected="selected"'; ?>>Formula por Calibraci&oacute;n</option>
          </select>

          <br class="clear" />
        </p>
        <br />
        <p class="save"><a href="#" class="btn_save onClickSave">Guardar</a></p>
        <input id="id_client" name="id_client" type="hidden"  value="<?php echo $id_client; ?>"/>
        <input id="id_asset" name="id_asset" type="hidden"  value="<?php echo $id_asset; ?>"/>

        <input id="ctrl" name="ctrl" type="hidden" value="client" />
        <input id="exec" name="exec" type="hidden" value="<?php echo $value; ?>" />
        <input type="hidden" id="back" name="back" value="index.php?call=cliente&id=<?php echo $id_client; ?>" />
      </fieldset>
    </form>


</div>

<script>

function calc_fuel_a(){
  pi = 3.1416;
  v = $("#fuel_a_v").val();
  d = parseFloat($("#fuel_a_d").val()/2)-(v/2);
  r = d*d;
  h = $("#fuel_a_l").val()-v;
  res = (pi*r*h)/1000;
  $("#fuel_a_r").val(floorFigure(res,2)+" L");
}

function calc_fuel_b(){
   pi = 3.1416;
  v = $("#fuel_b_v").val();
  d = parseFloat($("#fuel_b_d").val()/2)-(v/2);
  r = d*d;
  h = $("#fuel_b_l").val()-v;
  res = (pi*r*h)/1000;
  $("#fuel_b_r").val(floorFigure(res,2)+" L");
}

function calc_fuel_c(){
   pi = 3.1416;
  v = $("#fuel_c_v").val();
  d = parseFloat($("#fuel_c_d").val()/2)-(v/2);
  r = d*d;
  h = $("#fuel_c_l").val()-v;
  res = (pi*r*h)/1000;
  $("#fuel_c_r").val(floorFigure(res,2)+" L");
}

function floorFigure(figure, decimals){
    if (!decimals) decimals = 2;
    var d = Math.pow(10,decimals);
    return (parseInt(figure*d)/d).toFixed(decimals);
}

$(document).ready(function(){
  calc_fuel_a();
  calc_fuel_b();
  calc_fuel_c();
  $('.fuel_a').keyup(function(){ calc_fuel_a(); });
  $('.fuel_b').keyup(function(){ calc_fuel_b(); });
  $('.fuel_c').keyup(function(){ calc_fuel_c(); });

  $('.onFuelChange').change(function(){
    $(".fuel_lt").fadeToggle();
  });

  $('.onFuelChange').change(function(){
    $(".fuel_lt").fadeToggle();
  });

  $('.isDate').datepicker({
    altFormat: 'yy-mm-dd',
    altField: ".date_alt",
    dateFormat: 'd\' de \'MM\' del \'yy',
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
