<?php
 
  session_start();
  if(!isset($_SESSION['logged'])) { header('Location: login.php?s=401'); }

  require_once('_firephp/FirePHP.class.php'); 

 $mifirePHP = FirePHP::getInstance(true);
$mivariable=2; 
 
 

 
?>
<!DOCTYPE html>
<html>
<head>
<title>Norttrek - GPS</title>
<meta charset="utf-8" /> 
<link rel="shortcut icon" href="http://norttrek.com/site/wp-content/uploads/2014/07/logo50x50.ico"  />
<link rel="apple-touch-icon-precomposed" href="http://norttrek.com/site/wp-content/uploads/2014/08/Norttrek-Rastreo-Satelital-GPS-Icon-57x57.png">
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="http://norttrek.com/site/wp-content/uploads/2014/08/Norttrek-Rastreo-Satelital-GPS-Icon-114x114.png">
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="http://norttrek.com/site/wp-content/uploads/2014/08/Norttrek-Rastreo-Satelital-GPS-Icon-72x72.png">
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="http://norttrek.com/site/wp-content/uploads/2014/08/Norttrek-Rastreo-Satelital-GPS-Icon-144x144.png">
</head>

<body>
<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,600,300&subset=latin' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="_lib/fawesome/css/font-awesome.min.css">
<link rel="stylesheet" href="_css/default.css">
<link rel="stylesheet" href="_css/introjs.min.css">
<link rel="stylesheet" href="_css/tabulous.css">
<link rel="stylesheet" href="_css/jquery-confirm.css">
<link rel="stylesheet" type="text/css" href="_lib/fancybox/jquery.fancybox.css?v=2.1.4" media="screen" />
<link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/jquery-1.8.3.js"></script>
<script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
<script type="text/javascript" src="_js/jquery.tablesorter.min.js"></script>
<script type="text/javascript" src="_lib/fancybox/jquery.fancybox.pack.js?v=2.0.6"></script>
<script type="text/javascript" src="_js/jquery.form.js"></script>
<script type="text/javascript" src="_js/jquery.tooltipster.js"></script>
<script type="text/javascript" src="_js/intro.min.js"></script>
<script type="text/javascript" src="_js/jquery-confirm.js"></script>


<script>
var stage = new Object();
var wait = 0;
var submenu_open = false;
var submenu_int;

function countdown(){
  wait += 1;
  if(wait<=300){
  per = ((wait*100)/300);
  $('.progress').animate({width: per+"%"}, 800);
    setTimeout(countdown,700);
  }else{
   objTrack.get_gprs();
   wait = 0;
   setTimeout(countdown,1000);
  }
}
setTimeout(countdown,1000);



$(document).ready(function(){
  stage.width = $(document).width();
 window.setTimeout(function(){ $("#loading").fadeOut(); },4000);
  $('.onClickConfig').click(function(){
    $('.dropdown').slideToggle();
  });

  $('.onClickViewGeoRoute').click(function(){
    $('.isSubMenu').slideUp();
    $('.dropdowngr').slideDown();
  });




  $('.onClickCloseDropDown').click(function(){  $('.dropdown').slideToggle(); });


  $(".modal").fancybox({padding: 2, width: 905, });

  $('.drag').draggable({ helper: 'clone' });

   $("#pleft").resizable({ maxWidth: 253, });
  $("#footer").resizable({ handles: 'n, s', maxHeight : 250 }).bind('resize', function(){ $(this).css("top", "auto");});;


  $("a.onClickCollapse").on("click",function(e){ $("#pleft").slideToggle(); });
  $("a.onClickRoute").on("click",function(e){
    $("#route").fadeToggle();
  });
console.log($('.gas1fill').attr('per'));


  $("a.onGeoFenceMouseOver").live("mouseover",function(){
    console.log('func0');
    /*console.log($(this).attr("rel"));
    var aux = $(this).attr("rel").split("|");
    var o = new Object();
    o.lat = aux[0];
    o.lng = aux[1];
    o.radius = parseFloat(aux[2]);
    console.log(o);
    objTrack.show_circle(o); */
  });

  $("div.isSubMenu").live("mouseleave",function(){ $(this).fadeOut(); });
  $("div.isSubMenu").live("mouseenter",function(){ submenu_open = true; });



  $("a.onGeoFenceMouseOver").live("mouseleave",function(){ objTrack.remove_circle(); });




  $("a.onClickOpenWindow").on("click",function(){
    var href = "#"+$(this).attr("rel");
    $('.window').fadeOut('fast');
    $(href).fadeIn();
    switch(href){

      case "#geofence": $(href+" .container").load( "_view/mod.save_geofence.php?zoom="+objTrack.geofence.zoom+"&type="+objTrack.geofence.type+"&data="+objTrack.geofence.vars);
      console.log(objTrack.geofence.zoom + 'objTrack.geofence.zoom');
      console.log(objTrack.geofence.type + 'objTrack.geofence.type');
      console.log(objTrack.geofence.vars + 'objTrack.geofence.vars');
    break;
    case "#geofences": objClient.get_geofences();
    break;
    case "#users": objClient.get_users();
    break;
    case "#groups": objClient.get_groups();
    break;
    case "#clear_map": location.reload();
    break;
    case "#checkpoints": objClient.get_checkpoints();
    break;
    case "#pois": objClient.get_pois();
    break;

    }

    $('.dropdowngr').mouseenter(function(){ console.log("---xx"); });
    $('.isSubMenu').slideUp();


  });

  $("a.onClickCloseWindow").on("click",function(e){ $(".window").fadeOut(); });

    $("a.onClickGetRoute").on("click",function(e){
    var s = $("#date_from").val() +" "+ $("#hour_from").val();
    var e = $("#date_to").val() +" "+ $("#hour_to").val();
    var i = $("#lst_route_imei").val();
    if(i=="NULL"){ alert("Seleccione una Unidad"); }
    objTrack.get_route(i,s,e);
  });

  $("a.onClickAnimateRoute").on("click",function(e){
    var s = $("#date_from").val() +" "+ $("#hour_from").val();
    var e = $("#date_to").val() +" "+ $("#hour_to").val();
    var i = $("#lst_route_imei").val();
    if(i=="NULL"){ alert("Seleccione una Unidad"); }
    switch($(this).attr("rel")){
      case "back": objTrack.animate_back();
    break;
    case "pause":
      objTrack.animate_pause = true;
      objTrack.animate_stop();
    break;
    case "play":
      objTrack.animate(i,s,e);
      objTrack.animate_pause = false;
    break;
    case "next": objTrack.animate_next();
    break;
    }

  });




  $("a.onClickReport").click(function(){
    $('.isSubMenu').slideUp();
    $(this).next().slideDown();
  });

  $("a.onClickAlarm").click(function(){
    submenu_open = true;
    $('.isSubMenu').slideUp();
    $(this).next().slideDown();
  });


  $("a.mmenu").live("mouseenter",function(){ $('.isSubMenu').slideUp(); });
  $("a.mmenu").live("mouseleave",function(){ submenu_open = false; close_submenu($(this).next()); });

  });





  function close_submenu(el){
      window.setTimeout(function(){ if(submenu_open==false){ $(el).fadeOut(); } },500);
  }





</script>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=drawing,places"></script>
<script type="text/javascript" src="http://google-maps-utility-library-v3.googlecode.com/svn/trunk/infobox/src/infobox.js"></script>
<script type="text/javascript" src="_js/track.js"></script>
<script type="text/javascript" src="_js/mrklbl.js"></script>


</head>
<div class="loader"  style="display:none"><img src="/clientes/_img/loader.gif"></div>
<a id="modal" href="mod.panel_info.php" class="modal fancybox.iframe" style="display:none;"></a>
<div id="loading" style="display:none;"><img id="spinner" src="loaders.png" width="285" height="293"></div>
<div id="layout">
  <?php include_once("_inc/inc.users.php"); ?>
  <?php include_once("_inc/inc.route.php"); ?>
  <?php include_once("_inc/inc.fuelrpt.php"); ?>
  <?php include_once("_inc/inc.georoute.php"); ?>
  <?php include_once("_inc/inc.georoute_add.php"); ?>
  <?php include_once("_inc/inc.groups.php"); ?>
  <?php include_once("_inc/inc.geofence.php"); ?>
  <?php include_once("_inc/inc.geofences.php"); ?>
  <?php include_once("_inc/inc.pois.php"); ?>
  <?php include_once("_inc/inc.checkpoints.php"); ?>
  <style>
  #header { height:56px; }
  #header .left { width:830px; height:56px;float:left; }
  #header .left .logo { margin-left:15px; margin-right:15px; }
  #header .left a.menu { display:block; color:#fff; margin-top:20px; padding-left:12px; padding-right:12px; font-weight:300; }
  #header .left a.mmenu { margin-top: 0; padding-top: 19px; height: 38px;  }
  #header .left a.menu:hover { color:#00ccff; }
  #header .left a.menu:hover i { color:#fff; }
  #header .left a.menu i { margin-right:5px; color:#fff;}
  #header .right { width:350px; height:56px;  float:right; }
  #header .right table tr td { height:56px; }
  #header .right a.menu { display:block; color:#fff; margin-top:20px; padding-left:18px; padding-right:18px; font-weight:300; }
  #header .right a.menu:hover { color:#00ccff; }
  #header .right a.menu:hover i { color:#fff; }
  #header .right a.menu i { margin-right:5px; color:#fff;}
   .clear { clear:both;}
  </style>
  <table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
    <!-- HEADER -->
    <tr>
      <td colspan="2" valign="top" style="background-color:#1b1e25;color:#fff;">
        <div id="header">
          <div class="left">
            <table border="0" cellpadding="0" cellspacing="0" height="56">
              <tr>
                <td ><a href="javascript:void(0)" class="onClickCollapse"><img src="_img/btn_exp.png" width="56" height="56"></a></td>
                <td><img class="logo" src="_img/logo.png" width="175" height="56"></td>
                <td valign="top" style="border-right:#343b48 dotted 1px; border-left:#343b48 dotted 1px; position:relative;">
                  <a href="javascript:void(0)" class="menu onClickAlarm mmenu"> <i class="fa fa-warning fa-lg" > </i> Alertas </a>
                  <div style="position:absolute; width:170px; right:-83px; top:57px; background-color:#1b1e25; height:140px; z-index:1000; display:none;" class="isSubMenu">
                    <a href="mod.alertas.php?o=1" class="menu modal fancybox.iframe" rel="route"><i class="fa fa-road fa-lg" > </i> Alertas Generales</a>
                    <a href="mod.alertas.php?o=2" class="menu fancybox.iframe modal"><i class="fa fa-circle fa-lg" > </i> Alerta de Parametros</a>
                    <a href="mod.alertas_geo.php" class="menu fancybox.iframe modal"><i class="fa fa-circle fa-lg" > </i> Alerta de Geo-Cercas</a>
                  </div>
                </td>
                <td valign="top" style="border-right:#343b48 dotted 1px; position:relative;">
                  <a href="javascript:void(0)" class="menu onClickReport mmenu"><i class="fa fa-paste fa-lg"> </i> Reportes</a>
                 <div style="position:absolute; width:160px; right:-62px; top:57px; background-color:#1b1e25; height:130px; z-index:1000; display:none;" class="isSubMenu">
                  <a href="javascript:void(0)" class="menu onClickOpenWindow" rel="route"><i class="fa fa-road fa-lg" > </i> Trayectoria</a>
                  <a href="mod.rpt.php" class="menu fancybox.iframe modal"><i class="fa fa-circle fa-lg" > </i> Geo-Cercas</a>
                   <a href="javascript:void(0)" class="menu onClickOpenWindow" rel="fuelrpt"><i class="fa fa-road fa-lg" > </i> Combustible</a>
                  </div>
                </td>
                <td valign="top" style="border-right:#343b48 dotted 1px; position:relative;">
                  <a href="javascript:void(0)" class="menu onClickViewGeoRoute mmenu"><i class="fa fa-exchange fa-lg" > </i> Geo-Herramientas</a>
                  <div class="dropdowngr isSubMenu" style="position:absolute; width:170px; right:-18px; top:57px; background-color:#1b1e25; height:135px; z-index:1000; display:none;">
                     <a href="javascript:void(0)" class="menu onClickOpenWindow " rel="geofences"><i class="fa fa-plus-square fa-lg"> </i> Geo-Cercas</a>
           <a href="javascript:void(0)" class="menu onClickOpenWindow " rel="pois"><i class="fa fa-plus-square fa-lg"> </i> Punto de Inter&eacute;s</a>
                     <a href="javascript:void(0)" class="menu onClickOpenWindow " rel="georoute_add"><i class="fa fa-plus-square fa-lg"> </i> CheckPoints</a>

                    <a href="javascript:void(0)" class="menu onClickOpenWindow" rel="checkpoints" style=" display:none;"><i class="fa fa-file-text-o fa-lg" > </i> Ver Check-Points</a>
                  </div>

                </td>
                <td valign="top" style="border-right:#343b48 dotted 1px;"><a href="javascript:void(0)" class="menu onClickOpenWindow" rel="clear_map"><i class="fa fa-refresh fa-lg" > </i> Limpiar Mapa</a></td>
                <td valign="top" style="border-right:#343b48 dotted 1px;">
                  <a  class="menu  " href="javascript:void(0);" id="tutorial" onclick="startIntro()">Novedades</a>
                  </td>


              </tr>
            </table>

          </div>
          <div class="right" align="right">
            <table border="0" cellpadding="0" cellspacing="0">
              <tr>
                <td>
                  <style>
           .tools { margin-right:10px; }
          .tools p { margin:0; padding:0; }
          .tools p span  { width:50px; display: inline-block; text-align:right; margin-right:3px;}
          .tools i { color: #00ccff; }
          </style>
                  <div class="tools">
                    <p><span>Tr&aacute;fico</span> <a href="javascript:void(0)" class="onClickTraffic traffic" rel="on"><i class="fa fa-lg fa-square-o"></i></a></p>
                    <p><span>Regla</span> <a href="javascript:void(0)" class="onClickRuler ruler" rel="on"><i class="fa fa-square-o fa-lg"></i></a></p>
                  </div>
                </td>
                <td valign="top" style="border-left:#343b48 dotted 1px; position:relative;">

                  <a href="javascript:void(0)" class="menu onClickConfig mmenu"><i class="fa fa-gears fa-lg" > </i> Configuraci&oacute;n</a>
                  <div class="dropdown isSubMenu" style="position:absolute; width:170px; right:-1px; top:57px; background-color:#1b1e25; height:169px; z-index:1000; display:none;">
                    <a href="javascript:void(0)" class="menu onClickOpenWindow onClickCloseDropDown" rel="users"><i class="fa fa-users fa-lg" > </i> Control de Acceso</a>
                    <a href="_view/frm.my_account.php" class="menu modal fancybox.ajax onClickCloseDropDown" rel="users"><i class="fa fa-users fa-lg" > </i> Mi Cuenta</a>
                    <a href="javascript:void(0)" class="menu onClickOpenWindow" rel="groups"><i class="fa fa-list fa-lg" > </i> Grupos</a>
                    <a href="javascript:void(0)" class="onClickUpdateTemp temp <?php echo $_SESSION['logged']['temp']; ?>" rel="<?php echo $_SESSION['logged']['temp']; ?>"></a>
                  </div>

                </td>
                <td valign="top" style="border-left:#343b48 dotted 1px;"><a href="logout.php" class="menu"><i class="fa fa-power-off fa-lg" > </i> Salir</a></td>
              </tr>
            </table>
          </div>
        </div>
      </td>
    </tr>
    <!-- /HEADER -->
    <tr>
      <td id="pleft" width="265" height="100%" valign="top" style="max-width:265px; background-color:#272a33; width:265px; color:#fff; box-shadow: 2px 2px 2px #000;">
        <div id="assets" style="height:95%; overflow-y:scroll;"></div>
        <div style=" position:absolute; bottom:0; right:0; width:26px; height:26px; background: url(_img/btn_minicollapse.png);"></div>
      </td>
      <td id="panel_right" height="100%" width="935" valign="top"><div id="map-canvas"></div></td>
    </tr> <!-- /eof tr content -->

    <tr>
      <td colspan="2" bgcolor="#ffffff">
      <div id="footer">
    <div class="container datagrid" style="background-color:#fff;">
      <div class="progress" style="width:0%; position:absolute; height:3px; background-color:#00acde;"></div>
      <table id="tbl_bottom" border="0" width="100%" cellpadding="0" cellspacing="0">
        <thead>
          <tr>
            <th class="sort" align="left"><i class="fa fa-truck fa-lg" style=" color:#737987;" > </i> Unidad</th>
            <th class="sort" align="left"><i class="fa fa-th fa-lg" style=" color:#737987;" > </i> Motor</th>
            <th class="sort" align="left"><i class="fa fa-th fa-lg" style=" color:#737987;" > </i> Estatus</th>
            <th class="sort" align="left"><i class="fa fa-tachometer fa-lg" style=" color:#737987;" > </i> Velocidad</th>
            <th class="sort" align="left"><i class="fa fa-clock-o fa-lg" style=" color:#737987;" > </i> Fecha</th>
            <th class="sort" align="left"><i class="fa fa-map-marker fa-lg" style=" color:#737987;" > </i> Coordenadas</th>
            <th class="sort" align="left"><i class="fa fa-map-marker fa-lg" style=" color:#737987;" > </i> Referencia</th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
    </div>
  </div>
      </td>
    </tr>

  </table>
<script type="text/javascript">
$(document).ready(function(){
  $('.speedAlarmActive').click(function(){
  if ($(this).is(':checked')) {
    alert('checked');
  }else{
    alert('not checked');
  }
});
  $.each($('.gas1fill'),function(){ 
  var per = $(this).attr('per');console.log(per);
  if(per < 30){
    $(this).css('background-color','red'); 
    console.log('red')
  }else if(per <= 60 && per >=30){
    $(this).css('background-color','yellow');
  }else if( per >=60){
    $(this).css('background-color','blue');
  }
});

$.each($('.gas2fill'),function(){ 
  var per = $(this).attr('per');console.log(per);
  if(per < 30){
    $(this).css('background-color','red'); 
    console.log('red')
  }else if(per <= 60 && per >=30){
    $(this).css('background-color','yellow');
  }else if( per >=60){
    $(this).css('background-color','blue');
  }
});

$.each($('.gas3fill'),function(){ 
  var per = $(this).attr('per');console.log(per);
  if(per < 30){
    $(this).css('background-color','red'); 
    console.log('red')
  }else if(per <= 60 && per >=30){
    $(this).css('background-color','yellow');
  }else if( per >=60){
    $(this).css('background-color','blue');
  }
});
});
$(document).ready(function() {
            $('.tooltip').tooltipster();

        });
</script>

</div>

</body>
</html>
