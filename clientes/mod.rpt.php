<?php 
session_start(); 
require_once("../_class/class.gprs.php");
require_once("../_class/class.client.php");
require_once("../_class/class.asset.php");
$objAsset = new Asset();
$objClient = new Client();
$objGPRS = new GPRS();
$geofences = $objClient->set_id_client($_SESSION['logged']['id_client'])->set_order('category ASC')->getClientGeofence();
 
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
 
?> 
<!DOCTYPE html>
<html>
<head>
<title>Norttrek - GPS</title>
<meta http-equiv="content-type" content="application/xhtml+xml; charset=UTF-8" />
<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,600&subset=latin' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="_lib/fawesome/css/font-awesome.min.css">
<link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>

<style>
body,html {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	width:100%;
	height:100%;
	font-family: 'Open Sans', sans-serif;
	font-size:12px;
	color:#000;
}
* { outline:none; }
ul { margin:0; padding:0; list-style:none;}
a { text-decoration:none; }

#layout{  height:100%; }


#panel_info { width: 900px; height:600px; border:#e4e4e4 solid 1px;}

#panel_info .tabs { background-color:#1b1e25; margin-bottom:20px; }
#panel_info .tabs ul li { float:left;}
#panel_info .tabs ul li a { color:#fff; min-width:100px; text-align:center; display:inline-block; padding:10px; border-right:#4a494a dotted 1px; }
#panel_info .tabs ul li a.active { color:#00ccff;}

#panel_info .tabs_content { width:870px; height:470px; margin:auto; border:#e4e4e4 solid 1px; overflow-y:auto; }
#panel_info .static { margin-left:10px; }
#panel_info .static .unit_icon { width:40px; height:40px; border:#ccc solid 1px; display:block; margin-right:10px; }
#panel_info .static .icons_container { position:absolute; width:207px; height:180px; background-color:#e4e4e4; border:#ccc solid 1px; overflow-y:scroll; margin-top:1px; display:none; }
#panel_info .static .icons_container ul { margin-top:5px; margin-left:3px; }
#panel_info .static .icons_container ul li { float:left; margin-right:5px; margin-bottom:5px; }
#panel_info .static .icons_container ul li a { width:40px; height:40px; display:block; background-color:#000; border:#e4e4e4 solid 1px; }
#panel_info .static h1 { font-size: 18px; margin-bottom:0; margin-top:0; color:#333;  }
#panel_info .static h2 { font-size:12px; font-weight:100; color:#4587ba; margin-bottom:0; margin-top:0; }
#panel_info .static hr.line { background-color:#e4e4e4; border:none; height:1px; margin-top:15px; margin-bottom:15px; }

.clear { clear:both;}
.gray { color:#999; }

#map-canvas { width:100%; height:100%; margin:auto;}
.onClickOpenOverlay { color:#fff; }


.datagrid table { height:100px; }
.datagrid table thead tr th { padding:6px 15px; text-align:left; background-color:#f5f5f5; border-bottom:#ebebeb solid 1px; border-right:#f1f1f1 solid 1px; padding-right:0; }
.datagrid table thead tr th a { color:#717171; display:block;  }
.datagrid table thead tr th a.order {  background: url(_admin/_img/bck_sort.png) no-repeat right 4px; }
.datagrid table thead tr th a.order.asc { background: url(_admin/_img/bck_sort.png) no-repeat right 4px; }
.datagrid table thead tr th a.order.desc { background: url(_admin/_img/bck_sort.png) no-repeat right -22px; }

.datagrid table tbody tr:nth-child(even) { background-color:#f9f9f9; }
.datagrid table tbody tr:nth-child(odd) { background-color:#fff; }
.datagrid table tbody tr td { padding:3px 15px; border-top:#f1f1f1 solid 1px;  }
.datagrid table tbody tr:hover { background-color:#9CF !important;}
</style>
</head>

<body>


<div id="reports"></div>

<div id="panel_info">
  <div class="tabs">
    <ul>
      <li><a href="javascript:void(0)" class="onClickTab active" rel="tab_info">Reportes</a></li>
     
    </ul>
    <br class="clear" />
  </div>
  
  <div class="static">

    <table border="0" width="100%" cellpadding="0" cellspacing="0">
      <tr>
      <td valign="top" style="position:relative;">
        <h1>Reporte de Geo-Cercas</h1>
      </td>
      </tr>
    </table>
    <hr class="line" />
  </div>
  <style>
  .tab_info ul li { margin-left:15px; margin-bottom:4px; font-size:13px;}
  .tab_info ul li .label { display:inline-block; width:130px;  }
  .tab_info ul li .value { display:inline-block; width:300px; }
  .isTabContent { display:none; background-color:#fff;  }
  .active { display:block !important;} 
  .red { color:#F33; }
  .green { color:#3C0; }
  .info_container { background-color:#1a1d25; color:#fff; padding-bottom:15px;}
  .info_container h1 { color:#fff; font-size:14px; padding:0; background:url(_img/bck_panel_h1.png); height:26px; padding-top:3px; text-indent:8px; text-shadow: 1px 1px 0px #1b1e25; margin-top:0; }
  .info_container ul { margin-left:5px;}
  a.on { background-color:#00a627; width:100px; text-align:center; color:#fff; padding:2px; font-weight:bold; border-radius:2px; display:block; }
  a.off { background-color:#f0003e; width:100px; text-align:center;  color:#fff;  padding:2px; font-weight:bold; border-radius:2px; display:block;}
  .fuel { position:relative; height:10px; width:97px; border:#343b48 solid 1px; margin-top:5px; margin-left:0px; display:inline-block; margin-right:5px; }
  .fuel ul { margin-left:0; }
.fuel ul li { width:17px; height:10px; display:block; float:left; background-color:#F90; margin:0; margin-right:3px;  }
.fuel ul li:nth-child(1) { !important; background-color:#f3002e; }
.fuel ul li:nth-child(2) { !important; background-color:#ffde00; }
.fuel ul li:nth-child(3) { !important; background-color:#ffde00; }
.fuel ul li:nth-child(4) { !important; background-color:#00f300; }
.fuel ul li:nth-child(5) { margin-right:0 !important; background-color:#00f300; }
.checks ul { margin:0; padding:0; margin-top:10px; margin-bottom:10px; }
.checks ul li { margin-left:0; float:left; display:inline-block; min-width:120px; }

.filters ul { margin:0; padding:0; margin-top:10px; margin-bottom:10px; }
.filters ul li { margin-left:0; float:left; display:inline-block; min-width:120px; margin-right:10px; }
  </style>
  
  
  <div class="tabs_content">
    
    <div class="tab_info isTabContent" style="display:block; margin:25px;">
        
        <form id="frm_geofence_rpt" name="frm_geofence_rpt">
        <div class="filters">
  <ul>
    <li><select id="lst_imei" name="lst_imei">
          <option>Seleccione Unidad</option>
          <?php if($_SESSION['logged']['type']==1){ ?>
          <option value="*">Todos</option>
		  <?php } ?>
          <?php echo $buffer_assets; ?>
        </select></li>
              <li>
         	   <input type="text" id="txt_date_from" name="txt_date_from" class="isDate isDateFrom" value="<?php echo $objAsset->formatDate(date("Y-m-d"),"min"); ?>">
         	   <input id="date_from" name="date_from" type="hidden" value="<?php echo date("Y-m-d"); ?>">
         	   <select id="hour_from" name="hour_from">
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
             <li>
          <input type="text" id="txt_date_to" name="txt_date_to" class="isDate isDateTo" value="<?php echo $objAsset->formatDate(date("Y-m-d"),"min"); ?>">
          <input id="date_to" name="date_to" type="hidden" value="<?php echo date("Y-m-d"); ?>">
          <select id="hour_to" name="hour_to">
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
        <li><a href="#" class="onClickGetRpt">Generar Reporte</a></li>
            </ul>
            <br class="clear">
  </div>
        
        
        <div class="checks">
          <ul>
            <li><input type="checkbox" id="chk_filter[]" name="chk_filter[]" class="onChkChange" value="zs" checked><label>Zona Segura</label></li>
            <li><input type="checkbox" id="chk_filter[]" name="chk_filter[]" class="onChkChange" value="zr" checked><label>Zona de Riesgo</label></li>
            <li><input type="checkbox" id="chk_filter[]" name="chk_filter[]" class="onChkChange" value="base" checked><label>Base</label></li>
            <li><input type="checkbox" id="chk_filter[]" name="chk_filter[]" class="onChkChange" value="cli" checked><label>Cliente</label></li>
          </ul>
          <br class="clear" />
        </div>
        
        <script>
		$('.onChkChange').change(function(){ 
		  if($(this).is(':checked')){ $('.'+$(this).val()).show(); }else{ $('.'+$(this).val()).hide(); }
		});
		</script>
        
        <div class="geocercas" style="border:#e4e4e4 solid 1px; height:130px; position:relative; overflow-y:scroll; width:55%">
        <div class="datagrid">
          <table border="0" width="100%" cellpadding="0" cellspacing="0">
            <thead> 
              <tr>
                <th width="20">*</th>
                <th>Geo-Cerca</th>
                <th>Tipo</th>
              </tr>
            </thead>
            <tbody>
            <?php
			for($i=0;$i<count($geofences);$i++){
			  echo '<tr class="'.$geofences[$i]['category'].'">';
			  echo '<td><input type="checkbox" id="chk_geofence[]" name="chk_geofence[]" value="'.$geofences[$i]['id'].'" /></td>';
			  echo '<td>'.$geofences[$i]['name'].'</td>';
			  switch($geofences[$i]['category']){
				case "zs": echo '<td>Zona Segura</td>'; break;
				case "zr": echo '<td>Zona de Riesgo</td>'; break;
				case "base": echo '<td>Base</td>'; break;
				case "cli": echo '<td>Clientes</td>'; break;
			  }
			  
			  echo '</tr>';
			}
			
			?>
            </tbody>
          </table>
          </div>
          
          
        </div>
        </form>
        
        <br />
        <div class="reporte" style="border:#e4e4e4 solid 1px; height:210px; position:relative; overflow-y:scroll;">
          <div class="datagrid">
          <table id="tbl_report" border="0" width="100%" cellpadding="0" cellspacing="0" >
          <thead>
              <tr> 
                <th>Unidad</th>
                <th>Tipo</th>
                <th>Velocidad</th>
                <th>Comb. 1</th>
                <th>Comb. 2</th>
                <th>Comb. 3</th>
                <th>Temp.</th>
                <th>Geo-Cerca</th>
                <th>Fecha</th>
              </tr>
            </thead>
            <tbody>
            <?php
		  //for($i=0;$i<count($geofence_rpt);$i++){
		  //  echo '<tr><td>'.$geofence_rpt[$i]['type'].'</td><td>'.$geofence_rpt[$i]['geofence'].'</td><td>'.$geofence_rpt[$i]['date'].'</td></tr>';	  
		 // }
		   
        ?>
            </tbody>
            </table>
        </div>
        </div>
        
    </div>
    
    
  

  
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
	    

  
  
  $('.onClickGetRpt').click(function(){ get_rpt(); });
  
  
    });
	
	
	function get_rpt(){ 
	
	 $.ajax({
      type: "POST",
	  url: '_json/json_geofence_rpt.php',
	  data: $("#frm_geofence_rpt").serialize(),
	  success: function(r) { 
		$("#tbl_report > tbody").empty().append(r);
	  }
    });
	
	
	 }
</script>
<script src="_lib/highcharts/highcharts.js"></script>
</body>

</html>

