<div id="pois" class="window">
  <a href="javascript:void(0)" class="btn_close onClickCloseWindow"><i class="fa fa-times fa-lg"></i></a></a>
  <h1>Administraci&oacute;n de Puntos de Inter&eacute;s</h1>
  <div class="container">
    <p align="right"><input type="checkbox" id="chk_show_poi" name="chk_show_poi" value="0" /><label>Mostrar Puntos de Inter&eacute;s</label></p>
    <div class="datagrid">
      
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
      </div>
       <form style="margin-top:5px;"> 
     <input type="text" id="txt_poi" name="txt_poi" placeholder="Nombre de Punto de Inter&eacute;s" style="width:195px;" />
     <a href="javascript:void(0)" class="btn_save onClickSavePoi btn_save_gz">GUARDAR</a>
     </form>
     <p class="small" style="font-size:11px;">Haga doble click para crear un marcador en el mapa y doble click para eliminarlo.</p>
    </div>
   
  </div>