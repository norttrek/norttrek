<?php
require_once("../_class/class.client.php");
$objClient = new Client();
$data = NULL;

$contacto = NULL;
if(isset($_GET['id'])){ 
  $data = $objClient->getClient($_GET['id']); 
  $data_user = $objClient->getUserMaster($_GET['id']);
  $park = json_decode($data[0]['park'],true);
  $payments = json_decode($data[0]['payment'],true);
  $security = json_decode($data[0]['security'],true);
  $info = json_decode($data[0]['info'],true);
  
  //echo '<pre>';
  //print_r($payments);


}

$total = $objClient->getTotalClientAssets($_GET['id']);



function NlToBr($inString){
  return str_replace("<br />", "",$inString);
}
?>

<style>
#cliente ul.steps { margin-left:10px; }
#cliente ul.steps li { width:12%; display:block; float:left; background-color:#e4e4e4; padding-top:10px; padding-bottom:10px; margin-right:2px; text-align:center; margin-left:10px; }
#cliente .panel { margin-left:15px; min-width:1170px }
#cliente .panel .left { width:39%; float:left; max-width:400px; background-color:#fff; border:#e4e4e4 solid 1px; position:relative; }
#cliente .panel .left .nav_tabs { position:absolute; right:0; }
#cliente .panel .left .nav_tabs ul { margin-top:-29px;}
#cliente .panel .left .nav_tabs ul li { background-color:#fff; color:#000; float:left; margin-left:5px; border:#e4e4e4 solid 1px; border-bottom:none; }
#cliente .panel .left .nav_tabs ul li a { display:block; padding:5px; color:#000; }

#cliente .panel .right { width:65%;float:left;position:relative; }

#cliente h1 { box-shadow:0px 0px 3px 0px rgba(41, 43, 51, 0.25); background-color:#fff; margin:0; font-size:18px; padding-left:15px; padding-top:10px; height:35px; }

#cliente .panel .left .info  { width:400px; border:#ccc solid 1px;}

#modals label
#modals label.label { font-weight:700; color:#505458; font-size:13px; margin-bottom:-2px; width:300px; }
#modals label.value { display:inline-block; color:#428bca; width:300px; }
#modals p { margin-bottom:10px; }
.clear { clear:both;}
.btn_edit { width:50px; padding:5px; background-color:#F90; color:#fff; position: absolute; display:inline-block; margin-top:-20px; left:340px; }

.submenu { position:absolute; width:300px; height:30px; right:30px; margin-top:10px; }
.submenu ul li { float:right; margin-left:10px; }
.submenu ul li a  { min-width:100px; text-align:center; padding:5px; background-color:#000; color:#fff; border-radius:3px; height:35px;}
</style>
<script>
var data;
$(document).ready(function() { 
   table = null;
   data = {
       table  : { id:"tbl_assets",source: "client", action: "datagrid_client_assets"},
     filter : { limit:1000, idate: null,edate:null, search: null,id:<?php echo $_GET['id']; ?>},
     order  : { order_by: 'id DESC', opt:null},
   }
   //table.source.order = "sort ASC";
   $("#"+data.table.id).render_table(data); 
  
  
  
  
  
  
  });
  
  
  
  
  
  
 
</script>

<div id="cliente">
  <div class="submenu" align="right">
    <ul>
      <li><?php if($_SESSION['onUserSession']['permissions']['cliente_uni_cre']=="on"){ ?><a href="_view/_form/frm.client_asset.php?id_client=<?php echo $_GET['id']; ?>" class="fancybox.ajax modal">Registrar Unidad</a><?php } ?></li>
    </ul>
    <br class="clear" />
  </div>
  <h1 class="title"><i class="fa fa-angle-right"></i> <?php echo $data[0]['client']; ?></h1>
  <br /><br />
  
  <div class="panel">
    <div class="left">
      <div class="nav_tabs">
        <ul>
          <li><a href="javascript:void(0)" rel="3" class="onTabClick">Seguridad</a></li>
          <li><a href="javascript:void(0)" rel="0" class="onTabClick">Info. General</a></li>
          <li><a href="javascript:void(0)" rel="2" class="onTabClick">Patio</a></li>
          <li><a href="javascript:void(0)" rel="1" class="onTabClick">Pagos</a></li>
        </ul>
      </div>
        
        <div class="tab_0 isTab active">
          <div id="modals">
          <h2 class="subtitle">Informaci&oacute;n General </h2>
        <form id="frm_cliente" name="frm_cliente">
        <?php if($_SESSION['onUserSession']['permissions']['cliente_inf_edi']=="on"){ ?>
        <a href="_view/_form/frm.client_info.php?id=<?php echo $_GET['id']; ?>" class="fancybox.ajax modal btn_edit">editar</a>
        <?php } ?>
        <fieldset>
          <p><label class="inline label">Nombre Comercial </label><label class="value"><?php echo $data[0]['client'] ?></label></p>
        
          <p><label class="inline label" label>Raz&oacute;n Social  </label><label class="value"><?php echo html_entity_decode($info['razon_social']); ?></label></p>
          <p><label class="inline label">RFC  </label><label class="value"><?php echo $info['rfc'] ?></label></p>
          <p><label class="inline label">E-mail</label><label class="value"><?php echo $info['correo']; ?></label></p>
          <p><label class="inline label">Giro de la Empresa  </label><label class="value"><?php echo html_entity_decode($info['giro_empresa']); ?></label></p>
          
          <p><label class="inline label">Calle y No. </label><label class="value"><?php echo html_entity_decode($info['calle_no']); ?></label></p>
          <p><label class="inline label">No. Exterior</label><label class="value"><?php echo $info['no_ext'] ?></label></p>
          <p><label class="inline label">No. Interior</label><label class="value"><?php echo $info['no_int'] ?></label></p>
          <p><label class="inline label">Colonia</label><label class="value"><?php echo html_entity_decode($info['colonia']); ?></label></p>
          <p><label class="inline label">Delegaci&oacute;n / Municipio</label><label class="value"><?php echo html_entity_decode($info['dele_mun']); ?></label></p>
          <p><label class="inline label">Ciudad</label><label class="value"><?php echo html_entity_decode($info['ciudad']); ?></label></p>
          <p><label class="inline label">Estado</label><label class="value"><?php echo html_entity_decode($info['estado']); ?></label></p>
          <p><label class="inline label">CP</label><label class="value"><?php echo $info['cp'] ?></label></p>
          
      <p>
            <label class="inline label">Tel. <?php echo $info['contacto_1_medio'] ?></label>
            <label class="inline value"><?php echo $info['contacto_1'] ?></label>
            <br class="clear" />
      </p>
      <p>
            
            <label class="inline label">Tel. <?php echo $info['contacto_2_medio'] ?></label>
        <label class="inline value"><?php echo $info['contacto_2'] ?></label>
        <br class="clear" />
      </p>
      <p>
            <label class="inline label">Tel. <?php echo $info['contacto_3_medio'] ?></label>
        <label class="inline value"><?php echo $info['contacto_3'] ?></label>
        <br class="clear" />
      </p>
         <p><label class="inline label" label>No. Cliente  </label><label class="value"><?php echo $info['clave_interna'] ?></label></p>
         <p><label class="inline label" label>Digito  </label><label class="value"><?php echo $info['digito'] ?></label></p>
      <input id="id" name="id" type="hidden"  value="<?php if(isset($_GET['id'])) echo $_GET['id']; ?>"/>
      <input id="ctrl" name="ctrl" type="hidden" value="cliente" />
      <input id="exec" name="exec" type="hidden" value="<?php echo $value; ?>" /> 
      <input type="hidden" id="back" name="back" value="index.php?call=cat_clientes" />
        </fieldset>
        </form>
          </div>
        </div>
        
        <div class="tab_1 isTab">
          <div id="modals">
          <h2 class="subtitle">Pagos</h2>
            <?php if($_SESSION['onUserSession']['permissions']['cliente_inf_edi']=="on"){ ?>
            <a href="_view/_form/frm.client_payment.php?id=<?php echo $_GET['id']; ?>" class="fancybox.ajax modal clear btn_edit">editar</a>
            <?php } ?>
    <form id="frm_cliente" name="frm_cliente">
      <fieldset>
      <p>
          <label class="inline label">Contacto Pagos </label>
          <label class="value"><?php echo html_entity_decode($payments['contacto_pagos']); ?></label>
        </p>
     
         <p>
          <label class="inline label">E-mail (Facturas)</label>
          <label class="value"><?php echo $payments['email_facturas'] ?></label>
        </p>
 
        <p>
          <label class="inline label">Dia de Corte  </label>
          <label class="value"><?php echo $payments['dia_corte'] ?></label>
        </p>
        <p>
          <label class="inline label">Metodo de Pago</label>
          <label class="value"><?php echo $payments['metodo_pago'] ?></label>
        </p>
        <p>
          <label class="inline label">Renta Unitaria sin I.V.A.</label>
          <label class="value"><strong>$<?php echo $payments['renta_unitaria']; ?></strong></label>
        </p>
        
         <p>
          <label class="inline label">TOTAL sin I.V.A.</label>
          <label class="value"><strong>$<?php echo number_format(($payments['renta_unitaria']*$total[0]['TOTAL']),2); ?></strong></label>
        </p>
        
         <p>
          <label class="inline label">Plazo Minimo</label>
          <label class="value"><?php echo $payments['plazo_minimo']; ?></label>
        </p>
        
         <p>
          <label class="inline label">Tipo de Plan</label>
          <label class="value"><?php echo $payments['tipo_plan']; ?></label>
        </p>
        
        <p>
          <label class="inline label">Tipo de Operaci&oacute;n</label>
          <label class="value"><?php echo $payments['tipo_operacion']; ?></label>
        </p>
        
        <p>
          <label class="inline label">Inicio de Contrato</label>
          <label class="value"><?php echo $objClient->formatDate($payments['contrato_inicio'],"max"); ?></label>
        </p>
        
         <p>
          <label class="inline label">Fin de Contrato</label>
          <label class="value"><?php echo $objClient->formatDate(date('Y-m-d', strtotime("+".$payments['plazo_minimo']." months", strtotime($payments['contrato_inicio']))),"max"); ?></label>
        </p>
        
         <p>
          <label class="inline label">Total de Unidades</label>
          <label class="value"><?php echo $total[0]['TOTAL'];  ?></label>
        </p>
  
        <p>
          <label class="inline label">Observaciones</label>
          <label class="value"><?php echo html_entity_decode($payments['observaciones']); ?></label>
        </p>

      
        
        <input id="id" name="id" type="hidden"  value="<?php if(isset($_GET['id'])) echo $_GET['id']; ?>"/>
        <input id="ctrl" name="ctrl" type="hidden" value="cliente" />
        <input id="exec" name="exec" type="hidden" value="<?php echo $value; ?>" /> 
        <input type="hidden" id="back" name="back" value="index.php?call=cat_clientes" />
      </fieldset>
    </form></div>
        </div>
        <div class="tab_2 isTab">
          <div id="modals">
          <h2 class="subtitle">Patio</h2>
          <?php if($_SESSION['onUserSession']['permissions']['cliente_inf_edi']=="on"){ ?>
          <a href="_view/_form/frm.client_park.php?id=<?php echo $_GET['id']; ?>" class="fancybox.ajax modal btn_edit">editar</a>
          <?php } ?>
        <form id="frm_cliente" name="frm_cliente">
        <fieldset>
          <p><label class="inline label">Direcci&oacute;n del Patio</label><label class="value"><?php echo html_entity_decode($park['direccion']); ?></label></p>
      <p>
        <label class="inline label">Contacto (Tel. <?php echo $park['contacto_medio']; ?>)</label>
            <label class="inline value"><?php echo $park['contacto']; ?></label>
            <br class="clear" />
      </p>
          <p>
          <label class="inline label">Observaciones</label>
          <label class="value"><?php echo html_entity_decode($park['observaciones']); ?></label>
          </p>
    
          
      <input id="id" name="id" type="hidden"  value="<?php if(isset($_GET['id'])) echo $_GET['id']; ?>"/>
      <input id="ctrl" name="ctrl" type="hidden" value="cliente" />
      <input id="exec" name="exec" type="hidden" value="<?php echo $value; ?>" /> 
      <input type="hidden" id="back" name="back" value="index.php?call=cat_clientes" />
        </fieldset>
        </form>
          </div>
        </div>
        
        
         <div class="tab_3 isTab">
        <div id="modals">
      
    <form id="frm_cliente" name="frm_cliente">
      <fieldset>
       <?php if($_SESSION['onUserSession']['permissions']['cliente_inf_edi']=="on"){ ?>
<a href="_view/_form/frm.client_security.php?id=<?php echo $_GET['id']; ?>" class="fancybox.ajax modal btn_edit">editar</a>
<?php } ?>
 
         <p><label class="inline label">PIN Telefonico</label><label class="value"><?php echo $security['pin_seguridad'] ?></label></p>
         <p><label class="inline label">PIN Bloqueo</label><label class="value"><?php echo $security['pin_bloqueos'] ?></label></p>
         <p><label class="inline label">Usuario</label><label class="value"><?php echo $data_user[0]['user'] ?></label></p>
         <p><label class="inline label">Contrase&ntilde;a</label><label class="value"><?php echo $data_user[0]['password'] ?></label></p>
      
        <input id="id" name="id" type="hidden"  value="<?php if(isset($_GET['id'])) echo $_GET['id']; ?>"/>
        <input id="ctrl" name="ctrl" type="hidden" value="cliente" />
        <input id="exec" name="exec" type="hidden" value="<?php echo $value; ?>" /> 
        <input type="hidden" id="back" name="back" value="index.php?call=cat_clientes" />
      </fieldset>
    </form></div>
        </div> <!-- -->
        
    </div>
    <div class="right">
         
          <table id="tbl_assets" class="result" cellpadding="0" cellspacing="0" width="100%" align="center">
            <thead>
              <th align="left" width="30"><i class="fa fa-signal"></i> ID</th>
              <th align="left"><i class="fa fa-signal"></i> ALIAS</th>
              <th align="left"><i class="fa fa-signal"></i> IMEI</th>
              <th align="left"><i class="fa fa-signal"></i> NO</th>
              <th align="center" width="20"><i class="fa fa-signal"></i> COMB</th>
              <th align="left"></th>
            </thead>
            <tbody id="client">
            </tbody>
            <tfoot>
            </tfoot>
          </table>
                  
          
    </div>
    
  </div>
  
  <style>
  #tab_r .tmenu ul { margin:0; padding:0; list-style:none; margin-right:25px; }
  #tab_r .tmenu ul li { background-color:#e4e4e4; float:right; margin-right:10px; }
  #tab_r .tmenu ul li a  { background-color:#F30; color:#fff; padding:7px;}
  .isTab { display:none;}
  .isTab.active { display:block; }
  </style>
    <script>
  $(document).ready(function(){ 
    $('.onTabClick').click(function(){
      $('.isTab').hide(); 
    $('.tab_'+$(this).attr("rel")).show();    
    });
  });
  </script>
 
  
  
</div>