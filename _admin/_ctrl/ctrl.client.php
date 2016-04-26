<?php
session_start();
require_once('../../_class/class.client.php');
$objClient = new Client();
switch($_POST['exec']) {
  case "save":
    $objClient->set_client($_POST['txt_client'])->set_no_client($_POST['txt_no_client'])->set_private($_POST['chk_private'])->set_date_reg(date("Y-m-d"))->set_status(1)->db('insert');
	$lastInserted = $objClient->getLastInserted();
	$objClient->set_group("Grupo General")->set_id_client($lastInserted)->db('insert_group');
	$objClient->set_id_client($lastInserted)->db('insert_info');
	$objClient->set_user($_POST['txt_user'])->set_password($_POST['txt_password'])->set_id_client($lastInserted)->set_type(1)->db('insert_user');
	/* SETTINGS */
	$settings = NULL;
	$settings['temp'] = "C";
	$objClient->set_id_client($lastInserted)->set_settings($setings)->db('insert_settings');
  break;

  case "save_asset":
    $data = NULL;
	$data = $_POST['info'];
    $data_lbl = $_POST['lbl_info'];
	$sensor = NULL;

	if(isset($_POST['chk_sensor_elock'])){ $sensor['elock'] = 1; }else{ $sensor['elock'] = 0; }
	if(isset($_POST['chk_sensor_temp'])){ $sensor['temp'] = 1; }else{ $sensor['temp'] = 0; }
  // aad ago 2015
  if(isset($_POST['chk_sensor_temp2'])){ $sensor['temp2'] = 1; }else{ $sensor['temp2'] = 0; }


	if(isset($_POST['chk_sensor_fuel_a'])){
	  $sensor['fuel_a'] = 1;
	  $sensor['fuel_a_d'] = $_POST['fuel_a_d'];
	  $sensor['fuel_a_l'] = $_POST['fuel_a_l'];
	  $sensor['fuel_a_a'] = $_POST['fuel_a_a'];
	  $sensor['fuel_a_as'] = $_POST['fuel_a_as'];
	  $sensor['fuel_a_v'] = $_POST['fuel_a_v'];
	  $sensor['fuel_a_vl'] = $_POST['fuel_a_vl'];
	}else{
	  $sensor['fuel_a'] = 0;
	}

	if(isset($_POST['chk_sensor_fuel_b'])){
	  $sensor['fuel_b'] = 1;
	  $sensor['fuel_b_d'] = $_POST['fuel_b_d'];
	  $sensor['fuel_b_l'] = $_POST['fuel_b_l'];
	  $sensor['fuel_b_a'] = $_POST['fuel_b_a'];
	  $sensor['fuel_b_as'] = $_POST['fuel_b_as'];
	  $sensor['fuel_b_v'] = $_POST['fuel_b_v'];
	  $sensor['fuel_b_vl'] = $_POST['fuel_b_vl'];
	}else{
	  $sensor['fuel_b'] = 0;
	}

	if(isset($_POST['chk_sensor_fuel_c'])){
	  $sensor['fuel_c'] = 1;
	  $sensor['fuel_c_d'] = $_POST['fuel_c_d'];
	  $sensor['fuel_c_l'] = $_POST['fuel_c_l'];
	  $sensor['fuel_c_a'] = $_POST['fuel_c_a'];
	  $sensor['fuel_c_as'] = $_POST['fuel_c_as'];
	  $sensor['fuel_c_v'] = $_POST['fuel_c_v'];
	  $sensor['fuel_c_vl'] = $_POST['fuel_c_vl'];
	}else{
	  $sensor['fuel_c'] = 0;
	}
	$sensor['formula'] = $_POST['lst_formula'];


    $buffer_data;

    $buffer_data['observaciones'] = $_POST['observaciones'];
    $buffer_data['tipo_vehiculo'] = $_POST['tipo_vehiculo'];
    $buffer_data['fecha_instalacion'] = $_POST['fecha_instalacion'];
    $buffer_data['ubicacion_equipo'] = $_POST['ubicacion_equipo'];

    $objClient->set_id_client($_POST['id_client'])->
				set_id_group($_POST['lst_id_group'])->
				set_imei($_POST['lst_imei'])->
				set_no($_POST['lst_no'])->
				set_alias($_POST['txt_alias'])->
				set_date_reg(date("Y-m-d"))->
				set_data(json_encode($buffer_data))->
				set_icon('default.png')->
				set_sensor(json_encode($sensor))->
				set_status(1)->db('insert_asset');
    $lastInserted = $objClient->getLastInserted();


  break;

  case "save_asset_fuel":
    $data = NULL;
	$data = $_POST['info'];
    $data_lbl = $_POST['lbl_info'];

	$buffer_data;
	for($i=0;$i<count($data);$i++){
	  $buffer_data[$i]['label'] = $data_lbl[$i];
	  $buffer_data[$i]['value'] = $data[$i];
	}


    $objClient->set_id_client($_POST['id_client'])->
				set_id_device(1)->
				set_id_group($_POST['lst_id_group'])->
				set_imei($_POST['lst_imei'])->
				set_parent($_POST['parent'])->
				set_no($_POST['lst_no'])->
				set_date_reg(date("Y-m-d"))->
				set_data(json_encode($buffer_data))->
				set_status(1)->db('insert_asset_fuel');


  break;

  case "insert_calib":
  $calib['t1'] = $_POST['t1'];
  $calib['t2'] = $_POST['t2'];
  $calib['t3'] = $_POST['t3'];
  $objClient->set_id_asset($_POST['id_asset'])->set_fuel(json_encode($calib))->db('insert_fuel');
  break;

  case "update_asset":
    $data = NULL;
	$data = $_POST['info'];
    $data_lbl = $_POST['lbl_info'];

	$sensor = NULL;
	if(isset($_POST['chk_sensor_elock'])){ $sensor['elock'] = 1; }else{ $sensor['elock'] = 0; }
	if(isset($_POST['chk_sensor_temp'])){ $sensor['temp'] = 1; }else{ $sensor['temp'] = 0; }
  // aad ago 2015
  if(isset($_POST['chk_sensor_temp2'])){ $sensor['temp2'] = 1; }else{ $sensor['temp2'] = 0; }

	if(isset($_POST['chk_sensor_fuel_a'])){ $sensor['fuel_a'] = 1; }else{ $sensor['fuel_a'] = 0; }
	$sensor['fuel_a_d'] = $_POST['fuel_a_d'];
	$sensor['fuel_a_l'] = $_POST['fuel_a_l'];
	$sensor['fuel_a_as'] = $_POST['fuel_a_as'];
	$sensor['fuel_a_v'] = $_POST['fuel_a_v'];
	$sensor['fuel_a_vl'] = $_POST['fuel_a_vl'];

	if(isset($_POST['chk_sensor_fuel_b'])){ $sensor['fuel_b'] = 1; }else{ $sensor['fuel_b'] = 0; }
	$sensor['fuel_b_d'] = $_POST['fuel_b_d'];
	$sensor['fuel_b_l'] = $_POST['fuel_b_l'];
	$sensor['fuel_b_as'] = $_POST['fuel_b_as'];
	$sensor['fuel_b_v'] = $_POST['fuel_b_v'];
	$sensor['fuel_b_vl'] = $_POST['fuel_b_vl'];

	if(isset($_POST['chk_sensor_fuel_c'])){ $sensor['fuel_c'] = 1; }else{ $sensor['fuel_c'] = 0; }
	$sensor['fuel_c_d'] = $_POST['fuel_c_d'];
	$sensor['fuel_c_l'] = $_POST['fuel_c_l'];
	$sensor['fuel_c_as'] = $_POST['fuel_c_as'];
	$sensor['fuel_c_v'] = $_POST['fuel_c_v'];
	$sensor['fuel_c_vl'] = $_POST['fuel_c_vl'];

	$sensor['formula'] = $_POST['lst_formula'];

   $buffer_data;

    $buffer_data['observaciones'] = $_POST['observaciones'];
    $buffer_data['tipo_vehiculo'] = $_POST['tipo_vehiculo'];
    $buffer_data['fecha_instalacion'] = $_POST['fecha_instalacion'];
    $buffer_data['ubicacion_equipo'] = $_POST['ubicacion_equipo'];
    $objClient->set_id_device($_POST['lst_id_device'])->set_id_group($_POST['lst_id_group'])->set_imei($_POST['lst_imei'])->set_no($_POST['lst_no'])->set_alias($_POST['txt_alias'])->set_id_asset($_POST['id_asset'])->set_data(json_encode($buffer_data))->set_sensor(json_encode($sensor))->db('update_asset');
  break;

  case "update_client_park":
    $data = $_POST['park'];
	$buffer_data;
	$buffer_data['direccion'] = htmlentities($_POST['direccion']);
	$buffer_data['contacto_medio'] = $_POST['contacto_medio'];
	$buffer_data['contacto'] = $_POST['contacto'];
	$buffer_data['observaciones'] = htmlentities($_POST['observaciones']);
    $objClient->set_id_client($_POST['id_client'])->set_park(json_encode($buffer_data))->db('update_client_park');
  break;

  case "update_client_security":
    $data = $_POST['security'];
	$buffer_data['pin_seguridad'] = $_POST['pin_seguridad'];
	$buffer_data['pin_bloqueos'] = $_POST['pin_bloqueos'];
    $objClient->set_id_client($_POST['id_client'])->set_security(json_encode($buffer_data))->db('update_client_security');
	$objClient->set_id_client($_POST['id_client'])->set_pin($_POST['txt_pin'])->db('update_client_pin');
	$objClient->set_user($_POST['txt_user'])->set_password($_POST['txt_password'])->set_date_ext('0000-00-00 00:00:00')->set_id_client($_POST['id_client'])->db('update_client_user');
  break;



  case "update_client_payment":
	$buffer_data['contrato_inicio'] = $_POST['contrato_inicio'];
	$buffer_data['contacto_pagos'] = htmlentities($_POST['contacto_pagos']);
	$buffer_data['email_facturas'] = $_POST['email_facturas'];
	$buffer_data['dia_corte'] = $_POST['dia_corte'];
	$buffer_data['metodo_pago'] = $_POST['metodo_pago'];
	$buffer_data['renta_unitaria'] = $_POST['renta_unitaria'];
	$buffer_data['plazo_minimo'] = $_POST['plazo_minimo'];
	$buffer_data['tipo_plan'] = $_POST['tipo_plan'];
	$buffer_data['tipo_operacion'] = $_POST['tipo_operacion'];
	$buffer_data['observaciones'] = htmlentities($_POST['observaciones']);
    $objClient->set_id_client($_POST['id_client'])->set_payment(json_encode($buffer_data))->db('update_client_payment');
  break;

  case "update_client_info":
	$buffer_data;
	$buffer_data['razon_social'] = htmlentities($_POST['razon_social']);
	$buffer_data['rfc'] = $_POST['rfc'];
	$buffer_data['correo'] = $_POST['correo'];
	$buffer_data['giro_empresa'] = htmlentities($_POST['giro_empresa']);
	$buffer_data['calle_no'] = htmlentities($_POST['calle_no']);
	$buffer_data['no_ext'] = $_POST['no_ext'];
	$buffer_data['no_int'] = $_POST['no_int'];
	$buffer_data['colonia'] = htmlentities($_POST['colonia']);
	$buffer_data['dele_mun'] = htmlentities($_POST['dele_mun']);

	$buffer_data['ciudad'] = htmlentities($_POST['ciudad']);
	$buffer_data['estado'] = htmlentities($_POST['estado']);
	$buffer_data['cp'] = $_POST['cp'];
	$buffer_data['contacto_1_medio'] = $_POST['contacto_1_medio'];
	$buffer_data['contacto_1'] = $_POST['contacto_1'];
	$buffer_data['contacto_2_medio'] = $_POST['contacto_2_medio'];
	$buffer_data['contacto_2'] = $_POST['contacto_2'];
	$buffer_data['contacto_3_medio'] = $_POST['contacto_3_medio'];
	$buffer_data['contacto_3'] = $_POST['contacto_3'];

	$buffer_data['clave_interna'] = $_POST['clave_interna'];
	$buffer_data['digito'] = $_POST['digito'];
    $objClient->set_id_client($_POST['id_client'])->set_no_client($_POST['clave_interna'])->set_info(json_encode($buffer_data))->db('update_client_info');
	$objClient->set_id_client($_POST['id_client'])->set_no_client($_POST['clave_interna'])->db('update_no_client');

  break;


  case "update":  $objClient->set_client($_POST['txt_client'])->set_pin($_POST['txt_pin'])->set_no_client($_POST['txt_no_client'])->set_private($_POST['chk_private'])->set_id($_POST['id'])->db('update');
  break;
  case "delete": $objClient->set_id($_POST['id'])->set_e($_POST['value'])->db('delete');
  break;
  case "delete_client_asset": $objClient->set_id_asset($_POST['id'])->db('delete_asset'); echo 1;
  break;
  case "estatus": $objClient->set_id($_POST['id'])->set_estatus($_POST['value'])->db('estatus');
  break;
  case "datagrid":
    $buffer = NULL;
    $buffer_tbody = NULL;
    $buffer_tfoot = NULL;
	$result = $objClient->getClient();
	
	$result = NULL;
	if($_POST['order']!=''){ $objClient->set_order($_POST['order']); }else{ $objClient->set_order('id ASC'); }
	if($_POST['filter']['search']!=''){ $objClient->set_search($_POST['filter']['search'])->set_search_field('client'); }
	$result = $objClient->getClient();
	$total = count($result);
	$result = NULL;
	$result = $objClient->set_limit($_POST['filter']['limit'])->getClient();
	//limit

	for($i=0;$i<count($result);$i++){
		if($result[$i]['activo']==1){
			$activo = "checked";
		} elseif($result[$i]['activo']==0){
			$activo = "";
		}
	  $class = (($i % 2)>0) ? 'odd' : 'even';
	  $buffer_tbody .= '<tr class="'.$class.'">';
	  
	  $buffer_tbody .= '<td valign="top">'.$result[$i]['no_client'].'</td>';
	  $buffer_tbody .= '<td valign="top">
	  	<span class="onoffswitch" style="float:left">
    <input type="checkbox" name="onoffswitch" onclick="activeUser('.$result[$i]['id'].')" imei="864244023027666" class="onoffswitch-checkbox activeUser" id="myonoffswitch'.$result[$i]['id'].'" '.$activo.'>
    <label class="onoffswitch-label" data-step="7" data-intro="Activar alerta de velocidad" for="myonoffswitch'.$result[$i]['id'].'">
        <span class="onoffswitch-inner"></span>
        <span class="onoffswitch-switch"></span>
    </label>
</span></td>';
	  $buffer_tbody .= '<td valign="top"><a href="?call=cliente&id='.$result[$i]['id'].'" class="link">'.$result[$i]['client'].'</a></td>';
	   $contacto = explode("|",$result[$i]['contacto']);

	  $contacto_html = '';
	  for($j=0;$j<count($contacto);$j++){
		 $aux = explode(",",$contacto[$j]);
		if($aux[0]!=NULL){ $contacto_html .= $aux[0].': <strong>'.$aux[1].'</strong><br>'; }
	  }

	  $total_assets = $objClient->getTotalClientAssets($result[$i]['id']);

	  $buffer_tbody .= '<td valign="top">'.$contacto_html.$result[$i]['activo'].'</td>';
	  $buffer_tbody .= '<td valign="top">'.$total_assets[0]['TOTAL'].'</td>';
	  $buffer_tbody .= '<td valign="top">'.$objClient->formatDate($result[$i]['date_reg'],"max").'</td>';
	  $buffer_tbody .= '<td align="center" valign="top">';

	  if($_SESSION['onUserSession']['permissions']['cliente_pla']=="on"){ $buffer_tbody .='<a class="go" href="_ctrl/ctrl.auth_client.php?idc='.$result[$i]['id'].'" target="_blank"><i class="fa fa-truck"></i></a>'; }
	  if($_SESSION['onUserSession']['permissions']['cliente_edi']=="on"){
	    $isb = '';
	    if($_SESSION['onUserSession']['permissions']['cliente_cla']=="on"){}
	    $buffer_tbody .='<a class="edit modal fancybox.ajax" href="_view/_form/frm.client.php?id='.$result[$i]['id'].'"><i class="fa fa-pencil"></i></a>';
	  }
	  if($_SESSION['onUserSession']['permissions']['cliente_eli']=="on"){
	    if($total_assets[0]['TOTAL']==0){
		  $buffer_tbody .='<a href="javascript:void(0)" class="remove onRemove" rel="'.$result[$i]['id'].'"><i class="fa fa-times"></i></a>';
		}
	  }
	  $buffer_tbody .= '</td>';
	  $buffer_tbody .= '</tr>';
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

  case "datagrid_client_assets":
    $buffer = NULL;
    $buffer_tbody = NULL;
    $buffer_tfoot = NULL;
	$result = $objClient->set_id_client($_POST['filter']['id'])->set_id_device('0')->getClientAssets();
	$result = NULL;
	if($_POST['order']!=''){ $objClient->set_order($_POST['order']); }else{ $objClient->set_order(); }
	if($_POST['filter']['search']!=''){ $objClient->set_search($_POST['filter']['search'])->set_search_field('cliente.nombre'); }
	if($_POST['filter']['id']!=''){ $objClient->set_id_client($_POST['filter']['id']); }

	$result = $objClient->getClientAssets();
	$total = count($result);
	$result = NULL;
	$result = $objClient->set_limit($_POST['filter']['limit'])->getClientAssets();



	//limit

	for($i=0;$i<count($result);$i++){
	  $info = json_decode($result[$i]['data'],true);
	  $sensor = NULL;
	  $sensor = json_decode($result[$i]['sensor'],true);
	  $fuel = true;
	  if($sensor['fuel_a']==0 && $sensor['fuel_b']==0 && $sensor['fuel_c']==0){ $fuel = false; }

	  $class = (($i % 2)>0) ? 'odd' : 'even';
	  $buffer_tbody .= '<tr class="'.$class.'">';
	  $buffer_tbody .= '<td valign="top">'.($i+1).'</td>';
	  $buffer_tbody .= '<td valign="top">'.$result[$i]['alias'].'</td>';
	  $buffer_tbody .= '<td valign="top"><a href="_view/_form/frm.client_asset.php?id_client='.$result[$i]['id_client'].'&id_asset='.$result[$i]['id'].'" class="fancybox.ajax modal link">'.$result[$i]['imei'].'</a></td>';
	  $buffer_tbody .= '<td valign="top">'.$result[$i]['no'].'</td>';
	  if($fuel){
	    $buffer_tbody .= '<td valign="top"><a class="fuel modal" href="_view/_form/frm.client_asset_calib.php?id_client='.$result[$i]['id_client'].'&id_asset='.$result[$i]['id'].'" data-fancybox-type="iframe"><i class="fa fa-cog"></i></a></td>';
	  }else{
		$buffer_tbody .= '<td valign="top"></td>';
	  }
	  $buffer_tbody .= '<td align="center" valign="top">';

	  if($_SESSION['onUserSession']['permissions']['cliente_uni_edi']=="on"){ $buffer_tbody .= '<a class="edit fancybox.ajax modal" href="_view/_form/frm.client_asset.php?id_client='.$result[$i]['id_client'].'&id_asset='.$result[$i]['id'].'"><i class="fa fa-pencil"></i></a>'; }
	  if($_SESSION['onUserSession']['permissions']['cliente_uni_eli']=="on"){ $buffer_tbody .= '<a href="javascript:void(0)" class="remove onRemoveAsset" rel="'.$result[$i]['id'].'"><i class="fa fa-times"></i></a>';  }

	  $buffer_tbody .='</td>';
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

  case "datagrid_client_assets_fuel":
    $buffer = NULL;
    $buffer_tbody = NULL;
    $buffer_tfoot = NULL;
	$result = $objClient->set_id_client($_POST['filter']['id'])->set_id_device(1)->getClientAssets();
	$result = NULL;
	if($_POST['order']!=''){ $objClient->set_order($_POST['order']); }else{ $objClient->set_order(); }
	$result = $objClient->getClientAssets();
	$total = count($result);
	$result = NULL;
	$result = $objClient->set_limit($_POST['filter']['limit'])->getClientAssets();



	//limit

	for($i=0;$i<count($result);$i++){
	  $info = json_decode($result[$i]['data'],true);
	  $class = (($i % 2)>0) ? 'odd' : 'even';
	  $buffer_tbody .= '<tr class="'.$class.'">';
	  $buffer_tbody .= '<td valign="top">'.($i+1).'</td>';
	  $buffer_tbody .= '<td valign="top">'.$info[0]['value'].'</td>';
	  $buffer_tbody .= '<td valign="top">'.$result[$i]['imei'].'</td>';
	  $buffer_tbody .= '<td valign="top">'.$result[$i]['no'].'</td>';
	  $buffer_tbody .= '<td align="center" valign="top">';


	  if($_SESSION['onUserSession']['permissions']['cliente_uni_eli']=="on"){ $buffer_tbody .= '<a href="javascript:void(0)" class="remove onRemoveAsset" rel="'.$result[$i]['id'].'"><i class="fa fa-times"></i></a>';  }

	  $buffer_tbody .='</td>';
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


case "datagrid_dev_rpt":
    $buffer = NULL;
    $buffer_tbody = NULL;
    $buffer_tfoot = NULL;
	$result = $objClient->getDevicesReport();
	$result = NULL;
	if($_POST['order']!=''){ $objClient->set_order($_POST['order']); }else{ $objClient->set_order('id ASC'); }
	if($_POST['filter']['search']!=''){ $objClient->set_search($_POST['filter']['search'])->set_search_field('client'); }
	$result = $objClient->getDevicesReport();
	$total = count($result);
	$result = NULL;
	$result = $objClient->set_limit($_POST['filter']['limit'])->getDevicesReport();
	//limit

	for($i=0;$i<count($result);$i++){
	  $info = json_decode($result[$i]['data'],true);
	  $class = (($i % 2)>0) ? 'odd' : 'even';
	  $buffer_tbody .= '<tr class="'.$class.'">';
	  $buffer_tbody .= '<td valign="top">'.$result[$i]['client'].'</td>';
	  $buffer_tbody .= '<td valign="top">'.$result[$i]['alias'].'</td>';
	  $buffer_tbody .= '<td valign="top">'.$result[$i]['device'].'</td>';
	  $buffer_tbody .= '<td valign="top">'.$result[$i]['imei'].'</td>';
	  $buffer_tbody .= '<td valign="top">'.$result[$i]['no'].'</td>';
	  $buffer_tbody .= '<td valign="top">'.$result[$i]['serial_no'].'</td>';
  	  $buffer_tbody .= '<td valign="top">'.$result[$i]['account'].'</td>';
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
 