<?php
require_once("../_class/class.gprs.php");
$objGPRS = new GPRS();

$search = $_GET['search'];
//$last = $objGPRS->get_gprs_report($search);


function formatDateTimeX($date,$format){
  $temp = explode(" ",$date);
  $aux = explode("-",$temp[0]);
  $mes = NULL;
  switch($aux[1]){
    case "01": $mes = "Enero"; 
	break;
    case "02": $mes = "Febrero"; 
	break;
    case "03": $mes = "Marzo"; 
	break;
    case "04": $mes = "Abril"; 
	break;
    case "05": $mes = "Mayo"; 
	break;
    case "06": $mes = "Junio";
	break;	
    case "07": $mes = "Julio";
	break;  
    case "08": $mes = "Agosto";
	break;
    case "09":  $mes = "Septiembre";
	break;
    case "10":  $mes = "Octubre";
	break;
    case "11":  $mes = "Noviembre";
	break;
    case "12":  $mes = "Diciembre";
	break;
  }
  switch($format){
    case "min": return $aux[2]."-".substr(strtoupper($mes),0,3)."-".$aux[0]." ".$temp[1]; 
	break;
	case "med": return $aux[2]."-".$mes."-".$aux[0]." ".$temp[1];
	break;
	case "max": return $aux[2]." de ".$mes." del ".$aux[0]." ".$temp[1];
	break;
	case "alt": return $aux[2]." ".substr($mes,0,3);
	break;
  }
  
 
}

?>
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

<div id="subcontent" >
<div class="actions">
    <ul>
    </ul>
  </div>
  <h1 class="title"> Reporte de Servidor GPRS</h1>
  
  <div id="result">
   
    <div class="filter">
      <ul>
       
        <li class="label"><i class="fa fa-cogs fa-2x"></i> </li>
        <li><input type="text" id="txt_search" name="txt_search" class="search" placeholder="B&uacute;squeda por cliente, imei, numero y/o alias" value="<?php if(isset($_GET['search'])){ echo $_GET['search']; } ?>"  />
        </li>
          
     
        <li><a href="javascript:filter()" class="btn_filter">Filtrar</a></li>
      </ul>
      <br class="clear" />
    </div>
    <table id="tbl_serv" class="result" cellpadding="0" cellspacing="0" width="100%" align="center">
      <thead>
      	<th align="left"><i class="fa fa-signal"></i> No.</th>
        <th align="left"><i class="fa fa-signal"></i> Cliente</th>
        <th align="left"><i class="fa fa-signal"></i> IMEI</th>
        <th align="left"><i class="fa fa-signal"></i> No. Celular</th>
        <th align="left"><i class="fa fa-signal"></i> Alias</th>
      	<th align="left"><i class="fa fa-signal"></i> Fecha</th>
        <th align="left"><i class="fa fa-signal"></i> Estatus</th>
        <th align="left"><i class="fa fa-signal"></i> Coordenadas</th>
        <th align="left"><i class="fa fa-signal"></i> Fecha Servidor</th>
        <th></th>
        <th></th>
      </thead>
      <tbody id="serv">
        <?php
		for($i=0;$i<count($last);$i++){
			$info = json_decode($last[$i]['data'],true);
		//  if(isset($_GET['imei']) && $_GET['imei']==$last[$i]['imei']){
			$telefono = '';
		    	
		    echo '<tr>';
		    echo '<td>'.($i+1).'</td>';
		    echo '<td>'.$last[$i]['client'].'</td>';
		    echo '<td>'.$last[$i]['imei'].'</td>';
			echo '<td>'.$last[$i]['no'].'</td>';
		    echo '<td>'.$last[$i]['alias'].'</td>';
		    echo '<td>'.formatDateTimeX($last[$i]['fecha'],"med").'</td>';
		    echo '<td>'.$last[$i]['status'].'</td>';
			echo '<td><a href="http://maps.google.com/?q='.$last[$i]['coor'].'" target="_blank">'.$last[$i]['coor'].'</a></td>';
			 echo '<td>'.formatDateTimeX($last[$i]['fecha_servidor'],"med").'</td>';
			 if($_SESSION['onUserSession']['permissions']['reportes_gprs_res']=="on"){
			 echo '<td><a href="javascript:void(0)" class="onClickReset" rel="'.$last[$i]['no'].'">Reset</a></td>';
			 echo '<td><a href="javascript:void(0)" class="onClickWipe" rel="'.$last[$i]['no'].'">Wipe</a></td>';
			 }
		    echo '</tr>';
		  //}
		}
		?>
      </tbody>
      <tfoot>
        <td></td>
      </tfoot>
    </table>
    <br />
    <a href="javascript:void(0)" class="onClickRestart" style="display:none;">Reset</a>
  </div>
</div>
<script>
var data;
$(document).ready(function() { 
   table = null;
   data = {
	     table  : { id:"tbl_serv",source: "serv", action: "datagrid"},
		 filter : { limit:1000, idate: null,edate:null, search: null},
		 order  : { order_by: 'id DESC', opt:null},
   }
   //table.source.order = "sort ASC";
   //$("#"+data.table.id).render_table(data); 
  });
  
  function filter(){ 
    if($("#txt_search").val()!=""){ 
	  location.href = 'index.php?call=rpt_gprs&search='+$("#txt_search").val();
	}else{ 
	location.href = 'index.php?call=rpt_gprs';
}
  }
  
 
</script>