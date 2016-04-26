
<div id="subcontent" >

  <h1 class="title"> Reporte General</h1>
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
	.filter ul li input.isDate { width:100px; padding:5px; border:#ccc solid 1px; margin-top:-7px; background:url(_img/cal.png) right no-repeat #fff; }
	</style>
    <div class="filter">
      <ul>
       
        <li class="label"><i class="fa fa-cogs fa-2x"></i> </li>
        <li>
          <input type="text" id="txt_from_date" name="txt_from_date" class="isDate isFromDate" placeholder="Fecha Inicio"  />
          <input id="from_date" name="from_date" type="hidden" value="<?php echo date("Y-m-d"); ?>" />
        
        </li>
        <li>
          <input type="text" id="txt_to_date" name="txt_to_date" class="isDate isToDate" placeholder="Fecha Final"/>
          <input id="to_date" name="to_date" type="hidden" value="<?php echo date("Y-m-d"); ?>" />
         
        </li>
        <li><a href="javascript:filter()" class="btn_filter">Filtrar</a></li>
      </ul>
      <br class="clear" />
    </div>
  <h2>Total Clientes VS Estatus</h2>
  <table id="tbl_rpt_general" class="result" cellpadding="0" cellspacing="0" width="300" align="left" style="width:500px;">
      <thead>
        <th align="left"><i class="fa fa-signal"></i> Estatus</th>
        <th align="left">Total</th>
      </thead>
      <tbody id="servicio">
        <tr><td>1. Por Atender</td><td>0</td></tr>
        <tr  class="odd"><td>2. Pre-Calificaci&oacute;n</td><td>0</td></tr>
        <tr><td>3. Integraci&oacute;n de Expediente</td><td>0</td></tr>
        <tr  class="odd"><td>4. Envio a Banco</td><td>0</td></tr>
        <tr><td>5. Analisis Bancario</td><td>0</td></tr>
        <tr  class="odd"><td>6. Avaluo y Pre-Pre</td><td>0</td></tr>
        <tr><td>7. Firma</td><td>0</td></tr>
        <tr  class="odd"><td>8. Cierre</td><td>0</td></tr>
        </tr>
      </tbody>
      <tfoot>
        <td></td>
      </tfoot>
    </table>
    
    <br class="clear" />
    
     <h2>Total Clientes VS Banco</h2>
  <table id="tbl_rpt_general" class="result" cellpadding="0" cellspacing="0" width="300" align="left" style="width:500px;">
      <thead>
        <th align="left"><i class="fa fa-signal"></i> Estatus</th>
        <th align="left">Total</th>
      </thead>
      <tbody id="servicio">
        <tr><td>1. Por Atender</td><td>0</td></tr>
        <tr  class="odd"><td>2. Pre-Calificaci&oacute;n</td><td>0</td></tr>
        <tr><td>3. Integraci&oacute;n de Expediente</td><td>0</td></tr>
        <tr  class="odd"><td>4. Envio a Banco</td><td>0</td></tr>
        <tr><td>5. Analisis Bancario</td><td>0</td></tr>
        <tr  class="odd"><td>6. Avaluo y Pre-Pre</td><td>0</td></tr>
        <tr><td>7. Firma</td><td>0</td></tr>
        <tr  class="odd"><td>8. Cierre</td><td>0</td></tr>
        </tr>
      </tbody>
      <tfoot>
        <td></td>
      </tfoot>
    </table>
    
     <br class="clear" />
    
     <h2>Total Clientes VS Servicios</h2>
  <table id="tbl_rpt_general" class="result" cellpadding="0" cellspacing="0" width="300" align="left" style="width:500px;">
      <thead>
        <th align="left"><i class="fa fa-signal"></i> Estatus</th>
        <th align="left">Total</th>
      </thead>
      <tbody id="servicio">
        <tr><td>1. Por Atender</td><td>0</td></tr>
        <tr  class="odd"><td>2. Pre-Calificaci&oacute;n</td><td>0</td></tr>
        <tr><td>3. Integraci&oacute;n de Expediente</td><td>0</td></tr>
        <tr  class="odd"><td>4. Envio a Banco</td><td>0</td></tr>
        <tr><td>5. Analisis Bancario</td><td>0</td></tr>
        <tr  class="odd"><td>6. Avaluo y Pre-Pre</td><td>0</td></tr>
        <tr><td>7. Firma</td><td>0</td></tr>
        <tr  class="odd"><td>8. Cierre</td><td>0</td></tr>
        </tr>
      </tbody>
      <tfoot>
        <td></td>
      </tfoot>
    </table>
    
    <br class="clear" />
    
     <h2>Total Clientes VS Usuarios</h2>
  <table id="tbl_rpt_general" class="result" cellpadding="0" cellspacing="0" width="300" align="left" style="width:500px;">
      <thead>
        <th align="left"><i class="fa fa-signal"></i> Estatus</th>
        <th align="left">Total</th>
      </thead>
      <tbody id="servicio">
        <tr><td>1. Por Atender</td><td>0</td></tr>
        <tr  class="odd"><td>2. Pre-Calificaci&oacute;n</td><td>0</td></tr>
        <tr><td>3. Integraci&oacute;n de Expediente</td><td>0</td></tr>
        <tr  class="odd"><td>4. Envio a Banco</td><td>0</td></tr>
        <tr><td>5. Analisis Bancario</td><td>0</td></tr>
        <tr  class="odd"><td>6. Avaluo y Pre-Pre</td><td>0</td></tr>
        <tr><td>7. Firma</td><td>0</td></tr>
        <tr  class="odd"><td>8. Cierre</td><td>0</td></tr>
        </tr>
      </tbody>
      <tfoot>
        <td></td>
      </tfoot>
    </table>
    
    
    <br class="clear" />
    
     <h2>Total Clientes VS Medio Publicitario</h2>
  <table id="tbl_rpt_general" class="result" cellpadding="0" cellspacing="0" width="300" align="left" style="width:500px;">
      <thead>
        <th align="left"><i class="fa fa-signal"></i> Estatus</th>
        <th align="left">Total</th>
      </thead>
      <tbody id="servicio">
        <tr><td>1. Por Atender</td><td>0</td></tr>
        <tr  class="odd"><td>2. Pre-Calificaci&oacute;n</td><td>0</td></tr>
        <tr><td>3. Integraci&oacute;n de Expediente</td><td>0</td></tr>
        <tr  class="odd"><td>4. Envio a Banco</td><td>0</td></tr>
        <tr><td>5. Analisis Bancario</td><td>0</td></tr>
        <tr  class="odd"><td>6. Avaluo y Pre-Pre</td><td>0</td></tr>
        <tr><td>7. Firma</td><td>0</td></tr>
        <tr  class="odd"><td>8. Cierre</td><td>0</td></tr>
        </tr>
      </tbody>
      <tfoot>
        <td></td>
      </tfoot>
    </table>
   
   
</div>
