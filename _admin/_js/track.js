var objTrack = null;

$(window).load(function(){ 
  objTrack = new Track(); 
});

var Track = function(){ 
    var t = this;
	
	this.div = 'map-canvas';
	this.interval = 60000;
	this.map;
	this.unit = [];
	this.markers = [];
	
		
	function onLoad(){ t.addEventListeners(); 
	//t.draw_route();
	  }
	
	this.addEventListeners = function(){ 
	  google.maps.event.addDomListener(window, 'load', t.init());
	}
	
	
	
	this.init = function(){
	  var mapOptions = { zoom: 7, center: new google.maps.LatLng(25.659234, -100.302106), mapTypeId: google.maps.MapTypeId.ROADMAP,  panControlOptions: { position: google.maps.ControlPosition.TOP_RIGHT
    }, zoomControlOptions: {
        style: google.maps.ZoomControlStyle.LARGE,
        position: google.maps.ControlPosition.TOP_RIGHT
    },};
      t.map = new google.maps.Map(document.getElementById(t.div),mapOptions);
	  t.get_units();
	  //setInterval(function(){ t.get_units(); },t.interval);

    }
	
	this.get_groups = function(){}
	

	this.get_units = function(){ 
	  t.clear_markers();
	  units = new Array();
	  
	 // $.post('_json/json.units2.php', { id: $(this).attr("id") }, 
	   // function(data){ 
		 // for(var i=0;i<data.length;i++){ units[i] =  { id: data[i].id, lat: data[i].lat ,lng: data[i].lon } }
		  //, icon : 'http://chart.apis.google.com/chart?chst=d_map_pin_letter&chld='+data[i].id+'|FF0000|000000'
		  //t.render_markers(units); 
		  //t.draw_route(units);
	  //},"json");
	  
	  //
	  //
	  units[0] = { id : 2, lat: 25.690632, lng: -100.376928 }
	  units[1] = { id : 3, lat: 25.697197, lng: -100.371291 }
	  t.render_markers(units); 
	  
	}
	
	this.render_markers = function(units){
	  for(var i=0;i<units.length;i++){
	    marker = new google.maps.Marker({ position: new google.maps.LatLng(units[i].lat,units[i].lng),map: t.map, id : units[i].id });
		google.maps.event.addListener(marker, "click", function(){ t.render_info(this.id); }); 
		t.markers.push(marker);
	  }	
	}
	
	
	this.clear_markers = function(){
	  for(var i=0;i<t.markers.length;i++){ t.markers[i].setMap(null); }
	  t.markers = [];
	}
	
	this.render_info = function(id){
	  
	  $( "#overlay" ).show().animate({ right: 0}, 1200);
	}
	
	this.go_to = function(lat,lon){
      t.map.setZoom(18);
      t.map.setCenter(new google.maps.LatLng(lat, lon));
	  var unitx = [];
	  unitx[0] =  { id: 1, lat: lat ,lng: lon }
	  t.clear_markers();
	  t.render_markers(unitx); 
	}
	
	this.draw_route = function(points){
      var lat_lng = new Array();
	 	  
      var latlngbounds = new google.maps.LatLngBounds();
      for (i = 0; i < points.length; i++){
        var myLatlng = new google.maps.LatLng(points[i].lat, points[i].lng);
        lat_lng.push(myLatlng);
        var marker = new google.maps.Marker({position: myLatlng, map: t.map,  icon: points[i].icon});
        latlngbounds.extend(marker.position);
      }
      t.map.setCenter(latlngbounds.getCenter());
      t.map.fitBounds(latlngbounds);		
		
	  var path = new google.maps.MVCArray();
	  var service = new google.maps.DirectionsService();
	  var poly = new google.maps.Polyline({ map: t.map, strokeColor: '#fx0000' });
 	  for (var i = 0; i < lat_lng.length; i++) {
 	    if((i + 1) < lat_lng.length){
 	      var src = lat_lng[i];
 	      var des = lat_lng[i + 1];
 	      path.push(src);
 	      poly.setPath(path);
 	      service.route({origin: src,destination: des, travelMode: google.maps.DirectionsTravelMode.DRIVING}, 
		  function (result, status) {
		    if(status == google.maps.DirectionsStatus.OK) {
		      for (var i = 0, len = result.routes[0].overview_path.length; i < len; i++) {
		        path.push(result.routes[0].overview_path[i]);
		      }
		    }
		  });
 	    }
	  }
	}

 
	
	
	
	onLoad();
}


if (typeof(Number.prototype.toRad) === "undefined") {
  Number.prototype.toRad = function() {
    return this * Math.PI / 180;
  }
}






