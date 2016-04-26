<?php
require_once("_class/class.equipo.php");
require_once("_class/class.staff.php");

$objEquipo = new Equipo();
$objStaff = new Staff();


$data = NULL;
if(isset($_GET['id'])){ 
  require_once("_class/class.unidad.php");
  $objUnidad = new Unidad();
  $data = $objUnidad->getUnidad($_GET['id']);
 
  $info_aux = explode("|",$data[0]['info']);
  
  $aux = explode(",",$info_aux[0]);
  $unidad['clave_interna'] = $aux[1];
 
  $aux = explode(",",$info_aux[1]);
  $unidad['nombre'] = $aux[1];
  
  $aux = explode(",",$info_aux[2]);
  $unidad['descripcion'] = $aux[1];
  
  $aux = explode(",",$info_aux[3]);
  $unidad['tipo'] = $aux[1];
  
  $aux = explode(",",$info_aux[4]);
  $unidad['marca'] = $aux[1];
  
  $aux = explode(",",$info_aux[5]);
  $unidad['modelo'] = $aux[1];
  
  $aux = explode(",",$info_aux[6]);
  $unidad['ano'] = $aux[1];
  
  $aux = explode(",",$info_aux[7]);
  $unidad['no_serie'] = $aux[1];
  
  $aux = explode(",",$info_aux[8]);
  $unidad['placa'] = $aux[1];
  
  $aux = explode(",",$info_aux[9]);
  $unidad['color'] = $aux[1];
  
  $aux = explode(",",$info_aux[10]);
  $unidad['no_telefono'] = $aux[1];
  
  $aux = explode(",",$info_aux[11]);
  $unidad['no_serie_chip'] = $aux[1];
  
  $aux = explode(",",$info_aux[12]);
  $unidad['fecha_instalacion'] = $aux[1];
  
  $aux = explode(",",$info_aux[13]);
  $unidad['observaciones'] = $aux[1];
    
  
}

$equipos = $objEquipo->getEquipo();
$buffer_equipos = '<option value="NULL">(Seleccione una Opci&oacute;n)</option>';
for($i=0;$i<count($equipos);$i++){
  $selected = '';
  if($data[0]['id_equipo']==$equipos[$i]['id']){ $selected = 'selected="selected"'; }
  $buffer_equipos .= '<option value="'.$equipos[$i]['id'].'" '.$selected.'>'.$equipos[$i]['equipo'].'</option>';
}	

$instaladores = $objStaff->set_tipo('Instalador')->getStaff();
$buffer_instaladores = '<option value="NULL">(Seleccione una Opci&oacute;n)</option>';
for($i=0;$i<count($instaladores);$i++){
  $selected = '';
  if($data[0]['id_staff']==$instaladores[$i]['id']){ $selected = 'selected="selected"'; }
  $buffer_instaladores .= '<option value="'.$instaladores[$i]['id'].'" '.$selected.'>'.$instaladores[$i]['nombre'].'</option>';
}	

function NlToBr($inString){
  return str_replace("<br />", "",$inString);
}	  
?>

<style>
#unidad ul.steps { margin-left:10px; }
#unidad .panel { margin-left:15px; min-width:1170px }
#unidad .panel .left { width:39%; float:left; max-width:400px; background-color:#fff; border:#e4e4e4 solid 1px; position:relative; }
#unidad .panel .left .nav_tabs { position:absolute; right:0; }
#unidad .panel .left .nav_tabs ul { margin-top:-29px;}
#unidad .panel .left .nav_tabs ul li { background-color:#fff; color:#000; float:left; margin-left:5px; border:#e4e4e4 solid 1px; border-bottom:none; }
#unidad .panel .left .nav_tabs ul li a { display:block; padding:5px; color:#000; }

#unidad .panel .right { width:60%;float:left; margin-left:10px; background-color:#fff; border:#e4e4e4 solid 1px; position:relative; }
#unidad .panel .right .nav_tabs { position:absolute; right:0; }
#unidad .panel .right .nav_tabs ul { margin-top:-29px;}
#unidad .panel .right .nav_tabs ul li { background-color:#fff; color:#000; float:left; margin-left:5px; border:#e4e4e4 solid 1px; border-bottom:none; }
#unidad .panel .right .nav_tabs ul li a { display:block; padding:5px; color:#000; }
#unidad .panel .right .tabs .isTab { display:none; }
#unidad .panel .right .tabs .active { display:block;}



#cliente .panel .left .info  { width:400px; border:#ccc solid 1px;}
.clear { clear:both;}
</style>


<div id="unidad">

  <h1 class="title"> Unidad</h1>
  <br />
  
  <div class="panel">
    <div id="modals">
    <form id="frm_unidad" name="frm_unidad">
      <fieldset>

         <p>
          <label class="inline">Clave Interna</label>
          <input type="text" id="info[]" name="info[]" value="<?php echo $unidad['clave_interna'] ?>" class="inline"/>
          <input id="lbl_info[]" name="lbl_info[]" type="hidden" value="clave_interna" />
          <br class="clear" />
        </p>
 
        <p>
          <label class="inline">Alias <em>*</em></label>
          <input type="text" id="txt_alias" name="txt_alias" value="<?php echo $data[0]['alias'] ?>" class="inline"/>
          <br class="clear" />
        </p>
        <p>
          <label class="inline">Nombre *</em></label>
          <input type="text" id="info[]" name="info[]" value="<?php echo $unidad['nombre'] ?>" class="inline"/>
          <input id="lbl_info[]" name="lbl_info[]" type="hidden" value="nombre" />
          <br class="clear" />
        </p>
        
         <p>
          <label class="inline">Descripcion <em>*</em></label>
          <input type="text" id="info[]" name="info[]" value="<?php echo $unidad['descripcion'] ?>" class="inline"/>
          <input id="lbl_info[]" name="lbl_info[]" type="hidden" value="descripcion" />
          <br class="clear" />
        </p>
        
        <p>
          <label class="inline">Tipo de Vehiculo <em>*</em></label>
          <select id="info[]" name="info[]">
            <option value="Automovil" <?php if($unidad['tipo']=='Automovil') echo 'selected="selected"'; ?>>Automovil</option>
            <option value="Camioneta de Reparto" <?php if($unidad['tipo']=='Camioneta de Reparto') echo 'selected="selected"'; ?>>Camioneta de Reparto</option>
            <option value="Caja Seca" <?php if($unidad['tipo']=='Caja Seca') echo 'selected="selected"'; ?>>Caja Seca</option>
            <option value="Caja Refrigerada" <?php if($unidad['tipo']=='Caja Refrigerada') echo 'selected="selected"'; ?>>Caja Refrigerada</option>
            <option value="Moto" <?php if($unidad['tipo']=='Moto') echo 'selected="selected"'; ?>>Moto</option>
            <option value="Plataforma" <?php if($unidad['tipo']=='Plataforma') echo 'selected="selected"'; ?>>Plataforma</option>
            <option value="Plataforma Cortina" <?php if($unidad['tipo']=='Plataforma Cortina') echo 'selected="selected"'; ?>>Plataforma Cortina</option>
            <option value="Pickup" <?php if($unidad['tipo']=='Pickup') echo 'selected="selected"'; ?>>Pickup</option>
            <option value="Tracto 5 Rueda" <?php if($unidad['tipo']=='Tracto 5 Rueda') echo 'selected="selected"'; ?>>Tracto 5 Rueda</option>
            <option value="Torton" <?php if($unidad['tipo']=='Torton') echo 'selected="selected"'; ?>>Torton</option>
            <option value="Utilitarios" <?php if($unidad['tipo']=='Utilitarios') echo 'selected="selected"'; ?>>Utilitarios</option>
          </select>
          <input id="lbl_info[]" name="lbl_info[]" type="hidden" value="tipo" />
          <br class="clear" />
        </p>
        
        <p>
          <label class="inline">Marca <em>*</em></label>
          <input type="text" id="info[]" name="info[]" value="<?php echo $unidad['marca'] ?>" class="inline"/>
          <input id="lbl_info[]" name="lbl_info[]" type="hidden" value="marca" />
          <br class="clear" />
        </p>
       
       
       <p>
          <label class="inline">Modelo <em>*</em></label>
          <input type="text" id="info[]" name="info[]" value="<?php echo $unidad['modelo'] ?>" class="inline"/>
          <input id="lbl_info[]" name="lbl_info[]" type="hidden" value="modelo" />
          <br class="clear" />
        </p>
        
        <p>
          <label class="inline">A&ntilde;o <em>*</em></label>
          <select id="info[]" name="info[]">
            
            <?php for($i=(date('Y')+1);$i>=1970;$i--){ echo '<option value="'.$i.'">'.$i.'</option>'; } ?>
            <option value="Otro">Otro</option>
          </select>
          <input id="lbl_info[]" name="lbl_info[]" type="hidden" value="ano" />
          <br class="clear" />
        </p>
       
       <p>
          <label class="inline">No. Serie <em>*</em></label>
          <input type="text" id="info[]" name="info[]" value="<?php echo $unidad['no_serie'] ?>" class="inline"/>
          <input id="lbl_info[]" name="lbl_info[]" type="hidden" value="no_serie" />
          <br class="clear" />
        </p>
        
        <p>
          <label class="inline">Placas<em>*</em></label>
          <input type="text" id="info[]" name="info[]" value="<?php echo $unidad['placa'] ?>" class="inline"/>
          <input id="lbl_info[]" name="lbl_info[]" type="hidden" value="placa" />
          <br class="clear" />
        </p>
        
          <p>
          <label class="inline">Color<em>*</em></label>
          <input type="text" id="info[]" name="info[]" value="<?php echo $unidad['color'] ?>" class="inline"/>
          <input id="lbl_info[]" name="lbl_info[]" type="hidden" value="color" />
          <br class="clear" />
        </p>
        
           <p>
          <label class="inline">No. Telefono<em>*</em></label>
          <input type="text" id="info[]" name="info[]" value="<?php echo $unidad['no_telefono'] ?>" class="inline"/>
          <input id="lbl_info[]" name="lbl_info[]" type="hidden" value="no_telefono" />
          <br class="clear" />
        </p>
        
        <p>
          <label class="inline">IMEI<em>*</em></label>
          <input type="text" id="txt_imei" name="txt_imei" value="<?php echo $data[0]['imei'] ?>" class="inline"/>
          <br class="clear" />
        </p>
        
         <p>
          <label class="inline">No. Serie CHIP<em>*</em></label>
          <input type="text" id="info[]" name="info[]" value="<?php echo $unidad['no_serie_chip'] ?>" class="inline"/>
          <input id="lbl_info[]" name="lbl_info[]" type="hidden" value="no_serie_chip" />
          <br class="clear" />
        </p>
        
        <p>
          <label class="inline">GPS<em>*</em></label>
          <select id="lst_id_equipo" name="lst_id_equipo"><?php echo $buffer_equipos; ?></select>
          <br class="clear" />
        </p>
        
         <p>
          <label class="inline">Instalador<em>*</em></label>
          <select id="lst_id_staff" name="lst_id_staff"><?php echo $buffer_instaladores; ?></select>
          <br class="clear" />
        </p>
        
          <p>
          <label class="inline">Fecha de Instalacion<em>*</em></label>
          <input type="text" id="info[]" name="info[]" value="<?php echo $unidad['fecha_instalacion'] ?>" class="inline"/>
          <input id="lbl_info[]" name="lbl_info[]" type="hidden" value="fecha_instalacion" />
          <br class="clear" />
        </p>
         <p>
          <label class="inline">Observaciones<em>*</em></label>
          <input type="text" id="info[]" name="info[]" value="<?php echo $unidad['observaciones'] ?>" class="inline"/>
          <input id="lbl_info[]" name="lbl_info[]" type="hidden" value="observaciones" />
          <br class="clear" />
        </p>
      
        
        <p class="save"><a href="#" class="btn_save onClickSave">Guardar</a></p>
        <input id="id" name="id" type="hidden"  value="<?php if(isset($_GET['id'])) echo $_GET['id']; ?>"/>
        <input id="id_cliente" name="id_cliente" type="hidden"  value="<?php if(isset($_GET['id_cliente'])) echo $_GET['id_cliente']; ?>"/>
        <input id="ctrl" name="ctrl" type="hidden" value="unidad" />
        <input id="exec" name="exec" type="hidden" value="save" /> 
        <input type="hidden" id="back" name="back" value="index.php?call=cliente" />
      </fieldset>
    </form>
    </div>
  </div>
  
  
  
   
</div>