
<div id="subcontent" >
  <div class="actions">
    <ul>
      <li><?php if($_SESSION['onUserSession']['permissions']['catalogo_eq_cre']=="on"){ ?><a href="_view/_form/frm.device.php" class="modal fancybox.ajax">Agregar</a><?php } ?></li>
    </ul>
  </div>
  <h1 class="title"> Equipos</h1>
  <div id="result">
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
	</style>
    <div class="filter">
      <ul>
       
        <li class="label"><i class="fa fa-cogs fa-2x"></i> </li>
        <li><input type="text" id="txt_search" name="txt_search" class="search" placeholder="Busqueda..."  />
        </li>
          
     
        <li><a href="javascript:filter()" class="btn_filter">Filtrar</a></li>
      </ul>
      <br class="clear" />
    </div>
    <table id="tbl_devices" class="result" cellpadding="0" cellspacing="0" width="100%" align="center">
      <thead>
        <th align="left"><i class="fa fa-signal"></i> Modelo</th>
        <th align="left"><i class="fa fa-signal"></i> Descripci&oacute;n</th>
		<th align="left"></th>
      </thead>
      <tbody id="device">
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
  
   table = null;
   data = {
	     table  : { id:"tbl_devices",source: "device", action: "datagrid"},
		 filter : { limit:1000, idate: null,edate:null, search: null, status : 1},
		 order  : { order_by: 'id DESC', opt:null},
   }
   //table.source.order = "sort ASC";
   $("#"+data.table.id).render_table(data); 
  });
  
  function filter(){ 
	str = $("#txt_search").val();
	data.filter.search = str;
   $("#"+data.table.id).render_table(data); 
  }
  
 
</script>