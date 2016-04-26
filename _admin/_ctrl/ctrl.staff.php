<?php
session_start();
require_once('../_class/class.staff.php');
$objStaff = new Staff();
switch($_POST['exec']) {
  case "save": 
    $contacto = $_POST['contacto'];
	$contacto_medio = $_POST['lbl_contacto'];
	$buffer_contacto = '';
	for($i=0;$i<count($contacto);$i++){
	  $buffer_contacto .= $contacto_medio[$i].','.$contacto[$i]."|";
	}
    $objStaff->set_tipo($_POST['lst_tipo'])->
	             set_nombre($_POST['txt_nombre'])->
				 set_correo($_POST['txt_correo'])->
				 set_contacto($buffer_contacto)->
                 set_usuario($_POST['txt_usuario'])->
				 set_contrasena($_POST['txt_contrasena'])->
                 set_status(1)->
                 db('insert');
	
  break;
  case "update": $objCliente->set_id_usuario($_POST['lst_id_usuario'])->
                              set_id_medio($_POST['lst_id_medio'])->
				              set_id_servicio($_POST['lst_id_servicio'])->
                              set_nombre($_POST['txt_nombre'])->
		                      set_direccion($_POST['txt_direccion'])->
					          set_colonia($_POST['txt_colonia'])->
							  set_ciudad($_POST['txt_ciudad'])->
							  set_edo($_POST['txt_edo'])->
							  set_cp($_POST['txt_cp'])->
							  set_pais($_POST['txt_pais'])->
					          set_id($_POST['id'])->
					          db('update');
  break;
  case "delete": $objCliente->set_id($_POST['id'])->set_e($_POST['value'])->db('delete');
  break;
  case "estatus": $objCliente->set_id($_POST['id'])->set_estatus($_POST['value'])->db('estatus');
  break;
  case "datagrid": 	
    $buffer = NULL;  	
    $buffer_tbody = NULL;
    $buffer_tfoot = NULL;
	$result = $objStaff->getStaff();
	$result = NULL;
	if($_POST['order']!=''){ $objStaff->set_order($_POST['order']); }else{ $objCliente->set_order('id ASC'); }
	if($_POST['filter']['search']!=''){ $objStaff->set_search($_POST['filter']['search'])->set_search_field('nombre'); }
	$result = $objStaff->getCliente();
	$total = count($result);
	$result = NULL;
	$result = $objStaff->set_limit($_POST['filter']['limit'])->getStaff();
	//limit

	for($i=0;$i<count($result);$i++){
	  $class = (($i % 2)>0) ? 'odd' : 'even';
	  $buffer_tbody .= '<tr class="'.$class.'">';	  
	  $buffer_tbody .= '<td valign="top">'.$result[$i]['id'].'</td>';
	  $buffer_tbody .= '<td valign="top"><a href="?call=cliente&id='.$result[$i]['id'].'" class="link">'.$result[$i]['nombre'].'</a></td>';
	  $buffer_tbody .= '<td valign="top">'.$result[$i]['usuario'].'</td>';
	  
	  $contacto = explode("|",$result[$i]['contacto']);
	 
	  $contacto_html = '';
	  for($j=0;$j<count($contacto);$j++){
		 $aux = explode(",",$contacto[$j]);
		if($aux[0]!=NULL){ $contacto_html .= $aux[0].': <strong>'.$aux[1].'</strong><br>'; }
	  }
	  
	  $buffer_tbody .= '<td valign="top">'.$contacto_html.'</td>';
	  $buffer_tbody .= '<td align="center" valign="top">
					<a class="edit modal fancybox.ajax" href="_view/_form/frm.staff.php?id='.$result[$i]['id'].'"><i class="fa fa-pencil"></i></a>
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
