<?php 
 session_start(); 
 require_once("../_class/class.client.php");
 require_once("../_class/class.asset.php");
 $objClient = new Client();
 $objAsset = new Asset();
 $asset = $objAsset->getAsset($_GET['id']);
 $asset_info = json_decode($asset[0]['data'],true);
 $capacidad = 450;
 $client = $objClient->getClient($_SESSION['logged']['id_client']);
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
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
<script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
<script>
$(document).ready(function(){ 
   $('.onClickTab').on("click",function(){ 
    $('.onClickTab').removeClass('active');
	$(this).addClass('active');
	$('.isTabContent').hide();
	$('.'+$(this).attr("rel")).fadeIn(function(){
	  if($(this).attr("rel")=="tab_fuel"){ $(window).trigger('resize'); }	
	});
  });
  
 
});
</script>
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


#panel_info { width: 900px; height:550px; border:#e4e4e4 solid 1px;}

#panel_info .tabs { background-color:#1b1e25; margin-bottom:20px; }
#panel_info .tabs ul li { float:left;}
#panel_info .tabs ul li a { color:#fff; min-width:100px; text-align:center; display:inline-block; padding:10px; border-right:#4a494a dotted 1px; }
#panel_info .tabs ul li a.active { color:#00ccff;}

#panel_info .tabs_content { width:870px; height:400px; margin:auto; border:#e4e4e4 solid 1px; overflow-y:auto; }
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


.datagrid table { height:100px; }
.datagrid table thead tr th { padding:6px 15px; text-align:left; background-color:#f5f5f5; border-bottom:#ebebeb solid 1px; border-right:#f1f1f1 solid 1px; padding-right:0; }
.datagrid table thead tr th a { color:#717171; display:block;  }
.datagrid table thead tr th a.order {  background: url(_admin/_img/bck_sort.png) no-repeat right 4px; }
.datagrid table thead tr th a.order.asc { background: url(_admin/_img/bck_sort.png) no-repeat right 4px; }
.datagrid table thead tr th a.order.desc { background: url(_admin/_img/bck_sort.png) no-repeat right -22px; }

.datagrid table tbody tr:nth-child(even) { background-color:#f9f9f9; }
.datagrid table tbody tr:nth-child(odd) { background-color:#fff; }
.datagrid table tbody tr td { padding:3px 15px; border-top:#f1f1f1 solid 1px;  }
.datagrid table tbody tr.hover:hover { background-color:#9CF !important;}
</style>
</head>

<body>
<?php
date_default_timezone_set('America/Monterrey');

require_once("../_class/class.gprs.php");
require_once("../_class/class.client.php");

$objGPRS = new GPRS();
$objClient = new Client();



?>

<div id="reports"></div>

<div id="panel_info">
  <div class="tabs">
    <ul>
      <li><a href="javascript:save_geofences()" class="onClickTab active" rel="tab_info">Guardar cambios</a></li>
     
    </ul>
    <br class="clear" />
  </div>
  
  <div class="static">

    <table border="0" width="100%" cellpadding="0" cellspacing="0">
      <tr>
      <td width="20" valign="top"></td>
      <td valign="top" style="position:relative;">
     
       
        <h1>Administraci&oacute;n de Alertas <?php if($_GET['o']==1){ echo "Generales"; }else{  echo "de Parametros"; } ?></h1>
        <h2><em class="gray"></em></h2>
      </td>
      </tr>
    </table>
    <hr class="line" />
  </div>
  
  <div class="tabs_content">
    
    <div class="tab_info isTabContent" style="display:block;">
    <div class="datagrid">
    <table width="100%" id="tbl_alarm">
      <thead>
       <tr>
          <th style="font-size:10px;">Global</th>
          <?php
		  $geofences = $objClient->set_id_client($_SESSION['logged']['id_client'])->getClientGeofence();
		  $assets_geofences = $objClient->set_id_client($_SESSION['logged']['id_client'])->getAssetGeofence();
		  for($i=0;$i<count($geofences);$i++){
			  echo '<Th><table border="0" cellpadding="0" cellspacing="0" style="height:10px;">
		  <tr><td style="padding:0; border:none;"><input id="chk_global_e_'.$geofences[$i]['id'].'" name="chk_global_e_'.$geofences[$i]['id'].'" type="checkbox" value="'.$geofences[$i]['id'].'" class="onGlobalEUpdate" /></td><td style="padding:0;">E</td></tr>
		  <tr><td style="padding:0; border:none;"><input id="chk_global_s_'.$geofences[$i]['id'].'" name="chk_global_s_'.$geofences[$i]['id'].'" type="checkbox" value="'.$geofences[$i]['id'].'" class="onGlobalSUpdate" /><td style="padding:0;">S</td></td></tr>
		  </table></th>
          ';
		  }
		  ?>
          
        </th>
        <th style="font-size:10px;"><input type="text" id="txt_valor[]" name="txt_valor[]" style="width:200px; border:#ccc solid 1px; padding:5px;" class="x0em onEnterUpdate"/></th>

          
    
       
          </tr>
        <tr>
          <th style="font-size:10px;" width="100">Unidad</th>
          <?php
		  $geofences = $objClient->set_id_client($_SESSION['logged']['id_client'])->getClientGeofence();
		  for($i=0;$i<count($geofences);$i++){
			  echo '<th style="font-size:10px;">'.$geofences[$i]['name'].'</th>';
		  }
		  ?>
          
          <th style="font-size:10px;">Correo Notificacion</th>
          </tr>
          
         
      </thead>
      <tbody>
      <?php
	  $assets_temp = $objAsset->set_id_client($_SESSION['logged']['id_client'])->getAsset();
	  $assets_alarms = $objClient->set_id_client($_SESSION['logged']['id_client'])->getClientGeoFences();
	  $assets_user = $objAsset->getUserAssets($_SESSION['logged']['id_user']); 
	  
	  $cont = 0;
	  if($_SESSION['logged']['type']==2){
	    for($i=0;$i<count($assets_temp);$i++){
	      if(in_array($assets_temp[$i]['id'],$assets_user)){
		    $assets[$cont] = $assets_temp[$i];
			$cont++;
		  }
	    }
	  }else{ 
	    $assets = $assets_temp; 
	  }
	  
	  
	  
	        $buffer = NULL;
for($i=0;$i<count($assets);$i++){
  
  $data = json_decode($assets[$i]['data'],true);
  
  $buffer .= '<tr id="a_'.$assets[$i]['imei'].'" class="hover">';
  $buffer .= '<td class="fixed">'.$assets[$i]['alias'].'</td>';
  
  $value = 0;
  $notifications = json_decode($assets[$i]['notification'],true);  
  for($k=0;$k<count($geofences);$k++){
    $settings = has_geofence($assets[$i]['imei'],$geofences[$k]['id'],$assets_geofences);
	
	$e_checked = '';
	$s_checked = '';
	if($settings['enter']==1){ $e_checked = 'checked="checked"'; }
	if($settings['exit']==1){ $s_checked = 'checked="checked"'; }
	
		$buffer	 .= '<td>
  		<table border="0" cellpadding="0" cellspacing="0" style="height:10px;">
		  <tr><td style="padding:0; border:none;"><input id="chk_single_e_'.$geofences[$k]['id'].'" name="chk_single_e_'.$geofences[$k]['id'].'" type="checkbox" value="'.$assets[$i]['imei'].'|E|'.$geofences[$k]['id'].'" class="isCheckGeofence chk_single_e_'.$geofences[$k]['id'].'" '.$e_checked.'/></td><td style="padding:0;">E</td></tr>
		  <tr><td style="padding:0; border:none;"><input id="chk_single_s_'.$geofences[$k]['id'].'" name="chk_single_s_'.$geofences[$k]['id'].'" type="checkbox" value="'.$assets[$i]['imei'].'|S|'.$geofences[$k]['id'].'" class="isCheckGeofence chk_single_s_'.$geofences[$k]['id'].'" '.$s_checked.'/><td style="padding:0;">S</td></td></tr>
		</table>
 		</td>';
 }
 $buffer .= '<td><input type="text" id="txt_valor[]" name="txt_valor[]" style="width:200px; border:#ccc solid 1px; padding:5px;" placeholder="e-mail" class="x0em" value="'.$notifications[3].'"/></td>';
 $buffer .= '</tr>';
		  

}

function has_geofence($imei,$id_geofence,$assets_geofences){
  $settings = NULL;
  for($i=0;$i<count($assets_geofences);$i++){
    if($assets_geofences[$i]['imei'] == $imei && $assets_geofences[$i]['id_geofence'] == $id_geofence){ 
	  $settings['enter'] = $assets_geofences[$i]['gf_enter'];
	  $settings['exit'] = $assets_geofences[$i]['gf_exit'];
	  break;
	}
  }
  return $settings;
}



echo $buffer;
	  ?>
      </tbody>
    </table>
    </div>
    </div>
    

  

    

  
</div>  

<script>
$(document).ready(function(){
	
  $('.onChkUpdate').change(function(){
    if($(this).is(":checked")){
	  $("."+$(this).val()).attr("checked","checked").val(1);  
	}else{
	  $("."+$(this).val()).removeAttr("checked").val(0);  
	}
    
  });
  
  
  $('.onGlobalEUpdate').change(function(){
    if($(this).is(":checked")){
	  $(".chk_single_e_"+$(this).val()).attr("checked","checked");  
	}else{
	  $(".chk_single_e_"+$(this).val()).removeAttr("checked");
	}
    
  });
  
  $('.onGlobalSUpdate').change(function(){
    if($(this).is(":checked")){
	  $(".chk_single_s_"+$(this).val()).attr("checked","checked");  
	}else{
	  $(".chk_single_s_"+$(this).val()).removeAttr("checked");  
	}
    
  });
  
  
  
  
  $('.onEnterUpdate').keypress(function(e){
    if(e.which==13){ 
	  var value = $(this).attr("class").split(" ");
	  $("."+value[0]).val($(this).val());
	  
	}
  });
  
  
  
	
  
 
  
 
});


function save_geofences(){
  var buffer = new Array();
  var imeis = null;
  var buffer_gc = new Array();
  
  $("#tbl_alarm > tbody tr.hover").each(function(index){
    var data = new Object();
    data.imei = $(this).attr("id").replace("a_","");
    data.email = $("#a_"+data.imei+ " input.x0em").val();
    buffer.push(data);
  });
  
  $("#tbl_alarm > tbody tr input.isCheckGeofence:checked").each(function(index){
	var data_gc = new Object();
	value = $(this).val().split("|");
	  data_gc.imei = value[0];
	  if($('.chk_single_e_'+value[2]).is(':checked')){ data_gc.enter = 1; }else { data_gc.enter = 0; }
	  if($('.chk_single_s_'+value[2]).is(':checked')){ data_gc.exit = 1; }else { data_gc.exit = 0; }
	  data_gc.id = value[2];
	  buffer_gc.push(data_gc);
  });
  
	
	
	$.post('../_ctrl/ctrl.client.php', { exec: "set_geofences", data: buffer, gc : buffer_gc, o: 3 }, 
	  function(data){ 
	  alert("Geocercas Guardadas con exito!");
	});
}	



 

 
  
</script>
</body>

</html>

