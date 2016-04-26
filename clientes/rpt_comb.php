<?php
session_start();
if(!isset($_SESSION['logged'])) { header('Location: login.php?s=401'); }
/*require_once("../_class/class.client.php");
require_once("../_class/class.asset.php");
require_once("../_class/class.gprs.php");

 
$objAsset = new Asset();
$objGPRS = new GPRS();
$objClient = new Client();


Array
(
    [onUserSession] => Array
        (
            [id_client] => 
            [id_user] => 1
            [type] => 1
            [permissions] => Array
                (
                    [cliente_vis] => on
                    [cliente_cre] => on
                    [cliente_edi] => on
                    [cliente_eli] => on
                    [cliente_cla] => 
                    [cliente_pla] => on
                    [cliente_inf_edi] => on
                    [cliente_inf_cla] => 
                    [cliente_uni_cre] => on
                    [cliente_uni_edi] => on
                    [cliente_uni_eli] => on
                    [cliente_uni_cla] => 
                    [catalogo_vis] => on
                    [catalogo_eq_vis] => on
                    [catalogo_eq_cre] => on
                    [catalogo_eq_edi] => on
                    [catalogo_eq_eli] => on
                    [catalogo_eq_cla] => 
                    [catalogo_staff_vis] => on
                    [catalogo_staff_cre] => on
                    [catalogo_staff_edi] => on
                    [catalogo_staff_eli] => on
                    [catalogo_staff_cla] => 
                    [catalogo_imei_vis] => on
                    [catalogo_imei_cre] => on
                    [catalogo_imei_edi] => on
                    [catalogo_imei_eli] => on
                    [catalogo_imei_cla] => 
                    [catalogo_num_vis] => on
                    [catalogo_num_cre] => on
                    [catalogo_num_edi] => on
                    [catalogo_num_eli] => on
                    [catalogo_num_cla] => 
                    [reportes_vis] => on
                    [reportes_gprs_vis] => on
                    [reportes_gprs_res] => on
                    [reportes_gen_vis] => on
                    [papelera_vis] => on
                )

        )

    [logged] => Array
        (
            [user] => 
            [id_user] => 114
            [id_client] => 36
            [type] => 1
            [temp] => c
        )

)
*/
error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once('_firephp/FirePHP.class.php');
ob_start();
 $mifirePHP = FirePHP::getInstance(true);

?>
<!DOCTYPE html>
<html>
<head>
<title>Norttrek - GPS</title>
<script src="//code.jquery.com/jquery-1.8.3.js"></script>
<script src="/clientes/_js/jquery.visible.min.js"></script>
<script src="/clientes/_js/jquery.doubleScroll.js"></script>

<script src="_lib/highcharts/highcharts.js"></script>
<script src="/clientes/_js/chosen.jquery.min.js"></script>
<script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script> 
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false"></script>
<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,600,300&subset=latin' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="_lib/fawesome/css/font-awesome.min.css">
<link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
<link rel="stylesheet" href="_css/fonts.css">
<link rel="stylesheet" href="_css/reports.css">
<link rel="stylesheet" href="_css/chosen.css">
</head>
<div class="loader"  style="display:none"><img src="/clientes/_img/loader.gif"></div>
<div id="overlay"></div>
<?php

require_once("../_class/class.client.php");
require_once("../_class/class.asset.php");
require_once("../_class/class.gprs.php");
require_once("../_functions/functions_reports.php");

$objAsset = new Asset();
$objGPRS = new GPRS();
$objClient = new Client();

$imei = $_GET['imei'];
$sdate = $_GET['sdate'];
$edate = $_GET['edate'];

$sGet = explode(" ", $sdate);
$sGet = $sGet[0];

$fGet = explode(" ", $edate);
$fGet = $fGet[0];


 
$id_user = NULL;
$id_user = $_SESSION['logged']['id_client'];
 
$fields=array('id','alias','id_group','imei','sensor');
$all_assets =$objAsset-> getAssetsFieldsByUser($id_user,$fields);

 
$all_assets_groups = $objAsset->set_order('client_group.group ASC')->getAssetGroups($id_user);
 



// aad Agosto 2015


$objAsset = new Asset();
$asset = $objAsset->getAssetByIMEI($imei);

$fuel_calib = json_decode($asset[0]['fuel'],true);

$tank1 = $fuel_calib['t1'];
$tank2 = $fuel_calib['t2'];
$tank3 = $fuel_calib['t3'];

$fsensors = json_decode($asset[0]['sensor'],true);
 
$fuel_a_sensor = $fsensors['fuel_a'];
$fuel_b_sensor = $fsensors['fuel_b'];
$fuel_c_sensor = $fsensors['fuel_c'];

$temp1_sensor = $fsensors['temp'];
$temp2_sensor = $fsensors['temp2'];

$formulaTruck = $fsensors['formula'];
 

$asset_cinfo = json_decode($asset[0]['info'],true);
$objGPRS = new GPRS();

//$route = $objGPRS->set_status("V")->set_between("'".$sdate."' AND '".$edate."'")->getAssetRoute($imei);

// route optimizada
if($formulaTruck==2){
  $fields_route = array('id','date','gps_speed','lat','lng','v2_fuel1','v2_fuel2','v2_fuel3','iostatus','v2_temp1','temp','v2_eng_status');
}elseif ($formulaTruck==1) {
  $fields_route = array('id','date','gps_speed','lat','lng','v2_fuel1','v2_fuel2','v2_fuel3','iostatus','v2_temp1','temp','v2_eng_status');
}

$route = $objGPRS->set_status("V")->set_between("'".$sdate."' AND '".$edate."'")->getAssetRouteOpt($imei,$fields_route);
 
 
$odometer = $asset_cinfo['odometro'];
$odometer += number_format($objAsset->getOdometer($imei,$sdate),2);

// aad test
 
$data = get_tank_fuel_report($asset, $route, $tank1, $tank2, $tank3, $imei,$formulaTruck);
$rpt =$data;
$rpt = array_reverse($rpt);
$mifirePHP->log($rpt,'rutalista');
//print_r($d_test_tt);
//end;
// FIN aad test

$detected = array();
$ttemp;
$tfuel;

$buffer_fecha;
$buffer_vel;
$buffer_t1;
$buffer_t2;
$buffer_t3;

// Para generar strings JSON de la Grafica
for($i=0;$i<count($rpt);$i++){
  $newF = explode(' ', $rpt[$i]['date']);
  $newH = explode(' ', $rpt[$i]['date']);
  $newF = $newF[0]; 
  $hora = $newH[1];
 $dia = $newH[0]; 
  $newF = explode('-', $newF);
  $newF = $newF[2];
  $buffer_fecha .= "'".$i."',";
  $buffer_vel .= "{ y: ".$rpt[$i]['km'].", custom : '".$rpt[$i]['km']." km/h <br> Hora: ". $hora ."'   ,scrollto:'".$i."'},";

  //Motor
  if($rpt[$i]['ignition'] ==1){
    $mifirePHP->log($rpt[$i]['ignition'],'como va');
    $rpt[$i]['ignition_graph'] = 10;
    $mensaje = 'Encendido';
    $buffer_encendido .= "{ y: ".$rpt[$i]['ignition_graph'].", custom : '".$mensaje. " ' ,color:'green' },";
   }elseif ($rpt[$i]['ignition'] == 0) {
    $rpt[$i]['ignition_graph'] = 10;
    $mensaje = "Apagado";
    $buffer_encendido .= "{ y: ".$rpt[$i]['ignition_graph'].", custom : '".$mensaje. " ' ,color:'grey' },";
   }



  // TANK 1
  if($rpt[$i]['t1col_f']!=0){
    $icon = 'up.png';
    $custom = $rpt[$i]['t1_chart'];
    if($rpt[$i]['t1col_f']<0){
      $icon = 'down.png';
    }
    if($rpt[$i]['t1']>=0){ 

        $buffer_t1 .= "{ y: ".$rpt[$i]['t1'].", marker: { symbol: 'url(".$icon.")' }, url: 'http://maps.google.com/?q=".$rpt[$i]['lat'].",".$rpt[$i]['lng']."', custom : '".$custom."' },";
    }
  }else{

     if($rpt[$i]['t1'] >=0){ 
        $buffer_t1 .= "{ y: ".$rpt[$i]['t1'].", custom : '".$rpt[$i]['t1']." Lts <br>Dia: ".$dia."<br>Hora: ". $hora ." <br>Motor: ".$mensaje."'  ,scrollto:'".$i."'},";
    }
  }
  // FIN TANK 1

  // TANK 2
  if($rpt[$i]['t2col_f']!=0){
    $icon = 'up.png';
    $custom = $rpt[$i]['t2_chart'];
    if($rpt[$i]['t2col_f']<0){
      $icon = 'down.png';
    }
    if($rpt[$i]['t2'] >=0){ 
    $buffer_t2 .= "{ y: ".$rpt[$i]['t2'].", marker: { symbol: 'url(".$icon.")' }, url: 'http://maps.google.com/?q=".$rpt[$i]['lat'].",".$rpt[$i]['lng']."', custom : '".$custom."' },";
    }
  }else{
    if($rpt[$i]['t2'] >=0){ 
    $buffer_t2 .= "{ y: ".$rpt[$i]['t2'].", custom : '".$rpt[$i]['t2']." Lts <br>Dia: ".$dia."<br>Hora: ". $hora ." <br>Motor: ".$mensaje."'  ,scrollto:'".$i."'},";
   }
  }
  // FIN TANK 2

  // TANK 3
  if($rpt[$i]['t3col_f']!=0){
    $icon = 'up.png';
    $custom = $rpt[$i]['t3_chart'];
    if($rpt[$i]['t3col_f']<0){
      $icon = 'down.png';
    }
    if($rpt[$i]['t3'] >=0){ 
    $buffer_t3 .= "{ y: ".$rpt[$i]['t3'].", marker: { symbol: 'url(".$icon.")' }, url: 'http://maps.google.com/?q=".$rpt[$i]['lat'].",".$rpt[$i]['lng']."', custom : '".$custom."' },";
    }
  }else{
    if($rpt[$i]['t3'] >=0){ 
      $buffer_t3 .= "{ y: ".$rpt[$i]['t3'].", custom : '".$rpt[$i]['t3']." Lts <br>Dia: ".$dia."<br>Hora: ". $hora ." <br>Motor: ".$mensaje."'  ,scrollto:'".$i."' },";
    }
  }
  // FIN TANK 3

  // TANK TOTAL
  if($rpt[$i]['ttcol_f']!=0){
    $icon = 'up.png';
    $custom = $rpt[$i]['tt_chart'];
    if($rpt[$i]['ttcol_f']<0){
      $icon = 'down.png';
    }

    $buffer_tt .= "{ y: ".$rpt[$i]['tt'].", marker: { symbol: 'url(".$icon.")' }, url: 'http://maps.google.com/?q=".$rpt[$i]['lat'].",".$rpt[$i]['lng']."', custom : '".$custom."' },";
  }else{
    $buffer_tt .= "{ y: ".$rpt[$i]['tt'].", custom : '".$rpt[$i]['tt']." Lts <br>Dia: ".$dia."<br>Hora: ". $hora ." <br>Motor: ".$mensaje."' ,scrollto:'".$i."'},";

  }
  // FIN TANK TOTAL


  //TEMPERATURA
  $tunit = 'C&deg';
if($_SESSION['logged']['temp']=="f"){ $tunit = 'F&deg'; }
   $buffer_temp .= "{ y: ".$rpt[$i]['temp_1'].", custom : '".$rpt[$i]['temp_1']. " ".$tunit ." <br>Dia: ".$dia."<br>Hora: ". $hora ." <br>Motor: ".$mensaje."'   ,scrollto:'".$i."'},";

   


  //TEMPERATURA 2
  $tunit = 'C&deg';
if($_SESSION['logged']['temp']=="f"){ $tunit = 'F&deg'; }
   $buffer_temp_2 .= "{ y: ".$rpt[$i]['temp_2'].", custom : '".$rpt[$i]['temp_2']. " ".$tunit ." <br>Dia: ".$dia."<br>Hora: ". $hora ." <br>Motor: ".$mensaje."'  ,scrollto:'".$i."'},";

   
}

$buffer_fecha = substr_replace($buffer_fecha ,"",-1);
$buffer_vel = substr_replace($buffer_vel ,"",-1);
$buffer_t1 = substr_replace($buffer_t1 ,"",-1);
$buffer_t2 = substr_replace($buffer_t2 ,"",-1);
$buffer_t3 = substr_replace($buffer_t3 ,"",-1);
$buffer_tt = substr_replace($buffer_tt ,"",-1);

?>
<body OnScroll="scrolling()"> 
<div id="header">
  <div class="divsHeader">
    <img class="logo" src="_img/logo.png" width="175" height="56" style="margin-left:10px;">
  </div>
  <div class="divsHeader">
    <span id="titleReport">Unidad: <?php echo $asset[0]['alias'] ?><br>
      <span style="font-size:12px;">Fecha Inicial: <?php echo $sdate  ?><br>
      Fecha Final: <?php  echo $edate ?>
      </span>
    </span>
  </div>
  <div class="divsHeader" style="float:right !important">
    <span style="color:white">Reporte de combustible</span><br>
<select  id="lst_fuelrpt_imei" name="lst_fuelrpt_imei" data-placeholder="Choose a Country..." class="chosen-select"  >
<?php 
 foreach($all_assets_groups as $key => $group){
    echo "<optgroup label='".$group['group']."'>";
    foreach ($all_assets as $key => $asset) {
      if($group['id'] == $asset['id_group']){
        $fuelrpt = json_decode($asset['sensor'],true);
        $formula = NULL;
        if(!isset($fuelrpt['formula'])){ $formula = 1; }else{ $formula = $fuelrpt['formula']; }
        if($imei == $asset['imei']){
          echo "<option value='" .$asset['imei'].'|'.$formula."'  selected>".$asset['alias'] ."</option>";
       }else{
          echo "<option value='" .$asset['imei'].'|'.$formula."' >".$asset['alias'] ."</option>";
       }
      }
    } 
    echo "</optgroup>";
  }
?>
</select>
   <ul id="lst_date">
              <li>
             <input type="text" id="txt_fdate_from" name="txt_fdate_from" class="isFDateFrom" value="<?php echo $objAsset->formatDate($sGet,"min"); ?>">
             <input id="fdate_from" name="fdate_from" type="hidden" value="<?php echo date("Y-m-d"); ?>">
             <select id="fhour_from" name="fhour_from">
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
          <input type="text" id="txt_fdate_to" name="txt_fdate_to" class="isFDateTo" value="<?php echo $objAsset->formatDate($fGet,"min"); ?>">
          <input id="fdate_to" name="fdate_to" type="hidden" value="<?php echo date("Y-m-d"); ?>">
          <select id="fhour_to" name="fhour_to">
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
             <button type="submit" id="sendReport" >Aceptar</button>


  </div>
  <div style="clear:both"></div>



 
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
  
  </script>        
           

         
</div> 
<div class="chart">
  <div id="grafs">
    <?php 
      $tanks = getTanksSensor($fsensors);
      $temps = getTempSensor($fsensors);
   
      if($tanks == 1 AND $temps == 1){
        ?>
        <div id="graph1" id="tanksG"> Tanques </div>
        <div  id="graph3"  id="tempsG" class="activeGraphsButtons">Temperatura</div>
        <?php
      }
       if($tanks == 1 AND $temps == 0){
        ?>
        <div id="graph1" id="tanksG"> Tanques </div> 
        <?php
      }
      if($tanks > 1 AND $temps == 1){
        ?>
        <div  id="graph2"  id="tanksTotalG">Combustible Total</div>
        <div id="graph1" id="tanksG" class="activeGraphsButtons"> Tanques </div>
        <div  id="graph3"  id="tempsG" class="activeGraphsButtons">Temperatura</div>
        <?php 
      }
      if($tanks > 1 AND $temps == 0){
        ?>
        <div  id="graph2"  id="tanksTotalG">Combustible Total</div>
        <div id="graph1" id="tanksG" class="activeGraphsButtons"> Tanques </div>
        <?php 
      }
      if($tanks == 0 AND $temps >= 1){
        ?>
        <div  id="graph3"  id="tempsG">Temperatura</div>
        <script type="text/javascript">

        </script>
        <?php 
      }
      
    ?> 
  </div>
  <div class="scrollBars" style=" width:90%; float:left">
    <div style="width:<?php echo count($rpt)*15; ?>px;">
      <div id="chart-A_t_D" class="titleChartD">Combustible Total</div>
      <div id="chart-A_t" class="titleChart">Combustible Total</div>
      <div id="chart-A" class="chart" ></div>


     <div id="detail_t_D" class="titleChartD">Tanques</div>
     <div id="detail_t" class="titleChart">Tanques</div>
     <div id="detail"  class="chart"></div>


     <div id="chart-B_t_D" class="titleChartD">Temperatura</div>
     <div id="chart-B_t" class="titleChart">Temperatura</div>
     <div id="chart-B" class="chart" ></div>
    <div style="clear:both" ></div>
    </div>
  </div>

  <?php
if($tanks == 1 AND $temps == 1){
        ?>
        <script type="text/javascript">
        $('#detail').show('slow');
        $('#detail_t').show('slow');
        $('#detail_t_D').show('slow');

        $('#chart-A').hide('slow');
        $('#chart-A_t').hide('slow');
        $('#chart-A_t_D').hide('slow');

        $('#chart-B').hide('slow');
        $('#chart-B_t').hide('slow');
        $('#chart-B_t_D').hide('slow');
        </script>
        <?php
      }

      if($tanks == 1 AND $temps == 0){
        ?>
        <script type="text/javascript">
        
        $('#detail').show('slow');
        $('#detail_t').show('slow');
        $('#detail_t_D').show('slow');

        $('#chart-A').hide('slow');
        $('#chart-A_t').hide('slow');
        $('#chart-A_D').hide('slow');

        $('#chart-B').hide('slow');
        $('#chart-B_t').hide('slow');
        $('#chart-B_D').hide('slow');
        </script>
        <?php
      }


      if($tanks > 1 AND $temps == 1){
        ?>
        <script type="text/javascript">
      
        $('#chart-B').hide('slow');
        $('#chart-B_t').hide('slow');
        $('#chart-B_t_D').hide('slow');

        $('#detail').hide('slow');
        $('#detail_t').hide('slow');
        $('#detail_t_D').hide('slow');

        $('#chart-A').show('slow');
        $('#chart-A_t').show('slow');
        $('#chart-A_D').show('slow');
       
        </script>
        <?php 
      }
      if($tanks > 1 AND $temps == 0){
        ?>
        <script type="text/javascript">
 
        $('#detail').hide('slow');
        $('#detail_t').hide('slow');
        $('#detail_t_D').hide('slow'); 

        $('#chart-A').show('slow');
        $('#chart-A_t').show('slow');
        $('#chart-A_t_D').show('slow');

        $('#chart-B').hide('slow');
        $('#chart-B_t').hide('slow');
        $('#chart-B_t_D').hide('slow');

        </script>
        <?php 
      }
      if($tanks == 0 AND $temps >= 1){
        ?>
        <script type="text/javascript">
       
        $('#detail').hide('slow');
        $('#detail_t').hide('slow');
        $('#detail_t_D').hide('slow');
 
        $('#chart-A').hide('slow');
        $('#chart-A_t').hide('slow');
        $('#chart-A_t_D').hide('slow');
 
        $('#chart-B').show('slow');
        $('#chart-B_t').show('slow');
        $('#chart-B_t_D').show('slow');
        </script>
        <?php 
      }
  ?>

<script>
 $('#graph1').click(function(){
    $('#detail').slideToggle('slow');
    $('#detail_t').slideToggle('slow')
    $('#detail_t_D').slideToggle('slow')
    $(this).toggleClass( "activeGraphsButtons" )
 });
 $('#graph2').click(function(){
    $('#chart-A').slideToggle('slow')
    $('#chart-A_t').slideToggle('slow')
    $('#chart-A_t_D').slideToggle('slow')
    $(this).toggleClass( "activeGraphsButtons" )
 });
 $('#graph3').click(function(){
    $('#chart-B').slideToggle('slow')
    $('#chart-B_t').slideToggle('slow')
    $('#chart-B_t_D').slideToggle('slow')
    $(this).toggleClass( "activeGraphsButtons" )
 });
$('#chart-A').highcharts({
        chart: {
           height: 100,
            zoomType: 'x',
          width: <?php echo count($rpt)*15; ?>,
         renderTo: 'detail',
          type: 'line'  
        },
       title: {
    text: '',
    style: {
        display: 'none'
    }
},
subtitle: {
    text: '',
    style: {
        display: 'none'
    }
},
        xAxis: {
             categories: [<?php echo $buffer_fecha; ?>],
      labels: { enabled : true,  style: { fontSize: '10px',fontFamily: 'Arial, sans-serif' } },
        },
        yAxis: {
           title: { text: '' },
            plotLines: [{
                value: 0,
                width: 1,
                color: '#808080'
            }] , labels: { style: { fontSize: '10px', fontFamily: 'Arial, sans-serif' } }
        },
        
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle',
            borderWidth: 0
        },
     tooltip : {
            formatter: function() {
                var tooltip;
                tooltip =  this.point.custom;
                return tooltip;
            }
        }
    ,
    plotOptions: {

            series: {
              turboThreshold:10000,
                cursor: 'pointer',
                point: {
                    events: { click: function(){ 
                      if(this.options.url!=null){ 
                        window.open(this.options.url); 
                      } else{
                            
                            var ancla = this.options.scrollto -10;
                            var posicion = $(".ancla"+ancla).offset().top;
                            $("html, body").animate({
                                scrollTop: posicion
                            }, 500); 
                            $("#tableRecorrido tr").css('background-color','');
                            $(".ancla"+this.options.scrollto).addClass('Selectedtr');
                          }
                    } 
                  }
                },
                marker: {
                    radius: 2, 
                }
            } 
        },series:
    [
     { name: 'Velocidad', data: [<?php echo $buffer_vel; ?>], color: '#d3340b' }, 
    { name: 'Tanque total', data: [<?php echo $buffer_tt; ?>], color: '#63c000' },
      
      
      
    /*  { name: 'Total',data: [<?php echo $buffer_tt; ?>], color: '#20a8ff' } */
    ]
    });

$('#chart-B').highcharts({
        chart: {
            zoomType: 'x',
          width: <?php echo count($rpt)*15; ?>,
         renderTo: 'detail',
          type: 'line'  
        },
       title: {
    text: '',
    style: {
        display: 'none'
    }
},
subtitle: {
    text: '',
    style: {
        display: 'none'
    }
},
        xAxis: {
             categories: [<?php echo $buffer_fecha; ?>],
      labels: { enabled : true,  style: { fontSize: '10px', fontFamily: 'Arial, sans-serif' } },
        },
        yAxis: {
           title: { text: '' },
            plotLines: [{
                value: 0,
                width: 1,
                color: '#808080'
            }] , labels: { style: { fontSize: '10px', fontFamily: 'Arial, sans-serif' } }
        },
        
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle',
            borderWidth: 0
        },
     tooltip : {
            formatter: function() {
                var tooltip;
                tooltip =  this.point.custom;
                return tooltip;
            }
        }
    ,
    plotOptions: {

            series: {
              turboThreshold:10000,
                cursor: 'pointer',
                point: {
                    events: { click: function(){ 
                      if(this.options.url!=null){ 
                        window.open(this.options.url); 
                      } else{
                            
                            var ancla = this.options.scrollto -10;
                            var posicion = $(".ancla"+ancla).offset().top;
                            $("html, body").animate({
                                scrollTop: posicion
                            }, 500); 
                            $("#tableRecorrido tr").css('background-color','');
                            $(".ancla"+this.options.scrollto).addClass('Selectedtr');
                          }
                    } 
                  }
                },
                marker: {
                    radius: 2, 
                }
            } 
        },series:
    [
     <?php if($temp1_sensor == 1 ){?>  { name: 'Temperatura 1', data: [<?php echo $buffer_temp; ?>], color: '#5489EA' },  <?php } ?>
   <?php if($temp2_sensor == 1 ){?>  { name: 'Temperatura 2', data: [<?php echo $buffer_temp_2; ?>], color: '#214EA0' },  <?php } ?>
    
    { name: 'Motor', type: 'column', data: [<?php echo $buffer_encendido ; ?>], color: '#63c000' },
      
      
      
    /*  { name: 'Total',data: [<?php echo $buffer_tt; ?>], color: '#20a8ff' } */
    ]
    });



var options;
var chart;

  doptions = {
    chart: {
       zoomType: 'x',
     width: <?php echo count($rpt)*15; ?>,
      renderTo: 'detail',
      type: 'line'    
    },
       title: {
    text: '',
    style: {
        display: 'none'
    }
},
subtitle: {
    text: '',
    style: {
        display: 'none'
    }
},

        xAxis: {
      categories: [<?php echo $buffer_fecha; ?>],
      labels: { enabled : true,  style: { fontSize: '10px',fontFamily: 'Arial, sans-serif' } },
      /*events: {
                    afterSetExtremes: function(event){
                        if(this.getExtremes().dataMin < event.min){
              console.log(this);
            chart.xAxis[0].update({ labels: { enabled : true } });

            }else{ chart.xAxis[0].update({ labels: { enabled : false } });
             }
                    }
                }*/
    },
        yAxis: { title: { text: '' },
            plotLines: [{
                value: 0,
                width: 1,
                color: '#808080'
            }] , labels: { 
              style: {
               fontSize: '10px', 
               fontFamily: 'Arial, sans-serif' } }
        },


        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle',
            borderWidth: 0
        },
     tooltip : {
            formatter: function() {
                var tooltip;
                tooltip =  this.point.custom;
                return tooltip;
            }
        }
    ,
    plotOptions: {

            series: {
              turboThreshold:10000,
                cursor: 'pointer',
                point: {
                    events: { click: function(){ 
                      if(this.options.url!=null){ 
                        window.open(this.options.url); 
                      } else{
                            
                            var ancla = this.options.scrollto -10;
                            var posicion = $(".ancla"+ancla).offset().top;
                            $("html, body").animate({
                                scrollTop: posicion
                            }, 500); 
                            $("#tableRecorrido tr").css('background-color','');
                            $(".ancla"+this.options.scrollto).addClass('Selectedtr');
                          }
                    } 
                  }
                },
                marker: {
                    radius: 2, 
                }
            } 
        },series:
    [
     { name: 'Velocidad', data: [<?php echo $buffer_vel; ?>], color: '#d3340b' }, 
    <?php if($fuel_a_sensor == 1 ){?> { name: 'T1', data: [<?php echo $buffer_t1; ?>], color: '#A70D85' }, <?php } ?>
    <?php if($fuel_b_sensor == 1 ){?> { name: 'T2', data: [<?php echo $buffer_t2; ?>], color: '#ffab00' }, <?php } ?>
    <?php if($fuel_c_sensor ==1 ){?> { name: 'T3',data: [<?php echo $buffer_t3; ?>], color: '#8612e9' },<?php } ?>
      
      
      
    /*  { name: 'Total',data: [<?php echo $buffer_tt; ?>], color: '#20a8ff' } */
    ]
    };


$(function () {
 dchart = new Highcharts.Chart(doptions);

});

</script>
<div style="clear:both"></div>
</div>

<!--
//No mostrar temperatura si no tiene
Ventana de alarma y filtrar alarmas


-->
<div class="container">
  <div class="left">
    <div class="tabs">
      <ul>
        <li class="active"><a href="javascript:void(0)" class="onClickTab" rel="tab_1">Detalle de Recorrido V2</a></li>
        <li><a href="javascript:void(0)" class="onClickTab" rel="tab_2">Rendimiento de Unidad</a></li>

      </ul>
      <br class="clear" />
    </div>

    <div class="tab_container">
    <div id="tab_1" class="tab active datagrid">
          <?php
echo '
<table border="0" width="100%" id="tableRecorrido" align="center" cellpadding="0" cellspacing="0" >';


$tunit = 'C&deg';
if($_SESSION['logged']['temp']=="f"){ $tunit = 'F&deg'; }
$tspeed;
$distance =0;
$ton = 0;
$toff = 0;
$recarga = 0;
$descarga = 0;
$tt_raw;
$odo = $odometer;
$comb_consumido = 0;

echo '<thead>
    <tr id="fixedTrR">
     
    <th>Hora</th>
    <th>km/h</th>
    <th>Comb. Total</th>';
    if($fuel_a_sensor == 1 ){?> <th>Tanque 1 L</th> <?php }  
    if($fuel_b_sensor == 1 ){?> <th>Tanque 2 L</th> <?php }  
    if($fuel_c_sensor == 1 ){?> <th>Tanque 3 L</th> <?php } 
    if($temp1_sensor == 1 ){ echo '<th>Temp 1. '.$tunit.'</th>'; }
    if($temp2_sensor == 1 ){ echo '<th>Temp 2. '.$tunit.'</th>'; }  
   
   
    echo '
    <th>Motor</th>
    <th>Odometro</th>
    <th>Ver</th>
    </tr>
    <div id="scrollDay" style="display:none">
       <span id="scrollDayText"></span> 
    </div>
    </thead>
    <tbody>';


    echo '<thead>
    <tr id="fixedTr" style="display:none">
     
    <th>Hora</th>
    <th>km/h</th>
    <th>Comb. Total</th>';
    if($fuel_a_sensor == 1 ){?> <th>Tanque 1 L</th> <?php }  
    if($fuel_b_sensor == 1 ){?> <th>Tanque 2 L</th> <?php }  
    if($fuel_c_sensor == 1 ){?> <th>Tanque 3 L</th> <?php } 
    if($temp1_sensor == 1 ){ echo '<th>Temp 1. '.$tunit.'</th>'; }
    if($temp2_sensor == 1 ){ echo '<th>Temp 2. '.$tunit.'</th>'; }  
    
    
    echo '
    <th>Motor</th>
    <th>Odometro</th>
    <th>Ver</th>
    </tr> 
    </thead>';
$actual_day=0;
$rowspan=1;
$days = array();
?>

<script>$('.loader').show('slow');$('#overlay').slideDown('slow');$("body").css("overflow", "hidden");</script>

<?php
for($i=0;$i<count($rpt);$i++){ 
 
   if($rpt[$i]['ignition']==0){
     $class = 'off';
   }else{
     $class= 'on';
   }
  
if($i == count($rpt)-1){
  ?>
<script type="text/javascript">$('.loader').hide('slow');$('#overlay').slideUp('slow');$("body").css("overflow", "auto");</script>
  <?php
}

  $iostatus = $objGPRS->get_iostatus($route[$i]['iostatus']);
  //$route[$i]['ignition'] = $iostatus['ignition'];
 //$route[$i]['ignition'] =  $rpt[$i]['ignition'];
  $fecha = explode(' ', $rpt[$i]['date']);
  $f = explode('-', $fecha[0]);
  if($actual_day == $f[2]){
    $rowspan++;
    echo ' ';
  }else{
    array_push($days, $fecha[0]);
    echo '<tr class="trDay'.$fecha[0].'" style="background-image:url(/clientes/_img/dark_Tire.png); " ><td style="color:white;    padding: 10px;
    text-align: center;" colspan="9"> '.$fecha[0].'</td></tr>';
  }
  $actual_day = $f[2];
  
   ?>
<script>
 $(<?php echo "'trDay".$fecha[0]."'"?>).visible();
</script>
   <?php
  echo '<tr class="ancla'.$i.' '.$class.'"  >';
  echo '<td>'.$i.' - '.$fecha[1].'</td>';
  echo '<td>'.$rpt[$i]['km'].'</td>';
  $tspeed[$i] = $rpt[$i]['km'];

  // TOTAL
  if($rpt[$i]['ttcol_f']!=0){
   if($rpt[$i]['ttcol_f'] > 0){ $color = '#59ad00'; }else{ $color = '#eb0000'; }
    echo '<td>
      <div style=" background-color:'.$color.'; color:#fff; max-width:55px; text-indent:10px; position:relative; cursor:pointer" align="left" class="onMover">
      <div style="position:absolute; display:none; width:170px; height:120px; border:#000 solid 1px;top:-10px;text-indent:0; padding-left:5px; background-color:#fff; color:#666; margin-left:55px;">'.$rpt[$i]['tt_chart'].'</div>
      '.$rpt[$i]['tt'].'
      </div>
      </td>';
   }else{
     echo '<td>'.$rpt[$i]['tt'].'</td>';
  }
  $tt_raw[$i] = $rpt[$i]['tt'];
  // FIN TOTAL


  // TANK 1
  if($fuel_a_sensor == 1 ){
  if($rpt[$i]['t1col_f']!=0){
   if($rpt[$i]['t1col_f'] > 0){ $color = '#59ad00'; }else{ $color = '#eb0000'; }
    echo '<td>
      <div style=" background-color:'.$color.'; color:#fff; max-width:55px; text-indent:10px; position:relative; cursor:pointer" align="left" class="onMover">
      <div style="position:absolute; display:none; width:170px; height:120px; border:#000 solid 1px;top:-10px;text-indent:0; padding-left:5px; background-color:#fff; color:#666; margin-left:55px;">'.$rpt[$i]['t1_chart'].'</div>
      '.$rpt[$i]['t1'].'
      </div>
      </td>';
   }else{
   // echo '<td>' . $rpt[$i]['volt_t1'].'</td>';
     echo '<td>'.$rpt[$i]['t1'].'</td>';
  }
  $t1_raw[$i] = $rpt[$i]['t1'];
 }
  // FIN TANK 1

  // TANK 2
 if($fuel_b_sensor == 1 ){
  if($rpt[$i]['t2col_f']!=0){
   if($rpt[$i]['t2col_f'] > 0){ $color = '#59ad00'; }else{ $color = '#eb0000'; }
    echo '<td>
      <div style=" background-color:'.$color.'; color:#fff; max-width:55px; text-indent:10px; position:relative; cursor:pointer" align="left" class="onMover">
      <div style="position:absolute; display:none; width:170px; height:120px; border:#000 solid 1px;top:-10px;text-indent:0; padding-left:5px; background-color:#fff; color:#666; margin-left:55px;">'.$rpt[$i]['t2_chart'].'</div>
      '.$rpt[$i]['t2'].'
      </div>
      </td>';
   }else{
    //echo '<td>' . $rpt[$i]['volt_t2'].'</td>';
     echo '<td>'.$rpt[$i]['t2'].'</td>';
  }
  $t2_raw[$i] = $rpt[$i]['t2'];
}
  // FIN TANK 2

  // TANK 3
 if($fuel_c_sensor == 1 ){
  if($rpt[$i]['t3col_f']!=0){
   if($rpt[$i]['t3col_f'] > 0){ $color = '#59ad00'; }else{ $color = '#eb0000'; }
    echo '<td>
      <div style=" background-color:'.$color.'; color:#fff; max-width:55px; text-indent:10px; position:relative; cursor:pointer" align="left" class="onMover">
      <div style="position:absolute; display:none; width:170px; height:120px; border:#000 solid 1px;top:-10px;text-indent:0; padding-left:5px; background-color:#fff; color:#666; margin-left:55px;">'.$rpt[$i]['t3_chart'].'</div>
      '.$rpt[$i]['t3'].'
      </div>
      </td>';
   }else{
    //echo '<td>' . $rpt[$i]['volt_t3'].'</td>';
     echo '<td>'.$rpt[$i]['t3'].'</td>';
  }
  $t3_raw[$i] = $rpt[$i]['t3'];
}
  // FIN TANK 3

  // TEMPERATURA
if($temp1_sensor == 1 ){
  echo '<td>'.$rpt[$i]["temp_1"].'</td>';
  $ttemp[$i] = $rpt[$i]['temp_1'];
}

if($temp2_sensor == 1 ){
  echo '<td>'.$rpt[$i]["temp_1"].'</td>';
  $ttemp[$i] = $rpt[$i]['temp_1'];
}

  if($rpt[$i]['ignition']==0){
    echo '<td><i class="fa fa-power-off " style=" color:red;"></i></td>';

  }else if($rpt[$i]['ignition']==1){
  echo '<td><i class="fa fa-power-off " style=" color:#1FB50B;"></i></td>';

  } 
  $distance  += getDistance($rpt[$i]['lat'],$rpt[$i]['lng'],$rpt[$i+1]['lat'],$rpt[$i+1]['lng']);
  $odo += getDistance($rpt[$i]['lat'],$rpt[$i]['lng'],$rpt[$i+1]['lat'],$rpt[$i+1]['lng']);
  echo '<td>'.number_format(($odo),2).'</td>';

  $direction = getCompassDirection($bearing);
  if($rpt[$i]['ignition']==1 && $rpt[$i]['km']>0){ $icon = "0_".$direction.".png"; }
  if($rpt[$i]['ignition']==1 && $rpt[$i]['km']<=0){ $icon = "1_".$direction.".png"; }
  if($rpt[$i]['ignition']==0){ $icon = "2_".$direction.".png"; }
  $rpt[$i]['direction'] = $icon;

  echo '<td><a href="javascript:void(0)" rel="'.$rpt[$i]['lat_lng'].'" class="onLatLngClick" id="'.$icon.'">Ver</a></td>';
  if($i<count($rpt)-1){
    if($rpt[$i]['ignition']==0){
      $toff += getMinutes($rpt[$i]['date'],$rpt[$i+1]['date']);
    }else{
      $ton += getMinutes($rpt[$i]['date'],$rpt[$i+1]['date']);
    }

  $bearing = (rad2deg(atan2(sin(deg2rad($rpt[$i+1]['lng']) - deg2rad($rpt[$i]['lng'])) * cos(deg2rad($rpt[$i+1]['lat'])), cos(deg2rad($rpt[$i]['lat'])) * sin(deg2rad($rpt[$i+1]['lat'])) - sin(deg2rad($rpt[$i]['lat'])) * cos(deg2rad($rpt[$i+1]['lat'])) * cos(deg2rad($rpt[$i+1]['lng']) - deg2rad($rpt[$i]['lng'])))) + 360) % 360;

  }

  echo '</tr>';

  $recarga += $data[$i]['ttcol_g'];
  $descarga += $data[$i]['ttcol_h'];


}



echo '</tbody>';
echo '</table>';

function getMinutes($t1,$t2){
  $to_time = strtotime($t1);
  $from_time = strtotime($t2);
  return round(abs($to_time - $from_time) / 60,2);

}


function getCompassDirection($bearing) {
     $tmp = round($bearing / 22.5);
     switch($tmp) {
          case 1:
               $direction = "NNE";
               break;
          case 2:
               $direction = "NE";
               break;
          case 3:
               $direction = "ENE";
               break;
          case 4:
               $direction = "E";
               break;
          case 5:
               $direction = "ESE";
               break;
          case 6:
               $direction = "SE";
               break;
          case 7:
               $direction = "SSE";
               break;
          case 8:
               $direction = "S";
               break;
          case 9:
               $direction = "SSW";
               break;
          case 10:
               $direction = "SW";
               break;
          case 11:
               $direction = "WSW";
               break;
          case 12:
               $direction = "W";
               break;
          case 13:
               $direction = "WNW";
               break;
          case 14:
               $direction = "NW";
               break;
          case 15:
               $direction = "NNW";
               break;
          default:
               $direction = "N";
     }
     return $direction;
}



function getDistance($lat1, $lng1, $lat2, $lng2) {
 if(!$lat2 || !$lng2){ return 0; }
 $pi80 = M_PI / 180;
  $lat1 *= $pi80;
  $lng1 *= $pi80;
  $lat2 *= $pi80;
  $lng2 *= $pi80;

  $r = 6372.797; // mean radius of Earth in km
  $dlat = $lat2 - $lat1;
  $dlng = $lng2 - $lng1;
  $a = sin($dlat / 2) * sin($dlat / 2) + cos($lat1) * cos($lat2) * sin($dlng / 2) * sin($dlng / 2);
  $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
  $km = $r * $c;

  return $km;
}
?>
    </div>
    <div id="tab_2" class="tab"><div class="datarpt">
    <table border="0" width="500" cellpadding="0" cellspacing="0">
     <tr class="top">
      <td colspan="1" valign="middle"><img src="_img/logo.png" alt="" width="114" height="36" class="logo" style="margin-left:10px;"></td>
      <td colspan="3" valign="middle"><span style="color:#fff;">RENDIMIENTO DE UNIDAD</span></td>
    </tr>
    <tr class="head">
      <td height="44" colspan="4" align="center" valign="middle"><strong>VEHICULO: <?php echo $asset[0]['alias']; ?> (<?php echo number_format($odo,2) ?> kms)</strong></td>
    </tr>
    <tr>
      <td>Ruta</td>
      <td colspan="3"> Sin Asignar</td>
    </tr>
    <tr>
      <td>Inicio</td>
      <td colspan="3"><a href="javascript:void(0)" class="onLatLngClick" rel="<?php echo $rpt[0]['lat_lng']; ?>" id="<?php echo $rpt[0]['direction']; ?>"><?php echo $objAsset->formatDateTime($rpt[0]['date'],"max"); ?></a></td>
    </tr>
    <tr>
      <td>Fin</td>
      <td colspan="3"><a href="javascript:void(0)" class="onLatLngClick" rel="<?php echo $rpt[count($rpt)-1]['lat_lng']; ?>" id="<?php echo $rpt[count($rpt)-1]['direction']; ?>"><?php echo $objAsset->formatDateTime($rpt[count($rpt)-1]['date'],"max"); ?>
      </a></td>
    </tr>

    <tr class="head">
    <td colspan="4"><strong>Distancia Recorrida Apr&oacute;x.</strong></td>
    </tr>
    <tr>
      <td>Distancia</td>
      <td colspan="3"><?php echo number_format($distance,2); ?> km</td>
    </tr>


    <tr class="head">
    <td colspan="4"><strong>Motor</strong></td>
    </tr>
    <tr>
      <td>Prendido</td>
      <td colspan="3"><?php echo secondsToTime($ton*60); ?></td>
    </tr>
     <tr>
      <td>Apagado</td>
      <td colspan="3"><?php echo secondsToTime($toff*60); ?></td>
    </tr>

    <?php
  function secondsToTime($inputSeconds) {

    $secondsInAMinute = 60;
    $secondsInAnHour  = 60 * $secondsInAMinute;
    $secondsInADay    = 24 * $secondsInAnHour;

    // extract days
    $days = floor($inputSeconds / $secondsInADay);

    // extract hours
    $hourSeconds = $inputSeconds % $secondsInADay;
    $hours = floor($hourSeconds / $secondsInAnHour);

    // extract minutes
    $minuteSeconds = $hourSeconds % $secondsInAnHour;
    $minutes = floor($minuteSeconds / $secondsInAMinute);

    // extract the remaining seconds
    $remainingSeconds = $minuteSeconds % $secondsInAMinute;
    $seconds = ceil($remainingSeconds);

    // return the final array
    $obj = array(
        'd' => (int) $days,
        'h' => (int) $hours,
        'm' => (int) $minutes,
        's' => (int) $seconds,
    );
  $buffer = '';
  $dias = '';
  $horas = '';
  $minutos = '';
  $segundos = '';
  if($obj["d"]>0) { $dias = $obj["d"]; }
  if($obj["d"]==1) { $dias = $obj["d"] ." d&iacute;a"; }
  if($obj["d"]>1) { $dias = $obj["d"] ." d&iacute;as"; }

  if($obj["h"]>0) { $horas = $obj["h"]; }
  if($obj["h"]==1) { $horas = $obj["h"] ." hora"; }
  if($obj["h"]>1) { $horas = $obj["h"] ." horas"; }

  if($obj["m"]>0) { $minutos = $obj["m"]; }
  if($obj["m"]==1) { $minutos = $obj["m"] ." minuto"; }
  if($obj["m"]>1) { $minutos = $obj["m"] ." minutos"; }

  if($obj["s"]>0) { $segundos = $obj["s"]; }
  if($obj["s"]==1) { $segundos = $obj["s"] ." segundo"; }
  if($obj["s"]>1) { $segundos = $obj["s"] ." segundos"; }

    return  $dias.' '.$horas.' '.$minutos.' '.$segundos.' ';
}
  ?>


    <tr class="head">
    <td><strong>Temperatura <?php echo $tunit; ?></strong></td>
    <td><strong>Global</strong></td>
    <td><strong>Sobre Cero</strong></td>
    <td><strong>Bajo Cero</strong></td>
    </tr>
    <tr>
      <td>Promedio</td>
      <td><?php echo number_format((array_sum($ttemp) / count($ttemp)),1); ?><?php $tunit = 'C&deg'; if($_SESSION['logged']['temp']=="f"){ $tunit = 'F&deg';  }?> </td>
      <td>
        <?php
      $aux_a;
      $aux_b;
      $cont_a = 0;
      $cont_b = 0;
      for($i=0;$i<count($ttemp);$i++){
        if($ttemp[$i]>0){ $aux_a[$cont_a] = $ttemp[$i];  }else{ $aux_b[$cont_b] = $ttemp[$i]; }
      }
      echo number_format((array_sum($aux_a) / count($aux_a)),1);
     ?>
      </td>
      <td><?php echo number_format((array_sum($aux_b) / count($aux_b)),1); ?></td>
    </tr>
    <tr>
      <td>Maxima</td>
      <td colspan="3"><?php echo max($ttemp);?>
    <a href="javascript:void(0)" class="onLatLngClick" rel="<?php echo $rpt[array_search(max($ttemp), $ttemp)]['lat_lng']; ?>" id="<?php echo $rpt[array_search(max($ttemp), $ttemp)]['direction']; ?>"><?php if(max($ttemp)!=NULL) echo '('.$objAsset->formatDateTime($rpt[array_search(max($ttemp), $ttemp)]['date'],"max").')'; ?></a></td>
    </tr>
    <tr>
      <td>Minima</td>
      <td colspan="3">
      <?php echo min($ttemp);?>
    <a href="javascript:void(0)" class="onLatLngClick" rel="<?php echo $rpt[array_search(min($ttemp), $ttemp)]['lat_lng']; ?>" id="<?php echo $rpt[array_search(min($ttemp), $ttemp)]['direction']; ?>">
     <?php if(max($ttemp)!=NULL) echo '('.$objAsset->formatDateTime($rpt[array_search(min($ttemp), $ttemp)]['date'],"max").')'; ?></a></td>
    </tr>


    <tr class="head">
    <td colspan="4"><strong>Velocidades</strong></td>
    </tr>
    <tr>
      <td>Promedio</td>
      <td colspan="3"><?php echo number_format((array_sum($tspeed) / count($tspeed)),2); ?></td>
    </tr>
     <tr>
      <td>Maxima</td>
      <td colspan="3">
    <?php echo max($tspeed);?> km/h <a href="javascript:void(0)" class="onLatLngClick" rel="<?php echo $rpt[array_search(max($tspeed), $tspeed)]['lat_lng']; ?>" id="<?php echo $rpt[array_search(max($tspeed), $tspeed)]['direction']; ?>">(<?php echo $objAsset->formatDateTime($rpt[array_search(max($tspeed), $tspeed)]['date'],"max"); ?>)</a>
      </td>
    </tr>



     <tr class="head" >
    <td colspan="4"><strong>Historico de Combustible</strong></td>
    </tr>
    <tr>
      <td>Inicial</td>
      <td colspan="3"><a href="javascript:void(0)" class="onLatLngClick" rel="<?php echo $rpt[0]['lat_lng']; ?>" id="<?php echo $rpt[0]['direction']; ?>"><?php echo $rpt[0]['tt']; ?> L</a></td>
    </tr>
     <tr>
      <td>Final</td>
      <td colspan="3"><a href="javascript:void(0)" class="onLatLngClick" rel="<?php echo $rpt[count($rpt)-1]['lat_lng']; ?>" id="<?php echo $rpt[count($rpt)-1]['direction']; ?>"><?php echo $rpt[count($rpt)-1]['tt']; ?> L</a></td>
    </tr>
     <tr>
      <td>Minimo</td>
      <td colspan="3">
    <?php echo min($tt_raw); ?>
      <a href="javascript:void(0)" class="onLatLngClick" rel="<?php echo $rpt[array_search(min($tt_raw), $tt_raw)]['lat_lng']; ?>" id="<?php echo $rpt[array_search(min($tt_raw), $tt_raw)]['direction']; ?>">
    <?php if(min($tt_raw)!=NULL) echo '('.$objAsset->formatDateTime($rpt[array_search(min($tt_raw), $tt_raw)]['date'],"max").')'; ?>
      </a>
      </td>
    </tr>
     <tr>
      <td>Maximo</td>
      <td colspan="3">
    <?php echo max($tt_raw); ?>
    <a href="javascript:void(0)" class="onLatLngClick" rel="<?php echo $rpt[array_search(max($tt_raw), $tt_raw)]['lat_lng']; ?>" id="<?php echo $rpt[array_search(max($tt_raw), $tt_raw)]['direction']; ?>">
    <?php if(max($tt_raw)!=NULL) echo '('.$objAsset->formatDateTime($rpt[array_search(max($tt_raw), $tt_raw)]['date'],"max").')'; ?>
      </a>
      </td>
    </tr>
    <tr>
      <td>Volumen Recargado</td>
      <td colspan="3"><?php echo $recarga; ?> lts</td>
    </tr>

    <tr>
      <td>Volumen Descargado</td>
      <td colspan="3"><?php echo $descarga; ?> lts</td>
    </tr>

    <?php
      // Se cambio a que se calculara dentro del FOR superior
     //$comb_consumido = ($recarga)-($descarga);
     // inicial - final + volumen recargado - volumen descargado
     $comb_consumido = $rpt[0]['tt'] - $rpt[count($rpt)-1]['tt']  + $recarga + $descarga;
    ?>
    <tr class="head">
    <td colspan="4"><strong>Rendimiento de Unidad</strong></td>
    </tr>
    <tr>
      <td>Combustible Consumido</td>
      <td colspan="3"><?php echo $comb_consumido; ?> lts <?php //echo $rpt[0]['tt'] ." - " . $rpt[count($rpt)-1]['tt'] ." + " . $recarga ." " . $descarga; ?></td>
    </tr>
     <tr>
      <td>Consumo x 100KM</td>
      <td colspan="3"><?php echo number_format(($comb_consumido/$distance)*100,2); ?> lts</td>
    </tr>
     <tr>
      <td>Rendimiento X LITRO</td>
      <td colspan="3"><?php echo number_format(($distance/$comb_consumido),2); ?> km/lt</td>
    </tr>
     <tr>
      <td>Consumo x Hora</td>
      <td colspan="3"><?php echo number_format(($comb_consumido/($ton/60)),2); ?> lts/hr</td>
    </tr>

    </table>
    </div></div>
    </div>

  </div>
  <div id="fixed" class="right"><div id="map-canvas" style="width:100%; height:500px; display:block; position:relative;"></div></div>
</div>




<script>

var objMap = null;

$(window).load(function(){ objMap = new Map('map-canvas'); });

var Map = function(map_id){
    var t = this;

  this.div = map_id;
  this.map;
  this.units = [];
  this.marker;

  function onLoad(){ t.addEventListeners(); }
  this.addEventListeners = function(){  google.maps.event.addDomListener(window, 'load', t.init()); }



  this.init = function(){
    var mapOptions = { zoom: 7, center: new google.maps.LatLng(<?php echo $route[0]['lat'].','.$route[0]['lng']; ?>), mapTypeId: google.maps.MapTypeId.ROADMAP,
       panControlOptions: { position: google.maps.ControlPosition.TOP_LEFT},
     zoomControlOptions: { style: google.maps.ZoomControlStyle.LARGE, position: google.maps.ControlPosition.TOP_LEFT },
       mapTypeControlOptions: { position: google.maps.ControlPosition.TOP_LEFT }
    };
      t.map = new google.maps.Map(document.getElementById(t.div),mapOptions);

    t.go_to(<?php echo $route[0]['lat'].','.$route[0]['lng']; ?>);
    google.maps.event.addListenerOnce(t.map, 'idle', function() {
    google.maps.event.trigger(t.map, 'resize');
    t.map.setCenter(new google.maps.LatLng(<?php echo $route[0]['lat'].','.$route[0]['lng']; ?>));
    });
    //t.get_gprs();


    $('.onLatLngClick').live('click',function(e){
      point = $(e.target).attr("rel").split(",");
      t.go_to(point[0],point[1],$(e.target).attr("id"));
    })


  }

  this.go_to = function(lat,lon,id){
    if(t.marker!=null){ t.marker.setMap(null); }
    t.marker = new google.maps.Marker({ position: new google.maps.LatLng(lat,lon),map: t.map, icon : "_icons/"+id });
      t.map.setZoom(15);
      t.map.panTo(new google.maps.LatLng(lat, lon));
  }


  onLoad();
}

</script>

<script>
$(document).ready(function(){
  $('.onMover').hover(function(){
    $(this).children().fadeIn().toggleClass('top');
  }, function(){
    $(this).children().fadeOut().toggleClass('top');
  })
   /* TABS */
  $("a.onClickTab").on("click",function(e){
    $(".tab").hide();
  $(".tabs ul li").removeClass('active');
  $(this).parent().addClass('active');
  $("#"+$(this).attr("rel")).fadeIn();
  google.maps.event.trigger(objMap.map, 'resize');
  });
  /* EOF TABS*/

  $('#tableRecorrido tr').click(function(){ 
    $(this).toggleClass( "Selectedtr" )
   
  })
});



$(document).ready(function() {
/*var stickyNavTop = 520;

var stickyNav = function(){
  var scrollTop = $(window).scrollTop();
 

  if (scrollTop > stickyNavTop) {
    $('#fixed').addClass('sticky');
    $('#fixedTr').show();
    $('#fixedTr').addClass('stickyHead');
    $('#scrollDay').show();
    $('#scrollDay').addClass('stickyDay');
} else {
    $('#fixed').removeClass('sticky');
     $('#fixedTr').removeClass('stickyHead');
     $('#scrollDay').removeClass('stickyDay');
     $('#scrollDay').hide();
     $('#fixedTr').hide();
}
};

stickyNav();

$(window).scroll(function() {
  stickyNav();
});
*/
var th =1; 
$.each($('#fixedTrR th'),function(){ 
  var ancho = $(this).width(); 
  $(this).css('width',ancho)  
  $('#fixedTr th:nth-child('+th+')').css('width',ancho) ;
  th++;

})
});

</script>
<script type="text/javascript">
function scrolling(){
   var scrollTop = $(window).scrollTop();
   var stickyNavTop = $('.scrollBars').height();
   var scrollTop = $(window).scrollTop();
 

  if (scrollTop > stickyNavTop) {
    $('#fixed').addClass('sticky');
    $('#fixedTr').show();
    $('#fixedTr').addClass('stickyHead');
    $('#scrollDay').show();
    $('#scrollDay').addClass('stickyDay');
} else {
    $('#fixed').removeClass('sticky');
     $('#fixedTr').removeClass('stickyHead');
     $('#scrollDay').removeClass('stickyDay');
     $('#scrollDay').hide();
     $('#fixedTr').hide();
}
   console.log(stickyNavTop);
  <?php 
 
 foreach ($days as $key => $value) {
   ?>
   if($(<?php echo "'.trDay".$value."'" ?>).visible()==true){


    var uno = $(<?php echo "'.trDay".$value."'" ?>).offset().top;  
    var va = uno - $(window).scrollTop(); 

    
    if(va<80){
      console.log('anterior<<<<<<<<<<');
       $('#scrollDayText').html(<?php echo "'".$value."'" ?>);
    }else if(va>80){
      console.log('siguiente');
       <?php if ($lastDay ==''){$lastDay = $value; } ?>
       $('#scrollDayText').html(<?php echo "'".$lastDay."'" ?>);
    }
   
     
   }
   
    <?php
    $lastDay = $value;
 }
?>
   
}
$('.chosen-select').chosen();
$('.scrollBars').doubleScroll();
$('#sendReport').click(function(){

  var s = $("#fdate_from").val() +" "+ $("#fhour_from").val();
  var e = $("#fdate_to").val() +" "+ $("#fhour_to").val();
  var ir = $("#lst_fuelrpt_imei").val().split("|");
   
  var rpt = 'rpt_comb_f1.php';
  if(ir=="NULL"){ alert("Seleccione una Unidad"); return false; }
  if(ir[1]==2){ rpt = 'rpt_comb.php';}
 // location.reload(rpt+"?sdate="+s+"&edate="+e+"&imei="+ir[0]);
window.location.href = window.location.pathname + "?sdate="+s+"&edate="+e+"&imei="+ir[0];

})
</script>
  
</body>
</html>
 