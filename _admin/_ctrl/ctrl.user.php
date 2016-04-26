<?php
session_start();
require_once('../../_class/class.user.php');
$objUser = new User();
switch($_POST['exec']) {
  case "save": 
    $permission["cliente_vis"] = $_POST['cliente_vis'];
    $permission["cliente_cre"] = $_POST['cliente_cre'];
    $permission["cliente_edi"] = $_POST['cliente_edi'];
    $permission["cliente_eli"] = $_POST['cliente_eli'];
    $permission["cliente_cla"] = $_POST['cliente_cla'];
    $permission["cliente_pla"] = $_POST['cliente_pla'];
    $permission["cliente_inf_edi"] = $_POST['cliente_inf_edi'];
    $permission["cliente_inf_cla"] = $_POST['cliente_inf_cla'];
    $permission["cliente_uni_cre"] = $_POST['cliente_uni_cre'];
    $permission["cliente_uni_edi"] = $_POST['cliente_uni_edi'];
    $permission["cliente_uni_eli"] = $_POST['cliente_uni_eli'];
    $permission["cliente_uni_cla"] = $_POST['cliente_uni_cla'];
    $permission["catalogo_vis"] = $_POST['catalogo_vis'];
    $permission["catalogo_eq_vis"] = $_POST['catalogo_eq_vis'];
    $permission["catalogo_eq_cre"] = $_POST['catalogo_eq_cre'];
    $permission["catalogo_eq_edi"] = $_POST['catalogo_eq_edi'];
    $permission["catalogo_eq_eli"] = $_POST['catalogo_eq_eli'];
    $permission["catalogo_eq_cla"] = $_POST['catalogo_eq_cla'];
    $permission["catalogo_staff_vis"] = $_POST['catalogo_staff_vis'];
    $permission["catalogo_staff_cre"] = $_POST['catalogo_staff_cre'];
    $permission["catalogo_staff_edi"] = $_POST['catalogo_staff_edi'];
    $permission["catalogo_staff_eli"] = $_POST['catalogo_staff_eli'];
    $permission["catalogo_staff_cla"] = $_POST['catalogo_staff_cla'];
    $permission["catalogo_imei_vis"] = $_POST['catalogo_imei_vis'];
    $permission["catalogo_imei_cre"] = $_POST['catalogo_imei_cre'];
    $permission["catalogo_imei_edi"] = $_POST['catalogo_imei_edi'];
    $permission["catalogo_imei_eli"] = $_POST['catalogo_imei_eli'];
    $permission["catalogo_imei_cla"] = $_POST['catalogo_imei_cla'];
    $permission["catalogo_num_vis"] = $_POST['catalogo_num_vis'];
    $permission["catalogo_num_cre"] = $_POST['catalogo_num_cre'];
    $permission["catalogo_num_edi"] = $_POST['catalogo_num_edi'];
    $permission["catalogo_num_eli"] = $_POST['catalogo_num_eli'];
    $permission["catalogo_num_cla"] = $_POST['catalogo_num_cla'];
    $permission["reportes_vis"] = $_POST['reportes_vis'];
    $permission["reportes_gprs_vis"] = $_POST['reportes_gprs_vis'];
    $permission["reportes_gprs_res"] = $_POST['reportes_gprs_res'];
    $permission["reportes_gen_vis"] = $_POST['reportes_gen_vis'];
	$permission["papelera_vis"] = $_POST['papelera_vis'];
	
	
  $objUser->set_type($_POST['lst_type'])->set_user($_POST['txt_user'])->set_password($_POST['txt_password'])->set_op_password($_POST['txt_op_password'])->set_permissions(json_encode($permission))->db('insert');
  
  break;
  case "update": 
    $permission["cliente_vis"] = $_POST['cliente_vis'];
    $permission["cliente_cre"] = $_POST['cliente_cre'];
    $permission["cliente_edi"] = $_POST['cliente_edi'];
    $permission["cliente_eli"] = $_POST['cliente_eli'];
    $permission["cliente_cla"] = $_POST['cliente_cla'];
    $permission["cliente_pla"] = $_POST['cliente_pla'];
    $permission["cliente_inf_edi"] = $_POST['cliente_inf_edi'];
    $permission["cliente_inf_cla"] = $_POST['cliente_inf_cla'];
    $permission["cliente_uni_cre"] = $_POST['cliente_uni_cre'];
    $permission["cliente_uni_edi"] = $_POST['cliente_uni_edi'];
    $permission["cliente_uni_eli"] = $_POST['cliente_uni_eli'];
    $permission["cliente_uni_cla"] = $_POST['cliente_uni_cla'];
    $permission["catalogo_vis"] = $_POST['catalogo_vis'];
    $permission["catalogo_eq_vis"] = $_POST['catalogo_eq_vis'];
    $permission["catalogo_eq_cre"] = $_POST['catalogo_eq_cre'];
    $permission["catalogo_eq_edi"] = $_POST['catalogo_eq_edi'];
    $permission["catalogo_eq_eli"] = $_POST['catalogo_eq_eli'];
    $permission["catalogo_eq_cla"] = $_POST['catalogo_eq_cla'];
    $permission["catalogo_staff_vis"] = $_POST['catalogo_staff_vis'];
    $permission["catalogo_staff_cre"] = $_POST['catalogo_staff_cre'];
    $permission["catalogo_staff_edi"] = $_POST['catalogo_staff_edi'];
    $permission["catalogo_staff_eli"] = $_POST['catalogo_staff_eli'];
    $permission["catalogo_staff_cla"] = $_POST['catalogo_staff_cla'];
    $permission["catalogo_imei_vis"] = $_POST['catalogo_imei_vis'];
    $permission["catalogo_imei_cre"] = $_POST['catalogo_imei_cre'];
    $permission["catalogo_imei_edi"] = $_POST['catalogo_imei_edi'];
    $permission["catalogo_imei_eli"] = $_POST['catalogo_imei_eli'];
    $permission["catalogo_imei_cla"] = $_POST['catalogo_imei_cla'];
    $permission["catalogo_num_vis"] = $_POST['catalogo_num_vis'];
    $permission["catalogo_num_cre"] = $_POST['catalogo_num_cre'];
    $permission["catalogo_num_edi"] = $_POST['catalogo_num_edi'];
    $permission["catalogo_num_eli"] = $_POST['catalogo_num_eli'];
    $permission["catalogo_num_cla"] = $_POST['catalogo_num_cla'];
    $permission["reportes_vis"] = $_POST['reportes_vis'];
    $permission["reportes_gprs_vis"] = $_POST['reportes_gprs_vis'];
    $permission["reportes_gprs_res"] = $_POST['reportes_gprs_res'];
    $permission["reportes_gen_vis"] = $_POST['reportes_gen_vis'];
	$permission["papelera_vis"] = $_POST['papelera_vis'];
	
	$objUser->set_type($_POST['lst_type'])->set_user($_POST['txt_user'])->set_password($_POST['txt_password'])->set_id($_POST['id'])->set_op_password($_POST['txt_op_password'])->set_permissions(json_encode($permission))->db('update');
	if($_SESSION['onUserSession']['id_user']==$_POST['id']){ $_SESSION['onUserSession']['permissions'] = $permission; }
  break;
  case "delete": $objUser->set_id($_POST['id'])->db('delete');
  break;
  case "datagrid": 
    $buffer = NULL;  	
    $buffer_tbody = NULL;
    $buffer_tfoot = NULL;
	$result = $objUser->getUser();
	$result = NULL;
	if($_POST['order']!=''){ $objUser->set_order($_POST['order']); }else{ $objUser->set_order('id ASC'); }
	$result = $objUser->getUser();
	$total = count($result);
	$result = NULL;
	$result = $objUser->set_limit($_POST['filter']['limit'])->getUser();
	//limit

	for($i=0;$i<count($result);$i++){
	  $class = (($i % 2)>0) ? 'odd' : 'even';
	  $buffer_tbody .= '<tr class="'.$class.'">';	  
	  $buffer_tbody .= '<td valign="top">'.$result[$i]['id'].'</td>';
	  $buffer_tbody .= '<td valign="top">'.$objUser->get_type($result[$i]['type']).'</td>';
	  $buffer_tbody .= '<td valign="top">'.$result[$i]['user'].'</td>';
	  $buffer_tbody .= '<td align="center" valign="top">';
	  
	   if($_SESSION['onUserSession']['permissions']['catalogo_staff_edi']=="on"){ $buffer_tbody .= '<a class="edit modal fancybox.ajax" href="_view/_form/frm.user.php?id='.$result[$i]['id'].'"><i class="fa fa-pencil"></i></a>'; }
	  if($_SESSION['onUserSession']['permissions']['catalogo_staff_eli']=="on"){ $buffer_tbody .= '<a href="javascript:void(0)" class="remove onRemove" rel="'.$result[$i]['id'].'"><i class="fa fa-times"></i></a>'; }
	  
	  
					
					
	  $buffer_tbody .= '</td>';	  				 
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
