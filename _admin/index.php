<?php
  session_start();
 if(!isset($_SESSION['onUserSession'])) { header('Location: login.php?s=401'); }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>NORTTREK - ADMINISTRADOR</title>
<link rel="shortcut icon" href="http://norttrek.com/site/wp-content/uploads/2014/07/logo50x50.ico"  />
<link rel="apple-touch-icon-precomposed" href="http://norttrek.com/site/wp-content/uploads/2014/08/Norttrek-Rastreo-Satelital-GPS-Icon-57x57.png">
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="http://norttrek.com/site/wp-content/uploads/2014/08/Norttrek-Rastreo-Satelital-GPS-Icon-114x114.png">
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="http://norttrek.com/site/wp-content/uploads/2014/08/Norttrek-Rastreo-Satelital-GPS-Icon-72x72.png">
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="http://norttrek.com/site/wp-content/uploads/2014/08/Norttrek-Rastreo-Satelital-GPS-Icon-144x144.png">
<link rel="stylesheet" type="text/css" href="_lib/fancybox/jquery.fancybox.css?v=2.1.4" media="screen" />
<link rel="stylesheet" href="_lib/fawesome/css/font-awesome.min.css">
<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300,700' rel='stylesheet' type='text/css'>
<link type="text/css" href="_css/redmond/jquery-ui-1.8.16.custom.css" rel="stylesheet" />	

<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.8.16.custom.min.js"></script>

<script type="text/javascript" src="_lib/fancybox/jquery.fancybox.pack.js?v=2.0.6"></script>
<script type="text/javascript" src="_js/jcarousellite.min.js"></script>
<script type="text/javascript" src="_js/kafeina.moka.js"></script>

<script>
var stage = new Object();
var progress = 0;
$(document).ready(function(){ 
  stage.width = $(window).width();
  
  $('.onClickSlide').click(function(){ 
    $("#panel_left").slideToggle(200);
  });
  
  $('.modal').fancybox({ padding: 2, width: 500 });
  
  $(".item").click(function(){ 
    //$("#overlay").css('right',0);
	$( "#overlay" ).show().animate({ right: 0,}, 1200);
  });
  
  $(".onClickCloseOverlay").click(function(){ 
    $( "#overlay" ).fadeOut(function(){ $(this).css('right','-810px'); });
  });
  
  $(".onClickReset").click(function(){ 
    if(confirm("Esta seguro de enviar SMS de Reset?")){
      var to = $(this).attr("rel"); 
	  $.post("_ctrl/ctrl.sms.php", { exec: "reset", to: to },function(r){ console.log(r); alert("Comando Reset Enviado"); });
	}
  }); 
  
  $(".onClickWipe").click(function(){ 
    if(confirm("Esta seguro de enviar SMS de Wipe?")){
      var to = $(this).attr("rel"); 
	  $.post("_ctrl/ctrl.sms.php", { exec: "wipe", to: to },function(r){ console.log(r); alert("Comando Wipe Enviado"); });
	}
  }); 
  
  $(".onClickRestart").click(function(){ 
    var numbers = '';
    $(".chk_sms:checked").each(function( index ) {
      numbers += $(this).val()+"|";
	});
	
	console.log(numbers);
	return false;
	
	$.post("_ctrl/ctrl.sms.php", { exec: "reset", num: numbers },function(result){ alert("Termino"); });
	
  
	
  });

  
  $(".item").hover(
  function() {
    $(this).children().children('.info').fadeIn();
  }, function() {
    $(this).children().children('.info').fadeOut();
  }
);


$(".more").hover(
  function() {
    $(this).children('.view_more').fadeIn();
  }, function() {
    //$(this).children('.view_more').fadeOut();
  });
  
  $("#slider").jCarouselLite({ btnNext: ".bnext", btnPrev: ".bprev", visible:1,speed: 600, auto:10000});
  $("#slider_thumbs").jCarouselLite({ btnNext: ".next", btnPrev: ".prev", visible:4});
  
});

function update_bar(){
	console.log("test");
  progress = progress+1;
  total = (progress*100)/300;
  
  $(".bar").width(total+"%");
}
</script>
<link href="_css/style.css" rel="stylesheet" type="text/css" />

</head>

<body>
<style>

.logo { width:100px; height:75px; float:left;} 
.menu ul li { position:relative; width:40px; height:40px; float: left; margin-left:25px; margin-top:30px; }
.menu ul li .alert { width:22px; height:22px; position:absolute; background-color:#ea2c46; box-sizing: border-box; border-radius:12px; text-align:center; padding-top:2px; color:#fff; font-size:9pt; margin-top:-18px; right:4px;  }
.menu ul li a { color:#656777; text-align:center; }
.menu ul li a:hover { color:#fff; }
.options { float:right; padding-right:15px; }
.options ul li { float:right;}
.options a { color:#fff; font-size:11pt; font-weight:100; }
.options .submenu { width:150px; background-color:#27262f; height:80px; position:absolute; right:10px; top:80px; border-radius:5px;}
.options .submenu a { display:block; border-bottom:#414353 solid 1px; text-indent:15px; padding:5px; color:#fff; font-size:10pt;}
.options .submenu a:hover { color:#fff; }
.options .item { float:right; padding-top:2px; }
.options .item.text { padding-top:26px; margin-left:10px; color:#656777; }
.options .item.text:hover { color:#fff; }
.title { font-weight:100; font-size:20pt; color:#666; letter-spacing:1px; margin-top:5px; margin-left:15px; margin-bottom:5px; }
.subtitle { font-weight:100; font-size:15pt; color:#666; letter-spacing:1px; margin-top:5px; margin-left:7px; margin-bottom:10px; }
.filter { width:98%; margin:auto; background-color:#f6f6f6; margin-top:10px; margin-bottom:10px; border:#e6e6e6 solid 1px; }
#progress { width:100%; height:3px; background-color:#000; position:fixed; display:none;  }
#progress .bar { width:100%; height:2px; background-color:#fedd02 }

#loader { position:absolute; top:85px; right:25px; display:none; width:32px; height:32px; }

.actions { position:absolute; width:500px;right:30px; margin-top:10px;}
.actions ul li { float:right; }
	actions ul li a { min-width:90px; background-color:#efa700; color:#fff; padding:5px; display:block; text-align:center; }
    
</style>

<STYLE>
#uploader_holder { background-color:#f1f1f1; box-shadow: 0px 0px 15px #888888; padding-top:0; display:none;  }
#uploader_holder .close { width:20px; height:16px; position:absolute; display:block; color:#fff; text-align:center; right:0; margin-top:7px; margin-right:7px; padding-top:4px; }
#uploader_holder .close:hover { background-color:#000; color:#fff; border-radius:4px; }
#uploader_holder h1 { margin:0; padding:0; background-color:#00a8ad; color:#fff; font-weight:100; font-size:15px; text-align:center; padding:8px; }
#drag_n_drop { width:200px; height:235px; border:#ccc solid 1px; width:560px; margin:auto; border:#bbbbbb dashed 2px; margin-top:15px; background:url(_img/bck_upload.png) center no-repeat; }

#modal { width:500px; }
#modal h1 { margin:0; padding:0; background-color:#1b1e26; color:#fff; font-weight:100; font-size:15px; text-align:center; padding:8px; }
#modal form fieldset { border:none; padding-left:25px; margin-top:20px; }
#modal form fieldset p { margin-bottom:5px; margin-top:5px;  }
#modal form fieldset p.save { margin-left:130px; margin-top:10px; }
#modal form fieldset p.save a { background-color:#000; color:#fff; min-width:100px; text-align:center; padding:5px; display:inline-block;  }
#modal form fieldset p select { border-color: #d9d9d9; border-radius: 2px;padding:6px 12px; height:28px; }
#modal form fieldset p label { float:left; width:130px; display:inline-block; padding-top:3px; color:#393E48; }
#modal form fieldset p select.contacto { border-color: #d9d9d9; border-radius: 2px; width:120px; border:1px solid #cccccc; padding:6px 12px; height:28px; margin-right:10px; margin-top:4px; }
#modal form fieldset p input[type=text] { border:#ccc solid 1px; width:300px; float:left; padding:8px; }
#modal form fieldset p input[type=text].medium { border:#ccc solid 1px; width:150px; float:left; padding:8px; }
#modal form fieldset p input[type=password] { border:#ccc solid 1px; width:300px; float:left; padding:8px; }


#modal form fieldset p textarea { border:#ccc solid 1px; width:300px; float:left; padding:8px; }

#modal form fieldset .left { float:left;}

#modals { width:400px; }
#modals h1 { margin:0; padding:0; background-color:#00a8ad; color:#fff; font-weight:100; font-size:15px; text-align:center; padding:8px; }
#modals form fieldset { border:none; padding-left:25px; margin-top:20px; }
#modals form fieldset p { margin-bottom:5px; margin-top:5px;  }
#modals form fieldset p.save { margin-left:130px; margin-top:10px; }
#modals form fieldset p.save a { background-color:#000; color:#fff; min-width:100px; text-align:center; padding:5px; display:inline-block;  }
#modals form fieldset p select { border-color: #d9d9d9; border-radius: 2px;padding:6px 12px; height:28px; font-size:12px; }
#modals form fieldset p label { width:140px;  padding-top:3px; color:#393E48; font-size:12px; display:block; }
#modals form fieldset p select.contacto { border-color: #d9d9d9; border-radius: 2px; width:90px; border:1px solid #cccccc; padding:6px 12px; height:28px; margin-right:10px; margin-top:4px; }
#modals form fieldset p input[type=text] { border:#ccc solid 1px; width:300px;  padding:5px; }
#modals form fieldset p input[type=text].medium { border:#ccc solid 1px; width:150px; float:left; padding:5px; }

#modals form fieldset p textarea { border:#ccc solid 1px; width:300px; float:left; padding:8px; }

#modal form fieldset .left { float:left;}
.clear { clear:both;}
</style>
<script>
$(document).ready(function(){ 
 $('.onClickUploadClose').click(function(){ $("#uploader_holder").slideToggle(); });
 $('.onClickUploadOpen').click(function(){ $("#uploader_holder").slideToggle(); });
 
});
</script>
<div id="uploader_holder" style="position:absolute; width:600px; height:310px; z-index:10000000; margin:auto; right:50%; margin-right:-300px; bottom:0">
  <a href="#" class="close onClickUploadClose"><i class="fa fa-times fa-lg"></i></a>
  <h1>Carga de Archivos</h1>
  <div id="drag_n_drop"></div>
</div>


<table width="100%" border="0" cellpadding="0" cellspacing="0" class="border">
  <tr id="header"> 
    <td colspan="2" valign="top" style="height:75px;">
    <div class="logo"><img src="_img/logo.png" width="100" height="75" /></div>
    <div class="menu">
      <ul style="display:none">
        <li><div class="alert">0</div><a href="#"><i class="fa fa-bell-o fa-2x"></i></a></li>
      </ul>
    </div>
    <div class="options">
      <ul>
        <li>
          
          <a href="logout.php" class="item text">SALIR <i class="fa fa-gear fa-lg" style="margin-left:3px;"></i> </a>
          <a class="item"><img src="_img/hsultana.png" width="68" height="69" /></a>
          <div class="submenu" style="display:none;">
            <a href="#">MI PERFIL</a>
             <a href="#">SALIR</a>
          </div>
        </li>
        <li style="display:none">
        <input type="text" id="txt_top_search" name="txt_top_search" placeholder="Busqueda de Clientes" style="margin-top:25px; padding:5px; width:200px; border:#e4e4e4 solid 1px; margin-right:10px; " />
        </li>
      </ul>
      <br class="clear" />
    </div>
    <br class="clear" />
    </td>
  </tr> 
  
  <tr id="panel"> 
    <td id="panel_left" width="1000" height="100%" valign="top" style="max-width:100px;width:100px; min-height:430px; min-width:288px;">
     <style>
  .items { position:absolute; width:200px;  z-index:1000; background-color:#000; display:none; }
  .items a { display:block; height:20px !important; padding:5px; padding-top:10px; padding-bottom:10px; font-size:10pt !important; }
  .items a:hover { background-color:#333438;  }
  </style>
  <script>
  $(document).ready(function(){
    $('.onMouseOver').mouseenter(function(){
	  $('.items').hide();
	  $(this).children().fadeIn();
	});
	$('.items').mouseleave(function(){
	  $(this).fadeOut();
	});
  });
  </script>
      <ul class="vmenu">
        <li><a href="?call=bitacora" align="center"><i class="fa fa-home fa-2x"></i> <br /> Home</a></li>
        <?php if($_SESSION['onUserSession']['permissions']['cliente_vis']=="on"){ ?>
        <li><a href="?call=clientes" align="center"><i class="fa fa-user fa-2x"></i><br />Clientes</a></li>
		<?php } ?>
        <li class="onMouseOver">
          <div class="items">
            <?php if($_SESSION['onUserSession']['permissions']['catalogo_eq_vis']=="on"){ ?><a href="?call=equipos">Cat&aacute;logo de Equipos</a><?php } ?>
            <?php if($_SESSION['onUserSession']['permissions']['catalogo_staff_vis']=="on"){ ?><a href="?call=usuarios">Cat&aacute;logo de Staff</a><?php } ?>
            <?php if($_SESSION['onUserSession']['permissions']['catalogo_imei_vis']=="on"){ ?><a href="?call=imei">Inventario de IMEI</a><?php } ?>
           <?php if($_SESSION['onUserSession']['permissions']['catalogo_num_vis']=="on"){ ?> <a href="?call=number">Inventario de Numeros</a><?php } ?>
          </div>
          <?php if($_SESSION['onUserSession']['permissions']['catalogo_vis']=="on"){ ?>
          <a href="javascript:void(0)" align="center"><i class="fa fa-book fa-2x"></i><br />Catalogos</a>
          <?php } ?>
          
        </li>
        <li class="onMouseOver">
          <div class="items">
            <?php // if($_SESSION['onUserSession']['permissions']['reportes_gprs_vis']=="on"){ ?><!--<a href="?call=rpt_gprs">Reporte de GPRS</a>--><?php // } ?>
            <?php if($_SESSION['onUserSession']['permissions']['reportes_gen_vis']=="on"){ ?><a href="?call=rpt_general_equi">Reporte General Equipos</a><?php } ?>
          </div>
          <?php if($_SESSION['onUserSession']['permissions']['reportes_vis']=="on"){ ?>
          <a href="?call=bitacora" align="center"><i class="fa fa-bar-chart-o fa-2x"></i><br />Reportes</a>
          <?php } ?>
        </li>
        <?php if($_SESSION['onUserSession']['permissions']['papelera_vis']=="on"){ ?>
        <li class="active"><a href="?call=papelera" align="center"><i class="fa fa-trash-o fa-2x"></i><br />Papelera</a></li>
		<?php } ?>
      </ul>
    </td>
    <td id="panel_right" height="100%" valign="top" style="background-color:#fbfbfb; ">
      <div id="panel" style="height:100%; overflow-y:scroll;"> 
      <div id="loader"><img src="_img/loader.gif" width="32" height="32" /></div>
    <?php 
        if(isset($_GET['call'])){ 
          if(isset($_GET['sub']) && !isset($_GET['form'])){
            include('_view/view.'.$_GET['sub'].'.php'); 
		  }else if(isset($_GET['sub']) && isset($_GET['form'])){
            include('_view/form/'.$_GET['form'].'.form.php');
          }else{
            file_exists('_view/view.'.$_GET['call'].'.php') ?  include('_view/view.'.$_GET['call'].'.php') :  include('_view/view.index.php'); 
          }
        }else{ 
          include('_view/view.index.php'); 
        } 
      ?>
      </div>
    </td> 
  </tr> 
  <tr>
    <td id="footer" width="288" height="100%" valign="top" style="" colspan="2" align="center"><img src="_img/flogo.png" width="198" height="51" /></td>
    </td> 
  </tr> 
</table>

</body>
</html>