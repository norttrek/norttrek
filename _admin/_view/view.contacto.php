<div id="contacto" style="width:500px; margin:0; padding:0; background:url(_img/bck_contacto.jpg) no-repeat;">
   <form action="enviar.php" method="post" id="frm_contacto" name="frm_contacto">
      <fieldset>
        <p><label>Nombre </label><input type="text" id="txt_nombre" name="txt_nombre" /><br class="clear" /></p>
        <p><label>E-mail </label><input type="text" id="txt_email" name="txt_email" /><br class="clear" /></p>
        <p><label>Tel&eacute;fono </label><input type="text" id="txt_telefono" name="txt_telefono" /><br class="clear" /></p>
        <!-- <p><label>Ciudad </label><input type="text" id="txt_ciudad" name="txt_ciudad" /><br class="clear" /></p> -->
        <p><label>Mensaje </label><textarea id="txt_mensaje" name="txt_mensaje"></textarea><br class="clear" /></p>
        <p class="btn"><a href="javascript:submitf()"><img src="_img/btn_enviar.png" width="118" height="51" /></a></p>
      </fieldset>
    </form>
    
    <script>
      function submitf(){
		var flag = true;
		if($("#txt_nombre").val()=="" || $("#txt_email").val()=="" || $("#txt_telefono").val()=="" || $("#txt_mensaje").val()==""){ alert("Debes llenar todos los campos marcados con asterisco."); flag = false; }
		if(flag){
		  $("#frm_contacto").submit();
		}
	  }
    </script>

</div>