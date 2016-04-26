<div id="geocercas_a" class="pois navmenu navmenu-default navmenu-fixed-left offcanvas" role="navigation">
  <div class="col-lg-10"><h4>Administraci&oacute;n de Geocercas</h4></div>
  <div class="col-lg-2">
    <a href="javascript:void(0)" side="geocercas_a" id="ClosegeocercasBtn" class="Closeside btn_close onClickCloseWindow"><i class="fa fa-times fa-lg"></i></a></a>
  </div>
  <div class="col-lg-12">
      <div id="toolbar">
      <ul> 
        <li><a id="maptool" href="javascript:void(0)" class="maptool onClickDrawCircle inactive"><i class="fa fa-circle-o fa-lg"></i></a></li>
        <li><a id="maptool" href="javascript:void(0)" class="maptool onClickDrawPolygon inactive"><i class="fa fa-location-arrow fa-lg"></i></a></li>
        <li><a id="maptool" href="javascript:void(0)" class="maptool onClickRemove inactive"><i class="fa fa-trash-o fa-lg"></i></a></li> 
        <li style="display:none;"><a href="javascript:void(0)" class="onClickOpenWindow inactive last" rel="geofences"><i class="fa fa-file-text-o fa-lg"></i></a><br class="clear"></li>
      </ul>
      <br class="clear" />
    </div>
  </div>
  <div class="col-lg-12">
     <div id="tableGeo">
      <table id="tbl_geofences" class="table table-striped">
        <thead>
            <tr>
              <th  >Geocerca</th>
              <th  >Nombre</th>
               
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
  </div>
</div>
<div class="col-lg-12" id="geomap">
</div>
<script>
$("#geocercas_a").offcanvas({ autohide: false, toggle: false  });
$('.maptool').click(function(){
  $('.maptool').removeClass('activetool');
  $(this).addClass('activetool');

})
    
    // objClient.get_geofences();
</script>