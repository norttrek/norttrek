<?php
session_start();
require_once('../_class/class.unidad.php');
$objUnidad = new Unidad();
switch($_POST['exec']) {
  case "save": 
    $info = $_POST['info'];
	$info_label = $_POST['lbl_info'];
	$buffer_info = '';
	for($i=0;$i<count($info);$i++){ $buffer_info .= $info_label[$i].','.$info[$i]."|"; }
    $objUnidad->set_id_cliente($_POST['id_cliente'])->
				 set_id_equipo($_POST['lst_id_equipo'])->
				 set_id_staff($_POST['lst_id_staff'])->
	             set_id_grupo(NULL)->
				 set_imei($_POST['txt_imei'])->
                 set_alias($_POST['txt_alias'])->
				 set_info($buffer_info)->
				 set_icono('default.png')->
				 set_fecha_registro(date("Y-m-d"))->
                 set_status(1)->
                 db('insert');
	
	
  break;
  case "update": 
  	$info = $_POST['info'];
	$info_label = $_POST['lbl_info'];
	$buffer_info = '';
	for($i=0;$i<count($info);$i++){ $buffer_info .= $info_label[$i].','.$info[$i]."|"; }
  	$objUnidad->set_id_equipo($_POST['lst_id_equipo'])->
				 set_id_staff($_POST['lst_id_staff'])->
				 set_imei($_POST['txt_imei'])->
                 set_alias($_POST['txt_alias'])->
				 set_info($buffer_info)->
				 set_id($_POST['id'])->db('update');
  break;
  case "delete": $objCliente->set_id($_POST['id'])->set_e($_POST['value'])->db('delete');
  break;
  case "estatus": $objCliente->set_id($_POST['id'])->set_estatus($_POST['value'])->db('estatus');
  break;
  case "datagrid_x_cliente": 	
    $buffer = NULL;  	
    $buffer_tbody = NULL;
    $buffer_tfoot = NULL;
	$result = $objUnidad->getUnidad();
	$result = NULL;
	if($_POST['order']!=''){ $objUnidad->set_order($_POST['order']); }else{ $objUnidad->set_order('id ASC'); }
	if($_POST['filter']['search']!=''){ $objUnidad->set_search($_POST['filter']['search'])->set_search_field('cliente.nombre'); }
	if($_POST['filter']['id']!=''){ $objUnidad->set_id_cliente($_POST['filter']['id']); }

	$result = $objUnidad->getUnidad();
	$total = count($result);
	$result = NULL;
	$result = $objUnidad->set_limit($_POST['filter']['limit'])->getUnidad();
	//limit

	for($i=0;$i<count($result);$i++){
	  $class = (($i % 2)>0) ? 'odd' : 'even';
	  $buffer_tbody .= '<tr class="'.$class.'">';	  
	  $buffer_tbody .= '<td valign="top">'.$result[$i]['id'].'</td>';
	  $buffer_tbody .= '<td valign="top">'.$result[$i]['alias'].'</td>';
	  $buffer_tbody .= '<td valign="top"><a href="#" class="link">'.$result[$i]['imei'].'</a></td>';
	  $buffer_tbody .= '<td align="center" valign="top">
					<a class="edit" href="?call=unidad&id='.$result[$i]['id'].'"><i class="fa fa-pencil"></i></a>
					<a href="javascript:void(0)" class="remove onRemove" rel="'.$result[$i]['id'].'"><i class="fa fa-times"></i></a>
				 </td>';	  				 
	  $buffer_tbody .='</tr>';
	}
	$buffer_tfoot .= '<tr>';	  
	$buffer_tfoot .= '<td></td>';
	$buffer_tfoot .= '<td></td>';
	$buffer_tfoot .= '<td></td>';
	$buffer_tfoot .='</tr>';
	
	$buffer['params'] = $_POST;
	$buffer['total'] = $total;
	$buffer['tbody'] = $buffer_tbody;
	$buffer['tfoot'] = $buffer_tfoot;
	echo json_encode($buffer);
  break;  
}
?>
