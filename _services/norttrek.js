/* CALAMP */
var dgram = require("dgram");
var moment = require('moment');
var moment = require('moment-timezone');
var mysql = require('mysql');
var request = require('request');

var Server = function(){ 
  var s = this;
  this.packet;
  this.mysql_conf = { host: 'external-db.s157888.gridserver.com', user: 'db157888_ntk',password: 'qwerty123', database: 'db157888_norttrek' };
  //this.mysql_conf = { host: 'dev', user: 'dev',password: 'qwerty123', database: 'norttrek' };

  this.mysql_link;
  this.listener = dgram.createSocket("udp4");
  this.lport = 6666;
  this.buffer_log;
  this.query;
  this.query_insert;
  
  this.guest;
  this.device;
  this.actions;
  this.alerts;
  this.asset;

  function onLoad(){ 	
	s.load_mysql();
	s.load_listener();
  }
  
  this.load_listener = function(){ 
    s.listener.bind(s.lport);
    s.listener.on("listening", function(){ console.log("[Listener Server is running and waiting for packets on port: "+s.lport+"]"); });
	s.listener.on("message", function(packet,guest){ s.process_dgram(packet,guest); });
  }
  
  this.load_mysql = function(){ s.mysql_up(); }
  
  this.mysql_up = function(){ 
    s.mysql_link = mysql.createConnection(s.mysql_conf); 
    s.mysql_link.connect(function(err){              
      if(err) {                                     
        console.log('[MYSQL-ERROR] - error when connecting to db:', err);
        setTimeout(s.mysql_up, 2000); 
      }                                     
    });                                     
    s.mysql_link.on('error', function(err){ if(err.code === 'PROTOCOL_CONNECTION_LOST'){ s.mysql_up(); } else { throw err; } });
  }
  
  this.process_dgram = function(datagram,guest){	
    console.log('[--- ('+moment().tz("America/Monterrey").format("YYYY-MM-DD HH:mm:ss")+'@'+guest.address+":"+guest.port+') ----]');
	console.log(datagram.toString());
	device = s.get_device(datagram.toString());
	if(!device){ return false; }
	if(device=="LMU700"){ s.packet = s.handler(datagram.toString('hex'),device); }else{ s.packet = s.handler(datagram.toString(),device); }
	s.guest = guest;
	s.insert();
	s.get_asset();
  }
  
  this.handler = function(buffer,device,guest){
    var packet = new Object();
	var subpacket;
    var records = new Array();
	switch(device){
	  case "LMU700":
	    header = parseInt(buffer.substr(2,2))+24;
		subpacket = new Array();
		subpacket[0] = buffer.substr(0,header);
		subpacket[1] = buffer.substr(header);
	  
		packet.optionsByte = parseInt(subpacket[0].substr(0,2),16);
	    packet.MobileIDLength = parseInt(subpacket[0].substr(2,2),16);
		packet.imei = subpacket[0].substr(4,(parseInt(packet.MobileIDLength)*2));
		packet.imei = packet.imei.replace("f","");
		packet.MobileIDLen = parseInt(subpacket[0].substr(((packet.MobileIDLength*2)+4),2),16);
		packet.MobileIDType = parseInt(subpacket[0].substr((packet.MobileIDLength*2+6),2),16);
		packet.ServiceType = parseInt(subpacket[0].substr((packet.MobileIDLength*2+8),2),16);
		packet.MessageType = parseInt(subpacket[0].substr((packet.MobileIDLength*2+10),2),16);
		packet.SequenceNo = parseInt(subpacket[0].substr((packet.MobileIDLength*2+12),4),16);
		
		packet.UpdateTime = parseInt(subpacket[1].substr(0,8),16);
		packet.TimeOfFix = parseInt(subpacket[1].substr(8,8),16);
		packet.datetime = moment(packet.UpdateTime).tz("America/Monterrey").format("YYYY-MM-DD HH:mm:ss");
		packet.lat_hex = subpacket[1].substr(16,8);
		packet.latitude = s.parse_LatLng(packet.lat_hex);
		packet.lng_hex = subpacket[1].substr(24,8);
		packet.longitude = s.parse_LatLng(packet.lng_hex);
		packet.altitude = parseInt(subpacket[1].substr(32,8),16);
		packet.speed = parseInt(subpacket[1].substr(40,8),16);
		packet.heading = parseInt(subpacket[1].substr(48,4),16);
		packet.satellites = parseInt(subpacket[1].substr(52,2),16);
		packet.FixStatus = parseInt(subpacket[1].substr(54,2),16);
		packet.Carrier = parseInt(subpacket[1].substr(56,4),16);
		packet.RSSI = parseInt(subpacket[1].substr(60,4),16);
		packet.CommState = parseInt(subpacket[1].substr(64,2),16);
		packet.HDOP = parseInt(subpacket[1].substr(66,2),16);
		packet.inputs = subpacket[1].substr(68,2);
		packet.iostatus = s.Hex2Bin(packet.inputs);
		if(packet.inputs==67){ packet.iostatus='C800'; }else { packet.iostatus='C000'; }
		packet.UnitStatus = parseInt(subpacket[1].substr(70,2),16);
		packet.EventIndex = parseInt(subpacket[1].substr(72,2),16);
		packet.EventCode = parseInt(subpacket[1].substr(74,2),16);
		packet.AccumCount = parseInt(subpacket[1].substr(76,2),16);
		packet.Spare = parseInt(subpacket[1].substr(78,2),16);
		packet.datetime_server = moment().tz("America/Monterrey").format("YYYY-MM-DD HH:mm:ss");
		
		packet.status = 23;
		
		
		//packet.datetime = moment.utc("20"+packet.timestamp.substr(0,2)+"-"+packet.timestamp.substr(2,2)+"-"+packet.timestamp.substr(4,2)+" "+packet.timestamp.substr(6,2)+ ":"+packet.timestamp.substr(8,2)+":"+packet.timestamp.substr(10,2)).zone("-06:00").format('YYYY-MM-DD HH:mm:ss');
		//
	  break;
	  case "AT03":
	    //C00013811000000000000004A587DF1A0300000004.500000002541.4347N10022.6049W003033F9
	    subpacket = buffer.split("|");
		packet.device = "AT03/AT06";
		packet.header = subpacket[0].substr(0,2);
	    packet.length = parseInt(subpacket[0].substr(2,2));
		packet.imei = subpacket[0].substr(4,18);
		packet.vehicle_status = subpacket[1].substr(0,2);	
		packet.status = subpacket[1].substr(0,2);		
		packet.timestamp = subpacket[1].substr(2,12);
		packet.datetime = moment.utc("20"+packet.timestamp.substr(0,2)+"-"+packet.timestamp.substr(2,2)+"-"+packet.timestamp.substr(4,2)+" "+packet.timestamp.substr(6,2)+ ":"+packet.timestamp.substr(8,2)+":"+packet.timestamp.substr(10,2)).zone("-06:00").format('YYYY-MM-DD HH:mm:ss');
		packet.datetime_server = moment().tz("America/Monterrey").format("YYYY-MM-DD HH:mm:ss");
		packet.iostatus = subpacket[1].substr(14,4);
	    packet.voltage = subpacket[1].substr(18,5);
		packet.supply_v = parseFloat(packet.voltage.substr(3,2));
		packet.battery_v = parseFloat(packet.voltage.substr(1,2)/10);
  		packet.adc = subpacket[1].substr(23,8);
	    packet.temperature = subpacket[1].substr(31,6);
		packet.lacci = subpacket[1].substr(37,8);
		packet.gps_status = subpacket[1].substr(45,1);
		packet.gps_satellite = subpacket[1].substr(46,2);
		packet.angle = subpacket[1].substr(48,3);
		packet.speed = subpacket[1].substr(51,3);
		packet.pdop = subpacket[1].substr(54,4);
		packet.mile = subpacket[1].substr(58,7);
		packet.lat = subpacket[1].substr(65,9);
		packet.latitude = parseInt(packet.lat.substr(0,2))+(packet.lat.substr(2)/60);
		packet.ns = subpacket[1].substr(74,1);
		packet.lng = subpacket[1].substr(75,10);
		packet.longitude = (parseInt(packet.lng.substr(0,3))+(parseFloat(packet.lng.substr(3)/60)))*-1;
		packet.ew = subpacket[1].substr(85,1);
		packet.serial_no = subpacket[1].substr(86,4);
		packet.checksum = subpacket[1].substr(90,4);
		packet.buffer = buffer; 
		packet.length_verify = packet.buffer.length;
		packet.checksum_verify = null;
		console.log(packet);
		
		/* INSERT QUERY */
		s.query_insert = 'INSERT INTO gprs (imei,status,date,ada_v,temp,lacci,voltage,battery_v,supply_v,mile,lat,lng,packet,iostatus,gps_status,gps_satellite,gps_angle,gps_speed,pdop,ns,ew,date_server) VALUES';
		s.query_insert += ' (';
		s.query_insert += '"'+packet.imei+'",';
		s.query_insert += '"'+packet.status+'",';
		s.query_insert += '"'+packet.datetime+'",';
		s.query_insert += '"'+packet.adc+'",';
		s.query_insert += '"'+packet.temperature+'",';
		s.query_insert += '"'+packet.lacci+'",';
		s.query_insert += '"'+packet.voltage+'",';
		s.query_insert += '"'+packet.battery_v+'",';
		s.query_insert += '"'+packet.supply_v+'",';
		s.query_insert += '"'+packet.mile+'",';
		s.query_insert += '"'+packet.latitude+'",';
		s.query_insert += '"'+packet.longitude+'",';
		s.query_insert += '"'+packet.buffer+'",';
		s.query_insert += '"'+packet.iostatus+'",';
		s.query_insert += '"'+packet.gps_status+'",';
		s.query_insert += '"'+packet.gps_satellite+'",';
		s.query_insert += '"'+packet.angle+'",';
		s.query_insert += '"'+packet.speed+'",';
		s.query_insert += '"'+packet.pdop+'",';
		s.query_insert += '"'+packet.ns+'",';
		s.query_insert += '"'+packet.ew+'",';
		s.query_insert += '"'+packet.datetime_server+'"';
		s.query_insert += ') ON DUPLICATE KEY UPDATE date_server="'+packet.datetime_server+'"';
	  break;
	  case "AT07":
	    subpacket = buffer.split("|");
	    packet.device = "AT07";
		packet.header = subpacket[0].substr(0,2);
	    packet.length = parseInt(subpacket[0].substr(2,4));
		packet.pckg_flag = subpacket[0].substr(6,2);
		packet.imei = subpacket[0].substr(8,20);
	    
		packet.status = subpacket[1].substr(0,8);
		packet.status = 23;
		
		packet.timestamp = subpacket[1].substr(8,12);
		packet.datetime = moment.utc("20"+packet.timestamp.substr(0,2)+"-"+packet.timestamp.substr(2,2)+"-"+packet.timestamp.substr(4,2)+" "+packet.timestamp.substr(6,2)+ ":"+packet.timestamp.substr(8,2)+":"+packet.timestamp.substr(10,2)).zone("-06:00").format('YYYY-MM-DD HH:mm:ss');
		packet.datetime_server = moment().tz("America/Monterrey").format("YYYY-MM-DD HH:mm:ss");
	    packet.battery_voltage = subpacket[1].substr(20,2)/10;
	    packet.supply_voltage = subpacket[1].substr(22,2);
		packet.adc_1 = subpacket[1].substr(24,4);
		packet.temp_1 = 0;
		packet.ada_v = packet.adc_1;
		packet.lacci = subpacket[1].substr(28,4);
		packet.cell_id = subpacket[1].substr(32,4);
		packet.gps_sat = subpacket[1].substr(36,2);
		packet.gsm_signal = subpacket[1].substr(38,2);
		packet.angle = subpacket[1].substr(40,3);
		packet.speed = subpacket[1].substr(43,3);
		packet.hdop = subpacket[1].substr(46,4);
		packet.mileage = subpacket[1].substr(50,7);
		packet.ns = subpacket[1].substr(66,1);
		packet.latitude = subpacket[1].substr(57,9);
		packet.lat = parseInt(packet.latitude.substr(0,2))+(packet.latitude.substr(2)/60);
		packet.longitude = subpacket[1].substr(67,10);
		packet.lng = (parseInt(packet.longitude.substr(0,3))+(parseFloat(packet.longitude.substr(3)/60)))*-1;
		packet.lat_lng = packet.lat+","+packet.lng;
		packet.ew = subpacket[1].substr(77,1);
		packet.serial_no = subpacket[1].substr(78,4);
		packet.check_sum = subpacket[1].substr(82,2);
		packet.buffer = buffer.replace("\n",""); 
		packet.length_verify = packet.buffer.length;
		packet.checksum_verify = null;
		
		console.log(packet);
		
		s.query_insert = 'INSERT INTO gprs (imei,status,date,iostatus,voltage,battery_v,supply_v,ada_v,fuel,temp,lacci,lat,lng,packet,gps_angle,gps_speed,gps_status,gps_satellite,mile,ns,ew,date_server) VALUES';
		s.query_insert += ' (';
		s.query_insert += '"'+packet.imei+'",';
		s.query_insert += '"'+packet.status+'",';
		s.query_insert += '"'+packet.datetime+'",';
		s.query_insert += '"'+packet.iostatus+'",';
		s.query_insert += '"'+packet.voltage+'",';
		s.query_insert += '"'+packet.battery_voltage+'",';
		s.query_insert += '"'+packet.supply_voltage+'",';
		s.query_insert += '"'+packet.ada_v+'",';
		s.query_insert += '"'+packet.fuel+'",';
		s.query_insert += '"'+packet.temp+'",';
		s.query_insert += '"'+packet.lacci+'",';
		s.query_insert += '"'+packet.lat+'",';
		s.query_insert += '"'+packet.lng+'",';
		s.query_insert += '"'+packet.buffer+'",';
		s.query_insert += '"'+packet.angle+'",';
		s.query_insert += '"'+packet.speed+'",';
		s.query_insert += '"'+packet.gps_status+'",';
		s.query_insert += '"'+packet.gps_satellites+'",';
		s.query_insert += '"'+packet.mileage+'",';
		s.query_insert += '"'+packet.ns+'",';
		s.query_insert += '"'+packet.ew+'",';
		s.query_insert += '"'+packet.datetime_server+'"';
		s.query_insert += ') ON DUPLICATE KEY UPDATE date_server="'+packet.datetime_server+'"';
		
		
	  break;
	  case "AT09":
	    subpacket = buffer.split("|");
		packet.device = "AT09";
		packet.header = subpacket[0].substr(0,2);
	    packet.length = parseInt(subpacket[0].substr(2,4));
		packet.datatype = subpacket[0].substr(6,2);
		packet.imei = subpacket[0].substr(8,20);
	    
		packet.vehicle_status = subpacket[1].substr(0,8);
		
		packet.timestamp = subpacket[1].substr(8,12);
		packet.datetime = moment.utc("20"+packet.timestamp.substr(0,2)+"-"+packet.timestamp.substr(2,2)+"-"+packet.timestamp.substr(4,2)+" "+packet.timestamp.substr(6,2)+ ":"+packet.timestamp.substr(8,2)+":"+packet.timestamp.substr(10,2)).zone("-06:00").format('YYYY-MM-DD HH:mm:ss');
		packet.datetime_server = moment().tz("America/Monterrey").format("YYYY-MM-DD HH:mm:ss");
	    packet.battery_voltage = subpacket[1].substr(20,2);
	    packet.supply_voltage = subpacket[1].substr(22,2);
	    packet.adc_1 = subpacket[1].substr(24,4);
		packet.adc_2 = subpacket[1].substr(28,4);
		packet.adc_3 = subpacket[1].substr(32,4);
		packet.adc_4 = subpacket[1].substr(36,4);
		packet.ada_v = packet.adc_1+""+packet.adc_2;
		packet.fuel = packet.adc_3;
		packet.temp_a = subpacket[1].substr(40,4);
		packet.temp_b = subpacket[1].substr(44,4);
		packet.temp = packet.temp_a+""+packet.temp_b;
		packet.lacci = subpacket[1].substr(48,4);
		packet.cell_id = subpacket[1].substr(52,4);
		packet.gps_satellites = subpacket[1].substr(56,2);
		packet.gsm_signal = subpacket[1].substr(58,2);
		packet.gps_status = 'A';
		packet.angle = subpacket[1].substr(60,3);
		packet.speed = subpacket[1].substr(63,3);
		packet.hdop = subpacket[1].substr(66,4);
		packet.mileage = subpacket[1].substr(70,7);
		
		packet.latitude = subpacket[1].substr(77,9);
		packet.lat = parseInt(packet.latitude.substr(0,2))+(packet.latitude.substr(2)/60);
		
		packet.ns = subpacket[1].substr(86,1);
		packet.longitude = subpacket[1].substr(87,10);
		packet.lng = (parseInt(packet.longitude.substr(0,3))+(parseFloat(packet.longitude.substr(3)/60)))*-1;
		packet.lat_lng = packet.lat+","+packet.lng;
		
		packet.ew = subpacket[1].substr(97,1);
		packet.serial_no = subpacket[1].substr(98,4);
		packet.check_sum = subpacket[1].substr(102,2);
		packet.buffer = buffer.replace("\n",""); 
		packet.length_verify = packet.buffer.length;
		packet.checksum_verify = null;
		
		s.query_insert = 'INSERT INTO gprs (imei,status,date,iostatus,voltage,battery_v,supply_v,ada_v,fuel,temp,lacci,lat,lng,packet,gps_angle,gps_speed,gps_status,gps_satellite,mile,ns,ew,date_server) VALUES';
		s.query_insert += ' (';
		s.query_insert += '"'+packet.imei+'",';
		s.query_insert += '"'+packet.status+'",';
		s.query_insert += '"'+packet.datetime+'",';
		s.query_insert += '"'+packet.iostatus+'",';
		s.query_insert += '"'+packet.voltage+'",';
		s.query_insert += '"'+packet.battery_voltage+'",';
		s.query_insert += '"'+packet.supply_voltage+'",';
		s.query_insert += '"'+packet.ada_v+'",';
		s.query_insert += '"'+packet.fuel+'",';
		s.query_insert += '"'+packet.temp+'",';
		s.query_insert += '"'+packet.lacci+'",';
		s.query_insert += '"'+packet.lat+'",';
		s.query_insert += '"'+packet.lng+'",';
		s.query_insert += '"'+packet.buffer+'",';
		s.query_insert += '"'+packet.angle+'",';
		s.query_insert += '"'+packet.speed+'",';
		s.query_insert += '"'+packet.gps_status+'",';
		s.query_insert += '"'+packet.gps_satellites+'",';
		s.query_insert += '"'+packet.mileage+'",';
		s.query_insert += '"'+packet.ns+'",';
		s.query_insert += '"'+packet.ew+'",';
		s.query_insert += '"'+packet.datetime_server+'"';
		s.query_insert += ') ON DUPLICATE KEY UPDATE date_server="'+packet.datetime_server+'"';
		
		console.log(packet);
	  break;
	}
	return packet;
	  
  }
  
  /* DEVICE */
   this.get_device= function(str){ 
    s.device = null;
	switch(str.substr(0,2)){
	  case "$$":
	    if(str.substr(2,2)=="72"){ s.device = "AT03"; }
		if(str.substr(2,4)=="0108"){ s.device = "AT07"; }
		if(str.substr(2,4)=="0128"){ s.device = "AT09"; } 
	  break;
	  case "83": s.device = "LMU700"; break;
	}
	return s.device;
  }
  
  /* PROCESS ASSET */
  
 
  this.get_asset = function(){ s.mysql_link.query(s.stored_queries('get_asset'),s.process_asset); }
  
  this.process_asset = function(e,r,f){ 
    if(e){ throw err;  }
	if(r.length>0){ 
	  this.asset = r[0]; 
	  s.mysql_link.query(s.stored_queries('get_actions'),s.process_actions);
	  s.mysql_link.query(s.stored_queries('get_alerts'),s.process_alerts);
	  s.mysql_link.query(s.stored_queries('get_geofences'),s.process_geofences);
	}
  }
  
  this.process_actions = function(e,r,f){ 
    if(e){ throw err; }
	if(r.length>0){ for(var i=0;i<r.length;i++){ s.exec_action(r[i].id,r[i].cmd); } }
  }
  
  this.exec_action = function(id,action){ 
    s.listener.send(new Buffer(action,encoding='utf8'), 0, action.length, s.guest.port, s.guest.address, function(err, bytes){
	s.update_action(id);
	console.log('-> ACTION ('+action+') sent to ' + s.guest.address +':'+ s.guest.port);
    });
  }
  
  this.process_alerts = function(e,r,f){ 
    if(e){ throw err; }
	if(r.length>0){ 
	  for(var i=0;i<r.length;i++){ 
	    switch(r[i].code){
		  case "0x5":
		    if(s.packet.status=="01"){ s.exec_alert("0x5","Panico"); }
		  break;
		  case "0xA":
		    if(s.packet.status=="03"){ s.exec_alert("0xA","Motor Apagado"); }
		  break;
		  case "0xF":
		    if(s.packet.status=="02"){ s.exec_alert("0xF"); }
		  break;
		  case "0x14":
		   //bateria baja
		    if(s.packet.status=="40"){ s.exec_alert("0x14"); }
		  break;
		  case "0x19":
		   //gps antena conectada
		    if(s.packet.status=="15"){ s.exec_alert("0x19"); }
		  break;
		  case "0x1E":
		   //antena desconectada
		    if(s.packet.status=="14"){ s.exec_alert("0x1E"); }
		  break;
		   case "0x23":
		   //movimiento
		    if(s.packet.status=="30"){ s.exec_alert("0x23"); }
		  break;
		   case "0x28":
		   // sin movimiento IDLE
		    if(s.packet.status=="39"){ s.exec_alert("0x28"); }
		  break;
		   case "0x55":
		   // velocidad
		    s.exec_alert("0x55","VELOCIDAD"); 
		  break;
		}
	    
	    
	  } 
	}
  }
  
  this.exec_alert = function(c,s,m){ 
	//s.insert_alert(code,s.packet.imei);
	
	console.log('--> ALERT ('+code+') '+s.packet.imei);
	
	request.post({
  	url: 'http://dev.norttrek.com/_services/srv.alert.php',
  	form:    { c: c, l: s }
	}, function(error, response, body){
  		console.log(body);
	});
  }
  
  
  /* DATABASE */
  this.insert = function(){ s.mysql_link.query(s.query_insert, function(err, rows, fields){ if(err){ throw err; } }); }
  this.update_action = function(id){  s.mysql_link.query("UPDATE client_asset_cmd SET status='1',date_recv = '"+moment().tz("America/Monterrey").format("YYYY-MM-DD HH:mm:ss")+"' WHERE id='"+id+"'", function(err, rows, fields){ if(err){ throw err; } }); }
  
  
  
  /* HELPERS */
 
	
  this.parse_LatLng = function(v){ d = parseInt(v,16); return (d < parseInt('7FFFFFFF', 16)) ? (d /  10000000) : 0 - ((parseInt('FFFFFFFF', 16) - d) / 10000000); }
  this.checkHex = function(n){ return/^[0-9A-Fa-f]{1,64}$/.test(n)}
  this.Hex2Bin = function(n){if(!s.checkHex(n))return 0;return parseInt(n,16).toString(2)}
  
  this.translate = function(code,device){ }
  
  
  

  this.str_reverse = function(str){  return str.split('').reverse().join('');  }
  
  /* ACTS */
  
  this.get_act = function(guest){ 
    s.mysql_link.query('SELECT * FROM client_asset_cmd WHERE imei = "'+buffer["imei"]+'" AND status = 0', function(err, rows, fields){ 
	  if(err){ throw err; } 
	  if(r.length>0){
	    for(var i=0;i<r.length;i++){	    
	      var cmd_packet = new Buffer(r[0].cmd,encoding='utf8');
		  s.send_act(guest,act);
		  server.send(cmd_packet, 0, cmd_packet.length, client.port, client.address, function(err, bytes) {
 	      if(err)throw err;
	      });
	    }
  	  }
	});
  }
  
  
  
  
  
  this.process_geofences = function(err,r,f){ 
    if(err){ throw err };
	if(r.length>0){
	  for(var i=0;i<r.length;i++){
	    switch(r[i].type){
	      case "circle":
		    if(s.inside_geofence(JSON.parse(r[i].data),s.packet.latitude,s.packet.longitude)){
			  if(r[i].gf_enter==1 && r[i].evt!="i"){ 
			    console.log("notificacion de que esta adentro!");
				//insert_asset_alarm("geofence-inside",buffer);
			  }
			  //update_asset_geofence(r[i].id,"i");
			}else{
			  if(r[i].gf_exit==1 && r[i].evt!="o"){ 
			    console.log("notificacion de que esta fuera!");	
				//insert_asset_alarm("device",buffer); 
			  }
			  //update_asset_geofence(r[i].id,"o");
			}
		  break;
		  case "polygon":
		  break;
	    }
	    
	  }
	
	}
  }
 
 
 /* QUERIES */
 this.stored_queries = function(o){
	query = '';
    switch(o){
	 case "get_asset": query = 'SELECT * FROM client_asset WHERE imei = "'+s.packet.imei+'" LIMIT 1'; break;
	 case "get_actions": query = 'SELECT * FROM client_asset_cmd WHERE imei = "'+s.packet.imei+'" AND status = 0'; break;
	 case "get_alerts": query = 'SELECT * FROM client_asset_alarm WHERE imei = "'+s.packet.imei+'"'; break;
	 case "get_geofences": query = 'SELECT cag.*, name, type,data FROM client_asset_geofence cag INNER JOIN client_geofence cg ON (cag.id_geofence = cg.id) WHERE cag.imei = "'+s.packet.imei+'" '; break;
	}
	return query;
  }
 
  
  
  
  this.exec_alerts = function(){
	var buffer_alerts = '';
	for(var i=0;i<r.length;i++){
	  var buffer = new Object();
	  buffer.code = '';
	  buffer.value ='';
	  buffer.type = '';
      switch(flag){
		case "0x5": if(s.packet.speed>=r[0].value){ buffer.code = '0x5'; buffer.type = 'general'; buffer.value = r[0].value; } break;
	    case "0x55": if(s.packet.speed>=r[0].value){ buffer.code = '0x55'; buffer.type = 'param'; buffer.value = r[0].value; } break;
	  }
	  buffer_alerts += "("+r[0].imei+",'+buffer.type+','0x55','{ }',0,"+r[0].datetime+"),";
	  
	}
	if(buffer_alerts.slice(-1)==","){ buffer_alerts.slice(0,-1); }
	s.mysql_insert('INSERT INTO client_alarm (imei,type,code,value,data,flag,datetime) VALUES '+buffer_alerts);
  }
  
  this.upddate_act = function(){ }
  
  
  
  this.distance = function(lat1,lon1,lat2,lon2){
    var R = 1371; // km 
    var x1 = parseFloat(lat2-lat1);
    var dLat = s.toRad(x1);  
    var x2 = parseFloat(lon2-lon1);
    var dLon = s.toRad(x2);  
    var a = Math.sin(dLat/2) * Math.sin(dLat/2) + Math.cos(s.toRad(lat1)) * Math.cos(s.toRad(lat2)) * Math.sin(dLon/2) * Math.sin(dLon/2);  
    var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a)); 
    var d = parseInt((R * c)*1000); 
    return d;
  }
  
  
  this.inside_geofence = function(circle,lat,lng){
    var d = s.distance(parseFloat(circle.lat),parseFloat(circle.lng),lat,lng);
    if(d<=circle.radius){ return true; }else{ return false; }
    return 0;
  }
  
  this.toRad = function(val){ return val * Math.PI / 180;  }
  

  
  onLoad();
}

var server = new Server();