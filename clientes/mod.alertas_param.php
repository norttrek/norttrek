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
 $client_info = json_decode($client[0]['security'],true);
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
<?php
date_default_timezone_set('America/Monterrey');

require_once("../_class/class.gprs.php");
require_once("../_class/class.client.php");

$objGPRS = new GPRS();
$objClient = new Client();








?>
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
  </style>
<div id="reports"></div>

<div id="panel_info">
  <div class="tabs">
    <ul>
      <li><a href="javascript:save_alarm()" class="onClickTab active" rel="tab_info">Guardar cambios</a></li>
     
    </ul>
    <br class="clear" />
  </div>
  
  <div class="static">

    <table border="0" width="100%" cellpadding="0" cellspacing="0">
      <tr>
      <td width="20" valign="top"></td>
      <td valign="top" style="position:relative;">
     
       
        <h1>Administraci&oacute;n de Alertas Generales</h1>
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
          <th style="font-size:10px; display:none;"><input id="global_setting[]" name="global_setting[]" type="checkbox" value="0x5" class="onChkUpdate" /></th>
          <th style="font-size:10px; display:none;"><input id="global_setting[]" name="global_setting[]" type="checkbox" value="0xA" class="onChkUpdate" /></th>
          <th style="font-size:10px; display:none;"><input id="global_setting[]" name="global_setting[]" type="checkbox" value="0xF" class="onChkUpdate" /></th>
          <th style="font-size:10px; display:none;"><input id="global_setting[]" name="global_setting[]" type="checkbox" value="0x14" class="onChkUpdate" /></th>
          <th style="font-size:10px; display:none;"><input id="global_setting[]" name="global_setting[]" type="checkbox" value="0x19" class="onChkUpdate" /></th>
          <th style="font-size:10px; display:none;"><input id="global_setting[]" name="global_setting[]" type="checkbox" value="0x1E" class="onChkUpdate" /></th>
          <th style="font-size:10px; display:none;"><input id="global_setting[]" name="global_setting[]" type="checkbox" value="0x23" class="onChkUpdate" /></th>
          <th style="font-size:10px; display:none;"><input id="global_setting[]" name="global_setting[]" type="checkbox" value="0x28" class="onChkUpdate" /></th>
          
    
          <th style="font-size:10px;"><input type="text" id="txt_valor[]" name="txt_valor[]" style="width:40px; border:#ccc solid 1px; padding:5px;" class="0x55 onEnterUpdate" /></th>
          <th style="font-size:10px;"><input type="text" id="txt_valor[]" name="txt_valor[]" style="width:40px; border:#ccc solid 1px; padding:5px;" class="0x5A onEnterUpdate"/></th>
          <th style="font-size:10px;"><input type="text" id="txt_valor[]" name="txt_valor[]" style="width:40px; border:#ccc solid 1px; padding:5px;" class="0x5F onEnterUpdate"/></th>
          <th style="font-size:10px;"><input type="text" id="txt_valor[]" name="txt_valor[]" style="width:40px; border:#ccc solid 1px; padding:5px;" class="0x69 onEnterUpdate"/></th>
          <th style="font-size:10px;"><input type="text" id="txt_valor[]" name="txt_valor[]" style="width:40px; border:#ccc solid 1px; padding:5px;" class="0x6E onEnterUpdate"/></th>
          <th style="font-size:10px; "><input type="text" id="txt_valor[]" name="txt_valor[]" style="width:200px; border:#ccc solid 1px; padding:5px;" class="x0em onEnterUpdate"/></th>
          </tr>
        <tr>
          <th style="font-size:10px; display:none;">Unidad</th>
          <th style="font-size:10px; display:none;">Panico</th>
          <th style="font-size:10px; display:none;">Motor Apagado</th>
          <th style="font-size:10px; display:none;">Motor Encendido</th>
          <th style="font-size:10px; display:none;">Bater&iacute;a Baja</th>
          <th style="font-size:10px; display:none;">Antena GPS Conect.</th>
          <th style="font-size:10px; display:none;">Antena GPS Descon.</th>
          <th style="font-size:10px; display:none;">Movimiento (Tremble)</th>
          <th style="font-size:10px; display:none;">Sin Movimiento (Idle)</th>
          

          
          <th style="font-size:10px;">Velocidad Max.</th>
          <th style="font-size:10px;">Comb. 1 Min</th>
          <th style="font-size:10px;">Comb. 2 Min</th>
          <th style="font-size:10px;">Temp Max.</th>
          <th style="font-size:10px;">Temp Min</th>
          <th style="font-size:10px;">Correo Notificacion</th>
          </tr>
         
      </thead>
      <tbody>
      <?php
	  $assets = $objAsset->set_id_client($_SESSION['logged']['id_client'])->getAsset();
	  $assets_alarms = $objClient->set_id_client($_SESSION['logged']['id_client'])->getClientAlarms();
	        $buffer = NULL;
for($i=0;$i<count($assets);$i++){
  
  $data = json_decode($assets[$i]['data'],true);
  
  $buffer .= '<tr id="a_'.$assets[$i]['imei'].'">';
  $buffer .= '<td class="fixed">'.$data[1]['value'].'</td>';
  
  $result=  
  $checked = '';
  $value = 0;
  if(has_alarm($assets_alarms,$assets[$i]['imei'],'0x5')){ $checked = 'checked'; $value = 1; }
  $buffer .= '<td style="display:none;"><input id="chk_single[]" name="chk_single[]" type="checkbox" value="'.$value.'" class="0x5 onSingleUpdate" '.$checked.'/></td>';
  $checked = '';
  $value = 0;
  if(has_alarm($assets_alarms,$assets[$i]['imei'],'0xA')){ $checked = 'checked'; $value = 1; }
  $buffer .= '<td style="display:none;"><input id="chk_single[]" name="chk_single[]" type="checkbox" value="'.$value.'" class="0xA onSingleUpdate" '.$checked.' /></td>';
  $checked = '';
  $value = 0;
  if(has_alarm($assets_alarms,$assets[$i]['imei'],'0xF')){ $checked = 'checked'; $value = 1; }
  $buffer .= '<td style="display:none;"><input id="chk_single[]" name="chk_single[]" type="checkbox" value="'.$value.'" class="0xF onSingleUpdate" '.$checked.' /></td>';
  $checked = '';
  $value = 0;
  if(has_alarm($assets_alarms,$assets[$i]['imei'],'0x14')){ $checked = 'checked'; $value = 1; }
  $buffer .= '<td style="display:none;"><input id="chk_single[]" name="chk_single[]" type="checkbox" value="'.$value.'" class="0x14 onSingleUpdate" '.$checked.' /></td>';
  $checked = '';
  $value = 0;
  if(has_alarm($assets_alarms,$assets[$i]['imei'],'0x19')){ $checked = 'checked'; $value = 1; }
  $buffer .= '<td style="display:none;"><input id="chk_single[]" name="chk_single[]" type="checkbox" value="'.$value.'" class="0x19 onSingleUpdate" '.$checked.' /></td>';
  $checked = '';
  $value = 0;
  if(has_alarm($assets_alarms,$assets[$i]['imei'],'0x1E')){ $checked = 'checked'; $value = 1; }
  $buffer .= '<td style="display:none;"><input id="chk_single[]" name="chk_single[]" type="checkbox" value="'.$value.'" class="0x1E onSingleUpdate" '.$checked.' /></td>';
  $checked = '';
  $value = 0;
  if(has_alarm($assets_alarms,$assets[$i]['imei'],'0x23')){ $checked = 'checked'; $value = 1; }
  $buffer .= '<td style="display:none;"><input id="chk_single[]" name="chk_single[]" type="checkbox" value="'.$value.'" class="0x23 onSingleUpdate" '.$checked.' /></td>';
  $checked = '';
  $value = 0;
  if(has_alarm($assets_alarms,$assets[$i]['imei'],'0x28')){ $checked = 'checked'; $value = 1; }
  $buffer .= '<td style="display:none;"><input id="chk_single[]" name="chk_single[]" type="checkbox" value="'.$value.'" class="0x28 onSingleUpdate" '.$checked.' /></td>';
  $checked = '';
  $value = 0;
  
  $value = has_alarm_value($assets_alarms,$assets[$i]['imei'],'0x55');
  $buffer .= '<td><input type="text" id="txt_valor[]" name="txt_valor[]" style="width:40px; border:#ccc solid 1px; padding:5px;" placeholder="0 km" class="0x55" value="'.$value.'" /></td>';
  $value = has_alarm_value($assets_alarms,$assets[$i]['imei'],'0x5A');
  $buffer .= '<td><input type="text" id="txt_valor[]" name="txt_valor[]" style="width:40px; border:#ccc solid 1px; padding:5px;" placeholder="0 km" class="0x5A" value="'.$value.'" /></td>';
  $value = has_alarm_value($assets_alarms,$assets[$i]['imei'],'0x5F');
  $buffer .= '<td><input type="text" id="txt_valor[]" name="txt_valor[]" style="width:40px; border:#ccc solid 1px; padding:5px;" placeholder="0 L" class="0x5F" value="'.$value.'" /></td>';
  $value = has_alarm_value($assets_alarms,$assets[$i]['imei'],'0x69');
  $buffer .= '<td><input type="text" id="txt_valor[]" name="txt_valor[]" style="width:40px; border:#ccc solid 1px; padding:5px;" placeholder="0 C&deg;" class="0x69" value="'.$value.'"/></td>';
  $value = has_alarm_value($assets_alarms,$assets[$i]['imei'],'0x6E');
  $buffer .= '<td><input type="text" id="txt_valor[]" name="txt_valor[]" style="width:40px; border:#ccc solid 1px; padding:5px;" placeholder="0 C&deg;" class="0x6E" value="'.$value.'"/></td>';
  $value = has_alarm_value($assets_alarms,$assets[$i]['imei'],'x0em');
  $buffer .= '<td><input type="text" id="txt_valor[]" name="txt_valor[]" style="width:200px; border:#ccc solid 1px; padding:5px;" placeholder="e-mail" class="x0em" value="'.$value.'"/></td>';
  $buffer .= '</tr>';
}

function has_alarm($data,$imei,$code){
  $flag = false;
  for($i=0;$i<count($data);$i++){ 
    if($data[$i]['imei'] == $imei && $data[$i]['code'] == $code){ $flag = true; } 
  }
  return $flag;
}

function has_alarm_value($data,$imei,$code){
  $flag = false;
  for($i=0;$i<count($data);$i++){ 
    if($data[$i]['imei'] == $imei && $data[$i]['code'] == $code){ $flag = $data[$i]['value']; } 
  }
  return $flag;
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
  
  
  $('.onSingleUpdate').change(function(){
    if($(this).is(":checked")){
	  $(this).attr("checked","checked").val(1);  
	}else{
	  $(this).removeAttr("checked").val(0);  
	}
    
  });
  
  
  
  
  $('.onEnterUpdate').keypress(function(e){
    if(e.which==13){ 
	  var value = $(this).attr("class").split(" ");
	  $("."+value[0]).val($(this).val());
	  
	}
  });
  
  
  
	
  
  $('.onClickSaveAlarm').live("click",function(){ 
    console.log($("#frm_alarm ").serializeArray());
    return false;
    $.post('../_ctrl/ctrl.client.php', { imei: <?php echo $asset[0]['imei']; ?>, exec: "update_alarm", data: $("#frm_alarm").serializeArray() }, 
	    function(data){ 
		console.log(data);
	    });
  });
  
 
  
 
});


function save_alarm(){
  var buffer = new Array();
	$("#tbl_alarm > tbody tr").each(function(index){
		var data = new Object();
		var temp = new Array();
		data.imei = $(this).attr("id").replace("a_","");
		data.settings = '';
		if($("#a_"+data.imei+ " .0x5").val()==1){ data.settings += '0x5|'+$("#a_"+data.imei+ " .0x5").val()+','; }
		if($("#a_"+data.imei+ " .0xA").val()==1){ data.settings += '0xA|'+$("#a_"+data.imei+ " .0xA").val()+','; }
		if($("#a_"+data.imei+ " .0xF").val()==1){ data.settings += '0xF|'+$("#a_"+data.imei+ " .0xF").val()+','; }
		if($("#a_"+data.imei+ " .0x14").val()==1){ data.settings += '0x14|'+$("#a_"+data.imei+ " .0x14").val()+','; }
		if($("#a_"+data.imei+ " .0x19").val()==1){ data.settings += '0x19|'+$("#a_"+data.imei+ " .0x19").val()+','; }
		if($("#a_"+data.imei+ " .0x1E").val()==1){ data.settings += '0x1E|'+$("#a_"+data.imei+ " .0x1E").val()+','; }
		if($("#a_"+data.imei+ " .0x23").val()==1){ data.settings += '0x23|'+$("#a_"+data.imei+ " .0x23").val()+','; }
		if($("#a_"+data.imei+ " .0x28").val()==1){ data.settings += '0x28|'+$("#a_"+data.imei+ " .0x28").val()+','; }
		if($("#a_"+data.imei+ " .0x2D").val()==1){ data.settings += '0x2D|'+$("#a_"+data.imei+ " .0x2D").val()+','; }
		if($("#a_"+data.imei+ " .0x32").val()==1){ data.settings += '0x32|'+$("#a_"+data.imei+ " .0x32").val()+','; }
		if($("#a_"+data.imei+ " .0x37").val()==1){ data.settings += '0x37|'+$("#a_"+data.imei+ " .0x37").val()+','; }
		if($("#a_"+data.imei+ " .0x3C").val()==1){ data.settings += '0x3C|'+$("#a_"+data.imei+ " .0x3C").val()+','; }
		if($("#a_"+data.imei+ " .0x41").val()==1){ data.settings += '0x41|'+$("#a_"+data.imei+ " .0x41").val()+','; }
		if($("#a_"+data.imei+ " .0x46").val()==1){ data.settings += '0x46|'+$("#a_"+data.imei+ " .0x46").val()+','; }
		if($("#a_"+data.imei+ " .0x4B").val()==1){ data.settings += '0x4B|'+$("#a_"+data.imei+ " .0x4B").val()+','; }
		if($("#a_"+data.imei+ " .0x50").val()==1){ data.settings += '0x50|'+$("#a_"+data.imei+ " .0x50").val()+','; }
		
		if($("#a_"+data.imei+ " input.0x55").val() != ""){ data.settings += '0x55|'+$("#a_"+data.imei+ " input.0x55").val()+','; }
		if($("#a_"+data.imei+ " input.0x5A").val() != ""){ data.settings += '0x5A|'+$("#a_"+data.imei+ " input.0x5A").val()+','; }
		if($("#a_"+data.imei+ " input.0x5F").val() != ""){ data.settings += '0x5F|'+$("#a_"+data.imei+ " input.0x5F").val()+','; }
		if($("#a_"+data.imei+ " input.0x69").val() != ""){ data.settings += '0x69|'+$("#a_"+data.imei+ " input.0x69").val()+','; }
		if($("#a_"+data.imei+ " input.0x6E").val() != ""){ data.settings += '0x6E|'+$("#a_"+data.imei+ " input.0x6E").val()+','; }
		data.email = $("#a_"+data.imei+ " input.x0em").val();
		buffer.push(data);
	});
	
	
	
	
	$.post('../_ctrl/ctrl.client.php', { exec: "set_alarms", data: buffer }, 
	  function(data){ 
	    console.log(data);
	},"json");
}	

 
  
</script>
</body>

</html>

