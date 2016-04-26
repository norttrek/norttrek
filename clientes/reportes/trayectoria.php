<div id="trayectoria" class="navmenu navmenu-default navmenu-fixed-left offcanvas" role="navigation">
  <div class="col-lg-10"><h4>Reporte de Ruta (Trayectoria)</h4></div>
  <div class="col-lg-2">
    <a href="javascript:void(0)"  side="trayectoria" class="Closeside btn_close  "><i class="fa fa-times fa-lg"></i></a></a>
  </div>
<?php
require_once("../_class/class.asset.php");
$objAsset = new Asset();
$assets = $objAsset->set_id_client($_SESSION['logged']['id_client'])->set_id_device('0')->getAsset();

$assets_user = $objAsset->getUserAssets($_SESSION['logged']['id_user']); 

$buffer_assets = '';
for($i=0;$i<count($assets);$i++){
  if($_SESSION['logged']['type']==2){
    if(in_array($assets[$i]['id'],$assets_user)){
      $data = json_decode($assets[$i]['data'],true);
      $buffer_assets .= '<option value="'.$assets[$i]['imei'].'">'.$assets[$i]['alias'].'</option>';
    }
  }else{
    $data = json_decode($assets[$i]['data'],true);
    $buffer_assets .= '<option value="'.$assets[$i]['imei'].'">'.$assets[$i]['alias'].'</option>';
  }
}

$tunit = 'C&deg';
if($_SESSION['logged']['temp']=="f"){ $tunit = 'F&deg'; }
?>
<div id="route" class="col-lg-12">
   
      <div class="filter">
        <form id="frm_filter_route" name="frm_filter_route">
          <fieldset>
            <select class="form-control" id="lst_route_imei" name="lst_route_imei" style="width:180px;">
              <option value="NULL">Seleccione una Unidad</option>
              <?php echo $buffer_assets; ?>
            </select>
            <style>
      #lst_date li { float:left; margin-right:10px; margin-bottom:15px; }
      </style>
            
            <ul id="lst_date" style="padding:0">
              <li class="inputsReports">
                Fecha Inicial
             <input type="text" id="txt_date_from" name="txt_date_from" class="form-control isDate isDateFrom" value="<?php echo $objAsset->formatDate(date("Y-m-d"),"min"); ?>">
             <input id="date_from" class="form-control" name="date_from" type="hidden" value="<?php echo date("Y-m-d"); ?>">
             <select class="form-control" id="hour_from" name="hour_from">
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
          <input type="text" id="txt_date_to" name="txt_date_to" class="form-control isDate isDateTo" value="<?php echo $objAsset->formatDate(date("Y-m-d"),"min"); ?>">
          <input id="date_to" name="date_to" type="hidden" value="<?php echo date("Y-m-d"); ?>">
          <select id="hour_to" name="hour_to" class="form-control">
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
            <br class="clear" clear="all" />
            <a href="javascript:void(0)" class="onClickGetRoute" style="color:#fff; background-color: #00ccff;
color: #fff;
padding: 5px;
width: 100px;
text-align: center;
border-radius: 4px; margin-bottom:5px;">Aceptar</a>
  
            <br />
            <style>
      #ractions { position:absolute; right:10px; margin-top:-20px; } 
      #ractions li { display:inline-block; }
      #ractions li a { width:25px; height:20px; border:#fff solid 1px;  text-align:center; display:inline-block; padding-top:2px; }
      #ractions li a:hover { background-color:#00a1cf; border:#00a1cf solid 1px; }
      </style>
      <!--
            <ul id="ractions">
              <li><a href="javascript:void(0)" class="onClickAnimateRoute" style="color:#fff;" rel="back"><i class="fa fa-backward"></i></a></li>
              <li><a href="javascript:void(0)" class="onClickAnimateRoute" style="color:#fff;" rel="pause"><i class="fa fa-pause"></i></a></li>
              <li><a href="javascript:void(0)" class="onClickAnimateRoute" style="color:#fff;" rel="play"><i class="fa fa-play"></i></a></li>
              <li><a href="javascript:void(0)" class="onClickAnimateRoute" style="color:#fff;" rel="next"><i class="fa fa-forward"></i></a></li>
            </ul> -->
            
            
          </fieldset>
        </form>
      </div>
      <div class="datagrid" style="margin-top:5px;">
       
      </div>
      <a href="javascript:void(0)" target="_blank" class="onClickRouteExport excel" style="display:inline-block; padding-right:6px;">Exportar a Excel</a><br><span style="font-size:9px; display:inline; color:#00ccff;">(El reporte puede tardarse algunos segundos/minutos)</span>
  
  </div>
  <script>
  
  $(document).ready(function(){
   $('.isDateTo').datepicker({
    altFormat: 'yy-mm-dd', 
    altField: "#date_to",
    dateFormat: 'd \'de\' MM \'del\' yy',
    changeMonth: true,
    changeYear: true,
    yearRange: '1981:2020',
    monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'],
    monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
    dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
    dayNames: ['Domingo', 'Lunes', 'Martes', 'MiÃ©rcoles', 'Jueves', 'Viernes', 'SÃ¡bado']
    });

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
  $("#trayectoria").offcanvas({ autohide: false, toggle: false  });
  </script>
</div>
 