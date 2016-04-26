<?php
session_start();
if(!isset($_SESSION['logged'])) { header('Location: login.php?s=401'); }
require_once('_firephp/FirePHP.class.php'); 
$mifirePHP = FirePHP::getInstance(true);
 
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Norttrek</title>

    <!-- Bootstrap -->
    <link href="_css/bootstrap.min.css" rel="stylesheet">
    <link href="_css/jasny-bootstrap.min.css" rel="stylesheet">
    <link href="_css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="_css/resources.css">
    <link rel="stylesheet" href="_css/leaflet.css">
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,600,300&subset=latin' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="_lib/fawesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="_css/jquery-confirm.css">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">

        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="_js/bootstrap.min.js"></script>
    <script src="_js/jasny-bootstrap.min.js"></script>
    <script src="/clientes/_js/chosen.jquery.min.js"></script>
    <script src="_js/jquery-ui.min.js"></script>
    <script src="_js/jquery.jscrollpane.min.js"></script>
    <script src="_js/leaflet.js"></script> 
    <script type="text/javascript" src="_js/jquery-confirm.js"></script>
   
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=drawing,places"></script>
    <script type="text/javascript" src="http://google-maps-utility-library-v3.googlecode.com/svn/trunk/infobox/src/infobox.js"></script>
    
    <script type="text/javascript" src="_js/mrklbl.js"></script>


    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
<div class="progress" style="width:0%; position:absolute; height:3px; background-color:#00acde;"></div>
<div class="container-fluid" style="height:100%; padding-right:0; padding-left:0">

  <div class="loader"  style="display:none"><img width="30" src="/clientes/_img/loader.gif"></div>
  <div class="row header-bar"> 
     <div class="col-md-12" style="padding-right:0">
<nav class="navbar navbar-default">
  <div id="hideUnidadesDiv"><button id="hideUnidades"><span class="glyphicon glyphicon-menu-hamburger" aria-hidden="true"></span></button></div>
  <div class="container-fluid">
    
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="#"><img class="logo" src="_img/logo.png" ></a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
         
        <li class="dropdown">
          <a href="#" class="headerlink dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Reportes<span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a data-toggle="offcanvas" side="trayectoria" id="trayectoriaBtn" unidades="1" map="1" sideBarWidth="350" class="openSideBar">Trayectoria</a></li>
            <li><a data-toggle="offcanvas" side="geocercas"   unidades="0"  map="0" sideBarWidth="600" class="openSideBar">Geocercas</a></li>
            <li><a data-toggle="offcanvas" side="combustible"  unidades="1"  map="1" sideBarWidth="350" class="openSideBar">Combustible</a></li>
          </ul>
        </li>
        <li class="dropdown">
          <a href="#" class="headerlink dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Geoherramientas<span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a  id="geocercasBtn" side="geocercas_a" unidades="0" sideBarWidth="450">Geocercas</a></li>
            <li><a  side="interes"  sideBarWidth="350" unidades="0" id="interesBtn">Puntos de Interes</a></li>
            <li><a  data-toggle="offcanvas"   side="georutasb" id="tracksBtn" class="openSideBar"  sideBarWidth="350"unidades="1" >Georutas</a></li> 
          </ul>
        </li>
          <li><a href="/clientes/home.php">Refrescar Mapa <span class="sr-only">(current)</span></a></li>
      </ul>


      <ul class="nav navbar-nav navbar-right">
        <li><div class="tools">
                    <p><span>Tr&aacute;fico</span> <a href="javascript:void(0)" class="onClickTraffic traffic" rel="on"><i class="fa fa-lg fa-square-o"></i></a></p>
                    <p><span>Regla</span> <a href="javascript:void(0)" class="onClickRuler ruler" rel="on"><i class="fa fa-square-o fa-lg"></i></a></p>
                  </div> </li>
        
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Configuración <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a side="acceso" class="onClickOpenWindow" rel="users" sideBarWidth="450" unidades="0" >Control de Acceso</a></li>
            <li><a side="cuenta" class="onClickOpenWindow" rel="users" sideBarWidth="350" unidades="0" >Mi cuenta</a></li>
            <li><a href="#">Grupos</a></li>
          </ul>
        </li>
        <li><a href="logout.php">Salir</a></li>
      </ul>


    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
     </div>
    
  </div>
  <div class="row unidadesBar" style="margin-right:0;margin-left:0" >
    <style type="text/css">
 
 
#contenedor {width: 100%;  margin: 0px auto;background-color: #272A33;}
.izquierda {  width: 390px; float: left;}
.derecha { margin: 0px 0px 0px 390px;}
</style>
     <div id="contenedor">
<div id="unidades" class="izquierda">
<?php include('unidades/unidades.php') ?>
</div>
<div id="mapa" class="derecha">
<div id="map-canvas"></div>
</div>
</div>
     <!--<div id="unidades" style="padding-right:0" class="col-md-3  scroll-pane">
        
      
     </div>
     
     <div id="mapa" class="col-md-9 " style="height:100%; padding-left:0">
     
     </div> -->
  </div>
  
 
</div>
<?php include('footer/footer.php') ?>

<?php include('reportes/trayectoria.php') ?>

<?php include('reportes/geocercas.php') ?>

<?php include('reportes/combustible.php') ?>

<?php include('geoherramientas/puntosdeinteres.php') ?>
<?php include('usuarios/acceso.php') ?>
<?php include('usuarios/micuenta.php') ?>
<?php include('geoherramientas/georutas.php') ?> 
<?php include('geoherramientas/geocercas.php') ?>

  </body>
</html>
 
<script type="text/javascript">
$(function () {
  $('[data-toggle="tooltip"]').tooltip()
})
</script>
 <?php 
$objJson = json_encode($gprs_reports);
?>
<script src="_js/functions.js"></script>
<?php include("_js/maps.php") ?>
<script src="_js/client.js"></script>
<script type="text/javascript">
 $.each($('.gas1fill'),function(){ 
  var per = $(this).attr('per'); 
  if(per < 25){
    $(this).css('background-color','rgb(216, 40, 48)'); 
    
  }else if(per <= 50 && per >=25){
    $(this).css('background-color','rgb(206, 194, 51)');
    $(this).css('background','-webkit-linear-gradient(left,  rgb(216, 40, 48) 37px, rgb(206, 194, 51) 50% )');
     background: ;
  }else if( per >=50){
    $(this).css('background-color','rgb(57, 182, 57)');
     $(this).css('background','-webkit-linear-gradient(left,  rgb(216, 40, 48) 37px, rgb(206, 194, 51) 39px, rgb(57, 182, 57) )');
  }
});
 total_body = $('body').height();
 total_header = $('.header-bar').height();
 total_footer = $('.fix_footer').height();

 heightUnidades = total_body - total_header - total_footer;
$('.unidadesBar').css('height',heightUnidades);
$('.derecha').css('height',heightUnidades);
$('#unidades').css('height',heightUnidades);
$(window).resize(function() {
   total_body = $('body').height();
 total_header = $('.header-bar').height();
 total_footer = $('.fix_footer').height();

 heightUnidades = total_body - total_header - total_footer;
$('.unidadesBar').css('height',heightUnidades);
$('#unidades').css('height',heightUnidades);
});
$("[data-collapse-group='myDivs']").click(function () {
   $('.tab-pane').removeClass('active');
    console.log($(this).attr('href'));
    $('.tabnav li').removeClass('active')
    id = $(this).attr('href');
    $(id).addClass('active');
    /*$("[data-collapse-group='myDivs']:not([data-target='" + $(this).attr('href') + "'])").each(function () {
        $('.tab-pane').removeClass('active')
    });*/
});
</script>
