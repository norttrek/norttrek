 <script type="text/javascript">
 var objTrack = null;
 var objClient = null;
 

$(window).load(function(){
  var json = eval(<?php echo $objJson ?>);
  objTrack = new Track('map-canvas',json);
});
 
 
var Track = function(map_id,gprs_reports_array){
 
    var t = this;
	var infobox;
    this.gprs_reports = gprs_reports_array;
    
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
	this.Drawroute = [];
	this.Drawroutes;

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
	   $("a.onClickGetRoute").on("click",function(e){
		    console.log('get')
		    var s = $("#date_from").val() +" "+ $("#hour_from").val();
		    var e = $("#date_to").val() +" "+ $("#hour_to").val();
		    var i = $("#lst_route_imei").val();
		    if(i=="NULL"){ alert("Seleccione una Unidad"); }
		    t.get_route_timeline(i,s,e);
  		});

	  $("a.CompareRoute").on("click",function(e){ 
		    var s = $("#date_from").val() +" "+ $("#hour_from").val();
		    var e = $("#date_to").val() +" "+ $("#hour_to").val();
		    var i = $("#lst_route_imei").val();
		    if(i=="NULL"){ alert("Seleccione una Unidad"); }
		    t.get_compare_timeline(i,s,e);
  		});
	  
	  t.get_gprs();
	 
	  t.init_draw();
	 
	  geocoder = new google.maps.Geocoder();
	  /*
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
 */
 
	  google.maps.event.addListener(t.map, 'click', function(event){
 
        if($("body").attr("side")=='interes'){
         
	      if(!t.poi){ 
	        	t.poi = new google.maps.Marker({ position: event.latLng,map: t.map, draggable: true }); 
	          
	         }
	         else { 
	         	t.poi.setPosition(event.latLng);  
	         }
		    google.maps.event.addListener(t.poi, "dblclick", function() { 
		    	t.poi.setMap(null); t.poi = null; 
		    });
		}

	  });




	t.get_pois(); 
	   $('.onClickRemove').on('click',function(e){ 
	   	t.remove_shape(); 
	   	$('#geomap').html('')
	   });
	  $('.onClickDrawCircle').on('click',function(e){ 
	     if(gcircle.center==null)
	     	{ 
	     		drawingManager.setDrawingMode(google.maps.drawing.OverlayType.CIRCLE); 
	     	}else{  
	     		alert("Solo puedes dibujar una geozona ala vez."); 
	     	}
	  });

	  $('.onClickDrawPolygon').on('click',function(e){
	    if(gcircle.center==null){ drawingManager.setDrawingMode(google.maps.drawing.OverlayType.POLYGON); }else{ alert("Solo puedes dibujar una geozona ala vez."); }
	  });

	  $('.rowtrack').on('click','.setTrackonMap',function(){
	  	console.log('clicked')
	  	var flightPlanCoordinates = [
	    {lat: 37.772, lng: -122.214},
	    {lat: 21.291, lng: -157.821},
	    {lat: -18.142, lng: 178.431},
	    {lat: -27.467, lng: 153.027}
	  ];
	  var flightPath = new google.maps.Polyline({
	    path: flightPlanCoordinates,
	    geodesic: true,
	    strokeColor: '#FF0000',
	    strokeOpacity: 1.0,
	    strokeWeight: 2
	  });

	  flightPath.setMap(t.map);
	  });
	  $('.onClickDrawPoliline').on('click',function(e){
	  		 t.map.setOptions({draggableCursor:'crosshair'});
	  		var routes = new google.maps.MVCArray();
	  		lineW = t.map.getZoom() + 20;
	  		zoom = t.map.getZoom();
	  		 
	  		if(zoom == 16){
	  			lineW = 36;
	  		}else{
	  			lineW = 15;
	  		} 
			var polyline = new google.maps.Polyline({
				path: routes
				, map: t.map
				, strokeColor: '#ff0000'
				, strokeWeight: 5
				, strokeOpacity: 0.4
				, clickable: false
			});

			google.maps.event.addListener(t.map, 'click', function(e){
				var path = polyline.getPath();
				path.push(e.latLng)
				t.path = path;
				 
			});
			google.maps.event.addListener(t.map, 'rightclick', function(e){
				var path = polyline.getPath();
				 
				path.pop();
			});
			google.maps.event.addListener(t.map, 'zoom_changed', function(e){
				zoom = t.map.getZoom();
				/*if(zoom == 16){
	  			lineW = 30;
		  		}else if(zoom == 17){
		  			lineW = 32;
		  		}else if(zoom == 18){
		  			lineW = 35;
		  		}else if(zoom == 19){
		  			lineW = 40;
		  		}else if(zoom == 20){
		  			lineW = 45;
		  		}
		  		else if(zoom > 14){
		  			lineW = 10;
		  		}
		  		else if(zoom > 18){
		  			lineW = 7;
		  		}*/ 
				polyline.setOptions({strokeWeight:lineW});

			});

 	  });

	 $('.onClickSavePoliline').on('click',function(e){  
	 	console.log('sace')
	 	var encodeString = google.maps.geometry.encoding.encodePath(t.path); 
	 	var image =  "https://maps.googleapis.com/maps/api/staticmap?size=400x400&path=weight:3%7Ccolor:blue%7Cenc:"+encodeString; 
	  		points = []; 
	  		console.log(t.path)
	  		t.path.forEach(function(item, index) {  
    			points.push(item.lat() + ',' + item.lng())
			}); 
			var client_id = $('#id_client').attr('cliente');
			var name = $("#trak_name").val();
			var tol = $("#tolerancia").val();
			errorPista ="";
			error=0;
			errorToleracia = "";
			if(name == ('')){
				 error=1;
				var errorPista = 'Debe introducir Nombre de la pista'; 
			}
			if(tolerancia == ('')){
				 error =1;
				var errorToleracia = 'Debe introducir la tolerancia en mts'; 
			}
			if(error == 1){
				alert(errorPista + " "+ errorToleracia);
			}else{
				$.ajax({
		    url: '../_functions/functions.php', // url del recurso
		    type: "post", // podría ser get, post, put o delete.
		    data: { action:'saveRoute', points:points, id:client_id ,map:image,trak_name:name,tolerancia:tol }, // datos a pasar al servidor, en caso de necesitarlo
		    success: function (r) { 
			console.log(r);
		     var respuesta = JSON.parse(r);

		 	alert('Pista Guardada'); 
				       $('#tbl_tracks tbody').append(respuesta.row);
				    }
				}); 
			}
	  	 	

 	  }); 
	 
	  $('.onMarkerHover').on('mouseover',function(e){ t.route_marker_mover(e); });
	  $('.onMarkerHover').on('mouseout',function(e){ t.route_marker_mout(e); });

	 
	  $('.onClickSaveGeo').on('click',function(e){
	  	 
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
	 
	   for(var i=0;i<t.gprs_reports.length;i++){
	   
	   	if(t.gprs_reports[i][0].v2_speed>0){ micon = "marker.png"; }
	   	if(t.gprs_reports[i][0].v2_speed==0 && t.gprs_reports[i][0].v2_eng_status==1){ micon = "idle.png"; }
	   	if(t.gprs_reports[i][0].v2_eng_status==0){ micon = "off.png"; }

	   var marker_aux = new MarkerWithLabel({
		   		position: new google.maps.LatLng(t.gprs_reports[i][0].v2_latitude,t.gprs_reports[i][0].v2_longitude),
				map: t.map,
				labelContent: '<div style="background:white; border-radius:50%; margin:0 auto; width:30px; height:30px;"><i style="font-size:30px; color:'+t.gprs_reports[i][0].icon_color+'" class="fa fa-arrow-circle-o-up   fa-rotate-'+t.gprs_reports[i][0].v2_heading+'"></i></div><div class="nameMap">'+t.gprs_reports[i][0].name+"</div>"+'<div class="nameMap">'+t.gprs_reports[i][0].alert+"</div>",
				labelClass: "label",
				labelStyle: {opacity: 0.90},
				labelAnchor: new google.maps.Point(28, 0),
				id : t.gprs_reports[i][0].v2_imei,
				icon: "_icons/"
		   });
	   
 
	   //resize map al hacer grande el div que contiene el mapa
	   $('#hideUnidades').click(function() {
			google.maps.event.trigger(t.map, 'resize');
		});
	  
	    	t.markers.push(marker_aux);
	    	google.maps.event.trigger(t.map, 'resize')
	    	
	    	google.maps.event.addListener(t.map, "bounds_changed", function(){
        		google.maps.event.trigger(t.map, 'resize'); 
        	});
         
 			/*google.maps.event.addListener(t.markers[i], "click", function(){
			  var marker_aux = this;
			  var coord = marker_aux.getPosition();
			  $.post('_json/json.asset.php', { id: marker_aux.id },
			  function(data){
			    infobox.close();
			    infobox.setContent(data);
			    infobox.open(t.map,marker_aux) ;
			  });
			});*/
 		}
	 
	  //$("#tbl_bottom > tbody tr").empty();
	  $("#tbl_assets > tbody tr").empty();

	  $('.blockengine').on('click', function () {
 		function_to_do = $(this).attr('function');
        imei = $(this).attr('imei');
        name = $(this).attr('name');
        if(function_to_do == 'lockEngine'){
        	$.confirm({
        		title: 'Bloqueo de Motor',
						content: '¿Deseas bloquear el motor de la unidadd ' + name + '?',
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
$.each($('.saveTempAlarms'),function(){
	var num = $(this).attr('imei');
 
	$('#saveTempAlarms'+num).click(function(){
		 alert('d')
		 var formData = $('#formsaveTempAlarms'+num).serialize();
	 		 formData = new FormData( $('#saveTempAlarms'+num)[0]);
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
	 
//FOOTER/*
	 /* $.post('_json/json.assets.php', { id: $(this).attr("id") },
	    function(r){ 
		  $("#tbl_bottom > tbody:last ").append(r.html_bottom);
		   


		  $("#units tbody").sortable({
			update: function( event, ui ){
			  var order = new Array();
			  $('#units > tbody tr').each(function(idx){ if($(this).attr("id")!=null){ order.push($(this).attr("id")); } });
			  $.post("../_ctrl/ctrl.client.php", { exec: "save_units_order", data : JSON.stringify(order) },function(r){ console.log(r); });
			}
		  });


	    },"json"); */

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
		console.log('go')
     alert('dddd')
     // t.map.setZoom(15);
      //t.map.panTo(new google.maps.LatLng(lat, lon));
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


$('.onLatLngClick').on('click',function(e){
      alert('go');
      point = $(e.target).attr("rel").split(","); 
      t.go_to(point[0],point[1],$(e.target).attr("id"));
    })

  this.go_to = function(lat,lon,id){

   console.log('ff'+t.marker)

   
    if(t.marker!=null){ 
    	t.marker.setMap(null); 
    }
    t.marker = new google.maps.Marker({ 
      position: new google.maps.LatLng(lat,lon),
      map: t.map, 
      icon : "_icons/"+id 
    });
      t.map.setZoom(15);
      t.map.panTo(new google.maps.LatLng(lat, lon));
     
  }


	this.get_compare_timeline = function(i,s,e){
	  var path = [];
	  t.clear_markers();
	  t.clear_markers_route();
	  $("#tbl_route > tbody").empty();
	  $('.loader').show('slow');

	  $.post('_json/json.route.track.php', { imei: i, sdate : s, edate: e },
	    function(r){ 
	    	if(r == null){
	    		alert('No existen reportes de trayectoria en las fechas seleccionadas');
	    		$('.loader').hide('slow');
	    	}
		   t.georoute_points = r; 
		   $("#tbl_route thead").empty();
		   if(r[0]['sensor_fuel_a']==1){
		   	ActiveColumns = "<td>T1</td>";
		   }
		   if(r[0]['sensor_fuel_b']==1){
		   	ActiveColumns = ActiveColumns +  "<td>T2</td>";
		   }
		   if(r[0]['sensor_fuel_c']==1){
		   ActiveColumns = ActiveColumns + "<td>T3</td>";
		   }
		   if(r[0]['sensor_temp1']==1){
		   	ActiveColumns = ActiveColumns + "<td>Temperatura 1</td>";
		   }
		   if(r[0]['sensor_temp2']==1){
		   	ActiveColumns = ActiveColumns + "<td>Temperatura 2</td>";
		   }
		   $("#tbl_route thead").append('<tr><td>No.</td><td>Fecha</td><td>Velocidad km/h</td>'+ActiveColumns+'<td>Evento</td><td>Coordenadas</td></tr>');
		   
		   var name = r[0]['assets'][0].alias;
		   var reportInfo = "  Unidad:"+name+" <span style='font-size:12px;'> Reporte del <b>"+s+"</b> al <b>"+e+"</b> <button class='onClickRouteExport excel'>Exportar a Excel </span>"; 
		   $('#reportInfo').html(reportInfo);
		   $(".onClickRouteExport").click(function(){
				var s = $("#date_from").val() +" "+ $("#hour_from").val();
				var e = $("#date_to").val() +" "+ $("#hour_to").val();
				var i = $("#lst_route_imei").val();
				 
				if(i=="NULL"){ alert("Seleccione una Unidad"); }
				window.open("_export/exp.route.php?sdate="+s+"&edate="+e+"&imei="+i);
			});
		   $("#tbl_route > tbody").empty();
		  for(var i=0;i<r.length;i++){
		  	evenCode = r[i].event_code;
		  	//console.log(i + " - "+ evenCode)
		    color = '';
			/*if(r[i].km>0){ color = '0097ee'; } 
		    if(r[i].km==0 && r[i].ignition==1){ color = "ff5a00"; }
		    if(r[i].ignition==0){ color = "bebebe"; }*/
		    //r[i].event_code = 36;
		     marker = 'http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld='+i+'|'+color+'|000000' ; 
		    
		     //-------SIN ALARMAS SIN RECARGAS---------
		    if(r[i].ttcol_f == '' || r[i].ttcol_f == undefined){
		    	//NO ALARMAS
		    	var marker = 'http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld='+i+'|0097ee|000000' ; 
		    } 



		    //-------ALERTAS--------//
		    if(r[i].event_code == 1){
		   		//SOS RED
		    	color = 'FF0000' 
		    	var marker = 'https://chart.googleapis.com/chart?chst=d_simple_text_icon_left&chld='+i+'|14|000000|caution|24|'+color+'|000000' ; 
		    } 
		    if(r[i].event_code == 2){
		   		//ENG ON GREEN
		    	color = "1cf100";
		    	var marker = 'http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld='+i+'|'+color+'|000000' ; 
		    } 
		    if(r[i].event_code == 3){
		    	//ENG OF GREY
		    	color = "ADAFAD"; 
		    	var marker = 'http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld='+i+'|'+color+'|000000' ; 
		    }
		    if(r[i].event_code == 4){
		   		//exvel
		    	color = 'FFFF33' 
		    	var marker = 'https://chart.googleapis.com/chart?chst=d_simple_text_icon_left&chld='+i+'|14|000000|caution|24|'+color+'|000000' ; 
		    } 
		    if(r[i].event_code == 6){
		    	//ENG OF GREY
		    	color = "FF0000";
		    	var marker = 'https://chart.googleapis.com/chart?chst=d_simple_text_icon_left&chld='+i+'|14|000000|glyphish_outlet|16|'+color+'|fff'; 
		    } 
		    if(r[i].event_code == 7){
		    	//CONEXION EQUIPO AZUL
		    	color = "0066FF";
		    	var marker = 'https://chart.googleapis.com/chart?chst=d_simple_text_icon_left&chld='+i+'|14|000000|glyphish_outlet|16|'+color+'|fff'; 
		    }
		    if(r[i].event_code == 25){
		   		//RESUMEN MARCHA AZUL
		    	color = '0066FF' 
		    	var marker = 'https://chart.googleapis.com/chart?chst=d_simple_text_icon_left&chld='+i+'|14|000000|car-dealer|24|'+color+'|000000' ; 
		    }
		    if(r[i].event_code == 26){
		   		//DETIENE MARCHA GRIS
		    	color = 'ADAFAD' 
		    	var marker = 'https://chart.googleapis.com/chart?chst=d_simple_text_icon_left&chld='+i+'|14|000000|car-dealer|24|'+color+'|000000' ; 
		    }

		    if(r[i].event_code == 31){
		    	//1min AZUL
		    	color = "0066FF";
		    	var marker = 'https://chart.googleapis.com/chart?chst=d_simple_text_icon_left&chld='+i+'|14|000000|glyphish_stopwatch|16|'+color+'|fff'; 
		    }
		    if(r[i].event_code == 32){
		    	//1min AZUL
		    	color = "0066FF";
		    	var marker = 'https://chart.googleapis.com/chart?chst=d_simple_text_icon_left&chld='+i+'|14|000000|glyphish_stopwatch|16|'+color+'|fff'; 
		    }
		    if(r[i].event_code == 33){
		    	//1min AZUL
		    	color = "0066FF";
		    	var marker = 'https://chart.googleapis.com/chart?chst=d_simple_text_icon_left&chld='+i+'|14|000000|glyphish_stopwatch|16|'+color+'|fff'; 
		    }
		    if(r[i].event_code == 34){
		    	//1min AZUL
		    	color = "0066FF";
		    	var marker = 'https://chart.googleapis.com/chart?chst=d_simple_text_icon_left&chld='+i+'|14|000000|glyphish_stopwatch|16|'+color+'|fff'; 
		    }
		    if(r[i].event_code == 35){
		    	//1min AZUL
		    	color = "0066FF";
		    	var marker = 'https://chart.googleapis.com/chart?chst=d_simple_text_icon_left&chld='+i+'|14|000000|glyphish_stopwatch|16|'+color+'|fff'; 
		    }
		    if(r[i].event_code == 36){
		    	//1min AZUL
		    	color = "0066FF";
		    	var marker = 'https://chart.googleapis.com/chart?chst=d_simple_text_icon_left&chld='+i+'|14|000000|glyphish_stopwatch|16|'+color+'|fff'; 
		    }
		    if(r[i].event_code == 37){
		    	//1min AZUL
		    	color = "0066FF";
		    	var marker = 'https://chart.googleapis.com/chart?chst=d_simple_text_icon_left&chld='+i+'|14|000000|glyphish_stopwatch|16|'+color+'|fff'; 
		    }
		    if(r[i].event_code == 38){
		    	//1min AZUL
		    	color = "0066FF";
		    	var marker = 'https://chart.googleapis.com/chart?chst=d_simple_text_icon_left&chld='+i+'|14|000000|glyphish_stopwatch|16|'+color+'|fff'; 
		    }
		    if(r[i].event_code == 39){
		    	//1min AZUL
		    	color = "0066FF";
		    	var marker = 'https://chart.googleapis.com/chart?chst=d_simple_text_icon_left&chld='+i+'|14|000000|glyphish_stopwatch|16|'+color+'|fff'; 
		    }

		    if(r[i].event_code == 40){
		   		//BATEIRA BAJA AMARILLO
		    	color = 'FFFF33' 
		    	var marker = 'https://chart.googleapis.com/chart?chst=d_simple_text_icon_left&chld='+i+'|14|000000|caution|24|'+color+'|000000' ; 
		    } 




		    //-------RECARGAS--------//
		    if(r[i].ttcol_f>0){ 
		    	//RECARGA
		    	color = '06B344' 
		    	var marker = 'https://chart.googleapis.com/chart?chst=d_simple_text_icon_left&chld='+i+'|14|000000|petrol|24|05733D|000000' ; 
		    }else if(r[i].ttcol_f< 0){
		    	//DESCARGA
		    	color = "E60A0A";
		    	var marker = 'https://chart.googleapis.com/chart?chst=d_simple_text_icon_left&chld='+i+'|14|000000|petrol|24|E60A0A|000000' ; 
		    } 
		    var ide = r.length-i;
	 		// console.log(r[i].lat)
	 		//console.log(marker); 
		    t.markers_route.push(new google.maps.Marker({ 
		    		position: new google.maps.LatLng(r[i].lat,r[i].lng),map: t.map, 
		    		id :ide, 
		    		icon : marker
		    	}));
		   // console.log('Se crean los puntos con push' + r[i].lat);

			$("#tbl_route > tbody").append(r[i].tbl_route);
			lat = r[i].lat;
			var contentString = '<div id="content">'+
      '<div id="siteNotice">'+
      '</div>'+
      '<h1 id="firstHeading" class="firstHeading">'+lat +'</h1>'+
      '<div id="bodyContent">'+ 
      '</div>'+
      '</div>';
 var infowindow = new google.maps.InfoWindow({
    content: contentString
  });
			  
			google.maps.event.addListener(t.markers_route[i], "click", function(){
			  
			 	infowindow.open(t.map, t.markers_route[i]);
			  //marker_aux.setZIndex(google.maps.Marker.MAX_ZINDEX + 1);
			  //marker_aux.setIcon('http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld='+marker_aux.id+'|ff7e00|000000');
			  //$("#tr_route_"+marker_aux.id).addClass('active');
			}); 

			google.maps.event.addListener(t.markers_route[i], "mouseout", function(){
			  var marker_aux = this;
			  //marker_aux.setZIndex(0)
			  //marker_aux.setIcon('http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld='+marker_aux.id+'|009cff|000000');
			  //$("#tr_route_"+marker_aux.id).removeClass('active');
			});

			/*google.maps.event.addListener("click", function(){
			   infowindow.open(map, marker);
			});*/ 
		  }
		  $('.loader').hide('slow');
		  $('#tbl_route >').each(function(){
    	  	var list = $(this).children('tr');
    	  	//$(this).html(list.get().reverse())
		  });
		  //t.draw_route(1); 
	    },"json");
		
	} 

	this.get_route_timeline = function(i,s,e){
		$('#tbl_bottom').slideUp();
		$('#tbl_report_geo').slideUp();
		 $("#tbl_route").slideDown();
	  var path = [];
	  t.clear_markers();
	  t.clear_markers_route();
	  $("#tbl_route > tbody").empty();
	  $('.loader').show('slow');

	  $.post('_json/json.route.time.php', { imei: i, sdate : s, edate: e },
	    function(r){ 
	    	if(r == null){
	    		alert('No existen reportes de trayectoria en las fechas seleccionadas');
	    		$('.loader').hide('slow');
	    	}
		   t.georoute_points = r; 
		   $("#tbl_route thead").empty();
		   ActiveColumns ="";
		   if(r[0]['sensor_fuel_a']==1){
		   	ActiveColumns = "<td>T1</td>";
		   }
		   if(r[0]['sensor_fuel_b']==1){
		   	ActiveColumns = ActiveColumns +  "<td>T2</td>";
		   }
		   if(r[0]['sensor_fuel_c']==1){
		   ActiveColumns = ActiveColumns + "<td>T3</td>";
		   }
		   if(r[0]['sensor_temp1']==1){
		   	ActiveColumns = ActiveColumns + "<td>Temperatura 1</td>";
		   }
		   if(r[0]['sensor_temp2']==1){
		   	ActiveColumns = ActiveColumns + "<td>Temperatura 2</td>";
		   }
		   $("#tbl_route thead").append('<tr><td>No.</td><td>Fecha</td><td>Velocidad km/h</td>'+ActiveColumns+'<td>Evento</td><td>Coordenadas</td></tr>');
		   
		   var name = r[0]['assets'][0].alias;
		  // var reportInfo = "  Unidad:"+name+" <span style='font-size:12px;'> Reporte del <b>"+s+"</b> al <b>"+e+"</b> <button class='onClickRouteExport excel'>Exportar a Excel </span>"; 
		   //$('#reportInfo').html(reportInfo);
		   $(".onClickRouteExport").click(function(){
				var s = $("#date_from").val() +" "+ $("#hour_from").val();
				var e = $("#date_to").val() +" "+ $("#hour_to").val();
				var i = $("#lst_route_imei").val();
				 
				if(i=="NULL"){ alert("Seleccione una Unidad"); }
				window.open("_export/exp.route.php?sdate="+s+"&edate="+e+"&imei="+i);
			});
		   $("#tbl_route > tbody").empty();
		  for(var i=0;i<r.length;i++){
		  	evenCode = r[i].event_code;
		  	//console.log(i + " - "+ evenCode)
		    color = '';
			/*if(r[i].km>0){ color = '0097ee'; } 
		    if(r[i].km==0 && r[i].ignition==1){ color = "ff5a00"; }
		    if(r[i].ignition==0){ color = "bebebe"; }*/
		    //r[i].event_code = 36;
		     marker = 'http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld='+i+'|'+color+'|000000' ; 
		    
		     //-------SIN ALARMAS SIN RECARGAS---------
		    if(r[i].ttcol_f == '' || r[i].ttcol_f == undefined){
		    	//NO ALARMAS
		    	var marker = 'http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld='+i+'|0097ee|000000' ; 
		    } 



		    //-------ALERTAS--------//
		    if(r[i].event_code == 1){
		   		//SOS RED
		    	color = 'FF0000' 
		    	var marker = 'https://chart.googleapis.com/chart?chst=d_simple_text_icon_left&chld='+i+'|14|000000|caution|24|'+color+'|000000' ; 
		    } 
		    if(r[i].event_code == 2){
		   		//ENG ON GREEN
		    	color = "1cf100";
		    	var marker = 'http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld='+i+'|'+color+'|000000' ; 
		    } 
		    if(r[i].event_code == 3){
		    	//ENG OF GREY
		    	color = "ADAFAD"; 
		    	var marker = 'http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld='+i+'|'+color+'|000000' ; 
		    }
		    if(r[i].event_code == 4){
		   		//exvel
		    	color = 'FFFF33' 
		    	var marker = 'https://chart.googleapis.com/chart?chst=d_simple_text_icon_left&chld='+i+'|14|000000|caution|24|'+color+'|000000' ; 
		    } 
		    if(r[i].event_code == 6){
		    	//ENG OF GREY
		    	color = "FF0000";
		    	var marker = 'https://chart.googleapis.com/chart?chst=d_simple_text_icon_left&chld='+i+'|14|000000|glyphish_outlet|16|'+color+'|fff'; 
		    } 
		    if(r[i].event_code == 7){
		    	//CONEXION EQUIPO AZUL
		    	color = "0066FF";
		    	var marker = 'https://chart.googleapis.com/chart?chst=d_simple_text_icon_left&chld='+i+'|14|000000|glyphish_outlet|16|'+color+'|fff'; 
		    }
		    if(r[i].event_code == 25){
		   		//RESUMEN MARCHA AZUL
		    	color = '0066FF' 
		    	var marker = 'https://chart.googleapis.com/chart?chst=d_simple_text_icon_left&chld='+i+'|14|000000|car-dealer|24|'+color+'|000000' ; 
		    }
		    if(r[i].event_code == 26){
		   		//DETIENE MARCHA GRIS
		    	color = 'ADAFAD' 
		    	var marker = 'https://chart.googleapis.com/chart?chst=d_simple_text_icon_left&chld='+i+'|14|000000|car-dealer|24|'+color+'|000000' ; 
		    }

		    if(r[i].event_code == 31){
		    	//1min AZUL
		    	color = "0066FF";
		    	var marker = 'https://chart.googleapis.com/chart?chst=d_simple_text_icon_left&chld='+i+'|14|000000|glyphish_stopwatch|16|'+color+'|fff'; 
		    }
		    if(r[i].event_code == 32){
		    	//1min AZUL
		    	color = "0066FF";
		    	var marker = 'https://chart.googleapis.com/chart?chst=d_simple_text_icon_left&chld='+i+'|14|000000|glyphish_stopwatch|16|'+color+'|fff'; 
		    }
		    if(r[i].event_code == 33){
		    	//1min AZUL
		    	color = "0066FF";
		    	var marker = 'https://chart.googleapis.com/chart?chst=d_simple_text_icon_left&chld='+i+'|14|000000|glyphish_stopwatch|16|'+color+'|fff'; 
		    }
		    if(r[i].event_code == 34){
		    	//1min AZUL
		    	color = "0066FF";
		    	var marker = 'https://chart.googleapis.com/chart?chst=d_simple_text_icon_left&chld='+i+'|14|000000|glyphish_stopwatch|16|'+color+'|fff'; 
		    }
		    if(r[i].event_code == 35){
		    	//1min AZUL
		    	color = "0066FF";
		    	var marker = 'https://chart.googleapis.com/chart?chst=d_simple_text_icon_left&chld='+i+'|14|000000|glyphish_stopwatch|16|'+color+'|fff'; 
		    }
		    if(r[i].event_code == 36){
		    	//1min AZUL
		    	color = "0066FF";
		    	var marker = 'https://chart.googleapis.com/chart?chst=d_simple_text_icon_left&chld='+i+'|14|000000|glyphish_stopwatch|16|'+color+'|fff'; 
		    }
		    if(r[i].event_code == 37){
		    	//1min AZUL
		    	color = "0066FF";
		    	var marker = 'https://chart.googleapis.com/chart?chst=d_simple_text_icon_left&chld='+i+'|14|000000|glyphish_stopwatch|16|'+color+'|fff'; 
		    }
		    if(r[i].event_code == 38){
		    	//1min AZUL
		    	color = "0066FF";
		    	var marker = 'https://chart.googleapis.com/chart?chst=d_simple_text_icon_left&chld='+i+'|14|000000|glyphish_stopwatch|16|'+color+'|fff'; 
		    }
		    if(r[i].event_code == 39){
		    	//1min AZUL
		    	color = "0066FF";
		    	var marker = 'https://chart.googleapis.com/chart?chst=d_simple_text_icon_left&chld='+i+'|14|000000|glyphish_stopwatch|16|'+color+'|fff'; 
		    }

		    if(r[i].event_code == 40){
		   		//BATEIRA BAJA AMARILLO
		    	color = 'FFFF33' 
		    	var marker = 'https://chart.googleapis.com/chart?chst=d_simple_text_icon_left&chld='+i+'|14|000000|caution|24|'+color+'|000000' ; 
		    } 




		    //-------RECARGAS--------//
		    if(r[i].ttcol_f>0){ 
		    	//RECARGA
		    	color = '06B344' 
		    	var marker = 'https://chart.googleapis.com/chart?chst=d_simple_text_icon_left&chld='+i+'|14|000000|petrol|24|05733D|000000' ; 
		    }else if(r[i].ttcol_f< 0){
		    	//DESCARGA
		    	color = "E60A0A";
		    	var marker = 'https://chart.googleapis.com/chart?chst=d_simple_text_icon_left&chld='+i+'|14|000000|petrol|24|E60A0A|000000' ; 
		    } 
		    var ide = r.length-i;
	 		// console.log(r[i].lat)
	 		//console.log(marker); 
		    t.markers_route.push(new google.maps.Marker({ 
		    		position: new google.maps.LatLng(r[i].lat,r[i].lng),map: t.map, 
		    		id :ide, 
		    		icon : marker
		    	}));
		   // console.log('Se crean los puntos con push' + r[i].lat);

			$("#tbl_route > tbody").append(r[i].tbl_route);
			lat = r[i].lat;
			var contentString = '<div id="content">'+
      '<div id="siteNotice">'+
      '</div>'+
      '<h1 id="firstHeading" class="firstHeading">'+lat +'</h1>'+
      '<div id="bodyContent">'+ 
      '</div>'+
      '</div>';
 var infowindow = new google.maps.InfoWindow({
    content: contentString
  });
			  
			google.maps.event.addListener(t.markers_route[i], "click", function(){
			  
			 	infowindow.open(t.map, t.markers_route[i]);
			  //marker_aux.setZIndex(google.maps.Marker.MAX_ZINDEX + 1);
			  //marker_aux.setIcon('http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld='+marker_aux.id+'|ff7e00|000000');
			  //$("#tr_route_"+marker_aux.id).addClass('active');
			}); 

			google.maps.event.addListener(t.markers_route[i], "mouseout", function(){
			  var marker_aux = this;
			  //marker_aux.setZIndex(0)
			  //marker_aux.setIcon('http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld='+marker_aux.id+'|009cff|000000');
			  //$("#tr_route_"+marker_aux.id).removeClass('active');
			});

			/*google.maps.event.addListener("click", function(){
			   infowindow.open(map, marker);
			});*/ 
		  }
		  $('.loader').hide('slow');
		  $('#tbl_route >').each(function(){
    	  	var list = $(this).children('tr');
    	  	//$(this).html(list.get().reverse())
		  });
		  //t.draw_route(1);
		  console.log('se manda a trazar la ruta' + t.draw_route(1));
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
	   
	    $('body').on('click','.onLatLngOver',function(e){ 
	    	console.log('000')
	    point = $(e.target).attr("rel").split(",");
	    t.go_to(point[0],point[1]);
	  })
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
	   t.showGeo(t.geofence_handler);
	  google.maps.event.addListener(geocircle, 'radius_changed', function(e){ 
	 	t.geofence_handler(geocircle,'c'); 
	  	 t.showGeo(t.geofence_handler);
	  	});
	  google.maps.event.addListener(geocircle, 'center_changed', function(e){ 
	  t.geofence_handler(geocircle,'c'); 
	  t.showGeo(t.geofence_handler);
	   });

	  
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
		  t.showGeo(t.geofence_handler)
		  google.maps.event.addListener(e, 'insert_at', function(){ 
  		});


		}

            if (e.type != google.maps.drawing.OverlayType.MARKER) {
            // Switch back to non-drawing mode after drawing a shape.
            drawingManager.setDrawingMode(null);

            var newShape = e.overlay;
            newShape.type = e.type; 
            if(newShape.type == "polygon"){
            	google.maps.event.addListener(newShape.getPath(), 'set_at', function() { 
            t.set_selection(newShape); 
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
		  t.showGeo(t.geofence_handler)
        });

        google.maps.event.addListener(newShape.getPath(), 'insert_at', function() { 
            t.set_selection(newShape); 
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
		  t.showGeo(t.geofence_handler)
        });
            }
            

          


            google.maps.event.addListener(newShape, 'click', function() { 
            	  
              t.set_selection(newShape); 
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
		  t.showGeo(t.geofence_handler)

            });
            t.set_selection(newShape);
          } 
        });



  }
this.showGeo = function(geo){ 
	$('#geomap').load( "_view/mod.save_geofence.php?zoom="+t.geofence.zoom+"&type="+t.geofence.type+"&data="+t.geofence.vars);
	 

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
		 
	  t.geofence = {};
	  t.geofence.type = "poly";
	  t.geofence.path = circle;
	  t.geofence.center = null;
	  t.geofence.zoom = t.map.getZoom();
	  t.geofence.vars = circle;
	 
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
		$.post("../_ctrl/ctrl.client.php", { exec: "get_pois" },function(r){ 
			t.cpoi = r.poi; 
		},"json"); 
	}

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
	 $('#tabs-6'+imei).slideUp('slow');
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


 
function createTabs(num){
	
    /*$('.tabs_container').css('height','210px');
    $('.opentabs').css('display','block');*/

    
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

 
</script>