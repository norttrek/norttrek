$(document).ready(function(){ 
	$('.up').click(function(){
		$('.footer').css('height','500px');
		$('.glyphicon-triangle-bottom').show();
		$('.glyphicon-triangle-top').hide();	 
	}) 
	$('.down').click(function(){
		$('.footer').css('height','180px');
		 $('.glyphicon-triangle-top').show();
		$('.glyphicon-triangle-bottom').hide(); 
	})
var wait = 0;
	function countdown(){
  wait += 1;
  if(wait<=300){
  per = ((wait*100)/300);
  $('.progress').animate({width: per+"%"}, 800);
    setTimeout(countdown,700);
  }else{
   objTrack.get_gprs();
   wait = 0;
   setTimeout(countdown,1000);
  }
}
setTimeout(countdown,1000);


	//$(document).off('.data-api')
	$('#hideUnidades').click(function(){
		console.log('hide')
	if($('#unidades').is(':visible')==true){
		$('#unidades').hide('fast') 
		$('.derecha').css('margin','0')
		// $('#mapa').removeClass('col-md-9')
		// $('#mapa').addClass('col-md-12')
		$('#mapa').css('padding-left','15px')
	}else{
		$('#unidades').show() 
		$('.derecha').css('margin','0px 0px 0px 390px')
		// $('#mapa').removeClass('col-md-12')
		// $('#mapa').addClass('col-md-9')
		$('#mapa').css('padding-left','0px')
	}
	
});
	function hideUnidades(){

		if($('#unidades').is(':visible')==true){ 
		$('#unidades').hide('fast') 
		//$('#mapa').removeClass('col-md-9')
		//$('#mapa').addClass('col-md-12')
		$('#mapa').css('padding-left','15px')
	}else{  
		//$('#unidades').show('col-md-4') 
		//$('#mapa').removeClass('col-md-12')
		//$('#mapa').addClass('col-md-9')
		$('#mapa').css('padding-left','0px')
	}
	}
 $('.openSideBar').click(function(){
 	
 	var unidades = $(this).attr('unidades');
 	$('.dropdown ').removeClass('open');
 	var id = $(this).attr('data-target');
 	if(id == '#interes'){
 		console.log('#interes');
 		$('#interes').offcanvas({ autohide: false });
 	}
 	/*if(unidades == 0){
 		hideUnidades();
 	}*/
 	var sideBarWidth = $(this).attr('sideBarWidth');
 	$('.navmenu').css('width',sideBarWidth);
 })

$('#interesBtn').click(function(){
	console.log('interes');
	objClient.get_pois()
	 
	//$('#interes').offcanvas({ autohide: false });
	 
})

$('#tracksBtn').click(function(){
	console.log('tracksBtn');
	objClient.get_tracks() 
	 
})

$('.tracksBtnTab').click(function(){
	imei = $(this).attr('imei'); 
	objClient.get_tracks_tabs(imei) 	 
})

$('#CloseinteresBtn').click(function(){	 
	$('#interes').offcanvas("hide"); 
})

$('#geocercasBtn').click(function(){
	imei = $(this).attr('imei');
	$("#geo_on").attr('imei',imei);
	objClient.get_geofences();
	$("a.onRemoveGeofence").click(function(){
			objClient.del_geofence
	})
})

$('.geocercastab').click(function(){
 	imei = $(this).attr('imei'); 
 	alert('dos')
 	console.log(imei);
 	$('.geotable'+imei +' td').each(function() { $(this).attr('imei',imei)  });
 	console.log($('.geotable'+imei +' td'))
	objClient.get_geofences_tab(imei);
	$('#geotable'+imei);
	$("a.onRemoveGeofence").click(function(){
		alert('de')
			objClient.del_geofence
			objClient.del_geofence_history(imei)
	})
})
 
$('.alertTabs').click(function(){
 	imei = $(this).attr('imei'); 
 	console.log(imei+'eeeee'); 
 	$.ajax({
		    url: '/_functions/functions.php', // url del recurso
		    type: "post", // podría ser get, post, put o delete.
		    data: { action:'getAlarms', imei_n:imei }, // datos a pasar al servidor, en caso de necesitarlo
		    success: function (r) {
		    if(r==0){
		    	alert("no existen alertas")
		    }else{
		    	$("#eventAlerts"+imei + " tbody").empty()
		      	$("#eventAlerts"+imei + " tbody").append(r)
		      } 
		      
		    }              
		});
	  
})

$('.georutastab').click(function(){
 	imei = $(this).attr('imei'); 
 	console.log(imei);
 	$('.geotable'+imei +' td').each(function() { $(this).attr('imei',imei)  });
 	console.log($('.geotable'+imei +' td'))
	objClient.get_georutas_tab(imei);
	$('#geotable'+imei);
	$("a.onRemoveGeofence").click(function(){
			objClient.del_geofence
	})
})
 



/*$.each($('.dropdown-menu li a'),function(){
	var unidades = $(this).attr('unidades');
 	var id = $(this).attr('data-target');
 	 
 	if(unidades == 0){
 		$(id).on('hide.bs.offcanvas', function (e) {
 			console.log('esconder al cerrar')
	   		hideUnidades()
		});
 	}
})*/


	function get_direction(lat,lng,imei){
 
   var latlng = new google.maps.LatLng(lat, lng);
    geocoder = new google.maps.Geocoder();
   geocoder.geocode({'latLng': latlng}, function(results, status) {
      if (status == google.maps.GeocoderStatus.OK) {
         if (results[0]) { 
         	console.log();
         	if($('.obt'+imei).text()=='Esconder Dirección'){
         		 $('.address'+imei).hide('slow');
         		 $('.obt'+imei).text('Obtener Dirección');
         	}else{

         		$('.address'+imei).attr('value',results[0].formatted_address+" , " + "http://www.google.com/maps/place/"+lat+","+lng+"");
         		$('.bdir'+imei).addClass('dirA');
            	$('.obt'+imei).text('Esconder Dirección');
         	}
            
           
         } else {
            alert('No results found');
         }
      } else {
         alert('Geocoder failed due to: ' + status);
      }
   });
}
 $('.getDir').click(function(){
 	var latlangs 
 	var mystr  = $(this).attr('latlang');
	var myarr = mystr.split(","); 
	var myvar = myarr[0] + ":" + myarr[1];
	var imei = $(this).attr('imei');
 	lat = myarr[0];
 	lng = myarr[1];
 	  var latlng = new google.maps.LatLng(myarr[0],myarr[1]);
 	  console.log(latlng)
      geocoder = new google.maps.Geocoder();
      geocoder.geocode({'latLng': latlng}, function(results, status) {
      if (status == google.maps.GeocoderStatus.OK) {
         if (results[0]) { 
         	console.log();
         	if($('.obt'+imei).text()=='Esconder Dirección'){
         		 $('.address'+imei).hide('slow');
         		 $('.obt'+imei).text('Obtener Dirección');
         	}else{

         		$('.address'+imei).attr('value',results[0].formatted_address+" , " + "http://www.google.com/maps/place/"+lat+","+lng+"");
         		$('.bdir'+imei).addClass('dirA');
            	$('.obt'+imei).text('Esconder Dirección');
         	}
         } else {
            alert('No results found');
         }
      } else {
      	 console.log(results)
         alert('Geocoder failed due to: ' + status);
      }
   });
 })

 $('.speedAlarmActive').click(function(){
	if ($(this).is(':checked')) {
		var imei = $(this).attr('imei');
		var speed_val = $('#speed'+imei).val(); 
		$('.loader').show('slow');
		$.ajax({
		    url: '/clientes/nexmo/sms.php', // url del recurso
		    type: "post", // podría ser get, post, put o delete.
		    data: { action:'activeAlarm', imei_n:imei, speed:speed_val }, // datos a pasar al servidor, en caso de necesitarlo
		    success: function (r) {
		      $('.loader').hide();
		       	alert('Información Guardada')
		    }              
		});
	}else{
		var imei = $(this).attr('imei');
		$('.loader').show('slow');
		$.ajax({
		    url: '/clientes/nexmo/sms.php', // url del recurso
		    type: "post", // podría ser get, post, put o delete.
		    data: { action:'disableSpeedAlarm', imei_n:imei }, // datos a pasar al servidor, en caso de necesitarlo
		    success: function (r) {
		       
		       $('.loader').hide();
		       	alert('Alarma Desactivada')
		    }              
		}); 
	}
})


    $('.speedLimitActive').click(function(){
	if ($(this).is(':checked')) {
		var imei = $(this).attr('imei');
		var speed_val = $('#speedLimit'+imei).val();
 		$('.loader').show('slow');
		$.ajax({
		    url: '/clientes/nexmo/sms.php', // url del recurso
		    type: "post", // podría ser get, post, put o delete.
		    data: { action:'activeSpeedLimit', imei_n:imei, speed:speed_val }, // datos a pasar al servidor, en caso de necesitarlo
		    success: function (r) {
		    	$('.loader').hide();
		       alert("Velocidad Limite Activada");
		    }              
		});
		 
	}else{
		 
		var imei = $(this).attr('imei');
		$('.loader').show('slow');
		$.ajax({
		    url: '/clientes/nexmo/sms.php', // url del recurso
		    type: "post", // podría ser get, post, put o delete.
		    data: { action:'DisableSpeedLimit', imei_n:imei }, // datos a pasar al servidor, en caso de necesitarlo
		    success: function (r) {
		    	$('.loader').hide();
		       alert("Velocidad Limite Desactivada");
		    }              
		});
		 
	}
})	

function setReportTime(imei){
 
	var time_n = $('.setReportTime'+imei).val();
 	$('.loader').show('slow');
	$.ajax({
	    url: '/clientes/nexmo/sms.php', // url del recurso
	    type: "post", // podría ser get, post, put o delete.
	    data: { action: 'setReportTime' ,imei_n:imei , time:time_n }, // datos a pasar al servidor, en caso de necesitarlo
	    success: function (r) {
	       alert('Reporte de tiempo activado');
	       $('.loader').hide('slow');
	    }
	});
	 
}

$.each($('.saveUnidad'),function(){
	var num = $(this).attr('imei');
	$('#saveUnidad'+num).click(function(){
		 var formData = $('#formUnidad'+num).serialize();
	 		 formData = new FormData( $('#formUnidad'+num)[0]);
	 		 active = $('#active'+num).is(':checked');
	 		 formData.append('active',active);  
		 console.log(formData);
		 $('.loader').show('slow');
		$.ajax({
		    url: '/_functions/functions.php', // url del recurso
		    type: "post", // podría ser get, post, put o delete.
		    data:     formData , 
		    processData: false,
			contentType: false,
		    success: function (r) { 
		       if(r==1){
		       	$('.loader').hide();
		       	alert('Información Guardada')
		       }
		    }              
		});
		return false;
	});
});


$.each($('.saveEngine'),function(){
	var num = $(this).attr('imei');
	$('#saveEngine'+num).click(function(){
		 
		 var formData = $('#formEngine'+num).serialize();
	 		 formData = new FormData( $('#formEngine'+num)[0]);
	    $('.loader').show('slow');
		$.ajax({
		    url: '/_functions/functions.php', // url del recurso
		    type: "post", // podría ser get, post, put o delete.
		    data:     formData , 
		    processData: false,
			contentType: false,
		    success: function (r) { 
		    	 
		       if(r==1){
		       	$('.loader').hide();
		       	alert('Información Guardada')
		       }
		    }              
		});
		return false;
	});
});

$.each($('.fmDate'),function(){ 
	var num = $(this).attr('imei');
	$( "#vencimiento"+num ).datepicker({ dateFormat: 'yy-mm-dd' });
	$( "#fmDate"+num ).datepicker({ dateFormat: 'yy-mm-dd' });
	$( "#tcDate"+num ).datepicker({ dateFormat:'yy-mm-dd' });
	$( "#vaDate"+num ).datepicker({ dateFormat:'yy-mm-dd' });
	$( "#ctDate"+num ).datepicker({ dateFormat: 'yy-mm-dd' });
	$( "#neDate"+num ).datepicker({ dateFormat: 'yy-mm-dd' });
});

$.each($('.saveMecanic'),function(){
	var num = $(this).attr('imei');
	$('#saveMecanic'+num).click(function(){
		 
		 var formData = $('#formMecanic'+num).serialize();
	 		 formData = new FormData( $('#formMecanic'+num)[0]);
	 		  active = $('#tecnomecanica'+num).is(':checked');
	 		 formData.append('tecnomecanica',active);  
	 		  active = $('#ambiental'+num).is(':checked');
	 		 formData.append('ambiental',active);  
	 		  active = $('#neec'+num).is(':checked');
	 		 formData.append('neec',active);  
	 		  active = $('#fisicomecanica'+num).is(':checked');
	 		 formData.append('fisicomecanica',active);  
	 		  active = $('#tpat'+num).is(':checked');
	 		 formData.append('tpat',active);  
	 		  $('.loader').show('slow');
		$.ajax({
		    url: '/_functions/functions.php', // url del recurso
		    type: "post", // podría ser get, post, put o delete.
		    data:     formData , 
		    processData: false,
			contentType: false,
		    success: function (r) { 
		    	 
		       if(r==1){
		       	$('.loader').hide();
		       	alert('Información Guardada')
		       }
		    }              
		});
		return false; 
	});
});
$(".onClickRouteExport").click(function(){
		var s = $("#date_from").val() +" "+ $("#hour_from").val();
	var e = $("#date_to").val() +" "+ $("#hour_to").val();
	var i = $("#lst_route_imei").val();
	if(i=="NULL"){ alert("Seleccione una Unidad"); }
	window.open("_export/exp.route.php?sdate="+s+"&edate="+e+"&imei="+i);
	});

 $(".ClickCombustibleReport").click(function(){
 	  console.log('reporte de combuss')
	var s = $("#fdate_from").val() +" "+ $("#fhour_from").val();
	var e = $("#fdate_to").val() +" "+ $("#fhour_to").val();
	var ir = $("#lst_fuelrpt_imei").val().split("|");
	console.log(ir);
	var rpt = 'rpt_comb.php';
	if(ir=="NULL"){ alert("Seleccione una Unidad"); return false; }
	if(ir[1]==2){ rpt = 'rpt_comb.php';}
	window.open(rpt+"?sdate="+s+"&edate="+e+"&imei="+ir[0]);
 

 });


   $('.onClickGetRpt').click(function(){ 
   	console.log('fff')
   	get_rpt(); 
   });
  
  
 
	
	 
	function get_rpt(){ 
	 
	 if($('#lst_imei').val()=='Seleccione Unidad'){
	 	alert('Seleccione Unidad')
	 }else{
	 	$('#tbl_bottom').slideUp();
		$('#tbl_report_geo').slideDown();
		 $("#tbl_route").slideUp();
	 	$('.loader').show('slow');
	 	$.ajax({
      type: "POST",
	  url: '_json/json_geofence_rpt.php',
	  data: $("#frm_geofence_rpt").serialize(),
	  success: function(r) {  
	  	if(r == 0){
	  		alert('No existen reportes en la geocerca seleccionada');
	  	}
		$("#tbl_report_geo ").empty().append(r);
		$('.loader').hide('slow');
	  }
    });
	 }
	 }
	 $('.onClickSavePoi').click(function(){
		console.log('g')
	  if(!objTrack.poi){ alert("No existen marcadores"); return false; }
	  data = new Object();
	  data.name = $("#txt_poi").val();
	  console.log(data.name)
	  data.lat = objTrack.poi.getPosition().lat();
	  console.log(data.lat)
	  data.lng = objTrack.poi.getPosition().lng();
	  if(data.name==""){ alert("Ingrese un Nombre Valido"); return false; }
	  $.post('../_ctrl/ctrl.client.php', { exec: 'save_poi', data : data },function(r){
	    alert("Punto de Interes creado con exito.");
	    objClient.get_pois();
	  });
	}); 

function mapReload(){
	alert('recargar');
}

$('#tbl_tracks ').on('click','#deltrack',function(){
	alert('click')
	idetrackt = $(this).attr('ide');
 
	$('.loader').show('slow'); 
		$.ajax({
		    url: '/_functions/functions.php', // url del recurso
		    type: "post", // podría ser get, post, put o delete.
		    data:   {  action:'del_track',idetrack:idetrackt }, 
		  
		    success: function (r) { 
		     
		       if(r==1){
		       	$('#tbl_tracks tbody').empty()
		       	objClient.get_tracks() 
		       	$('.loader').hide();
		       	 
		       }
		    }              
		});
})

$('.tbl_georutas_tab ').on('click','#Activetrack',function(){
	idetrack_val = $(this).attr('ide');
	imei_val = $(this).attr('imei');
 	active_val = $(this).attr('active'); 
     
		$.ajax({
		    url: '/_functions/functions.php', // url del recurso
		    type: "post", // podría ser get, post, put o delete.
		    data:   {  action:'active_track',idetrack:idetrack_val,imei:imei_val,active:active_val }, 
		  
		    success: function (r) { 
		   
		     var obj = JSON.parse(r);
		     $.each(obj, function(index, value) {
    						console.log(index +"---"+value);
    						if(value == 1){
    							$(".track"+index).addClass('activeAlarm');
    							$(".track"+index).attr('active','0');
    							$(".track"+index).html('Desactivar');
    						}else if(value == 0){
    							$(".track"+index).removeClass('activeAlarm');
    							$(".track"+index).attr('active','1');
    							$(".track"+index).html('Activar');
    						}
    						
				}); 
		    }              
		}); 
})

$("a.onClickOpenWindow").on("click",function(){
    var href = "#"+$(this).attr("rel");
    $('.window').fadeOut('fast');
    $(href).fadeIn();
    console.log(href)
    switch(href){
      case "#geofence": 
      console.log('pone')
      $('#geomap').load( "_view/mod.save_geofence.php?zoom="+objTrack.geofence.zoom+"&type="+objTrack.geofence.type+"&data="+objTrack.geofence.vars);
      console.log(objTrack.geofence.zoom + 'objTrack.geofence.zoom');
      console.log(objTrack.geofence.type + 'objTrack.geofence.type');
      console.log(objTrack.geofence.vars + 'objTrack.geofence.vars');
      objClient.set_saveGeoBtn(); 
    break;
    case "#geofences": objClient.get_geofences();
    break;
    case "#users": objClient.get_users(); 
    break;
    case "#groups": objClient.get_groups();
    break;
    case "#clear_map": location.reload();
    break;
    case "#checkpoints": objClient.get_checkpoints();
    break;
    case "#pois": objClient.get_pois();
    break;

    }

    $('.dropdowngr').mouseenter(function(){ console.log("---xx"); });
    $('.isSubMenu').slideUp();


  });


$('.navbar ul li').click(function(){
	$('#trayectoria').offcanvas('hide');
})

$('.alerts').on('click','.alarmSwitch',function(){
 
	imei_val = $(this).attr('imei');
	alarm_name = $(this).attr('name');
	alarm_v = $(this).attr('active');
	if(alarm_v == 0){
		alarm_v =1;
	}else if(alarm_v ==1){
		alarm_v=0;
	} 
	$('.loader').show('slow');
		$.ajax({
		    url: '/_functions/functions.php',
		    type: "post", // podría ser get, post, put o delete.
		    data: { action:'updateAlarm', imei:imei_val, alarm:alarm_name, alarm_val:alarm_v}, // datos a pasar al servidor, en caso de necesitarlo
		    success: function (r) {
 				$(this).attr('active','ser');
		    	$('#'+alarm_name+imei_val).attr('active',alarm_v);
		      	$('.loader').hide();
		       	alert (r);
		       	//alert('Alerta Actualizada')
		    }              
		});
})


$('.tbl_geofences_tab').on('click','#geotab',function(){
 
	 var id_geo_val = $(this).attr('id_geo');
	 var imei_val = $(this).attr('imei'); 
	 var pos_val = $(this).val(); 

	 if($(this).is(':checked')==true){0
	 	active_val = 1;
	 }else{
	 	active_val = 0;
	 } 
	$('.loader').show('slow');
		$.ajax({
		    url: '/_functions/functions.php',
		    type: "post", // podría ser get, post, put o delete.
		    data: { action:'updateGeoAlarm', id_geo:id_geo_val, imei:imei_val, active:active_val,pos:pos_val}, // datos a pasar al servidor, en caso de necesitarlo
		    success: function (r) {
 			 
		      	$('.loader').hide(); 
		    }              
		});
})

$('.onClickSaveEmail').click(function(){
	email_val = $('#txt_mail').val();
	ide = $('#id_user_email').val(); 
	$.ajax({
		    url: '/_functions/functions.php',
		    type: "post", // podría ser get, post, put o delete.
		    data: { action:'updateEmail', user_id:ide, email:email_val}, // datos a pasar al servidor, en caso de necesitarlo
		    success: function (r) {
 			 alert(r);
		      	$('.loader').hide(); 
		    }              
		}); 
})


})

