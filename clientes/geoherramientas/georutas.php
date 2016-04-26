<div id="georutasb" class="navmenu navmenu-default navmenu-fixed-left offcanvas" role="navigation">
   <div id="id_client" cliente="<?php echo $_SESSION['logged']['id_client'] ?>" class="col-lg-10"><h4> </h4></div>
  <div class="col-lg-2">
    <a href="javascript:void(0)" side="georutasb" id="ClosegeocercasBtn" class="Closeside btn_close onClickCloseWindow"><i class="fa fa-times fa-lg"></i></a></a>
  </div>
  <div class="col-lg-12">
  	<div id="toolbar">
      <ul> 
        <li><a id="maptool" href="javascript:void(0)" class="maptool onClickDrawPoliline inactive"><i class="fa fa-location-arrow fa-lg"></i></a></li>
                <li><a id="maptool" href="javascript:void(0)" class="maptool onClickSavePoliline inactive">save</a></li>

        <!--<li><a id="maptool" href="javascript:void(0)" class="maptool onClickRemove inactive"><i class="fa fa-trash-o fa-lg"></i></a></li> -->
       </ul>
     </div>
     <div class="col-lg-12">
      <div class="row">
        <div class="form-group">
          <label for="exampleInputEmail1">Nombre de la Pista</label>
          <input type="text" class="form-control"  name="trak_name" id="trak_name" placeholder="Nombre">
        </div>

        <div class="form-group">
          <label for="exampleInputEmail1">Metros de tolerancia</label>
          <input type="email" class="form-control" name="tolerancia" id="tolerancia" onkeypress="return validar(event)" placeholder="Metros de tolerancia">
        </div>
       
      
       <img id="track" src="">
      <table id="tbl_tracks" border="0" width="100%" cellpadding="0" cellspacing="0">
        <thead>
            <tr>
              <th width="100">Pistas</th>
              
             </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
    </div>
  </div>
   
    
  
    
</div>
</div>
<script> 
 
function validar(e) {
    tecla = (document.all)?e.keyCode:e.which;
    if (tecla==8) return true;
    patron = /\d/;
    te = String.fromCharCode(tecla);
    return patron.test(te); 
} 

    $("#georutasb").offcanvas({ autohide: false, toggle: false  });
</script>