 var objClient = null;
 

$(window).load(function(){ 
	objClient = new Client(); 
});
 

var Client = function(){ 
  var c = this;
  this.ctrl = '../_ctrl/ctrl.client.php';

  function onLoad(){  
  	c.addEventListeners(); 
  }

  this.addEventListeners = function(){  
	$("a.onClickUserAdd").click(function(){ 
		 $('.addUser').show();
		this,c.add_user});

	$('#addUser').on('click','.onClickUserAdd',function(){
		 
		var exec = 'save_user';
		

		var id = '';
		var date = $("#date_exp").val()+" "+$("#hour").val();
		var data = { id: id, user: $("#txt_user").val() , password:  $("#txt_password").val(),date_exp : date , assets: $(".chk_asset").serializeArray() };
		if(data.user == "" || data.password==""){ 
			alert("Error!"); 
			return false; 
		}
	    $.post(c.ctrl, { exec: exec, data : data },function(r){
			if(r=="0"){ alert("ERROR! El nombre de usuario se encuentra en uso, por favor utilice otro."); return false; }
			alert("Usuario creado con exito.");
			$('#users').slideDown('slow'); 
			$('.addUser').show();
		$('#addUser').slideUp('slow');
			c.get_users();
		});
	})

	$('#editUser').on('click','.onClickUserEdit',function(){
		 $('#users').slideDown('slow'); 
		 $('#editUser').slideUp('slow');
		var exec = 'update_user';
		

		var id = $(this).attr('ide');
		var date = $("#date_exp").val()+" "+$("#hour").val();
		var data = { id: id, user: $("#txt_user").val() , password:  $("#txt_password").val(),date_exp : date , assets: $(".chk_asset").serializeArray() };
		if(data.user == "" || data.password==""){ 
			alert("Error!"); 
			return false; 
		}
	    $.post(c.ctrl, { exec: exec, data : data },function(r){
			 
			alert("Usuario editado con exito.");
			$('.addUser').show();
			c.get_users();
		});
	})


	$("a.onClickChangePass").click(function(){  
		var data = { password:  $("#txt_password").val() };
	if(data.password==""){ alert("Error!"); return false; }
	if(data.password!=$("#txt_password_verify").val()){ alert("Error! campos no coinciden."); return false; }
    $.post(c.ctrl, { exec: "update_pass", data : data },function(r){ 
    		alert('Contraseña Actualizada') 
    	});
	});
	 
	/*$("#geocercasBtn").click(function(){
		console.log('clicked') 
		 c.get_geofences();
	})*/
	$('.dropdown-menu li a').click(function(){ 
		console.log('hey')
		wdt = $(this).attr('sideBarWidth');
		unidades = $(this).attr('unidades');
		side = $(this).attr('side');
		$('#'+side).offcanvas("show");
		$('body').attr('side',side);


		var sideActive = $('body').attr('side');
    	sideWidth = $('#'+sideActive).width();
    	var tableWidth =   $('body').width() - sideWidth - 50;
    	$('#tbl_route').css('width',tableWidth+'px')
    	
    	
		 
		if(unidades == 0){
			c.hideUnidades();
		}
	 
		$('body').css('left',wdt+'px')
		$('.navmenu').css('width',wdt+'px')
		$('body').addClass('move')
	})
	$('.Closeside').click(function(){
	 console.log('close side')
	$('#tbl_route').css('width','')
	/* $('#geocercas_a').offcanvas("hide"); */
	var side = $(this).attr('side');
 
	$('#'+side).offcanvas("hide");
	$('body').removeClass('move');
	$('.derecha').css('margin','0px 0px 0px 390px') ;
	c.showUnidades();
})
	$('.headerlink').click(function(){
		
		side = $('body').attr('side');
		if(side != undefined){
			//c.hideUnidades();
		}
		c.showUnidades();
		$('#'+side).offcanvas("hide");
	})
	$("a.onClickGroupAdd").click(function(){ this,c.add_group});

 
 $('#tbl_users').on('click','.onRemoveUser',function(){
 
 	var values = $(this).attr("rel").split("|");
   var id = values[1];
    var data = { id : values[0] }
    if(confirm("Desea eliminar el usuario: "+values[0] +" ?")){
	  $.post(c.ctrl, { exec: "remove_user", data : data },function(r){ console.log(r); c.get_users(); });
	}
 })

 $('#tbl_users').on('click','.editUser',function(){
 	$('#users').slideUp('slow'); 
 	$('.addUser').hide();
 	id = $(this).attr('ide');
 	 
 	$('#addUser').html('');
 	$('#editUser').load('/clientes/_view/frm.user_edit.php?id='+id);
 })
 $('#tbl_users').on('click','.adduser',function(){
 	
 	
 }) 
 $('#addUser').on('click','#cancelEditUser',function(){
		 $('.addUser').show();
	 $('#users').slideDown('slow');
	 $('#addUser').html('');
	})

$('.addUser').click(function(){
	$('#users').slideUp('slow');
	$('#editUser').html(' ');
	$(this).hide()
 	$('#addUser').load('/clientes/_view/frm.user_add.php');
})

	$("a.onRemoveUser").click(function(){});
	$("a.onRemoveGroup").click(function(){ c.del_group});
	$("a.onRemovePoi").click(function(){ c.del_poi});
	$("a.onRemoveGeofence").click(function(){ c.del_geofence});
 
	$("a.onClickUpdateTemp").addClass('ok')
	$("a.onClickUpdateTemp").click(function(){ 
	 
		var temp_unit = null;
    var temp_curr = $(".onClickUpdateTemp").attr("rel");
	var exec = "update_settings_temp";
	if(temp_curr=="c"){ temp_unit = "f"; }else{ temp_unit = "c"; }
	$(".onClickUpdateTemp").attr("rel",temp_unit);
	$(".onClickUpdateTemp").removeClass("c").removeClass("f").addClass(temp_unit);
	$.post(c.ctrl, { exec: exec, value : temp_unit },function(r){ location.reload(); });
	});



	$("a.onClickFullRpt").click(function(){ c.get_fuel_rpt});

	$("a.onClickRouteExport").click(function(){ c.export_route});

	$("a.onClickSaveRouteData").click(function(){
	    if($("#txt_route_name").val()==""){ alert("Es necesario asignar un nombre para la Geo-Ruta"); return false; }
		var georoute_name = $("#txt_route_name").val();
	    var georoute_path = new Array();
		var count = 0;
		$("tr.gr_data").each(function(idx){ georoute_path[idx] = new Array($(this).children('.mrk').children().val(),$(this).children('.dis').children().val(),$(this).children('.time').children().val(),$(this).children('.latlng').children().val()); });
		 
		$.post(c.ctrl, { exec: "save_georoute", data : JSON.stringify(georoute_path), route: georoute_name },function(r){
		  alert("Geo-Ruta guardad con éxito.");
		});



	  });

 
/*
	$('.onClickSavePoi').click(function(){
		console.log('guardar');
	  if(!objTrack.poi){ alert("No existen marcadores"); return false; }
	  data = new Object();
	  data.name = $("#txt_poi").val();
	  data.lat = objTrack.poi.getPosition().lat();
	  data.lng = objTrack.poi.getPosition().lng();
	  if(data.name==""){ alert("Ingrese un Nombre Valido"); return false; }
	  $.post(c.ctrl, { exec: 'save_poi', data : data },function(r){
	    alert("Punto de Interes creado con exito.");
	    c.get_pois();
	  });
	});

*/
 


  }


 
  this.update_temp = function(){
  	console.log('Actualizando')
    var temp_unit = null;
    var temp_curr = $(".onClickUpdateTemp").attr("rel");
	var exec = "update_settings_temp";
	if(temp_curr=="c"){ temp_unit = "f"; }else{ temp_unit = "c"; }
	$(".onClickUpdateTemp").attr("rel",temp_unit);
	$(".onClickUpdateTemp").removeClass("c").removeClass("f").addClass(temp_unit);
	$.post(c.ctrl, { exec: exec, value : temp_unit },function(r){ location.reload(); });
  }

 
  this.add_user = function(e){
	var option = $(e.target).attr("rel").split("|");
	var exec = option[0];
	

	var id = option[1];
	var date = $("#date_exp").val()+" "+$("#hour").val();
	var data = { id: id, user: $("#txt_user").val() , password:  $("#txt_password").val(),date_exp : date , assets: $(".chk_asset").serializeArray() };
	if(data.user == "" || data.password==""){ 
		alert("Error!"); 
		return false; 
	}
    $.post(c.ctrl, { exec: exec, data : data },function(r){
		if(r=="0"){ alert("ERROR! El nombre de usuario se encuentra en uso, por favor utilice otro."); return false; }
		$('#users').slideUp('slow');
		alert("Usuario creado con exito.");
		$('.addUser').show();
		$('#addUser').slideUp('slow');
		c.get_users();
	});
  }


  this.get_fuel_rpt = function(){
	var s = $("#fdate_from").val() +" "+ $("#fhour_from").val();
	var e = $("#fdate_to").val() +" "+ $("#fhour_to").val();
	var ir = $("#lst_fuelrpt_imei").val().split("|");
	 
	var rpt = 'rpt_comb.php';
	if(ir=="NULL"){ alert("Seleccione una Unidad"); return false; }
	if(ir[1]==2){ rpt = 'rpt_comb.php';}
	window.open(rpt+"?sdate="+s+"&edate="+e+"&imei="+ir[0]);
  }


   this.mod_pass = function(){
	var data = { password:  $("#txt_password").val() };
	if(data.password==""){ alert("Error!"); return false; }
	if(data.password!=$("#txt_password_verify").val()){ alert("Error! campos no coinciden."); return false; }
    $.post(c.ctrl, { exec: "update_pass", data : data },function(r){ console.log(r); });
  }

  this.del_user = function(){ 
   var values = $(this).attr("rel").split("|");
   var id = values[1];
    var data = { id : values[0] }
    if(confirm("Desea eliminar el usuario: "+values[0] +" ?")){
	  $.post(c.ctrl, { exec: "remove_user", data : data },function(r){ console.log(r); c.get_users(); });
	}
  }

  this.get_users = function(){
   $.post(c.ctrl, { exec: "get_user", type : 2},
   	function(r){ 
   		$("#tbl_users > tbody").empty().append(r); }); 
   		 }

  this.add_group = function(e){
	var option = $(e.target).attr("rel").split("|");
	var exec = option[0];
	var id = option[1];
	var data = { id: id, group: $("#txt_group").val(), assets: $(".chk_asset").serializeArray() };
	if(data.group==""){ alert("Error!"); return false; }
    $.post(c.ctrl, { exec: exec, data : data },function(r){
		 
	  alert("Grupo creado con exito.");
	  c.get_groups();
	});
  }

  this.del_group = function(){
    var values = $(this).attr("rel").split("|");
    var id = values[0];
    var data = { id : values[0] }
    if(confirm("Desea eliminar el Grupo ?")){
	  $.post(c.ctrl, { exec: "remove_group", data : data },function(r){ c.get_groups();	 });
	}
  }

 this.del_poi = function(){ 
    var id = $(this).attr("rel");
    if(confirm("Desea eliminar el Punto de Interes ?")){
	  $.post(c.ctrl, { exec: "remove_poi", id : id },function(r){c.get_pois();	 });
	}
  }


  this.get_groups = function(){ $.post(c.ctrl, { exec: "get_group"},function(r){ $("#tbl_groups > tbody").empty().append(r); });  }

  this.add_geofence = function(o){
 
	var values = { lat: o.lat, lng: o.lng, name: o.name, category: o.category, preview: o.preview, radius: o.radius, type : o.type, vars: o.vars };
     
    $.post(c.ctrl, { exec: "save_geofence", data : values },function(r){
	  alert("Geocerca creada con exito!");
	  $('#geomap').html('');
	  c.hideUnidades();
	  $('#geocercas_a').offcanvas("hide"); 
	  $('.window').fadeOut('fast');
	  objTrack.remove_shape();
	  objTrack.geofence = { };
	});
  }
    this.hideUnidades = function(){
    	if($('#unidades').is(':visible')==true){ 
    		console.log('escondidas')
		$('#unidades').hide('fast') ;
		$('.derecha').css('margin','0px 0px 0px 0px') ;
		 
		//$('#mapa').removeClass('col-md-9')
		//$('#mapa').addClass('col-md-12')
		//$('#mapa').css('padding-left','15px')
	}else{  
		console.log('visibles')
		$('#unidades').show() 
		$('.derecha').css('margin','0px 0px 0px 390px') ;
		//$('#mapa').removeClass('col-md-12')
		//$('#mapa').addClass('col-md-9')
		//$('#mapa').css('padding-left','0px')
	}
    }
    this.showUnidades = function(){
    	//Editar tabla del footer
    	

    
		$('#unidades').show( ) 
		$('.derecha').css('margin','0px 0px 0px 390px') ;
		//$('#mapa').removeClass('col-md-12')
		//$('#mapa').addClass('col-md-9')
		//$('#mapa').css('padding-left','0px')
    }
  this.set_saveGeoBtn = function(){ 
		$('.onClickSaveGeo').click(function(e){ 
	    if($("#txt_geocerca").val()==""){ alert("Debe ingresar un nombre para la geocerca."); return false; }
		 geofence.name = $("#txt_geocerca").val();
		 geofence.category = $("#lst_gc_cat").val();
	     geofence.preview = $("#geofence_preview").attr("src");
	     c.add_geofence(t.geofence);
	  });
	}

  this.add_asset_geofence = function(o){
	var values = { imei: o.imei, id_geofence: o.id_geofence, enter : o.enter, exit : o.exit };
    $.post(c.ctrl, { exec: "save_asset_geofence", data : values },function(r){
	});
  }

  this.del_asset_geofence = function(o){
    var values = { imei: o.imei, id_geofence: o.id_geofence };
	  $.post(c.ctrl, { exec: "remove_asset_geofence", data : values },function(r){});
  }

  this.get_geofences = function(){ 
  	$.post(c.ctrl, 
  		{ exec: "get_geofences"},
  		function(r){ 
  			$("#tbl_geofences > tbody").empty().append(r); 

  			$('.onRemoveGeofence').click(function(){
  				 var values = $(this).attr("rel");
    var data = { id : values }
    if(confirm("Desea eliminar la geocerca?")){
	  $.post(c.ctrl, { exec: "remove_geofence", data : data },function(r){ c.get_geofences(); });
	}
  			});
  		});  
  }

    this.get_geofences_tab = function(imei_n){ 
  	$.post(c.ctrl, 
  		{ exec: "get_geofences_tab",imei:imei_n},
  		function(r){ 
  			$("#tbl_geofences_tab"+imei_n+" > tbody").empty().append(r); 
 		 
  			$('.onRemoveGeofence').click(function(){
  				 var values = $(this).attr("rel");
    var data = { id : values }
    if(confirm("Desea eliminar la geocerca?")){
	  $.post(c.ctrl, { exec: "remove_geofence", data : data },function(r){ c.get_geofences(); });
	}
  			});
  		});  
  }
 
 
  this.get_pois = function(){ $.post(c.ctrl, { exec: "get_pois" },function(r){  
 
  	$("#tbl_pois > tbody").empty().append(r.html); 
  	 $("a.onRemovePoi").click(function(){
 
	 		 var id = $(this).attr("rel");

		    if(confirm("Desea eliminar el Punto de Interes ?")){
			  $.post(c.ctrl, { exec: "remove_poi", id : id },function(r){c.get_pois();	 });
			}
	 	}
	 );
  },"json"); }

  this.get_tracks = function(){  
  	$.post(c.ctrl, { 
  		exec: "get_tracks" },function(r){  
 
  	$("#tbl_tracks > tbody").empty().append(r.html); 
  	  
	  
  },"json"); }


  	this.get_tracks_tabs = function(imei_n){  
  	 
  	$.post(c.ctrl, { 
  		exec: "get_tracks_tabs" ,imei:imei_n},function(r){  
 	 
  	$("#tbl_georutas_tab"+imei_n+" > tbody").empty().append(r.html); 
  	  
	  
  },"json"); }


  this.get_checkpoints = function(){ $.post(c.ctrl, { exec: "get_checkpoints"},function(r){ console.log(r); $("#tbl_checkpoints > tbody").empty().append(r); });  }

  this.del_geofence = function(){
   var values = $(this).attr("rel");
    var data = { id : values }
    if(confirm("Desea eliminar la geocerca?")){
	  $.post(c.ctrl, { exec: "remove_geofence", data : data },function(r){ c.get_geofences(); });
	}
  }

  this.export_route = function(){
	var s = $("#date_from").val() +" "+ $("#hour_from").val();
	var e = $("#date_to").val() +" "+ $("#hour_to").val();
	var i = $("#lst_route_imei").val();
	if(i=="NULL"){ alert("Seleccione una Unidad"); }
	window.open("_export/exp.route.php?sdate="+s+"&edate="+e+"&imei="+i);
  }



  this.set_com = function(str){
	  var data = { imei : str[0], option: str[1] }
	  $.post(c.ctrl, { exec: "cmd", data : data },function(r){
		  console.log(r);
		  alert("Comando Enviado!");
	  });
	}


  onLoad();
}