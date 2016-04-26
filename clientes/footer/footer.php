<footer class="fix_footer">
 <div class="container-fluid">
  <div class="row">
     <div class="col-md-12 footer">
      <div class="upbutton">
          <span class="glyphicon glyphicon-triangle-top icon_up up" aria-hidden="true"></span>
           <span class="glyphicon glyphicon-triangle-bottom icon_up down" aria-hidden="true" style="display:none"></span>
      </div>
      <div id="reportInfo"></div>
     <table id="tbl_route" style="margin-bottom:0px" class="table table-striped"  border="0" width="100%" cellpadding="0" cellspacing="0">
          <thead></thead>
          <tbody>
          </tbody>
        </table>
        <table id="tbl_report_geo" class="table table-striped">
  <table id="tbl_bottom"class="table table-striped"  border="0" width="100%" cellpadding="0" cellspacing="0">
        <thead>
          <tr>
            <th class="sort" align="left"><i class="fa fa-truck fa-lg" style=" color:#737987;" > </i> Unidad</th>
            <th class="sort" align="left"><i class="fa fa-power-off"></i></th>
            <th class="sort" align="left"><i class="glyphicon glyphicon-list"></i>Evento</th>
            <th class="sort" align="left"><i class="fa fa-arrow-circle-o-up   fa-rotate-0 "></i> Orientación</th>
            <th class="sort" align="left"><i class="fa fa-tachometer fa-lg" style=" color:#737987;" > </i> Vel </th>
            <th class="sort" align="left"><i class="fa fa-clock-o fa-lg" style=" color:#737987;" > </i> Fecha</th>
            <th class="sort" align="left"><i class="fa fa-map-marker fa-lg" style=" color:#737987;" > </i> Coordenadas</th>
            <th class="sort" align="left"><i class="fa fa-map-marker fa-lg" style=" color:#737987;" > </i> Referencia</th>
          </tr>
        </thead>
        <tbody>
          <?php   
foreach ($footer_content as $unidad_f) {
  echo "<tr>";
  echo '<td>'.$unidad_f['nombre'].'</td>';
  echo '<td>'.$unidad_f['motor'].'</td>';
  echo '<td>'.$unidad_f['evento'].'</td>';
  echo '<td>'.$unidad_f['orientacion'].'</td>';
  echo '<td>'.round($unidad_f['velocidad']).'</td>';
  echo '<td>'.$unidad_f['fecha'].'</td>';
  echo '<td>'.$unidad_f['coordenadas'].'</td>';
    echo '<td>'.$unidad_f['referencia'].'</td>';
  echo "</tr>";
}
?>
        </tbody>
      </table>
 
</table>

     </div>
     </div>
  </div>
  </footer>