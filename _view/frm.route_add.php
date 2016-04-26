<?php
require_once("../../_class/class.asset.php");
$objAsset = new Asset();
$assets = $objAsset->set_id_client($_SESSION['logged']['id_client'])->getAsset(); 
$buffer_assets = '';
for($i=0;$i<count($assets);$i++){
  $data = json_decode($assets[$i]['data'],true);
  $buffer_assets .= '<option value="'.$assets[$i]['imei'].'">'.$data[1]['value'].'</option>';
}

?>
<style>
.datagrid { height:220px; overflow-y:scroll; }
.datagrid table thead tr th { color:#333; }
</style>
<div id="modal" class="small darkblue">
  <h1>Registro de Geo-Ruta</h1>
  <form id="frm_georoute_add" name="frm_georoute_add">
    <fieldset>
      <p>Seleccione la fecha de inicio ( se tomar&aacute; en cuenta para comenzar la ruta) X</p>
      <p>
        <label>Fecha de Inicio</label>
        <input type="text" id="txt_date" name="txt_date" value="" class="isDateFrom">
        <input type="hidden" id="date_from" name="date_from"  />
      </p>
      <p>
        <label>Unidad</label>
        <select id="lst_route_imei" name="lst_route_imei">
          <option value="NULL">Seleccione una Unidad</option>
          <?php echo $buffer_assets; ?>
        </select>
      </p>
      <p>
        <label>Descripcion</label>
        <textarea id="txt_descripcion" name="txt_descripcion" style="width:220px;" rows="3" ></textarea>
      </p>
  	  <br />
      <a href="javascript:void(0)" class="onClickUserAdd btn_save" rel="<?php echo $value; ?>|<?php echo $_GET['id']; ?>">Guardar</a>
    </fieldset>  
         	   
  </form>  
</div>  

<script>
  $(document).ready(function(){

 $('.isDateFrom').datepicker({
    altFormat: 'yy-mm-dd', 
    altField: "#date_from",
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


