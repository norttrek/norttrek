<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Norttrek - Rastreo Satelital GPS</title>
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
    if(($_GET['s']==401)){
    ?>
      <br>
     <div class="error"><p class="error" align="center">Error, revise su usuario y contrase&ntilde;a.</p></div>
     <br>
     <?php } ?>
     <?php 
    if(($_GET['s']==40)){
    ?>
      <br>
     <div class="error"><p class="error" align="center">
      Le informamos que su cuenta ha sido inhabilitada por falta de pago, favor de liquidar las facturas pendientes para poder habilitar su usuario.
<br><br>Para dudas y/o aclaraciones favor de comunicarse con su Ejecutivo de Ventas. 
</p></div>
     <br>
     <?php } ?>
     <?php 
     if(($_GET['s']==41)){
    ?>
      <br>
     <div class="error"><p class="error" align="center">Cuenta inhabilitada, favor de comunicarse con su proveedor </p></div>
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