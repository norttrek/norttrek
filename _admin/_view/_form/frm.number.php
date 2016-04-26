<?php
error_reporting(1);
require_once("../../../_class/class.number.php");

$obj = new Number();

$data = NULL;
$value = (isset($_GET['id'])) ? "update" : "save";
$label = (isset($_GET['id'])) ? "Modificar" : "Guardar";

if(isset($_GET['id'])){ 
  $data = $obj->getNumber($_GET['id']); 
}




?>

<div id="modal">
  <h1>Registro de N&uacute;mero</h1>
  <form id="frm_imei" name="frm_imei">
    <fieldset>
      <p>
        <label class="inline">No. Movil</label>
        <input type="text" id="txt_no" name="txt_no" value="<?php echo $data[0]['no'] ?>" class="inline"/>
        <br class="clear" />
      </p>
      <p>
        <label class="inline">No. Serie</label>
        <input type="text" id="txt_serial_no" name="txt_serial_no" value="<?php echo $data[0]['serial_no'] ?>" class="inline"/>
        <br class="clear" />
      </p>
      <p>
        <label class="inline">No. Cuenta</label>
        <input type="text" id="txt_account" name="txt_account" value="<?php echo $data[0]['account'] ?>" class="inline"/>
        <br class="clear" />
      </p>
      
      <p>
        <label class="inline">Fecha de Contrataci&oacute;n</label>
        <input type="text" id="txt_date" name="txt_date" value="<?php if(isset($_GET['id'])) echo $obj->formatDate($data[0]['date_reg'],"max"); ?>" class="inline isDate"/>
        <input type="hidden" id="date" name="date" value="<?php echo $data[0]['date_reg'] ?>" class="date_alt" />
        <br class="clear" />
      </p>

      <p class="save"><a href="#" class="btn_save onClickSave">Guardar</a></p>
        <input id="id" name="id" type="hidden"  value="<?php echo $_GET['id']; ?>"/>
        <input id="ctrl" name="ctrl" type="hidden" value="number" />
        <input id="exec" name="exec" type="hidden" value="<?php echo $value; ?>" /> 
        <input type="hidden" id="back" name="back" value="index.php?call=number" />
      </fieldset>
    </form>
  
</div>
<script>
$(document).ready(function(){ 
  $('.isDate').datepicker({
	showOptions: "slideUp",
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