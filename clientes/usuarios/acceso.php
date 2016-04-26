<div id="acceso" class="containerHeadSide navmenu navmenu-default navmenu-fixed-left offcanvas" role="navigation">
  <div class=" row headSide">
  <div class="col-lg-10"> Administraci&oacute;n de Usuarios </div>
  <div class="col-lg-2">
    <a href="javascript:void(0)" side="acceso" id="ClosegeocercasBtn" class="Closeside btn_close onClickCloseWindow"><i class="fa fa-times fa-lg"></i></a></a>
  </div>
 </div>
  <div class="col-lg-12">
    <a class="addUser" >Crear nuevo Usuario</a>
  </div>
  <div class="col-lg-12" id="users">
  
    
 

 
        <table class="table table-striped" id="tbl_users"  >
          <thead>
            <tr>
              <th>Usuario</th>
              <th>Contrase&ntilde;a</th>
              <th>Expira</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table> 
  </div>

<div class="col-lg-12" id="editUser"></div>
<div class="col-lg-12" id="addUser"></div>
 

</div> 
<script>
$("#acceso").offcanvas({ autohide: false, toggle: false  });
 
</script>