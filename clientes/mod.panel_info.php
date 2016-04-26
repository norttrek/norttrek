<?php
 session_start();
 require_once("../_class/class.client.php");
 require_once("../_class/class.asset.php");
 $objClient = new Client();
 $objAsset = new Asset();
 $asset = $objAsset->getAsset($_GET['id']);
 $fuel_calib = json_decode($asset[0]['fuel'],true);
 $t1 = $fuel_calib['t1'];
 $t2 = $fuel_calib['t2'];
 $t3 = $fuel_calib['t3'];
 $asset_info = json_decode($asset[0]['data'],true);
 $asset_cinfo = json_decode($asset[0]['info'],true);

 $capacidad = 450;
 $sensor = json_decode($asset[0]['sensor'],true);
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

  $('.onChkGeoFence').on("change",function(){
    var data =  new Object();
    $("#"+$(this).parent().parent().attr("id")+ ".exit").show();
    data.imei = '<?php echo $asset[0]['imei']; ?>';
    data.id_geofence = $(this).val();

    data.enter = 0;
    data.exit = 0;

    if($("#gf_"+data.id_geofence +" .enter").is(":checked")){ data.enter = 1; }
    if($("#gf_"+data.id_geofence +" .exit").is(":checked")){ data.exit = 1; }

    if(data.enter == 0 && data.exit == 0){ parent.objClient.del_asset_geofence(data); }else{ parent.objClient.add_asset_geofence(data); }


  });



   $('.onClickCmd').click(function(){
     //if($("#txt_pin").val()==""){ alert("Ingrese su PIN de Bloqueo"); return false; }
     //if($("#psec").val().toString(2)==0){ alert("Favor de comunicarse a soporte Norttrek para asignar un PIN de bloqueo."); return false; }
     //if($("#psec").val()!=parseInt($("#txt_pin").val()).toString(2)){ alert("PIN de bloqueo Invalido."); return false; }
     parent.objClient.set_com($(this).attr("rel").split("|"));
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
<input type="hidden" id="psec" name="psec" value="<?php echo decbin($client_info['pin_bloqueos']); ?>" />
<?php
date_default_timezone_set('America/Monterrey');

require_once("../_class/class.gprs.php");
require_once("../_class/class.client.php");

$objGPRS = new GPRS();
$objClient = new Client();
$gprs_data = $objGPRS->set_limit(200)->set_order("date DESC")->getGprsReport($asset[0]['imei']);

$iostatus = $objGPRS->get_iostatus($gprs_data[0]['iostatus']);

// aad
$iostatus['ignition'] = $gprs_data[0]['v2_eng_status'];

$data = json_decode($asset[0]['data'],true);


$geocode = geocode($gprs_data[0]['lat'],$gprs_data[0]['lng']);
$direccion = explode(",",$geocode);


function geocode($lat,$lon){
  
   $json_string = @file_get_contents("http://www.geocode.farm/v3/json/reverse/?key=605e596a-31b84556a748-8415e4e06d35&lat=".$lat."&lon=".$lon."&count=1");
   $parsed_json = json_decode($json_string,true);

   return $parsed_json['geocoding_results']['RESULTS'][0]['formatted_address'];

}

?>

<div id="reports"></div>

<div id="panel_info">
  <div class="tabs">
    <ul>
      <li><a href="javascript:void(0)" class="onClickTab active" rel="tab_info">Detalles</a></li>
      <li><a href="javascript:void(0)" class="onClickTab" rel="tab_unit_info">Unidad</a></li>
      <li><a href="javascript:void(0)" class="onClickTab" rel="tab_registros">Registros del D&iacute;a</a></li>
      <li><a href="javascript:void(0)" class="onClickTab" rel="tab_alarmas"><i class="fa fa-calendar fa-lg" style=" color:#737987;" > </i> Hist. Alarmas</a></li>
      <li style="display:none;"><a href="javascript:void(0)" class="onClickTab" rel="tab_vista_calle">Vista de Calle</a></li>
      <li><a href="javascript:void(0)" class="onClickTab" rel="tab_geo">Geo-Cercas</a></li>
      <li><a href="javascript:void(0)" class="onClickTab" rel="tab_georoutes">Georutas</a></li>
      <li style="display:none;"><a href="javascript:void(0)" class="onClickTab" rel="tab_geo">Alarmas</a></li>
      <li><a href="javascript:void(0)" class="onClickTab" rel="tab_fuel">Estad&iacute;stica</a></li>
    </ul>
    <br class="clear" />
  </div>

  <div class="static">

    <table border="0" width="100%" cellpadding="0" cellspacing="0">
      <tr>
      <td width="20" valign="top">
        <a href="javascript:void();" class="unit_icon onClickIconSelector"><img src="_icons/<?php echo $asset[0]['icon']; ?>" style="width:40px; height:40px;"></a>
        <div class="icons_container">
          <ul class="icons_lst">
            <ul>
              <li><a href="#" class="onClickIcon" rel="flag.png"><img src="_icons/flag.png" width="40" height="40" /></a></li>
              <li><a href="#" class="onClickIcon" rel="truck.png"><img src="_icons/truck.png" width="40" height="40" /></a></li>
              <li><a href="#" class="onClickIcon" rel="flag.png"><img src="_icons/flag.png" width="40" height="40" /></a></li>
              <li><a href="#" class="onClickIcon" rel="green.png"><img src="_icons/green.png" width="40" height="40" /></a></li>
              <li><a href="#" class="onClickIcon" rel="pin.png"><img src="_icons/pin.png" width="40" height="40" /></a></li>
              <li><a href="#" class="onClickIcon" rel="flag.png"><img src="_icons/flag.png" width="40" height="40" /></a></li>
              <li><a href="#" class="onClickIcon" rel="truck.png"><img src="_icons/truck.png" width="40" height="40" /></a></li>
              <li><a href="#" class="onClickIcon" rel="flag.png"><img src="_icons/flag.png" width="40" height="40" /></a></li>
              <li><a href="#" class="onClickIcon" rel="truck.png"><img src="_icons/truck.png" width="40" height="40" /></a></li>
              <li><a href="#" class="onClickIcon" rel="flag.png"><img src="_icons/flag.png" width="40" height="40" /></a></li>
              <li><a href="#" class="onClickIcon" rel="green.png"><img src="_icons/green.png" width="40" height="40" /></a></li>
              <li><a href="#" class="onClickIcon" rel="pin.png"><img src="_icons/pin.png" width="40" height="40" /></a></li>
              <li><a href="#" class="onClickIcon" rel="flag.png"><img src="_icons/flag.png" width="40" height="40" /></a></li>
              <li><a href="#" class="onClickIcon" rel="truck.png"><img src="_icons/truck.png" width="40" height="40" /></a></li>
              <li><a href="#" class="onClickIcon" rel="flag.png"><img src="_icons/flag.png" width="40" height="40" /></a></li>
              <li><a href="#" class="onClickIcon" rel="truck.png"><img src="_icons/truck.png" width="40" height="40" /></a></li>
              <li><a href="#" class="onClickIcon" rel="flag.png"><img src="_icons/flag.png" width="40" height="40" /></a></li>
              <li><a href="#" class="onClickIcon" rel="green.png"><img src="_icons/green.png" width="40" height="40" /></a></li>
              <li><a href="#" class="onClickIcon" rel="pin.png"><img src="_icons/pin.png" width="40" height="40" /></a></li>
              <li><a href="#" class="onClickIcon" rel="flag.png"><img src="_icons/flag.png" width="40" height="40" /></a></li>
              <li><a href="#" class="onClickIcon" rel="truck.png"><img src="_icons/truck.png" width="40" height="40" /></a></li>
              <li><a href="#" class="onClickIcon" rel="flag.png"><img src="_icons/flag.png" width="40" height="40" /></a></li>
              <li><a href="#" class="onClickIcon" rel="truck.png"><img src="_icons/truck.png" width="40" height="40" /></a></li>
              <li><a href="#" class="onClickIcon" rel="flag.png"><img src="_icons/flag.png" width="40" height="40" /></a></li>
              <li><a href="#" class="onClickIcon" rel="green.png"><img src="_icons/green.png" width="40" height="40" /></a></li>
              <li><a href="#" class="onClickIcon" rel="pin.png"><img src="_icons/pin.png" width="40" height="40" /></a></li>
              <li><a href="#" class="onClickIcon" rel="flag.png"><img src="_icons/flag.png" width="40" height="40" /></a></li>
              <li><a href="#" class="onClickIcon" rel="truck.png"><img src="_icons/truck.png" width="40" height="40" /></a></li>

            </ul>
            <br class="clear" />
          </ul>
        </div>
      </td>
      <td valign="top" style="position:relative;">
        <style>
    #txt_name { font-size:18px; font-family: 'Open Sans', sans-serif; color:#333; font-weight:600; width:500px; border:none; background-color:#f9f9f9; }
    #txt_name:focus { border:#ccc solid 1px; }
    </style>
        <script>
    $(document).ready(function(){
      $('#txt_name').keypress(function(e){
        if(e.which == 13){
        $(this).blur();


        $.post('../_ctrl/ctrl.client.php', { id: <?php echo $_GET['id']; ?>, name: $(this).val(), exec: "update_asset_name" },
          function(data){
          alert("Nombre de la unidad ha sido cambiado con éxito.");
        parent.location.reload();
        });


        }
      });
    });
    </script>
        <h1><input type="text" id="txt_name" name="txt_name" value="<?php echo $asset[0]['alias']; ?>"></h1>
        <h2><?php echo $geocode; ?><em class="gray">(Ultima Ubicacion Registrada)</em></h2>
      </td>
      </tr>
    </table>
    <hr class="line" />
  </div>
  <style>
  .tab_info ul li { margin-left:15px; margin-bottom:4px; font-size:13px;}
  .tab_info ul li .label { display:inline-block; width:130px;  }
  .tab_info ul li .value { display:inline-block; width:290px; }
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
  <div class="tabs_content">

    <div class="tab_info isTabContent" style="display:block;">

      <table width="100%" border="0">
  <tr>
    <td valign="top">
    <div class="info_container" style="position:relative;">
    <?php
  $speed = 0;
    if($gprs_data[0]['gps_speed']=='000'){ $speed = '0'; }else{ $speed = intval($gprs_data[0]['gps_speed']); }
  ?>
      <h1>Informacion de  la Unidad</h1>
     <ul>
        <li style=" display:none;"><div class="label">Unidad: </div><div class="value" style="color:#00ccff;"><strong><?php echo $asset_info[1]['value']; ?></strong></div></li>
        <?php if(isset($sensor['temp']) && $sensor['temp']!=NULL){ ?>
        <li><div class="label">Temperatura: </div><div class="value">
      <?php

      $temp_c = 0;
      $temp_c = substr($gprs_data[0]['temp'],0,4)/10;
      $tunit = 'C&deg';
      #FARENHEIT
      if($_SESSION['logged']['temp']=="f"){
        $temp_c  = ($temp_c*1.8+32);
        $tunit = 'F&deg';
      }
      echo number_format($temp_c,1).' '.$tunit;
      ?>
          </div></li>
         <?php } ?>
        <?php if(isset($sensor['fuel']) && $sensor['fuel']!=NULL){ ?>
        <?php
    $fuel_html = '';
    $fuel = $gprs_data[0]['ada_v'];
    if($fuel<1){ $fuel_html = '<li></li>'; }
    if($fuel>=1 && $fuel<2){ $fuel_html = '<li></li><li></li>'; }
    if($fuel>=2 && $fuel<3){ $fuel_html = '<li></li><li></li><li></li>'; }
    if($fuel>=3 && $fuel<4.5){  $fuel_html = '<li></li><li></li><li></li><li></li>';}
    if($fuel>=4.5){ $fuel_html = '<li></li><li></li><li></li><li></li><li></li>'; }
    ?>
        <li><div class="label">Combustible: </div><div class="value"><div class="fuel"><ul><?php echo $fuel_html; ?></ul></div><span style="display:inline-block;"></span></div></li>
        <?php } ?>
        <li><div class="label">Evento: </div><div class="value"><?php echo $objGPRS->get_status($gprs_data[0]['status']); ?></div></li>
        <li><div class="label">Ultimo Registro: </div><div class="value"><?php echo $objGPRS->get_time_ago(date("Y-m-d H:i:s"),$gprs_data[0]['date']); ?></div></li>
        <li><div class="label">Calle: </div><div class="value"><?php echo $direccion[0]; ?></div></li>
        <li><div class="label">Colonia: </div><div class="value"><?php echo $direccion[1]; ?></div></li>
        <li><div class="label">Estado: </div><div class="value"><?php echo $direccion[3]; ?></div></li>
        <li><div class="label">Velocidad: </div><div class="value"><?php echo $speed; ?> km/h</div></li>
        <li><div class="label">Motor: </div><div class="value"><?php  if($iostatus['ignition']==1){ echo '<i class="fa fa-power-off green"></i> Encendido</div>'; }else{ echo '<i class="fa fa-power-off red"></i> Apagado</div>'; } ?></li>
        <li><div class="label">Bater&Iacute;a: </div><div class="value"><?php echo $gprs_data[0]['battery_v']; ?>V<div></li>
        <li><div class="label">Alimentación (V): </div><div class="value"><?php echo $gprs_data[0]['supply_v']; ?>V</div></li>
        <li><div class="label">Coordenadas: </div><div class="value"><?php echo $gprs_data[0]['lat']; ?>,<?php echo $gprs_data[0]['lng']; ?></div></li>
        <li><div class="label">Comandos </div><div class="value"><select id="lst_comandos" name="lst_comandos"><option value="gprs" selected>GPRS</option><option value="SMS">SMS</option></select><input id="txt_pin" name="txt_pin" type="text" placeholder="PIN" style="height:20px; width:50px; border:#fff solid 1px; border-radius:5px" /> </div></li>
        <li><div class="label" style="margin-bottom:5px; margin-top:5px;">Bloqueo de Marcha: </div><div class="value"><?php if($iostatus['ignition_cut']==1){ echo '<a href="javascript:void(0)" class="off onClickCmd" rel="'.$asset[0]['imei'].'|A0">Desbloqueo</a>'; }else{ echo '<a href="javascript:void(0)" class="on onClickCmd" rel="'.$asset[0]['imei'].'|A1">Bloquear</a>'; } ?></div></li>
        <li><div class="label">Bloqueo de Motor: </div><div class="value"><?php if($iostatus['ignition_blocked']==1){ echo '<a href="javascript:void(0)" class="off onClickCmd" rel="'.$asset[0]['imei'].'|B0">Desbloqueo</a>'; }else{ echo '<a href="javascript:void(0)" class="on onClickCmd" rel="'.$asset[0]['imei'].'|B1">Bloquear</a>';  } ?></div></li>
        <?php
      $elock = $gprs_data[0]['outputs'];
    ?>
    <?php if($sensor['elock']==1){ ?>
        <li><div class="label" style="margin-bottom:5px; margin-top:5px;">E-Lock:</div><div class="value"><?php if($elock[10]==1){ echo '<a href="javascript:void(0)" class="off onClickCmd" rel="'.$asset[0]['imei'].'|C0">Cerrado</a>'; }else{ echo '<a href="javascript:void(0)" class="on onClickCmd" rel="'.$asset[0]['imei'].'|C1">Abierto</a>'; } ?></div></li>
        <?php } ?>
      </ul>
    </div>



    </td>
    <td valign="top">
      <style>
     .image a.ext { position:absolute; width:400px; height:380px; display:block; }
     .image #one { z-index:100; }
     .image #two { z-index:0; }
     .image a.nav { position:absolute; width:40px; height:40px; display:block; background-color:#000; z-index:1000000; text-align:center; }
     .image a.nav i { color:#4d5260; text-align:center; margin-top:7px; }
     .image a.nav:hover i { color:#fff; }
     .image a.nav.next { left:0; top:165px; }
     .image a.nav.prev { right:0; top:165px; }
    </style>
      <script>
    var active = 'atwo';
    $(document).ready(function(){

    $('.next').click(function(){
      if(active=='atwo'){
      $("#aone").hide().css('z-index',100).fadeIn(function(){ $("#atwo").css('z-index',0); });
      active = 'aone';
      }else{
      $("#atwo").hide().css('z-index',100).fadeIn(function(){ $("#aone").css('z-index',0); });
      active = 'atwo';
      }
      });

    $('.prev').click(function(){
      if(active=='atwo'){
      $("#aone").hide().css('z-index',100).fadeIn(function(){ $("#atwo").css('z-index',0); });
      active = 'aone';
      }else{
      $("#atwo").hide().css('z-index',100).fadeIn(function(){ $("#aone").css('z-index',0); });
      active = 'atwo';
      }
      });

    });
    </script>
      <div class="image" style="width:400px; height:380px; border:#ccc solid 1px; position:relative;">
        <a href="javascript:void(0)" class="nav next"><i class="fa fa-arrow-left fa-2x"></i></a>
        <a href="javascript:void(0)" class="nav prev"><i class="fa fa-arrow-right fa-2x"></i></a>
        <a href="https://www.google.com/maps/?q=<?php echo $gprs_data[0]['lat']; ?>,<?php echo $gprs_data[0]['lng']; ?>" class="ext" id="aone" target="_blank">
        <img id="one" src="http://maps.googleapis.com/maps/api/staticmap?center=<?php echo $gprs_data[0]['lat']; ?>,<?php echo $gprs_data[0]['lng']; ?>&zoom=15&size=400x380&&markers=color:red%7Clabel:P%7C<?php echo $gprs_data[0]['lat']; ?>,<?php echo $gprs_data[0]['lng']; ?>&sensor=false"></a>
        <a id="atwo" href="http://maps.google.com/maps?q=&layer=c&cbll=<?php echo $gprs_data[0]['lat']; ?>,<?php echo $gprs_data[0]['lng']; ?>" class="ext" target="_blank">
        <img id="two" src="http://maps.googleapis.com/maps/api/streetview?location=<?php echo $gprs_data[0]['lat']; ?>,<?php echo $gprs_data[0]['lng']; ?>&heading=150&size=400x380&sensor=false"></a>
      </div>
    </td>
  </tr>


</table>








    </div>

    <div class="tab_registros isTabContent">
      <div class="registros_container datagrid">
        <table width="100%" cellpadding="1" cellspacing="1" border="0">
          <thead>
          <tr>
            <th width="100">Fecha / Hora</th>
            <th>Evento</th>
            <th>Bateria</th>
            <th>Voltaje</th>
            <th>Comb. 1 L</th>
            <th>Comb. 2 L </th>
            <th>Comb. 3 L</th>
            <th>Temp. <?php echo $tunit; ?></th>
            <th>Velocidad</th>
            <th>Lat / Lon</th>
          </tr>
          </thead>
          <tbody>
          <?php

      for($i=0;$i<count($gprs_data);$i++){
      $temp_c = 0;
      $temp_c = number_format((substr($gprs_data[$i]['temp'],0,4)/10),1);
      $tunit = 'C&deg';
      #FARENHEIT
      if($_SESSION['logged']['temp']=="f"){
        $temp_c  = ($temp_c*1.8+32);
        $tunit = 'F&deg';
      }

      $fuel_a = substr($gprs_data[$i]['ada_v'],0,4)/100;
      $fuel_b = substr($gprs_data[$i]['ada_v'],4,8)/100;
      $fuel_c = substr($gprs_data[$i]['fuel'],0,4)/100;

      $status = '';
      $speed = 0;
          if($gprs_data[$i]['gps_speed']=='000'){ $speed = '0'; }else{ $speed = intval($gprs_data[$i]['gps_speed']); }
      $fuel = substr($gprs_data[$i]['ada_v'],1,4)/1000;
        echo '<tr>
            <td>'.$objAsset->formatDateTime($gprs_data[$i]['date'],"min").'</td>
            <td>'.$objGPRS->get_status($gprs_data[$i]['status']).'</td>
            <td>'.$gprs_data[$i]['battery_v'].'v</td>
      <td>'.$gprs_data[$i]['supply_v'].'v</td>';
      if(!isset($sensors['formula']) || $sensors['formula']==1){
        echo '<td>'.number_format($objGPRS->get_fuel_lt($sensor['fuel_a_d'],$sensor['fuel_a_l'],$sensor['fuel_a_as'],$sensor['fuel_a_v'],$sensor['fuel_a_vl'],$fuel_a),2).' L</td>
              <td>'.number_format($objGPRS->get_fuel_lt($sensor['fuel_b_d'],$sensor['fuel_b_l'],$sensor['fuel_b_as'],$sensor['fuel_b_v'],$sensor['fuel_b_vl'],$fuel_b),2).' L</td>
              <td>'.number_format($objGPRS->get_fuel_lt($sensor['fuel_c_d'],$sensor['fuel_c_l'],$sensor['fuel_c_as'],$sensor['fuel_c_v'],$sensor['fuel_c_vl'],$fuel_c),2).' L</td>';
      }else{
        echo '
             <td>'.number_format($objGPRS->get_fuel_alt($fuel_a,$t1),2).'</td>
             <td>'.number_format($objGPRS->get_fuel_alt($fuel_b,$t2),2).'</td>
             <td>'.number_format($objGPRS->get_fuel_alt($fuel_c,$t3),2).'</td>';
      }
      echo '
        <td>'.number_format($temp_c,1).' '.$tunit.'</td>
      <td>'.$speed.' km/h</td>
            <td>'.$gprs_data[$i]['lat'].','.$gprs_data[$i]['lng'].'</td>
      </tr>';
      }


      ?>
          </tbody>
        </table>


      </div>
    </div>


      <div class="tab_unit_info isTabContent">
      <div class="registros_container datagrid" style="height:390px;">

        <?php
    $data = $objClient->set_id_asset($_GET['id'])->getClientAssets();
    $info = json_decode($data[0]['data'],true);

    ?>
        <style>

    #frm_asset p { margin:0; padding:0; height:41px; margin-left:25px;}
    #frm_asset label { font-weight:bold;}
    #frm_asset input[type=text] { display:block; min-width:250px; border:#e4e4e4 solid 1px; padding:3px; }
    a.save { padding: 5px;
background-color: #008fd8;
width: 80px;
border-radius: 2px;
text-align: center;
display: block;
margin-top: 15px;
color: #fff;
font-weight: bold;
text-decoration: none;}
    </style>

        <form id="frm_asset" name="frm_asset">
        <p>
          <label class="inline">Nombre </label>
          <input type="text" id="name" name="name" value="<?php echo $asset[0]['alias']; ?>" class="inline">
          <br class="clear">
        </p>

        <p>
          <label class="inline">Marca <em></em></label>
          <input type="text" id="marca" name="marca" value="<?php echo $asset_cinfo['marca'] ?>" class="inline">
          <br class="clear">
        </p>


       <p>
          <label class="inline">Modelo <em></em></label>
          <input type="text" id="modelo" name="modelo" value="<?php echo $asset_cinfo['modelo'] ?>" class="inline">
          <br class="clear">
        </p>

        <p>
          <label class="inline">Año <em></em></label>
          <input type="text" id="ano" name="ano" value="<?php echo $asset_cinfo['ano'] ?>" class="inline">
          <br class="clear">
        </p>

       <p>
          <label class="inline">No. Serie <em></em></label>
          <input type="text" id="no_serie" name="no_serie" value="<?php echo $asset_cinfo['no_serie'] ?>" class="inline">
          <br class="clear">
        </p>

        <p>
          <label class="inline">Placas<em></em></label>
          <input type="text" id="placas" name="placas" value="<?php echo $asset_cinfo['placas'] ?>" class="inline">
          <br class="clear">
        </p>

          <p>
          <label class="inline">Color<em></em></label>
          <input type="text" id="color" name="color" value="<?php echo $asset_cinfo['color'] ?>" class="inline">
          <br class="clear">
        </p>
        <p>
          <label class="inline">Od&oacute;metro<em></em></label>
          <input type="text" id="odometro" name="odometro" value="<?php echo $asset_cinfo['odometro'] ?>" class="inline">
          <br class="clear">
        </p>
        <p><a href="#" class="onClickUpdateAsset save" style="margin-top:5px;">Guardar</a></p>

        </form>

        <script>

    $(document).ready(function(){
      $('.onClickUpdateAsset').click(function(){
        $.post('../_ctrl/ctrl.client.php', { id: <?php echo $_GET['id']; ?>, exec: "update_asset_client", data: $("#frm_asset").serializeArray() },
        function(r){
      alert("Unidad Actualizada con Exito!");
        });
      });
    });
    </script>



      </div>
    </div>


    <div class="tab_alarmas isTabContent">

    <div class="registros_container datagrid">
        <table width="100%" cellpadding="1" cellspacing="1" border="0">
          <thead>
          <tr>
            <th>Fecha / Hora</th>
            <th>Evento</th>
            <th>Bateria</th>
            <th>Supply V</th>
            <th>Velocidad</th>
            <th>Lat / Lon</th>
          </tr>
          </thead>
          <tbody>
          <?php
      for($i=0;$i<count($gprs_data);$i++){
        if($gprs_data[$i]['status']!=23){
      $fecha = $gprs_data[$i]['date'];
        echo '<tr>
            <td>'.$objAsset->formatDateTime($gprs_data[$i]['date'],"med").'</td>
            <td>'.$objGPRS->get_status($gprs_data[$i]['status']).'</td>
            <td>'.$gprs_data[$i]['battery_v'].'</td>
            <td>'.$gprs_data[$i]['supply_v'].'</td>
            <td>'.$gprs_data[$i]['gps_speed'].' km/h</td>
            <td>'.$gprs_data[$i]['lat'].','.$gprs_data[$i]['lng'].'</td>
      </tr>';
      }
      }


      ?>
          </tbody>
        </table>


      </div>

    </div>

    <div class="tab_geo isTabContent">

    <div class="datagrid">
    <table width="100%" cellpadding="0" cellspacing="0" border="0">
          <thead>
          <tr>
            <th align="left" width="20">Geocerca</th>
            <th align="left" width="150">Nombre</th>
            <th align="left" width="150">Alarma de Entrada</th>
            <th align="left" width="150">Alarma de Salida</th>
          </tr>
          </thead>
          <tbody>
          <?php
      $geofences = $objClient->set_id_client($_SESSION['logged']['id_client'])->getClientGeofence();
      $asset_geofences = $objClient->set_imei($asset[0]['imei'])->getAssetGeofence();
      for($i=0;$i<count($geofences);$i++){
      $data = json_decode($geofences[$i]['data'],true);
      $enter = 0;
      $exit  = 0;
      for($k=0;$k<count($asset_geofences);$k++){
        if($asset_geofences[$k]['id_geofence']==$geofences[$i]['id']){
          $enter = $asset_geofences[$k]['gf_enter'];
        $exit = $asset_geofences[$k]['gf_exit'];
        }
      }
      $enter_chked = '';
      $exit_chked = '';
      if($enter == 1){ $enter_chked = 'checked'; }
      if($exit == 1){ $exit_chked = 'checked'; }

        echo '<tr id="gf_'.$geofences[$i]['id'].'">
            <td><img src="'.$data['preview'].'" style=" max-width:180px; border:#ccc solid 1px;"/></td>
      <td>'.$geofences[$i]['name'].' <br / ><strong></strong></td>
            <td><input id="chk_geozona_entrada[]" name="chk_geozona_entrada[]" type="checkbox" value="'.$geofences[$i]['id'].'" class="enter onChkGeoFence" '.$enter_chked.'/>Entrada</td>
            <td><input id="chk_geozona_salida[]" name="chk_geozona_salida[]" type="checkbox" value="'.$geofences[$i]['id'].'" class="exit onChkGeoFence" '.$exit_chked.'/>Salida</td>
      </tr>';
      }
      ?>
          </tbody>
        </table>
        </div>

    </div>
    <style>
  a.btn_save_alarm { background-color: #333;
color: #fff;
width: 90px;
text-align: center;
margin: 5px;
display: inline-block;
position: absolute;
right: 12px;
height: 21px;
margin-top: 2px;
padding-top: 4px;
border-radius: 2px; }
.btn_save_alarm:hover { background-color:#000; }
  </style>
  <div class="tab_alarm_set isTabContent">
    <div align="right"><a href="javascript:void(0)" class="btn_save_alarm onClickSaveAlarm">Guardar</a></div>
    <div class="datagrid">
    <form id="frm_alarm" name="frm_alarm">
    <table width="100%" cellpadding="0" cellspacing="0" border="0">
      <thead>
        <tr>
          <th align="left" width="20">Activar</th>
          <th align="left" width="150">Alarma</th>
          <th align="left" width="50">Valor</th>
          <th align="left" width="100">Correo de Notificaci&oacute;n</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td><input id="chk_alarm[]" name="chk_alarm[]" type="checkbox" value="speed_max" /></td>
          <td>Velocidad M&aacute;xima</td>
          <td><input type="text" id="txt_valor[]" name="txt_valor[]" style="width:40px; border:#ccc solid 1px; padding:5px;" placeholder="0 km"/></td>
          <td><input type="text" id="txt_correo[]" name="txt_correo[]" style="width:400px; border:#ccc solid 1px; padding:5px;" value="<?php echo $client_info[1]['value']; ?>" /></td>
        </tr>
        <tr>
          <td><input id="chk_alarm[]" name="chk_alarm[]" type="checkbox" value="speed_min" /></td>
          <td>Velocidad M&iacute;nima</td>
          <td><input type="text" id="txt_valor[]" name="txt_valor[]" style="width:40px; border:#ccc solid 1px; padding:5px;" placeholder="0 km"/></td>
          <td><input type="text" id="txt_correo[]" name="txt_correo[]" style="width:400px; border:#ccc solid 1px; padding:5px;" value="<?php echo $client_info[1]['value']; ?>" /></td>
        </tr>
         <tr>
          <td><input id="chk_alarm[]" name="chk_alarm[]" type="checkbox" value="temp_max" /></td>
          <td>Temperatura M&aacute;xima</td>
          <td><input type="text" id="txt_valor[]" name="txt_valor[]" style="width:40px; border:#ccc solid 1px; padding:5px;" placeholder="0 F&deg;"/></td>
          <td><input type="text" id="txt_correo[]" name="txt_correo[]" style="width:400px; border:#ccc solid 1px; padding:5px;" value="<?php echo $client_info[1]['value']; ?>" /></td>
        </tr>
        <tr>
          <td><input id="chk_alarm[]" name="chk_alarm[]" type="checkbox" value="temp_min" /></td>
          <td>Temperatura M&iacute;nima</td>
          <td><input type="text" id="txt_valor[]" name="txt_valor[]" style="width:40px; border:#ccc solid 1px; padding:5px;" placeholder="0 F&deg;"/></td>
          <td><input type="text" id="txt_correo[]" name="txt_correo[]" style="width:400px; border:#ccc solid 1px; padding:5px;" value="<?php echo $client_info[1]['value']; ?>" /></td>
        </tr>
        <tr>
          <td><input id="chk_alarm[]" name="chk_alarm[]" type="checkbox" value="fuel_max" /></td>
          <td>Combustible M&iacute;nimo</td>
          <td><input type="text" id="txt_valor[]" name="txt_valor[]" style="width:40px; border:#ccc solid 1px; padding:5px;" placeholder="0 L"/></td>
          <td><input type="text" id="txt_correo[]" name="txt_correo[]" style="width:400px; border:#ccc solid 1px; padding:5px;" value="<?php echo $client_info[1]['value']; ?>" /></td>
        </tr>
        <tr>
          <td><input id="chk_alarm[]" name="chk_alarm[]" type="checkbox" value="fuel_min" /></td>
          <td>Combustible M&aacute;ximo</td>
          <td><input type="text" id="txt_valor[]" name="txt_valor[]" style="width:40px; border:#ccc solid 1px; padding:5px;" placeholder="0 L"/></td>
          <td><input type="text" id="txt_correo[]" name="txt_correo[]" style="width:400px; border:#ccc solid 1px; padding:5px;" value="<?php echo $client_info[1]['value']; ?>" /></td>
        </tr>
        <tr>
          <td><input id="chk_alarm[]" name="chk_alarm[]" type="checkbox" value="ignition_on" /></td>
          <td>Motor Encendido</td>
          <td></td>
          <td><input type="text" id="txt_correo[]" name="txt_correo[]" style="width:400px; border:#ccc solid 1px; padding:5px;" value="<?php echo $client_info[1]['value']; ?>" /></td>
        </tr>
        <tr>
          <td><input id="chk_alarm[]" name="chk_alarm[]" type="checkbox" value="ignition_off" /></td>
          <td>Motor Apagado</td>
          <td></td>
          <td><input type="text" id="txt_correo[]" name="txt_correo[]" style="width:400px; border:#ccc solid 1px; padding:5px;" value="<?php echo $client_info[1]['value']; ?>" /></td>
        </tr>
        <tr>
          <td><input id="chk_alarm[]" name="chk_alarm[]" type="checkbox" value="ignition_off" /></td>
          <td>Panico</td>
          <td></td>
          <td><input type="text" id="txt_correo[]" name="txt_correo[]" style="width:400px; border:#ccc solid 1px; padding:5px;" value="<?php echo $client_info[1]['value']; ?>" /></td>
        </tr>

      </tbody>
    </table>
    </form>
    </div>
  </div>




      <div class="tab_georoutes isTabContent">

    <div class="datagrid">
    <table width="100%" cellpadding="0" cellspacing="0" border="0">
          <thead>
          <tr>
            <th align="left" width="150">Georuta</th>
            <th align="left" width="150">Distancia Total (km)</th>
            <th align="left" width="150">Tiempo Total (m)</th>
            <th align="left" width="150"></th>
          </tr>
          </thead>
          <tbody>
          <?php
      $georoute = $objClient->set_id_client($_SESSION['logged']['id_client'])->getClientGeoroute();
      for($i=0;$i<count($georoute);$i++){
      $data = json_decode($georoute[$i]['data'],true);
        echo '<tr id="georoute_'.$georoute[$i]['id'].'">
      <td>'.$georoute[$i]['name'].'</td>
            <td>'.$georoute[$i]['dist'].' km</td>
      <td>'.$georoute[$i]['time'].' m</td>
            <td><div  style=""><input id="chk_geo_route[]" name="chk_geo_route[]" type="checkbox" value="'.$georoute[$i]['id'].'" class=""/>Activar</div></td>
      </tr>';
      }
      ?>
          </tbody>
        </table>
        </div>

    </div>




    <div class="tab_vista_calle isTabContent">

   <iframe width="880" height="400" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://www.google.com/maps?f=q&source=embed&hl=es-419&geocode=&q=<?php echo $gprs_data[0]['lat']; ?>,<?php echo $gprs_data[0]['lng']; ?>&aq=&sll=<?php echo $gprs_data[0]['lat']; ?>,<?php echo $gprs_data[0]['lng']; ?>&sspn=0.068119,0.097761&ie=UTF8&t=m&layer=c&cbll=<?php echo $gprs_data[0]['lat']; ?>,<?php echo $gprs_data[0]['lng']; ?>&ll=<?php echo $gprs_data[0]['lat']; ?>,<?php echo $gprs_data[0]['lng']; ?>&spn=0.024282,0.048237&z=14&output=svembed"></iframe>
 </div>



 <div class="tab_fuel isTabContent">
<script type="text/javascript">
<?php
  $speed_values = '';
  $fuel_a_values = '';
  $datetime = '';
 for($i=0;$i<count($gprs_data);$i++){
  $speed = 0;
  if($gprs_data[$i]['gps_speed']=='000'){ $speed = '0'; }else{ $speed = intval($gprs_data[$i]['gps_speed']); }
  if($temp<=0){ $temp = 0; }
  $speed_values .= $speed.',';
  $datetime .= "'".substr($gprs_data[$i]['date'],11,5)."',";

}
?>
$(function () {

  $('#chart_fuel').highcharts({
  chart: { type: 'line', width: 2000 },
  title: { text: 'Reporte de Combustible' },
  subtitle: { text: '<?php echo $asset_info[1]['value']; ?>' },
            xAxis: { categories: [<?php echo $datetime; ?>],labels: { rotation: -90, style: { fontSize: '10px', fontFamily: 'Arial, sans-serif' } } },
            yAxis: { title: { text: 'Unidades' }, labels: { style: { fontSize: '10px', fontFamily: 'Arial, sans-serif' } } },
      scrollbar: { enabled: true },
            plotOptions: {
                line: {
                    dataLabels: { enabled: false },
                    enableMouseTracking: true
                }
            },
            series: [{ name: 'Velocidad', data: [<?php echo $speed_values ?>], color: '#d03018'},
           { name: 'Combustible 1', data: [], color: '#68bf1a'},
           { name: 'Combustible 2', data: [], color: '#ffa800'},
           { name: 'Combustible 3', data: [], color: '#8612e9'},
           { name: 'Temperatura', data: [], color: '#00a7ec'}
          ]
        });


});
</script>
  <style>
  #filters { background-color:#e4e4e4; padding:5px; }
   #filters ul li { float:left;}
  </style>
  <div id="filters">
  <ul>
    <li><a href="javascript:void(0)" class="onClickAceptar">Aceptar</a></li>
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
        <?php if(isset($sensor['temp']) && $sensor['temp']!=NULL){ ?><li><input type="checkbox" id="chk_temp" name="chk_temp" class="onSeriesChange" value="3" checked/><label>Temperatura</label></li><?php } ?>
        <?php if(isset($sensor['fuel_a']) && $sensor['fuel_a']!=NULL){ ?><li><li><input type="checkbox" id="chk_fuel" name="chk_fuel" class="onSeriesChange" value="1" checked/><label>Combustible 1</label></li><?php } ?>
        <?php if(isset($sensor['fuel_b']) && $sensor['fuel_b']!=NULL){ ?><li><li><input type="checkbox" id="chk_fuel" name="chk_fuel" class="onSeriesChange" value="2" checked/><label>Combustible 2</label></li><?php } ?>
        <?php if(isset($sensor['fuel_c']) && $sensor['fuel_c']!=NULL){ ?><li><li><input type="checkbox" id="chk_fuel" name="chk_fuel" class="onSeriesChange" value="3" checked/><label>Combustible 3</label></li><?php } ?>
        <li><input type="checkbox" id="chk_speed" name="chk_speed" checked class="onSeriesChange" value="0"/><label>Velocidad</label></li>
            </ul>
            <br class="clear">
  </div>
  <div class="container">
    <div id="chart_fuel" style=" width:100%; height:345px;"></div>
  </div>
</div>

  </div>

</div>

<script>
var stemp = <?php if(isset($sensor['temp']) && $sensor['temp']!=NULL){ echo 'true'; }else{ echo 'false'; }?>;
var sfuel_a = <?php if(isset($sensor['fuel_a']) && $sensor['fuel_a']!=NULL){ echo 'true'; }else{ echo 'false'; }?>;
var sfuel_b = <?php if(isset($sensor['fuel_b']) && $sensor['fuel_b']!=NULL){ echo 'true'; }else{ echo 'false'; }?>;
var sfuel_c = <?php if(isset($sensor['fuel_c']) && $sensor['fuel_c']!=NULL){ echo 'true'; }else{ echo 'false'; }?>;
$(document).ready(function(){



  $('.onClickIconSelector').live('click',function(){ $('.icons_container').slideToggle(); });
   $('.onClickOpenOverlay').click(function(){
    var id = $(this).attr("rel");
    $('#modal').attr("href","mod.panel_info.php?id="+id).click();
  });

  $('.onClickIcon').live("click",function(){
   var icon = $(this).attr("rel");
   $('.unit_icon').children().attr("src","_icons/"+icon);
   $.post('_ctrl/ctrl.unidad.php', { imei: null,icon : icon, exec: "update_icon" },
      function(data){
      });
  });

  $('.onClickSaveAlarm').live("click",function(){
    return false;
    $.post('../_ctrl/ctrl.client.php', { imei: <?php echo $asset[0]['imei']; ?>, exec: "update_alarm", data: $("#frm_alarm").serializeArray() },
      function(data){
      });
  });

  $('.onClickIngitionOff').live("click",function(){
    if($("#txt_pass").val()!="0"){ alert("codigo invalido"); return false; }
    if(confirm("Confirme que desea apagar motor ?")){
    $.post('_ctrl/ctrl.terminal.php', { imei: data[0], exec: "010" },
      function(data){
      });
  }
  });


});


$(document).ready(function(){

  $(window).trigger('resize');

  chart = $("#chart_fuel").highcharts();
    chart.yAxis[0].isDirty = true;


   $('.isDateTo').datepicker({
    altFormat: 'yy-mm-dd',
    altField: "#date_to",
    dateFormat: 'd\'-\'MM\'-\'yy',
    changeMonth: true,
    changeYear: true,
    yearRange: '1981:2020',
    monthNamesShort: ['ENE','FEB','MAR','ABR','MAY','JUN','JUL','AGO','SEP','OCT','NOV','DIC'],
    monthNames: ['ENE','FEB','MAR','ABR','MAY','JUN','JUL','AGO','SEP','OCT','NOV','DIC'],
    dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
    dayNames: ['Domingo', 'Lunes', 'Martes', 'MiÃ©rcoles', 'Jueves', 'Viernes', 'SÃ¡bado']
    });

 $('.isDateFrom').datepicker({
    altFormat: 'yy-mm-dd',
    altField: "#date_from",
    dateFormat: 'd\'-\'MM\'-\'yy',
    changeMonth: true,
    changeYear: true,
    yearRange: '1981:2020',
    monthNamesShort: ['ENE','FEB','MAR','ABR','MAY','JUN','JUL','AGO','SEP','OCT','NOV','DIC'],
    monthNames: ['ENE','FEB','MAR','ABR','MAY','JUN','JUL','AGO','SEP','OCT','NOV','DIC'],
    dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
    dayNames: ['Domingo', 'Lunes', 'Martes', 'MiÃ©rcoles', 'Jueves', 'Viernes', 'SÃ¡bado']
  });

  $('.onClickAceptar').click(function(){ get_charts(); });

  });

  $('.onSeriesChange').change(function(){
    var series = null;
   if(!$(this).is(":checked")){
     series = chart.series[parseInt($(this).val())];
   series.hide();
   }else{
   series = chart.series[parseInt($(this).val())];
   series.show();
   }

  });




  function get_charts(){
  chart = $("#chart_fuel").highcharts();
  var s = $("#date_from").val() +" "+ $("#hour_from").val();
    var e = $("#date_to").val() +" "+ $("#hour_to").val();
    $.post('_json/json.charts.php', { imei: <?php echo $asset[0]['imei']; ?>, sdate : s, edate: e },
      function(r){
    //
    console.log(r);
    var time = r.time.split(",");
    var speed = r.speed.split(",");
    var fuel_a = r.fuel_a.split(",");
    var fuel_b = r.fuel_b.split(",");
    var fuel_c = r.fuel_c.split(",");
    var temp = r.temp.split(",");
    time.pop();
    speed.pop();
    fuel_a.pop();
    fuel_b.pop();
    temp.pop();
    for(var i=0;i<speed.length;i++){ speed[i] = parseInt(speed[i]); }
    for(var i=0;i<fuel_a.length;i++){ fuel_a[i] = parseInt(fuel_a[i]); }
    for(var i=0;i<fuel_b.length;i++){ fuel_b[i] = parseFloat(fuel_b[i]); }
    for(var i=0;i<fuel_c.length;i++){ fuel_c[i] = parseFloat(fuel_c[i]); }
    for(var i=0;i<temp.length;i++){ temp[i] = parseFloat(temp[i]); }

    chart.series[0].update({ data: speed },true);
    if(fuel_a.length > 0){ chart.series[1].update({ data: fuel_a },true); }
    if(fuel_b.length > 0){ chart.series[2].update({ data: fuel_b },true); }
    if(fuel_c.length > 0){ chart.series[3].update({ data: fuel_c },true); }
    if(temp.length > 0){ chart.series[4].update({ data: temp },true); }
    if(!stemp){ chart.series[4].hide(); }

    if(!sfuel_a){ chart.series[1].hide(); }
    if(!sfuel_b){ chart.series[2].hide(); }
    if(!sfuel_c){ chart.series[3].hide(); }

    chart.xAxis[0].update({categories: time},true);
      },"json");
  }

  function get_chart(){

  var s = $("#date_from").val() +" "+ $("#hour_from").val();
  var e = $("#date_to").val() +" "+ $("#hour_to").val();

  $.post('_json/json.fuel.php', { imei: <?php echo $asset[0]['imei']; ?>, sdate : s, edate: e },
      function(r){
      var array = r['series'].split(',');
      var values = r['values'].split(',');
      chart = $("#chart_fuel").highcharts();
      chart.xAxis[0].update({categories: array});
      chart.series.update({data: values});
      },"json");


  }
</script>
<script src="_lib/highcharts/highcharts.js"></script>

</body>

</html>
