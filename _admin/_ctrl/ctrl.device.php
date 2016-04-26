<?php
session_start();
require_once('../../_class/class.device.php');
$obj = new Device();
switch($_POST['exec']) {
  case "save": $obj->set_device($_POST['txt_device'])->set_description($_POST['txt_description'])->set_status(1)->db('insert');
  break;
  case "update": $obj->set_device($_POST['txt_device'])->set_description($_POST['txt_description'])->set_id($_POST['id'])->db('update');
  break;
  case "delete": $obj->set_id($_POST['id'])->db('delete');
   echo 213;
  break;
  case "status": $obj->set_id($_POST['id'])->set_status($_POST['value'])->db('status');
  break;
  case "datagrid":
    $buffer = NULL;  	
    $buffer_tbody = NULL;
    $buffer_tfoot = NULL;
	
	if($_POST['filter']['search']!=""){  $obj->set_search_field('device')->set_search($_POST['filter']['search']);  }
	$result = $obj->set_status(1)->getDevice();
	
	for($i=0;$i<count($result);$i++){
	  $class = (($i % 2)>0) ? 'odd' : 'even';
	  $buffer_tbody .= '<tr class="'.$class.'">';	  
	  $buffer_tbody .= '<td valign="top">'.$result[$i]['device'].'</td>';
	  $buffer_tbody .= '<td valign="top">'.$result[$i]['description'].'</td>';
	  $buffer_tbody .= '<td align="center" valign="top">';
	  
	   if($_SESSION['onUserSession']['permissions']['catalogo_eq_edi']=="on"){ $buffer_tbody .= '<a class="edit modal fancybox.ajax" href="_view/_form/frm.device.php?id='.$result[$i]['id'].'"><i class="fa fa-pencil"></i></a>'; }
	   if($_SESSION['onUserSession']['permissions']['catalogo_eq_eli']=="on"){ $buffer_tbody .= '<a href="javascript:void(0)" class="remove onRemove" rel="'.$result[$i]['id'].'"><i class="fa fa-times"></i></a>'; }
		
	  $buffer_tbody .= '</td>';	  				 
	  $buffer_tbody .='</tr>';
	}
	$buffer_tfoot .= '<tr>';	  
	$buffer_tfoot .= '<td></td>';
	$buffer_tfoot .= '<td></td>';
	$buffer_tfoot .= '<td></td>';
	$buffer_tfoot .='</tr>';
	
	$buffer['tbody'] = $buffer_tbody;
	$buffer['tfoot'] = $buffer_tfoot;
	echo json_encode($buffer);
  break;
  }
?>