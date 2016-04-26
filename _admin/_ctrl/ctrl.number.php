<?php
session_start();
require_once('../../_class/class.number.php');
$obj = new Number();
switch($_POST['exec']) {
  case "save": $obj->set_no($_POST['txt_no'])->set_serial_no($_POST['txt_serial_no'])->set_account($_POST['txt_account'])->set_date_reg($_POST['date'])->set_status(1)->db('insert');
  break;
  case "update": $obj->set_no($_POST['txt_no'])->set_serial_no($_POST['txt_serial_no'])->set_account($_POST['txt_account'])->set_date_reg($_POST['date'])->set_id($_POST['id'])->db('update');
  break;
  case "delete": $obj->set_id($_POST['id'])->db('delete');
  break;
  case "datagrid": 	
    $buffer = NULL;  	
    $buffer_tbody = NULL;
    $buffer_tfoot = NULL;
	$result = NULL;
	if($_POST['order']!=''){ $obj->set_order($_POST['order']); }else{ $obj->set_order('id ASC'); }
	if($_POST['filter']['search']!=''){ $obj->set_search($_POST['filter']['search']); }
	$result = $obj->set_status(1)->getNumber();
	$total = count($result);
	$result = NULL;
	$result = $obj->set_status(1)->set_limit($_POST['filter']['limit'])->getNumber();
	//limit

	for($i=0;$i<count($result);$i++){
	  $class = (($i % 2)>0) ? 'odd' : 'even';
	  $buffer_tbody .= '<tr class="'.$class.'">';	
	  $isActive = '';
	  if($result[$i]['imei']==NULL){ $isActive= '<i class="fa fa-check" style=" color:#66ca16;"></i>'; }else{ $isActive = '<i class="fa fa-times" style=" color:#ea2c46;"></i>'; }
	  $buffer_tbody .= '<td valign="top">'.$isActive.'</td>'; 
	  $buffer_tbody .= '<td valign="top">'.$result[$i]['no'].'</td>';
	  $buffer_tbody .= '<td valign="top">'.$result[$i]['serial_no'].'</td>';
	  $buffer_tbody .= '<td valign="top">'.$result[$i]['account'].'</td>';
	  $buffer_tbody .= '<td valign="top">'.$obj->formatDate($result[$i]['date_reg'],"max").'</td>';
	  $buffer_tbody .= '<td align="center" valign="top">';
	   
	  if($_SESSION['onUserSession']['permissions']['catalogo_num_edi']=="on"){ $buffer_tbody .= '<a class="edit modal fancybox.ajax" href="_view/_form/frm.number.php?id='.$result[$i]['id'].'"><i class="fa fa-pencil"></i></a>'; }
	  if($_SESSION['onUserSession']['permissions']['catalogo_num_eli']=="on"){ $buffer_tbody .= '<a href="javascript:void(0)" class="remove onRemove" rel="'.$result[$i]['id'].'"><i class="fa fa-times"></i></a>'; }	  
					
	  $buffer_tbody .= ' </td>';	  				 
	  $buffer_tbody .='</tr>';
	}
	$buffer_tfoot .= '<tr>';	  
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
