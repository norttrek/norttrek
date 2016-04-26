<div id="combustible" class="navmenu navmenu-default navmenu-fixed-left offcanvas" role="navigation">
  <div class="col-lg-10"><h4>Reporte de Combustible</h4></div>
  <div class="col-lg-2">
    <a href="javascript:void(0)"  side="combustible" class="Closeside btn_close  "><i class="fa fa-times fa-lg"></i></a></a>
  </div>
<?php
require_once("../_class/class.asset.php");
$objAsset = new Asset();
$assets = $objAsset->set_id_client($_SESSION['logged']['id_client'])->set_id_device('0')->getAsset(); 
$assets_user = $objAsset->getUserAssets($_SESSION['logged']['id_user']); 

$buffer_assets = '';
for($i=0;$i<count($assets);$i++){
  $fuelrpt = json_decode($assets[$i]['sensor'],true);
  $formula = NULL;
  if(!isset($fuelrpt['formula'])){ $formula = 1; }else{ $formula = $fuelrpt['formula']; }
  if($_SESSION['logged']['type']==2){
    if(in_array($assets[$i]['id'],$assets_user)){
      $data = json_decode($assets[$i]['data'],true);    
      $buffer_assets .= '<option value="'.$assets[$i]['imei'].'|'.$formula.'">'.$assets[$i]['alias'].'</option>';
    }
  }else{
    $data = json_decode($assets[$i]['data'],true);
    $buffer_assets .= '<option value="'.$assets[$i]['imei'].'|'.$formula.'">'.$assets[$i]['alias'].'</option>';
  }
}

?>
<div id="fuelrpt" class="col-lg-12">
 
   
      <div class="filter">
        <form id="frm_filter_route" name="frm_filter_route">
          <fieldset>
            <select class="form-control" id="lst_fuelrpt_imei" name="lst_fuelrpt_imei" style="width:180px;">
              <option value="NULL">Seleccione una Unidad</option>
              <?php echo $buffer_assets; ?>
            </select>
            <style>
      #lst_date li { float:left; margin-right:10px; }
      </style>
            
            <ul id="lst_date">
              <liclass="inputsReports">
                Fecha Inicial
             <input type="text" id="txt_fdate_from" name="txt_fdate_from" class="form-control isFDateFrom" value="<?php echo $objAsset->formatDate(date("Y-m-d"),"min"); ?>">
             <input id="fdate_from" class="form-control" name="fdate_from" type="hidden" value="<?php echo date("Y-m-d"); ?>">
             <select id="fhour_from" class="form-control" name="fhour_from">
            <option value="00:00:00" selected="selected">00:00</option>
            <option value="01:00:00">01:00</option>
            <option value="02:00:00">02:00</option>
            <option value="03:00:00">03:00</option>
            <option value="04:00:00">04:00</option>
            <option value="05:00:00">05:00</option>
            <option value="06:00:00">06:00</option>
            <option value="07:00:00">07:00</option>
            <option value="08:00:00">08:00</option>
            <option value="09:00:00">09:00</option>
            <option value="10:00:00">10:00</option>
            <option value="11:00:00">11:00</option>
            <option value="12:00:00">12:00</option>
            <option value="13:00:00">13:00</option>
            <option value="14:00:00">14:00</option>
            <option value="15:00:00">15:00</option>
            <option value="16:00:00">16:00</option>
            <option value="17:00:00">17:00</option>
            <option value="18:00:00">18:00</option>
            <option value="19:00:00">19:00</option>
            <option value="20:00:00">20:00</option>
            <option value="21:00:00">21:00</option>
            <option value="22:00:00">22:00</option>
            <option value="23:00:00">23:00</option>
            <option value="23:59:59">23:59:59</option>
          </select>
        </li>
             <li class="inputsReports">
                Fecha Final
          <input type="text" id="txt_fdate_to" name="txt_fdate_to" class="form-control isFDateTo" value="<?php echo $objAsset->formatDate(date("Y-m-d"),"min"); ?>">
          <input id="fdate_to" name="fdate_to"  class="form-control" type="hidden" value="<?php echo date("Y-m-d"); ?>">
          <select id="fhour_to" class="form-control" name="fhour_to">
            <option value="00:00:00">00:00</option>
            <option value="01:00:00">01:00</option>
            <option value="02:00:00">02:00</option>
            <option value="03:00:00">03:00</option>
            <option value="04:00:00">04:00</option>
            <option value="05:00:00">05:00</option>
            <option value="06:00:00">06:00</option>
            <option value="07:00:00">07:00</option>
            <option value="08:00:00">08:00</option>
            <option value="09:00:00">09:00</option>
            <option value="10:00:00">10:00</option>
            <option value="11:00:00">11:00</option>
            <option value="12:00:00">12:00</option>
            <option value="13:00:00">13:00</option>
            <option value="14:00:00">14:00</option>
            <option value="15:00:00">15:00</option>
            <option value="16:00:00">16:00</option>
            <option value="17:00:00">17:00</option>
            <option value="18:00:00">18:00</option>
            <option value="19:00:00">19:00</option>
            <option value="20:00:00">20:00</option>
            <option value="21:00:00">21:00</option>
            <option value="22:00:00">22:00</option>
            <option value="23:00:00">23:00</option>
            <option value="23:59:59" selected="selected">23:59:59</option>
          </select>
        </li>
            </ul>
            <br class="clear" />
            
          </fieldset>
          <a href="javascript:void(0)" class="ClickCombustibleReport"  style="color:#fff; background-color: #00ccff;
color: #fff;
padding: 5px;
width: 100px;
text-align: center;
border-radius: 4px; margin-bottom:5px;">Generar Reporte de Combustible</a>
        </form>
      </div>
   
  </div>
  <script>
  $(document).ready(function(){
   $('.isFDateTo').datepicker({ 
    altFormat: 'yy-mm-dd', 
    altField: "#fdate_to",
    dateFormat: 'd \'de\' MM \'del\' yy',
    changeMonth: true,
    changeYear: true,
    yearRange: '1981:2020',
    monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'],
    monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
    dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
    dayNames: ['Domingo', 'Lunes', 'Martes', 'MiÃ©rcoles', 'Jueves', 'Viernes', 'SÃ¡bado']
    });

 $('.isFDateFrom').datepicker({
    altFormat: 'yy-mm-dd', 
    altField: "#fdate_from",
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
  $("#combustible").offcanvas({ autohide: false, toggle: false  });
  </script>
</div>
 