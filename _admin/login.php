<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Norttrek - Rastreo Satelital GPS</title>
<link rel="shortcut icon" href="http://norttrek.com/site/wp-content/uploads/2014/07/logo50x50.ico"  />
<link rel="apple-touch-icon-precomposed" href="http://norttrek.com/site/wp-content/uploads/2014/08/Norttrek-Rastreo-Satelital-GPS-Icon-57x57.png">
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="http://norttrek.com/site/wp-content/uploads/2014/08/Norttrek-Rastreo-Satelital-GPS-Icon-114x114.png">
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="http://norttrek.com/site/wp-content/uploads/2014/08/Norttrek-Rastreo-Satelital-GPS-Icon-72x72.png">
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="http://norttrek.com/site/wp-content/uploads/2014/08/Norttrek-Rastreo-Satelital-GPS-Icon-144x144.png">
<link rel="stylesheet" href="_css/login.css">
<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,600&subset=latin' rel='stylesheet' type='text/css'>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
<script>
$(document).ready(function(){ 
  $('.onClickLogin').click(function(){ $("#frm_login").submit(); });
  $(document).keypress(function(e){  if(e.which == 13){ $("#frm_login").submit(); } });
  
});
</script>
</head>

<body>

<div id="login">
  <div class="logo" align="center"><img src="_img/top_logo.png" width="198" height="51" /></div>
    <?php 
	  if(isset($_GET['s'])){
	  ?>
      <br>
     <div class="error"><p class="error" align="center">Error, revise su usuario y contrase&ntilde;a.</p></div>
     <br>
     <?php } ?>
  <form id="frm_login" name="frm_login" method="post" action="_ctrl/ctrl.auth.php">
    <fieldset>
      <p><label class="inline">Usuario</label><input type="text" id="txt_user" name="txt_user" value="" class="inline"/></p>
      <p><label class="inline">Contrasena</label><input type="password" id="txt_password" name="txt_password" value="" class="inline"/></p>
      <p align="center"><a href="javascript:void(0)" class="btn_save onClickLogin">Entrar</a></p>
    </fieldset>
  </form>
</div>

</body>
</html>
