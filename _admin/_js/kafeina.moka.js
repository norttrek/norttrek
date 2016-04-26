/* Event Listeners */ 
$(document).ready(function(){
	
  $('.onClickSave').live('click',function(e){ save(e); });
  $('.onRemove').live('click',function(e){ remove(e); }); 
  
  $('.onClickSaveImei').live('click',function(e){ save_imei(e); });
  $('.onRemoveAsset').live('click',function(e){ remove_asset(e); }); 
  
  
  
  
  $('.isFromDate').datepicker({
    altFormat: 'yy-mm-dd', 
    altField: "#from_date",
    dateFormat: 'd\'/\'M\'/\'yy',
    changeMonth: true,
    changeYear: true,
    yearRange: '1981:2020',
    monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'],
    monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
    dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
    dayNames: ['Domingo', 'Lunes', 'Martes', 'MiÃ©rcoles', 'Jueves', 'Viernes', 'SÃ¡bado']
  });
  $('.isToDate').datepicker({
    altFormat: 'yy-mm-dd', 
    altField: "#to_date",
    dateFormat: 'd\'/\'M\'/\'yy',
    changeMonth: true,
    changeYear: true,
    yearRange: '1981:2020',
    monthNamesShort: ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'],
    monthNames: ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],
    dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'],
    dayNames: ['Domingo', 'Lunes', 'Martes', 'MiÃ©rcoles', 'Jueves', 'Viernes', 'SÃ¡bado']
  });

  $(".isNumber").live("keydown",function(event){
    if( event.keyCode == 46 || event.keyCode == 8 || event.keyCode == 190) {}
    else { if (event.keyCode < 48 || event.keyCode > 57) { event.preventDefault();	}}
  });  
  
  
});
 
 
/* Functions */
function save(e){
  var form = "#"+$(e.target).parents("form").attr("id");
  var module = $(form +" #ctrl").val();
  var back = $(form +" #back").val();
  if(validateForm(form)){
    $.ajax({
      type: "POST",
	  url: '_ctrl/ctrl.'+module+'.php',
	  data: $(form).serialize(),
	  success: function(err) { 
		alert("Registro guardado con exito");
 	    if(back != ""){ location.href = back; }else{ location.reload(); }
	  }
    });
  }
}


function save_imei(e){
  var form = "#"+$(e.target).parents("form").attr("id");
  var module = $(form +" #ctrl").val();
  var back = $(form +" #back").val();
  if(validateForm(form)){
	  
    $.ajax({
      type: "POST",
	  url: '_ctrl/ctrl.'+module+'.php',
	  data: $(form).serialize(),
	  datatype: 'json',
	  success: function(err) { 
		var obj = jQuery.parseJSON(err);
		if(obj.status==404){ alert("IMEI Duplicado, error"); return false;   }else{ alert("Registro guardado con exito"); }
 	    if(back != ""){ location.href = back; }else{ location.reload(); }
	  }
    });
  }
}


function validateForm(frm){
  var flag = true;  
  //$(frm + " :input[type=text].isRequired").each(function(index){ if($(this).val()==""){ $(this).addClass('required_txt'); flag=false; }else{ $(this).removeClass('required_txt'); } });
  //$(frm + "  select.isRequired").each(function(index){ if($(this).val()=="NULL"){ $(this).next().css({visibility: "visible"}); flag=false; }else{ $(this).next().css({visibility: "hidden"}); } });
  return flag;
}

function remove(e){
  selected = $(e.target);
  var id = $(selected).attr("rel");
  if(isNaN(id)){ id = $(selected).parent().attr("rel"); selected = $(e.target).parent();  }
  var ctrl = $(selected).parents("tbody").attr("id");
  if(confirm("Desea eliminar el registro?")){ 
  $.post('_ctrl/ctrl.'+ctrl+'.php', { id: id, exec: "delete" }, 
    function(data){
	  $("#tbl_"+ctrl +" > tbody tr").removeClass(); 
	  $(selected).parent().parent("tr").fadeOut('fast',function(){  $(this).remove(); $("#tbl_"+ctrl +" > tbody tr:odd").addClass("odd"); });  
	}); 
  }
}

function remove_asset(e){
  var selected = $(e.target);
  var id = $(selected).attr("rel");
  if(isNaN(id)){ id = $(selected).parent().attr("rel"); selected = $(e.target).parent(); }
  var ctrl = $(selected).parents("tbody").attr("id");
  if(confirm("Desea eliminar el registro?")){ 
  $.post('_ctrl/ctrl.'+ctrl+'.php', { id: $(selected).attr("rel"), exec: "delete_client_asset" }, 
    function(data){
	  $("#tbl_"+ctrl +" > tbody tr").removeClass(); 
	  $(selected).parent().parent("tr").fadeOut('fast',function(){  $(this).remove(); $("#tbl_"+ctrl +" > tbody tr:odd").addClass("odd"); });  
	}); 
  }
}


function number_format( number, decimals, dec_point, thousands_sep ) { 
    // *     example 1: number_format(1234.5678, 2, '.', '');
    // *     returns 1: 1234.57     
    var n = number, c = isNaN(decimals = Math.abs(decimals)) ? 2 : decimals;
    var d = dec_point == undefined ? "," : dec_point;
    var t = thousands_sep == undefined ? "." : thousands_sep, s = n < 0 ? "-" : "";
    var i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "", j = (j = i.length) > 3 ? j % 3 : 0;
    
    return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
}



/* Datagrid */

function render_datagrid(data){ $("#"+data.table.id).render_table(data); }

jQuery.fn.render_table = function(data){ 
  $("#loader").fadeIn();
  $("#"+data.table.id+' > tbody:last').empty();
  $("#"+data.table.id+' > tfoot:last').empty();
  $.post("_ctrl/ctrl."+data.table.source+".php", 
  { 
    order: data.order.order_by,
    limit: data.table.limit, 
    filter: data.filter,
    exec: data.table.action 
  },function(result){
    $("#"+data.table.id +" > tbody").append(result.tbody);
	$("#"+data.table.id +" > tfoot").append(result.tfoot);
	$("#"+data.table.id +" > tbody tr:odd").addClass("odd");
	$("#loader").fadeOut(500);
  },"json");
}  

