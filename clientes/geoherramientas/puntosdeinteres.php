<div id="interes" class="pois navmenu navmenu-default navmenu-fixed-left offcanvas" role="navigation">
   <div class="col-lg-10"><h4>Administraci&oacute;n de Puntos de Inter&eacute;s</h4></div>
  <div class="col-lg-2">
    <a href="javascript:void(0)" side="interes" id="ClosegeocercasBtn" class="Closeside btn_close onClickCloseWindow"><i class="fa fa-times fa-lg"></i></a></a>
  </div>
   
    <p align="right"><input type="checkbox" id="chk_show_poi" name="chk_show_poi" value="0" /><label>Mostrar Puntos de Inter&eacute;s</label></p>
 
      <table id="tbl_pois" border="0" width="100%" cellpadding="0" cellspacing="0">
        <thead>
            <tr>
              <th width="100">Punto de Inter&eacute;s</th>
              <th>Coordenadas</th>
              <th></th>
             </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
 
       <form style="margin-top:5px;"> 
     <input type="text" id="txt_poi" name="txt_poi" placeholder="Nombre de Punto de Inter&eacute;s" style="width:195px;" />
     <a href="javascript:void(0)" class="btn_save onClickSavePoi btn_save_gz">GUARDAR</a>
     </form>
     <p class="small" style="font-size:11px;">Haga doble click para crear un marcador en el mapa y doble click para eliminarlo.</p>  
</div>
<script>
    $("#interes").offcanvas({ autohide: false, toggle: false });
</script>