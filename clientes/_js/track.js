 var objTrack = null;
var objClient = null;

$(window).load(function(){
  objTrack = new Track('map-canvas');
  objClient = new Client(); 
});

var Client = function(){
  var c = this;
  this.ctrl = '../_ctrl/ctrl.client.php';

  function onLoad(){ c.addEventListeners(); }

  this.addEventListeners = function(){ 
	$("a.onClickUserAdd").live("click",this,c.add_user);
	$("a.onClickChangePass").live("click",c.mod_pass);


	$("a.onClickGroupAdd").live("click",this,c.add_group);


	$("a.onRemoveUser").live("click",c.del_user);
	$("a.onRemoveGroup").live("click",c.del_group);
	$("a.onRemovePoi").live("click",c.del_poi);
	$("a.onRemoveGeofence").live("click",c.del_geofence);
	$("a.onClickUpdateTemp").live("click",c.update_temp);



	$("a.onClickFullRpt").live("click",c.get_fuel_rpt);

	$("a.onClickRouteExport").live("click",c.export_route);

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



	$('.onClickSavePoi').click(function(){
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






  }

  this.update_temp = function(){
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
		alert("Usuario creado con exito.");
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

  this.get_users = function(){ $.post(c.ctrl, { exec: "get_user", type : 2},function(r){ $("#tbl_users > tbody").empty().append(r); });  }

  this.add_group = function(e){
	var option = $(e.target).attr("rel").split("|");
	var exec = option[0];
	var id = option[1];
	var data = { id: id, group: $("#txt_group").val(), assets: $(".chk_asset").serializeArray() };
	if(data.group==""){ alert("Error!"); return false; }
    $.post(c.ctrl, { exec: exec, data : data },function(r){
		console.log(r);
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
	  $('.window').fadeOut('fast');
	  objTrack.remove_shape();
	  objTrack.geofence = { };
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

  this.get_geofences = function(){ $.post(c.ctrl, { exec: "get_geofences"},function(r){ $("#tbl_geofences > tbody").empty().append(r); });  }

  this.get_pois = function(){ 
  	console.log('get pois') 
  	$.post("../_ctrl/ctrl.client.php", { exec: "get_pois" },function(r){  
  		console.log('ddd');$("#tbl_pois > tbody").empty().append(r.html); }
  		,"json"); 
  }


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

var Track = function(map_id){
    var t = this;
	var infobox;

	this.div = map_id;
	this.interval = 180000;
	this.map;
	this.units = [];
	this.markers = [];
	this.markers_route = [];
	this.marker_geroute;
	this.markers_georoute = [];
	this.path_route = [];
	this.route_distance = 0;
	this.geofence = new Object();
	this.georoute_tool = false;
	this.georoute_points;

	this.traffic;

	this.ruler_marker_a;
	this.ruler_marker_b;
	this.ruler_poly;

	this.poi;
	this.cpoi;
	this.pois = new Array();

	this.polyline;
	this.path;

	this.directionsDisplay;
	this.directionsService = new google.maps.DirectionsService();

	this.showcircle = new google.maps.Circle();

	this.animate_int;
	this.animate_idx =0;
	this.animate_pause = false;

	/* Drawing Tools*/
	var drawingManager;
	var selectedShape;
	var gcircle = new Object();
	var gpolygon = new Object();



	function onLoad(){ t.addEventListeners();


	}

	this.addEventListeners = function(){
	  google.maps.event.addDomListener(window, 'load', t.init());

	  $(".onClickGetDirections").click(function(){ t.draw_directions(); });

	  $(".onChangeStartGroute").change(function(){
	    if($(this).is(':checked')){ t.georoute_tool = true; }else{ t.georoute_tool = false; }
	  });

	  $('#chk_show_poi').change(function(){
	     if($(this).is(':checked')){ t.render_pois();  }else{ t.clear_pois();  }
	  });

	  $('a.onClickTraffic').click(function(e){ t.overlay_traffic($(this).attr("rel")); });
	  $('a.onClickRuler').click(function(e){ t.ruler($(this).attr("rel")); });






	}



	this.init = function(){
	  var mapOptions = { zoom: 7, center: new google.maps.LatLng(25.659234, -100.302106), mapTypeId: google.maps.MapTypeId.ROADMAP,
	  	 panControlOptions: { position: google.maps.ControlPosition.TOP_LEFT},
	 	 zoomControlOptions: { style: google.maps.ZoomControlStyle.LARGE, position: google.maps.ControlPosition.TOP_LEFT },
	  	 mapTypeControlOptions: { position: google.maps.ControlPosition.TOP_LEFT }
	  };
      t.map = new google.maps.Map(document.getElementById(t.div),mapOptions);
	  infobox = new InfoBox({ map: t.map, disableAutoPan: true, maxWidth: 330, pixelOffset: new google.maps.Size(-140, -40), zIndex: null,alignBottom: true, boxStyle: { width: "330px" },
        closeBoxURL: "_img/btn_close.png",closeBoxMargin: "-10px -30px -25px 30px",infoBoxClearance: new google.maps.Size(1, 1)
      });

	  t.traffic = new google.maps.TrafficLayer();
	  t.traffic.setMap(null);
	  //t.traffic.setMap(t.map);

	  t.directionsDisplay = new google.maps.DirectionsRenderer({draggable: true});
	  t.directionsDisplay.setMap(t.map);

	  t.get_gprs();
	  console.log( t.get_gprs())
	  t.init_draw();

	  geocoder = new google.maps.Geocoder();
 	  var point_a = new google.maps.places.Autocomplete($("#txt_point_a")[0],{ types: ['(regions)'],componentRestrictions: {country: "MX"} });
	  var point_b = new google.maps.places.Autocomplete($("#txt_point_b")[0],{ types: ['(regions)'],componentRestrictions: {country: "MX"} });

	  google.maps.event.addListener(point_a, 'place_changed', function() {
	    var place = point_a.getPlace();
		$("#point_a").val(place.geometry.location.lat()+","+place.geometry.location.lng());
	    //geocodePosition(place.geometry.location);
	  });

	  google.maps.event.addListener(point_b, 'place_changed', function() {
	    var place = point_b.getPlace();
		$("#point_b").val(place.geometry.location.lat()+","+place.geometry.location.lng());
	    //geocodePosition(place.geometry.location);
	  });

	  console.log('aqui sigue')
	  google.maps.event.addListener(t.map, 'dblclick', function(event){
	  	console.log('escucha')
        if($("#pois").is(":visible")){
        	console.log('esta visible')
	      if(!t.poi){ t.poi = new google.maps.Marker({ position: event.latLng,map: t.map, draggable: true }); }else { t.poi.setPosition(event.latLng);  }
		  google.maps.event.addListener(t.poi, "dblclick", function() { t.poi.setMap(null); t.poi = null; });
		}

	  });



	  console.log('ir por los pois')
	t.get_pois();

	   $('.onClickRemove').live('click',function(e){ t.remove_shape(); });
	  $('.onClickDrawCircle').live('click',function(e){
	    if(gcircle.center==null){ drawingManager.setDrawingMode(google.maps.drawing.OverlayType.CIRCLE); }else{  alert("Solo puedes dibujar una geozona ala vez."); }
	  });

	  $('.onClickDrawPolygon').live('click',function(e){
	    if(gcircle.center==null){ drawingManager.setDrawingMode(google.maps.drawing.OverlayType.POLYGON); }else{ alert("Solo puedes dibujar una geozona ala vez."); }
	  });

	  $('.onLatLngOver').live('click',function(e){
	    point = $(e.target).attr("rel").split(",");
	    t.go_to(point[0],point[1]);
	  })

	  $('.onMarkerHover').live('mouseover',function(e){ t.route_marker_mover(e); });
	  $('.onMarkerHover').live('mouseout',function(e){ t.route_marker_mout(e); });


	  $('.onClickSaveGeo').live('click',function(e){
	    if($("#txt_geocerca").val()==""){ alert("Debe ingresar un nombre para la geocerca."); return false; }
		 t.geofence.name = $("#txt_geocerca").val();
		 t.geofence.category = $("#lst_gc_cat").val();
	     t.geofence.preview = $("#geofence_preview").attr("src");
	     objClient.add_geofence(t.geofence);
	  });


	  google.maps.event.addListener(t.map, 'click', function(event){
	    if(!t.georoute_tool){ return false; }
    	t.marker_georoute = new google.maps.Marker({ map: t.map, position:  event.latLng, id: t.markers_georoute.length, icon : 'http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld='+((t.markers_georoute.length))+'|ed003d|000000' });

		google.maps.event.addListener(t.marker_georoute, "dblclick", function(){ $("#tr_gr_"+this.id).empty(); this.setMap(null); });

		t.markers_georoute.push(t.marker_georoute);

		idx = t.markers_georoute.length-1;
		curr = t.markers_georoute[idx];
		curr.lat = t.markers_georoute[idx].position.lat();
		curr.lng = t.markers_georoute[idx].position.lng();
		curr.lat_lng = t.markers_georoute[idx].position.lat()+ ","+t.markers_georoute[idx].position.lng();
		last = 0;
		if(idx>0){
		last = t.markers_georoute[idx-1];
		last.lat = t.markers_georoute[idx-1].position.lat();
		last.lng = t.markers_georoute[idx-1].position.lng();
		var dist = (distanceFrom(last,curr)).toFixed(2);
		if(dist=="" || dist=='undefined'){ dist = '0'; }
		time = '';
		}else{
		  dist = 0;
		  time = 0;
		}

		$('#tbl_georoute > tbody:last').append('<tr id="tr_gr_'+idx+'" class="gr_data"><td class="mrk"><input type="text" id="txt_m[]" name="txt_m[]" style="width:70px" placeholder="Nombre de Marcador..." /></td><td class="dis"><input type="text" id="txt_d[]" name="txt_d[]" style="width:50px" value="'+dist+'" /></td><td class="time"><input type="text" id="txt_t[]" name="txt_t[]" style="width:30px" value="'+time+'"/></td><td class="latlng" style=" display:none;"><input type="hidden" id="txt_ll[]" name="txt_ll[]" value="'+curr.lat_lng+'" /></td></tr>');
	 });



	 }


	this.overlay_traffic = function(mode){
	  if(mode=="on"){
	    t.traffic.setMap(t.map);
		$('a.traffic').children().removeClass('fa-square-o').addClass('fa-check-square-o');
		$('a.traffic').attr("rel","off");
	  }else{
		t.traffic.setMap(null);
		$('a.traffic').children().addClass('fa-square-o').removeClass('fa-check-square-o');
		$('a.traffic').attr("rel","on");
	  }
	}


	this.get_gprs = function(){
	  t.clear_markers();
	  $("#tbl_bottom > tbody tr").empty();
	  $("#tbl_assets > tbody tr").empty();
	  $.post('_json/json.assets.php', { id: $(this).attr("id") },
	    function(r){
	    	console.log('respuesta'+r)
		  for(var i=0;i<r.gprs.length;i++){
		   if(r.gprs[i].speed>0){ micon = "marker.png"; }
		   if(r.gprs[i].speed==0 && r.gprs[i].ignition==1){ micon = "idle.png"; }
		    if(r.gprs[i].ignition==0){ micon = "off.png"; }
		    if(r.gprs[i].alert==null){
		    	r.gprs[i].alert ='';
		    }
		   var marker_aux = new MarkerWithLabel({
		   		position: new google.maps.LatLng(r.gprs[i].lat,r.gprs[i].lng),
				map: t.map,
				labelContent: '<div style="background:white; border-radius:50%; margin:0 auto; width:30px; height:30px;"><i style="font-size:30px; color:'+r.gprs[i].icon_color+'" class="fa fa-arrow-circle-o-up   fa-rotate-'+r.gprs[i].grados+'"></i></div><div class="nameMap">'+r.gprs[i].name+"</div>"+'<div class="nameMap">'+r.gprs[i].alert+"</div>",
				labelClass: "label",
				labelStyle: {opacity: 0.90},
				labelAnchor: new google.maps.Point(28, 0),
				id : r.gprs[i].imei,
				icon: "_iconss/"+r.gprs[i].icon
		   });
		   t.markers.push(marker_aux);
		    //t.markers.push(new google.maps.Marker({ position: new google.maps.LatLng(r.gprs[i].lat,r.gprs[i].lng),map: t.map, id : r.gprs[i].imei, icon: "_img/marker.png" }));
			google.maps.event.addListener(t.markers[i], "click", function(){
			  var marker_aux = this;
			  var coord = marker_aux.getPosition();
			  $.post('_json/json.asset.php', { id: marker_aux.id },
			  function(data){
			    infobox.close();
			    infobox.setContent(data);
			    infobox.open(t.map,marker_aux) ;
			  });
			});
			//google.maps.event.addListener(t.markers[i], "dblclick", function(){ infobox.close(); aux_id = this.id; $('#modal').attr("href","mod.panel_info.php?id="+aux_id).click();  });
			//google.maps.event.addListener(t.markers[i], "mouseout", function(){infobox.close();  });
		  }
		  $("#tbl_bottom > tbody:last ").append(r.html_bottom);
		   
		  $("#assets").empty().append(r.html_left);
		   $.each($('.gas1fill'),function(){ 
  var per = $(this).attr('per'); 
  if(per < 25){
    $(this).css('background-color','rgb(216, 40, 48)'); 
    
  }else if(per <= 50 && per >=25){
    $(this).css('background-color','rgb(206, 194, 51)');
    $(this).css('background','-webkit-linear-gradient(left,  rgb(216, 40, 48) 37px, rgb(206, 194, 51) 50% )');
     background: ;
  }else if( per >=50){
    $(this).css('background-color','rgb(57, 182, 57)');
     $(this).css('background','-webkit-linear-gradient(left,  rgb(216, 40, 48) 37px, rgb(206, 194, 51) 39px, rgb(57, 182, 57) )');
  }
});
/*
$.each($('.gas2fill'),function(){ 
  var per = $(this).attr('per'); 
  if(per < 30){
    $(this).css('background-color','rgb(216, 40, 48)'); 
    
  }else if(per <= 60 && per >=30){
    $(this).css('background-color','rgb(206, 194, 51)');
  }else if( per >=60){
    $(this).css('background-color','rgb(41, 101, 234)');
  }
});

$.each($('.gas3fill'),function(){ 
  var per = $(this).attr('per');
  if(per < 30){
    $(this).css('background-color','rgb(216, 40, 48)'); 
 
  }else if(per <= 60 && per >=30){
    $(this).css('background-color','rgb(206, 194, 51)');
  }else if( per >=60){
    $(this).css('background-color','rgb(41, 101, 234)');
  }
});*/
$.each($('.fmDate'),function(){ 
	var num = $(this).attr('imei');
	$( "#vencimiento"+num ).datepicker({ dateFormat: 'yy-mm-dd' });
	$( "#fmDate"+num ).datepicker({ dateFormat: 'yy-mm-dd' });
	$( "#tcDate"+num ).datepicker({ dateFormat:'yy-mm-dd' });
	$( "#vaDate"+num ).datepicker({ dateFormat:'yy-mm-dd' });
	$( "#ctDate"+num ).datepicker({ dateFormat: 'yy-mm-dd' });
	$( "#neDate"+num ).datepicker({ dateFormat: 'yy-mm-dd' });
	/*$('.tabstoDo').tabulous({
    	effect: 'scale'
    });	*/ 
});

$('.blockengine').on('click', function () {
 		function_to_do = $(this).attr('function');
        imei = $(this).attr('imei');
        name = $(this).attr('name');
        if(function_to_do == 'lockEngine'){
        	$.confirm({
        		title: 'Bloqueo de Motor',
						content: '¿Deseas bloquear el motor de la unidad ' + name + '?',
						confirmButtonClass: ' btn-active',
    					cancelButtonClass: ' btn-active',
    					confirmButton: 'Bloquear',
    					cancelButton: 'Cancelar',
						onAction: function(action){ 
                                        if(action == 'confirm'){
                                        	window[function_to_do](imei);
                                        }else if(action == 'cancel'){
                                         
                                        }
                                    }
                                });
        				}else if(function_to_do =='unlockEngine'){
        					window[function_to_do](imei);
        				}
});

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
		console.log(num+'aqui va')
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



$('.alertsTab a').unbind('click'); 
		 $("#tbl_bottom").tablesorter();
		 $('a.onClickOpenOverlay').on("dblclick",function(){
		   var id = $(this).attr("rel");
   		   $('#modal').attr("href","mod.panel_info.php?id="+id).click();
  		  });

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

 

		  $("#units tbody").sortable({
			update: function( event, ui ){
			  var order = new Array();
			  $('#units > tbody tr').each(function(idx){ if($(this).attr("id")!=null){ order.push($(this).attr("id")); } });
			  $.post("../_ctrl/ctrl.client.php", { exec: "save_units_order", data : JSON.stringify(order) },function(r){ console.log(r); });
			}
		  });


	    },"json");

	}


	this.clear_markers = function(){
	  for(var i=0;i<t.markers.length;i++){ t.markers[i].setMap(null); }
	  t.markers = [];
	}

	this.clear_markers_route = function(){
	  for(var i=0;i<t.markers_route.length;i++){ t.markers_route[i].setMap(null); }
	  t.markers_route = [];
	}



	this.render_info = function(id){
    $('#modal').attr("href","mod.panel_info.php?id="+id).click();
	  $("#modal").click();
	  //$( "#overlay" ).show().animate({ right: 0}, 1200);
	}

	this.go_to = function(lat,lon){
      t.map.setZoom(15);
      t.map.panTo(new google.maps.LatLng(lat, lon));
	}

	this.draw_directions = function(){
	  var start = $("#point_a").val();
      var end = $("#point_b").val();
      var request = { origin:start, destination:end,travelMode: google.maps.TravelMode.DRIVING };
      t.directionsService.route(request, function(response, status) {
        if(status == google.maps.DirectionsStatus.OK){ t.directionsDisplay.setDirections(response);}
      });
	}


	this.get_route = function(i,s,e){
	  var path = [];
	  t.clear_markers();
	  t.clear_markers_route();
	  $("#tbl_route > tbody").empty();
	  $.post('_json/json.route.php', { imei: i, sdate : s, edate: e },
	    function(r){
		   t.georoute_points = r;
		   $("#tbl_route > tbody").empty();
		  for(var i=0;i<r.length;i++){
		    color = '';
			if(r[i].speed>0){ color = '0097ee'; }
		    if(r[i].speed==0 && r[i].ignition==1){ color = "ff5a00"; }
		    if(r[i].ignition==0){ color = "bebebe"; }

		    t.markers_route.push(new google.maps.Marker({ position: new google.maps.LatLng(r[i].lat,r[i].lng),map: t.map, id : r.length-i, icon : 'http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld='+((r.length-i))+'|'+color+'|000000' }));
			$("#tbl_route > tbody").append(r[i].tbl_route);

			google.maps.event.addListener(t.markers_route[i], "mouseover", function(){
			  var marker_aux = this;
			  //marker_aux.setZIndex(google.maps.Marker.MAX_ZINDEX + 1);
			  //marker_aux.setIcon('http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld='+marker_aux.id+'|ff7e00|000000');
			  $("#tr_route_"+marker_aux.id).addClass('active');
			});

			google.maps.event.addListener(t.markers_route[i], "mouseout", function(){
			  var marker_aux = this;
			  //marker_aux.setZIndex(0)
			  //marker_aux.setIcon('http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld='+marker_aux.id+'|009cff|000000');
			  $("#tr_route_"+marker_aux.id).removeClass('active');
			});



		  }
		  $('#tbl_route >').each(function(){
    	  	var list = $(this).children('tr');
    	  	$(this).html(list.get().reverse())
		  });
		  t.draw_route(1);
	    },"json");
	}







 this.animate = function(r){
  if(!t.animate_pause){
    $('#route .datagrid').scrollTop(0);
    temp = t.markers_route.reverse();
	for(var i=t.animate_idx;i<t.markers_route.length;i++){ t.markers_route[i].setVisible(false); }
 }

    t.animate_int = setInterval(function(){
	  temp[t.animate_idx].setVisible(true);
	  t.animate_idx++;
	  $("#tbl_route tr").removeClass('active');
	  $("#tr_route_"+(temp.length-t.animate_idx)).addClass('active');
	  $('#route .datagrid').scrollTop((t.animate_idx*25));
    }, 500);
   return false;
 }

 this.animate_next = function(){
	  if(t.animate_idx>=temp.length){ return false; }
      t.markers_route[t.animate_idx].setVisible(true);
	  t.animate_idx++;
	  $("#tbl_route tr").removeClass('active');
	  $("#tr_route_"+(temp.length-t.animate_idx)).addClass('active');
	  $('#route .datagrid').scrollTop((t.animate_idx*25));
 }

  this.animate_back = function(){
      if(t.animate_idx<=0){ return false; }
	  t.animate_idx--;
	  t.markers_route[t.animate_idx].setVisible(false);
	  $("#tbl_route tr").removeClass('active');
	  $("#tr_route_"+(temp.length-t.animate_idx)).addClass('active');
	  $('#route .datagrid').scrollTop((t.animate_idx*25));
 }




 this.animate_stop = function(){ clearInterval(t.animate_int); }


  this.draw_route = function(z){
	  t.path = [];
	  var latlngbounds = new google.maps.LatLngBounds();

	  for(var i=0;i< t.markers_route.length;i++){
		t.path[i] = t.markers_route[i].getPosition();
		latlngbounds.extend(t.path[i]);
		//t.route_distance = distanceFrom(last,curr);
	  }

	   var iconsetngs = { path: google.maps.SymbolPath.BACKWARD_CLOSED_ARROW, strokeColor:'#000000', fillColor:'#000000' };
	   if(t.polyline===undefined){
	     t.polyline = new google.maps.Polyline({ map: t.map,path: t.path,strokeColor:'#0087b1', strokeOpacity: 0.7,strokeWeight: 2, icons: [{ icon: iconsetngs, repeat:'500px',offset: '100%'}] });
	   }else{
	     t.polyline.setPath([]);
		 t.polyline.setPath(t.path);
	   }
	   if(z){
	     //t.polyline = new google.maps.Polyline({ map: t.map,path: t.path,strokeColor:'#0087b1', strokeOpacity: 0.7,strokeWeight: 2, icons: [{ icon: iconsetngs, repeat:'500px',offset: '100%'}] });
	     t.map.setCenter(latlngbounds.getCenter());
	     t.map.fitBounds(latlngbounds);
	   }
  }


  this.init_draw = function(){
    drawingManager = new google.maps.drawing.DrawingManager({
      drawingMode: google.maps.drawing.OverlayType.MARKER,
      drawingControl: true,
      drawingControlOptions: { position: google.maps.ControlPosition.TOP_CENTER, drawingModes: [ google.maps.drawing.OverlayType.CIRCLE, google.maps.drawing.OverlayType.POLYGON,]},
      markerOptions: {  },
	  polygonOptions: { fillColor: '#0082a9', strokeColor: '#0082a9', fillOpacity: .6, strokeWeight: 2, clickable: true, editable: true, zIndex: 1},
      circleOptions: { fillColor: '#0082a9', strokeColor: '#0082a9', fillOpacity: .6, strokeWeight: 2, clickable: true, editable: true, zIndex: 1 }
    });
    drawingManager.setMap(t.map);
	drawingManager.setOptions({ drawingControl: false });
	drawingManager.setDrawingMode(null);
    t.map.setOptions({draggableCursor:''});











	google.maps.event.addListener(t.map, 'click', t.clear_selection);

	google.maps.event.addListener(drawingManager, 'circlecomplete', function(geocircle){
	  t.geofence_handler(geocircle,"c");
	  google.maps.event.addListener(geocircle, 'radius_changed', function(e){ t.geofence_handler(geocircle); });
	  google.maps.event.addListener(geocircle, 'center_changed', function(e){ t.geofence_handler(geocircle); });
	});


	google.maps.event.addListener(drawingManager, 'overlaycomplete', function(e) {
	  if(e.type==google.maps.drawing.OverlayType.POLYGON){
	    var vertices = e.overlay.getPath(); // MVCArray
	    var pointsArray = []; //list of polyline points
		var buff = '';
	    for(var i =0; i < vertices.getLength(); i++){
	      var xy = vertices.getAt(i); //LatLang for a polyline
	      var item = { "lat" : xy.lat(), "lng":xy.lng()};
		  buff += xy.lat()+','+xy.lng()+':';
	      pointsArray.push(item);
	    }
		  var polygon = {"points" : pointsArray};
		  t.geofence_handler(buff,"p");
		}

            if (e.type != google.maps.drawing.OverlayType.MARKER) {
            // Switch back to non-drawing mode after drawing a shape.
            drawingManager.setDrawingMode(null);

            var newShape = e.overlay;
            newShape.type = e.type;
            google.maps.event.addListener(newShape, 'click', function() {
              t.set_selection(newShape);
            });
            t.set_selection(newShape);
          }
        });



  }

  this.geofence_handler = function(circle,opt) {
	if(opt=="c"){
      if(circle.radius>30000){ circle.setRadius(30000); }
      if(circle.radius<=25){ circle.setRadius(26); }
	  t.geofence = {};
	  t.geofence.type = "circle";
	  t.geofence.center = circle.getCenter();
	  t.geofence.lat = circle.getCenter().lat();
	  t.geofence.lng = circle.getCenter().lng();
	  t.geofence.radius = circle.getRadius();
	  t.geofence.vars = t.geofence.lat+"|"+t.geofence.lng+"|"+t.geofence.radius+"|"+t.map.getZoom();
	}else{
		console.log('get vars');
	  t.geofence = {};
	  t.geofence.type = "poly";
	  t.geofence.path = circle;
	  t.geofence.center = null;
	  t.geofence.zoom = t.map.getZoom();
	  t.geofence.vars = circle;
	  console.log(t.geofence.zoom);
	}
  }

  this.clear_selection = function() {
    if(selectedShape){
      selectedShape.setEditable(false);
      selectedShape = null;
    }
  }

  this.remove_shape = function(){
    gcircle = null;
    gcircle = new Object();
    if(selectedShape){ selectedShape.setMap(null); }
  }


   this.route_marker_mover = function(e){
	 var idx = parseInt($(e.currentTarget).attr("id").replace("tr_route_",""));
	 var curr = parseInt(t.markers_route.length)-idx;
	 t.markers_route[idx].setIcon('http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld='+curr+'|ff7e00|000000');
	 t.markers_route[idx].setZIndex(google.maps.Marker.MAX_ZINDEX + 1);
   }

   this.route_marker_mout = function(e){
	 var idx = parseInt($(e.currentTarget).attr("id").replace("tr_route_",""));
	 var curr = parseInt(t.markers_route.length)-idx;
	 t.markers_route[idx].setIcon('http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld='+curr+'|009cff|000000');
	 t.markers_route[idx].setZIndex(0);
   }

   this.show_circle = function(o){
     t.showcircle.setOptions({ center: new google.maps.LatLng(o.lat,o.lng), map: t.map,fillColor: '#0082a9', fillOpacity: 0.5,strokeColor: '#0082a9',strokeOpacity: 1.0, strokeWeight: 2 });
     t.showcircle.setRadius(o.radius);
     t.map.fitBounds(t.showcircle.getBounds());
   }

   this.remove_circle = function(o){
	  t.showcircle.setMap(null);

   }

   this.ruler = function(mode){
     if(mode=="off"){
       t.ruler_marker_a.setMap(null);
       t.ruler_marker_b.setMap(null);
       t.ruler_poly.setMap(null);
	   $('a.ruler').children().addClass('fa-square-o').removeClass('fa-check-square-o');
	   $('a.ruler').attr("rel","on");
	  return false;
	 }else{
	  $('a.ruler').children().removeClass('fa-square-o').addClass('fa-check-square-o');
	  $('a.ruler').attr("rel","off");
       if(t.ruler_marker_a!=null){
         t.ruler_marker_a.setMap(null);
	     t.ruler_marker_b.setMap(null);
	     t.ruler_poly.setMap(null);
        }
	    t.ruler_marker_a = new MarkerWithLabel({ position: t.map.getCenter(), map: t.map,  labelContent: "0 km",  labelClass: "label_ruler",  labelStyle: {opacity: 1}, labelAnchor: new google.maps.Point(28, 0),draggable: true });
	    t.ruler_marker_b = new MarkerWithLabel({ position: t.map.getCenter(), map: t.map,  labelContent: "0 km",  labelClass: "label_ruler",  labelStyle: {opacity: 1}, labelAnchor: new google.maps.Point(28, 0),draggable: true });
		t.ruler_poly = new google.maps.Polyline({ path: [t.ruler_marker_a.position, t.ruler_marker_b.position], strokeColor: "#00abe0", strokeOpacity: .8, strokeWeight: 7 });
		t.ruler_poly.setMap(t.map);
		google.maps.event.addListener(t.ruler_marker_a, 'drag', function() {
		  t.ruler_poly.setPath([t.ruler_marker_a.getPosition(), t.ruler_marker_b.getPosition()]);
		  t.ruler_marker_a.labelContent = distance( t.ruler_marker_a.getPosition().lat(), t.ruler_marker_a.getPosition().lng(), t.ruler_marker_b.getPosition().lat(), t.ruler_marker_b.getPosition().lng());
		  t.ruler_marker_b.labelContent = t.ruler_marker_a.labelContent;
		  t.ruler_marker_a.label.setContent();
		  t.ruler_marker_b.label.setContent();
		});

		google.maps.event.addListener(t.ruler_marker_b, 'drag', function() {
		  t.ruler_poly.setPath([t.ruler_marker_a.getPosition(), t.ruler_marker_b.getPosition()]);
		  t.ruler_marker_a.labelContent = distance( t.ruler_marker_a.getPosition().lat(), t.ruler_marker_a.getPosition().lng(), t.ruler_marker_b.getPosition().lat(), t.ruler_marker_b.getPosition().lng());
		  t.ruler_marker_b.labelContent = t.ruler_marker_a.labelContent;
		  t.ruler_marker_b.label.setContent();
		  t.ruler_marker_a.label.setContent();
		});
	 }
   }


	this.set_selection = function(shape){
	    t.clear_selection();
        selectedShape = shape;
        shape.setEditable(true);
        //selectColor(shape.get('fillColor') || shape.get('strokeColor'));
	}

	this.get_pois = function(){
	console.log('get poist')  
		$.post("../_ctrl/ctrl.client.php", { exec: "get_pois" },function(r){
			console.log(r)
		 t.cpoi = r.poi; 
		},"json"); }

	this.clear_pois = function(){
	  for(var i=0;i<t.pois.length;i++){ t.pois[i].setMap(null); }
	  t.pois = [];
	}

	this.render_pois = function(){
	  t.clear_pois();
	  for(var i=0;i<t.cpoi.length;i++){
		   var poi_aux = new MarkerWithLabel({
		   		position: new google.maps.LatLng(t.cpoi[i].lat,t.cpoi[i].lng),
				map: t.map,
				labelContent: t.cpoi[i].poi,
				labelClass: "label",
				labelStyle: {opacity: 0.90},
				labelAnchor: new google.maps.Point(28, 0),
				id : t.cpoi[i].id,
				icon: "_img/poi.png"
		   });
		   t.pois.push(poi_aux);
	}
	}




  onLoad();
}



if (typeof(Number.prototype.toRad) === "undefined") {
  Number.prototype.toRad = function() { return this * Math.PI / 180; f}
}

function distanceFrom(last,curr) {
   var lat1 = last.lat;
   var radianLat1 = lat1 * ( Math.PI  / 180 );
   var lng1 = last.lng;
   var radianLng1 = lng1 * ( Math.PI  / 180 );
   var lat2 = curr.lat;
   var radianLat2 = lat2 * ( Math.PI  / 180 );
   var lng2 = curr.lng;
   var radianLng2 = lng2 * ( Math.PI  / 180 );
   var earth_radius = 6378.1; // (km = 6378.1) OR (miles = 3959) - radius of the earth
   var diffLat =  ( radianLat1 - radianLat2 );
   var diffLng =  ( radianLng1 - radianLng2 );
   var sinLat = Math.sin( diffLat / 2  );
   var sinLng = Math.sin( diffLng / 2  );
   var a = Math.pow(sinLat, 2.0) + Math.cos(radianLat1) * Math.cos(radianLat2) * Math.pow(sinLng, 2.0);
   var distance = earth_radius * 2 * Math.asin(Math.min(1, Math.sqrt(a)));
   return distance;
}

function distance(lat1,lon1,lat2,lon2) {
	var R = 6371; // km (change this constant to get miles)
	var dLat = (lat2-lat1) * Math.PI / 180;
	var dLon = (lon2-lon1) * Math.PI / 180;
	var a = Math.sin(dLat/2) * Math.sin(dLat/2) +
		Math.cos(lat1 * Math.PI / 180 ) * Math.cos(lat2 * Math.PI / 180 ) *
		Math.sin(dLon/2) * Math.sin(dLon/2);
	var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
	var d = R * c;
	if (d>1) return Math.round(d)+" km";
	else if (d<=1) return Math.round(d*1000)+" m";
	return d;
}

function ClickOpenUnitDetails(imei){
	createTabs(imei);
	if($('.info'+imei).is(":visible")){
 
		$(".moreInfo").hide('slow');
		$('i.'+imei).removeClass('fa-minus');
		$('i.'+imei).addClass('fa-plus');
	}else{
		$('i').removeClass('fa-minus');
		$('.bearing i').addClass('fa-plus')
		$(".moreInfo").hide('slow');
		$('div .'+imei).show('slow');
		$('i.'+imei).removeClass('fa-plus');
		$('i.'+imei).addClass('fa-minus');
	}
}

function setTabs(imei,tab){
 
	$('.tabli a').removeClass('activetabli');
	$('.tabli'+imei+tab + " a").addClass('activetabli');
	 $('#tabs-1'+imei).slideUp('slow');
	 $('#tabs-2'+imei).slideUp('slow');
	 $('#tabs-3'+imei).slideUp('slow');
	 $('#tabs-4'+imei).slideUp('slow');
	 $('#tabs-5'+imei).slideUp('slow');
	 if($('#tabs-'+tab+imei).is(':visible')){
	 	$('#tabs-'+tab+imei).slideUp('slow');
	 	$('#tabs_container').slideUp('slow');
	 }else{
	 	$('#tabs-'+tab+imei).slideDown('slow');
	 }
	 
	 
	if($('.container'+imei).is(':visible')){
 
	}else{
		$('.tabs_container ').css('display','none');
		height = $('#tabs-1').height();
	 
	 
		 
 		$('.container'+imei).slideDown('slow');
	 
	}
	 
}

function closeTabs(imei){
	$('.container'+imei).css('display','none')
} 

function get_direction(lat,lng,imei){
 
   var latlng = new google.maps.LatLng(lat, lng);
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
 
function createTabs(num){
	
    /*$('.tabs_container').css('height','210px');
    $('.opentabs').css('display','block');*/

    $('.speedAlarmActive').click(function(){
    	console.log('click');
	if ($(this).is(':checked')) {
		var imei = $(this).attr('imei');
		var speed_val = $('#speed'+imei).val(); 
		$.ajax({
		    url: '/clientes/nexmo/sms.php', // url del recurso
		    type: "post", // podría ser get, post, put o delete.
		    data: { action:'activeAlarm', imei_n:imei, speed:speed_val }, // datos a pasar al servidor, en caso de necesitarlo
		    success: function (r) {
		       alert(r)
		    }              
		});
		alert('checked');
	}else{
		alert('not');
		var imei = $(this).attr('imei');
		$.ajax({
		    url: '/clientes/nexmo/sms.php', // url del recurso
		    type: "post", // podría ser get, post, put o delete.
		    data: { action:'disableSpeedAlarm', imei_n:imei }, // datos a pasar al servidor, en caso de necesitarlo
		    success: function (r) {
		       alert(r)
		    }              
		});
		alert('not checked');
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
		       alert(r);
		       $('.loader').hide('slow');
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
		       alert(r);
		       $('.loader').hide('slow');
		    }              
		});
		 
	}
})
}

function sendCordsByEmail(imei,lat,Lang){
	var cel = $('.coordenadas'+imei).val();
	var adress_val = $('.adress'+imei).val(); 
	$('.loader').show('slow');
	$.ajax({
	    url: '/_functions/functions.php', // url del recurso
	    type: "post", // podría ser get, post, put o delete.
	    data: { action:'sendCordsByEmail',lat: lat, lang: Lang, num:cel, imei_n:imei, adress:adress_val, }, // datos a pasar al servidor, en caso de necesitarlo
	    success: function (r) {
	       alert(r);
	       $('.loader').hide('slow');
	    }              
	});
	
}

function sendCordsBySms(imei,lat,Lang){ 
 	var cel = $('.coordenadas'+imei).val();
	var address_val = $('.address'+imei).val(); 
	$('.loader').show('slow');
	$.ajax({
	    url: '/clientes/nexmo/sms.php', // url del recurso
	    type: "post", // podría ser get, post, put o delete.
	    data: { action:'sendCords',lat: lat, lang: Lang, num:cel, imei_n:imei, address:address_val }, // datos a pasar al servidor, en caso de necesitarlo
	    success: function (r) {
	       alert(r);
	       $('.loader').hide('slow');
	    }              
	});
	 
}

function lockEngine(imei){
	 
 	$('.loader').show('slow');
	$.ajax({
	    url: '/clientes/nexmo/sms.php', // url del recurso
	    type: "post", // podría ser get, post, put o delete.
	    data: { action: 'lockEngine' ,imei_n:imei }, // datos a pasar al servidor, en caso de necesitarlo
	    success: function (r) {
	       alert(r);
	       $('.loader').hide('slow');
	    }
	});
	
}

function unlockEngine(imei){
	 
 	$('.loader').show('slow');
	$.ajax({
	    url: '/clientes/nexmo/sms.php', // url del recurso
	    type: "post", // podría ser get, post, put o delete.
	    data: { action: 'unlockEngine' ,imei_n:imei }, // datos a pasar al servidor, en caso de necesitarlo
	    success: function (r) {
	       alert(r);
	       $('.loader').hide('slow');
	    }
	});
	 
}

function bloquearMarcha(imei){
	 
 	$('.loader').show('slow');
	$.ajax({
	    url: '/clientes/nexmo/sms.php', // url del recurso
	    type: "post", // podría ser get, post, put o delete.
	    data: { action: 'bloquearMarcha' ,imei_n:imei }, // datos a pasar al servidor, en caso de necesitarlo
	    success: function (r) {
	       alert(r);
	       $('.loader').hide('slow');
	    }
	});
	
}


function desbloquearMarcha(imei){
	 
 	$('.loader').show('slow');
	$.ajax({
	    url: '/clientes/nexmo/sms.php', // url del recurso
	    type: "post", // podría ser get, post, put o delete.
	    data: { action: 'desbloquearMarcha' ,imei_n:imei }, // datos a pasar al servidor, en caso de necesitarlo
	    success: function (r) {
	       alert(r);
	       $('.loader').hide('slow');
	    }
	});
	 
}
function closeElock(imei){
	 
 	$('.loader').show('slow');
	$.ajax({
	    url: '/clientes/nexmo/sms.php', // url del recurso
	    type: "post", // podría ser get, post, put o delete.
	    data: { action: 'closeElock' ,imei_n:imei }, // datos a pasar al servidor, en caso de necesitarlo
	    success: function (r) {
	       alert(r);
	       $('.loader').hide('slow');
	    }
	});
	 
}
function openElock(imei){
	 
 	$('.loader').show('slow');
	$.ajax({
	    url: '/clientes/nexmo/sms.php', // url del recurso
	    type: "post", // podría ser get, post, put o delete.
	    data: { action: 'openElock' ,imei_n:imei }, // datos a pasar al servidor, en caso de necesitarlo
	    success: function (r) {
	       alert(r);
	       $('.loader').hide('slow');
	    }
	});
	$('.loader').hide('slow');
}
function setSpeed(imei){
	var speed = $('#speed'+imei).val();
 	$('.loader').show('slow');
	$.ajax({
	    url: '/_functions/functions.php', // url del recurso
	    type: "post", // podría ser get, post, put o delete.
	    data: { action: 'setSpeed' ,imei_n:imei , speed_n:speed }, // datos a pasar al servidor, en caso de necesitarlo
	    success: function (r) {
	       
	    }
	});
	$.ajax({
		    url: '/clientes/nexmo/sms.php', // url del recurso
		    type: "post", // podría ser get, post, put o delete.
		    data: { action:'activeAlarm', imei_n:imei, speed:speed }, // datos a pasar al servidor, en caso de necesitarlo
		    success: function (r) {
		      $('.loader').hide();
		       	alert('Kilometraje Ingresado');
	       		$('.loader').hide('slow');
		    }              
		});
 
}

function setLastResetTime(imei){
	$('.loader').show('slow');
	$.ajax({
	    url: '/_functions/functions.php', // url del recurso
	    type: "post", // podría ser get, post, put o delete.
	    data: { action: 'setLastResetTime' ,imei_n:imei }, // datos a pasar al servidor, en caso de necesitarlo
	    success: function (r) {
	        
	    }
	});
	$.ajax({
	    url: '/clientes/nexmo/sms.php', // url del recurso
	    type: "post", // podría ser get, post, put o delete.
	    data: { action: 'reset' ,imei_n:imei }, // datos a pasar al servidor, en caso de necesitarlo
	    success: function (r) {
	       alert('Reset enviado');
	       $('.loader').hide('slow');
	    }
	});
	$('.loader').hide('slow');
}

function setSpeedLimit(imei){
	var speed = $('#speedLimit'+imei).val();
    $('.loader').show('slow');
	$.ajax({
	    url: '/_functions/functions.php', // url del recurso
	    type: "post", // podría ser get, post, put o delete.
	    data: { action: 'setSpeedLimit' ,imei_n:imei , speed_n:speed }, // datos a pasar al servidor, en caso de necesitarlo
	    success: function (r) {
	       alert('Limite Activado');
	       $('.loader').hide('slow');
	    }
	});
}
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

function openSmsBox(imei){
	if($('.sendByEmail'+imei).is(':visible')){
		$('.sendByEmail'+imei).hide();
	}
	$('.sendBySms'+imei).slideDown('slow');
}
function openEmailBox(imei){
	if($('.sendBySms'+imei).is(':visible')){
		$('.sendBySms'+imei).hide();
	}
	$('.sendByEmail'+imei).slideDown('slow');
}

$("input:submit").click(function() { return false; });


function startIntro(){
	  introJs().start().onbeforechange(function(targetElementId) {  
   
    switch($(targetElementId).attr("data-step")) {

        case "5":  imei = $(targetElementId).attr("imei"); setTabs(imei,1); break;
        case "11":  imei = $(targetElementId).attr("imei"); setTabs(imei,1); break;
        case "12":  imei = $(targetElementId).attr("imei"); setTabs(imei,2); break;
        case "13":  imei = $(targetElementId).attr("imei"); setTabs(imei,3); break; 
        case "15":  imei = $(targetElementId).attr("imei"); setTabs(imei,4); break;
        case "16":  imei = $(targetElementId).attr("imei"); setTabs(imei,4); break;
        case "17":  imei = $(targetElementId).attr("imei"); setTabs(imei,5); break;
 

    }
});
}

  