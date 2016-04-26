 <style>
   .filter { padding-top:20px; padding-bottom:20px; margin-bottom:20px; }
  .filter ul { margin-left:10px;}
  .filter ul li { float:left; }
  .filter ul li:nth-child(2)  { margin-right:15px; }
  .filter ul li:nth-child(3)  { margin-right:15px;  }
  .filter ul li:nth-child(4)  { margin-right:15px;  }
  .filter ul li.label { color:#999; margin-right:15px; }
  .filter ul li a.btn_filter { background-color:#0092f8; color:#fff; display:inline-block; width:60px; padding-top:5px; padding-bottom:5px; text-align:center; margin-top:-3px; border:#0081e1 solid 1px; }
  .filter ul li a.btn_filter:hover { background-color:#0081e1; }
  .filter ul li input.search { width:300px; padding:5px; padding-top:7px; padding-bottom:7px; border:#ccc solid 1px; margin-top:-7px; background:url(_img/cal.png) right no-repeat #fff; }
  .onoffswitch {
    position: relative; width: 42px;
    -webkit-user-select:none; -moz-user-select:none; -ms-user-select: none;
}
.onoffswitch-checkbox {
    display: none;
}
.onoffswitch-label {
    display: block; overflow: hidden; cursor: pointer;
    border: 2px solid #999999; border-radius: 21px;
}
.onoffswitch-inner {
    display: block; width: 200%; margin-left: -100%;
    transition: margin 0.3s ease-in 0s;
}
.onoffswitch-inner:before, .onoffswitch-inner:after {
    display: block; float: left; width: 50%; height: 13px; padding: 0; line-height: 13px;
    font-size: 9px; color: white; font-family: Trebuchet, Arial, sans-serif; font-weight: bold;
    box-sizing: border-box;
}
.onoffswitch-inner:before {
    content: "ON";
    padding-left: 5px;
    background-color: #00ACDE; color: #FFFFFF;
}
.onoffswitch-inner:after {
        content: "OFF";
    padding-right: 5px;
    background-color: #333333;
    color: #E4E2E2;
    text-align: right;
}
.onoffswitch-switch {
    display: block; width: 8px; margin: 2.5px;
    background: #FFFFFF;
    position: absolute; top: 0; bottom: 0;
    right: 25px;
    border: 2px solid #999999; border-radius: 21px;
    transition: all 0.3s ease-in 0s; 
}
.onoffswitch-checkbox:checked + .onoffswitch-label .onoffswitch-inner {
    margin-left: 0;
}
.onoffswitch-checkbox:checked + .onoffswitch-label .onoffswitch-switch {
    right: 0px; 
}
  
  
    </style>

<div id="subcontent" >
<div class="actions">
    <ul>
      <li><?php if($_SESSION['onUserSession']['permissions']['cliente_cre']=="on"){ ?><a href="_view/_form/frm.client.php" class="modal fancybox.ajax">Agregar</a><?php } ?></li>
    </ul>
  </div>
  <h1 class="title"> Clientes</h1>
  
  <div id="result">
   
    <div class="filter">
      <ul>
       
        <li class="label"><i class="fa fa-cogs fa-2x"></i> </li>
        <li><input type="text" id="txt_search" name="txt_search" class="search" placeholder="Busqueda..."  />
        </li>
          
     
        <li><a href="javascript:filter()" class="btn_filter">Filtrar</a></li>
      </ul>
      <br class="clear" />
    </div>
    <table id="tbl_client" class="result" cellpadding="0" cellspacing="0" width="100%" align="center">
      <thead>
        <th align="left"><i class="fa fa-signal"></i> No.</th>
        <th align="left" class="order">Activar</th>
        <th align="left" class="order" abbr="client"><i class="fa fa-signal"></i> Nombre</th>
        <th align="left"><i class="fa fa-signal"></i> Contacto</th>
        <th align="left" class="order" abbr="date_reg"><i class="fa fa-signal"></i> No. Unidades</th>
        <th align="left" class="order" abbr="date_reg"><i class="fa fa-signal"></i> Registro</th>
    <th align="left"></th>
      </thead>
      <tbody id="client">
      </tbody>
      <tfoot>
        <td></td>
      </tfoot>
    </table>
  </div>
</div>
<script>
var data;
$(document).ready(function() { 

  $(".result thead th.order").click(function(){
    var order = null;
    var col = $(this).attr("abbr");
    if($(this).hasClass('asc')){ 
      $(this).removeClass('asc').addClass('desc');
      order = 'desc';
    }else{
    $(this).removeClass('desc').addClass('asc');
    order ='asc';
    }
    
    data.order.order_by = col+' '+order;
    $("#"+data.table.id).render_table(data); 
    
   });

   table = null;
   data = {
       table  : { id:"tbl_client",source: "client", action: "datagrid"},
     filter : { limit:1000, idate: null,edate:null, search: null},
     order  : { order_by: 'client ASC', opt:null},
   }
   //table.source.order = "sort ASC";
   $("#"+data.table.id).render_table(data); 
   



  });
  
  function filter(){ 
  data.filter.search = $("#txt_search").val(); 
   $("#"+data.table.id).render_table(data); 
  }

function activeUser(id_user){
  if($("#myonoffswitch"+id_user).is(':checked'))
 {
  checked=1;
 }else{
  checked=0;
 }
  $.ajax({
      url: '/_functions/functions.php', // url del recurso
      type: "post", // podría ser get, post, put o delete.
      data: { action:'update_active_client',id: id_user, onoff:checked }, // datos a pasar al servidor, en caso de necesitarlo
      success: function (r) {
         if(r==1){
            alert('Cliente actualizado'); 
         }else{
          alert('Error al actualizar el cliente');
         }
         
      }              
  });
}
 
   
</script>