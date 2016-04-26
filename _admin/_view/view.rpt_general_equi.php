
<div id="subcontent" >
  <div class="actions">
    <ul>
      <li><a href="_view/_form/frm.imei.php" class="modal fancybox.ajax">Agregar</a></li>
    </ul>
  </div>
  <h1 class="title"> REPORTE GENERAL DE EQUIPOS</h1>
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
        <li><input type="text" id="txt_search" name="txt_search" class="search" placeholder="Busqueda..."  /></li>
        <li style="display:none;"><select><option value="NULL">Seleccione</option><option>Asignado</option><option>No Asignado</option></select></li>
          
     
        <li><a href="javascript:filter()" class="btn_filter">Filtrar</a></li>
      </ul>
      <br class="clear" />
    </div>
    <table id="tbl_rpt_dev" class="result" cellpadding="0" cellspacing="0" width="100%" align="center">
      <thead>
        <th align="left"><i class="fa fa-signal"></i>Cliente</th>
        <th align="left"><i class="fa fa-signal"></i>Alias</th>
        <th align="left"><i class="fa fa-signal"></i>Equipo</th>
        <th align="left"><i class="fa fa-signal"></i>IMEI</th>
        <th align="left"><i class="fa fa-signal"></i>No</th>
        <th align="left"><i class="fa fa-signal"></i>No. de Serie</th>
        <th align="left"><i class="fa fa-signal"></i>Cuenta</th>
      </thead>
      <tbody>
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
	     table  : { id:"tbl_rpt_dev",source: "client", action: "datagrid_dev_rpt"},
		 filter : { limit:1000, search: null},
		 order  : { order_by: 'n.no ASC', opt:null},
   }
   //table.source.order = "sort ASC";
   $("#"+data.table.id).render_table(data); 
  });
  
  function filter(){ 
	data.filter.search = $("#txt_search").val();
   $("#"+data.table.id).render_table(data); 
  }
  
 
</script>