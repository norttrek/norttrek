<div id="georoute_add" class="window" style="height:500px;">
  <a href="javascript:void(0)" class="btn_close onClickCloseWindow"><i class="fa fa-times fa-lg"></i></a></a>
  <h1>Nueva Geo-Ruta</h1>
  <div class="container" style="">
    <input type="text" id="txt_point_a" name="txt_point_a" placeholder="origen"/>
    <input type="text" id="txt_point_b" name="txt_point_b" placeholder="destino"/>
    <input type="hidden" id="point_a" name="point_a" value="" />
    <input type="hidden" id="point_b" name="point_b" value="" />
    <a href="javascript:void(0)" class="onClickGetDirections" style="display:block; color:#fff;">Generar Ruta</a>
    <Br />
    
    <input type="checkbox" id="chk_init_groute" name="chk_init_groute" class="onChangeStartGroute" />
    <label>Comenzar Ruta</label>
    <div class="datagrid">
   <table id="tbl_georoute" border="0" width="100%" cellpadding="0" cellspacing="0">
     <thead>
     <tr>
       <th>Nombre</th>
       <th>Distancia (km)</th>
       <th>Tiempo</th>
     </tr>
     </thead>
     <tbody>
     </tbody>
   </table>
   </div>
   <div class="form">
     <input type="text" id="txt_route_name" name="txt_route_name" value="" placeholder="Nombre de Geo-Ruta" style="width:290px;" /> <a href="javascript:void(0)" class="onClickSaveRouteData" style="color:#fff;">Guardar Checkpoints</a>
   </div>
  </div>
</div>