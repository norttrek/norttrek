
<div id="subcontent" >
  <div class="actions">
    <ul>
      <li><?php if($_SESSION['onUserSession']['permissions']['catalogo_num_cre']=="on"){ ?><a href="_view/_form/frm.number.php" class="modal fancybox.ajax">Agregar</a><?php } ?></li>
    </ul>
  </div>
  <h1 class="title"> Inventario de Numeros</h1>
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
    <table id="tbl_number" class="result" cellpadding="0" cellspacing="0" width="100%" align="center">
      <thead>
        <th align="left" width="10">A</th>
        <th align="left" abbr="no" class="order"><i class="fa fa-signal"></i> No.</th>
        <th align="left" abbr="serial_no" class="order"><i class="fa fa-signal"></i> No. de Serie</th>
        <th align="left" abbr="account" class="order"><i class="fa fa-signal"></i> Cuenta</th>
        <th align="left" abbr="date_reg" class="order"><i class="fa fa-signal"></i> Fecha Contrataci&oacute;n</th>
		<th align="left"></th>
      </thead>
      <tbody id="number">
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
	     table  : { id:"tbl_number",source: "number", action: "datagrid"},
		 filter : { limit:1000, idate: null, search: null},
		 order  : { order_by: 'imei ASC', opt:null},
   }
   //table.source.order = "sort ASC";
   $("#"+data.table.id).render_table(data); 
  });
  
  function filter(){ 
    str = $("#txt_search").val();
	data.filter.search = $("#txt_search").val(); 
   $("#"+data.table.id).render_table(data); 
  }
  
 
</script>