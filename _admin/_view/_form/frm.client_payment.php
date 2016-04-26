<?php
require_once("../../../_class/class.client.php");
require_once("../../../_class/class.device.php");
$objDevice = new Device();
$objClient = new Client();

$id_client = $_GET['id'];

$data = NULL;
$value = (isset($_GET['id'])) ? "update_client_payment" : "update_client_payment";
$label = (isset($_GET['id'])) ? "Modificar" : "Guardar";

$contacto = NULL;
if(isset($_GET['id'])){ 
  $data = $objClient->getClient($_GET['id']); 
  $payments = json_decode($data[0]['payment'],true);
}

function NlToBr($inString){
  return str_replace("<br />", "",$inString);
}




?>

<div id="modal">
  <h1>Informaci&oacute;n de Pagos</h1>
  <form id="frm_client" name="frm_client">
    <fieldset>
    <p>
      <label class="inline">Inicio de Contrato </label>
      <input type="hidden" id="contrato_inicio" name="contrato_inicio" value="<?php echo $payments['contrato_inicio']; ?>" class="date_alt" />
      <input type="text" id="date" name="date" value="<?php echo $objClient->formatDate($payments['contrato_inicio'],"max"); ?>" class="inline isDate"/>
      <br class="clear" />
    </p>
       
    <p>
      <label class="inline">Contacto Pagos </label>
      <input type="text" id="contacto_pagos" name="contacto_pagos" value="<?php echo html_entity_decode($payments['contacto_pagos']); ?>" class="inline"/>
      <br class="clear" />
    </p>
            
    <p>
      <label class="inline">E-mail (Facturas)</label>
      <input type="text" id="email_facturas" name="email_facturas" value="<?php echo $payments['email_facturas'] ?>" class="inline"/>
      <br class="clear" />
    </p>
 
    <p>
      <label class="inline">Dia de Corte  <em>*</em></label>
      <select id="dia_corte" name="dia_corte" class="left contacto inline">
      <?php for($i=0;$i<31;$i++){ if($payments['dia_corte']==($i+1)){ echo ' <option value="'.($i+1).'" selected="selected">'.($i+1).'</option>'; }else{ echo ' <option value="'.($i+1).'">'.($i+1).'</option>'; } } ?>
           
      </select>
      <br class="clear" />
    </p>
    <p>
      <label class="inline">Metodo de Pago  <em>*</em></label>
      <select id="metodo_pago" name="metodo_pago">
        <option value="Cheque" <?php if($payments['metodo_pago']=="Cheque") echo 'selected="selected"'; ?>>Cheque</option>
        <option value="Transferencia" <?php if($payments['metodo_pago']=="Transferencia") echo 'selected="selected"'; ?>>Transferencia</option>
        <option value="Efectivo" <?php if($payments['metodo_pago']=="Efectivo") echo 'selected="selected"'; ?>>Efectivo</option>
        <option value="Deposito" <?php if($payments['metodo_pago']=="Deposito") echo 'selected="selected"'; ?>>Deposito</option>
      </select>
      <br class="clear" />
    </p>
    <p>
      <label class="inline">Renta Unitaria sin I.V.A.<em>*</em></label>
      <input type="text" id="renta_unitaria" name="renta_unitaria" value="<?php echo $payments['renta_unitaria'] ?>" class="inline medium"/>
      <br class="clear" />
    </p>
        
    <p>
      <label class="inline">Plazo Minimo (Meses)</label>
      <select id="plazo_minimo" name="plazo_minimo" class="left inline">
        <?php for($i=0;$i<36;$i++){ if($payments['plazo_minimo']==($i+1)){ echo ' <option value="'.($i+1).'" selected="selected">'.($i+1).'</option>'; }else{ echo ' <option value="'.($i+1).'">'.($i+1).'</option>'; } } ?>
      </select>
      <br class="clear" />
    </p>
    <p>
      <label class="inline">Tipo de Plan <em>*</em></label>
      <select id="tipo_plan" name="tipo_plan">
        <option value="NULL">(Seleccionar)</option>
        <option value="Mensual" <?php if($payments['tipo_plan']=="Mensual") echo "selected"; ?>>Mensual</option>
        <option value="Trimestral" <?php if($payments['tipo_plan']=="Trimestral") echo "selected"; ?>>Trimestral</option>
        <option value="Semestral" <?php if($payments['tipo_plan']=="Semestral") echo "selected"; ?>>Semestral</option>
        <option value="Anual" <?php if($payments['tipo_plan']=="Anual") echo "selected"; ?>>Anual</option>
      </select>
      <br class="clear" />
    </p>
        
    <p>
      <label class="inline">Tipo de Operaci&oacute;n <em>*</em></label>
      <select id="tipo_operacion" name="tipo_operacion" class="lstTipoOp">
        <option value="NULL">(Seleccionar)</option>
        <option value="Venta" <?php if($payments['tipo_operacion']=="Venta") echo "selected"; ?>>Venta</option>
        <option value="Comodato" <?php if($payments['tipo_operacion']=="Comodato") echo "selected"; ?>>Comodato</option>
      </select>
      <br class="clear" />
    </p>
        
    <p>
      <label class="inline">Observaciones</label>
      <textarea id="observaciones" name="observaciones" placeholder="" rows="3"><?php echo html_entity_decode($payments['observaciones']); ?></textarea>
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
  
  $(".lstTipoOp").change(function(){ 
    if($(this).val()=="Venta"){ $('.precio_eq').show(); }else{ $('.precio_eq').hide();  }
	
  });
});
</script>