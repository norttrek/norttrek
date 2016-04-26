<?php
session_start();
require_once('../../_class/class.imei.php');
$objIMEI = new IMEI();
switch($_POST['exec']) {
  case "save":
    $result = NULL;
	if($objIMEI->isImeiDuplicate($_POST['txt_imei'])){
	  $result['status'] = '404';
	}else{
	  $objIMEI->set_id_device($_POST['lst_id_device'])->set_imei($_POST['txt_imei'])->set_status(1)->db('insert');
	  $result['status'] = '202';
	}
	echo json_encode($result);
  break;
  case "update": $objIMEI->set_id_device($_POST['lst_id_device'])->set_imei($_POST['txt_imei'])->set_id($_POST['id'])->db('update');
  break;
  case "delete": $objIMEI->set_id($_POST['id'])->db('delete');
  break;
  case "datagrid": 	
    $buffer = NULL;  	
    $buffer_tbody = NULL;
    $buffer_tfoot = NULL;
	$result = NULL;
	if($_POST['order']!=''){ $objIMEI->set_order($_POST['order']); }else{ $objIMEI->set_order('id ASC'); }
	if($_POST['filter']['search']!=''){ $objIMEI->set_search_field("imei.imei")->set_search($_POST['filter']['search']); }
	$result = $objIMEI->getIMEI();
	$total = count($result);
	$result = NULL;
	$result = $objIMEI->set_limit($_POST['filter']['limit'])->getIMEI();
	//limit

	for($i=0;$i<count($result);$i++){
	  $class = (($i % 2)>0) ? 'odd' : 'even';
	  $isActive = '';
	  if($result[$i]['ca_id']==NULL){ $isActive= '<i class="fa fa-check" style=" color:#66ca16;"></i>'; }else{ $isActive = '<i class="fa fa-times" style=" color:#ea2c46;"></i>'; }
	  $buffer_tbody .= '<tr class="'.$class.'">';	
	  $buffer_tbody .= '<td valign="top">'.$isActive.'</td>';  
	  $buffer_tbody .= '<td valign="top">'.$result[$i]['imei'].'</td>';
	  $buffer_tbody .= '<td valign="top">'.$result[$i]['device'].'</td>';
	  $buffer_tbody .= '<td align="center" valign="top">';
	 
	  if($_SESSION['onUserSession']['permissions']['catalogo_imei_eli']=="on"){ $buffer_tbody .= '<a class="edit modal fancybox.ajax" href="_view/_form/frm.imei.php?id='.$result[$i]['id'].'"><i class="fa fa-pencil"></i></a>'; }
	  if($_SESSION['onUserSession']['permissions']['catalogo_imei_eli']=="on"){ $buffer_tbody .= '<a href="javascript:void(0)" class="remove onRemove" rel="'.$result[$i]['id'].'"><i class="fa fa-times"></i></a>'; }
	   
	  $buffer_tbody .= '</td>';	  				 
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
