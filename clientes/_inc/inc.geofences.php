<div id="geofences" class="window">
  <a href="javascript:void(0)" class="btn_close onClickCloseWindow"><i class="fa fa-times fa-lg"></i></a></a>
  <h1>Administraci&oacute;n de Geocercas</h1>
  <div class="container">
    <div id="toolbar">
      <ul>
        <li><a href="javascript:void(0)" class="onClickDrawCircle inactive"><i class="fa fa-circle-o fa-lg"></i></a></li>
        <li><a href="javascript:void(0)" class="onClickDrawPolygon inactive"><i class="fa fa-location-arrow fa-lg"></i></a></li>
        <li><a href="javascript:void(0)" class="onClickRemove inactive"><i class="fa fa-trash-o fa-lg"></i></a></li>
        <li><a href="javascript:void(0)" class="onClickOpenWindow inactive" rel="geofence"><i class="fa fa-save fa-lg"></i></a><br class="clear"></li>
        <li style="display:none;"><a href="javascript:void(0)" class="onClickOpenWindow inactive last" rel="geofences"><i class="fa fa-file-text-o fa-lg"></i></a><br class="clear"></li>
      </ul>
      <br class="clear" />
    </div>
    <div class="datagrid">
      <table id="tbl_geofences" border="0" width="100%" cellpadding="0" cellspacing="0">
        <thead>
            <tr>
              <th width="100">Geocerca</th>
              <th width="200">Nombre</th>
              <th>Acciones</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>
    </div>
  </div>