<div   >
<?php 
require_once('PolylineEncoder.php');
if($_GET['type']=="circle"){

$data = explode("|",$_GET['data']);
$MapLat    = $data[0]; 
$MapLng    = $data[1]; 
$MapRadius = $data[2]/1000;         
$MapFill   = '0082a9';   
$MapBorder = '0082a9';    
$MapWidth  = 278;        
$MapHeight = 150;       

$EncString = GMapCircle($MapLat,$MapLng, $MapRadius);
$MapAPI = 'http://maps.google.com.au/maps/api/staticmap?';
$MapURL = $MapAPI.'center='.$MapLat.','.$MapLng.'&size='.$MapWidth.'x'.$MapHeight.'&maptype=roadmap&path=fillcolor:0x'.$MapFill.'70%7Cweight:5%7Ccolor:0x'.$MapBorder.'00%7Cenc:'.$EncString.'&zoom='.(($data[3])-3).'&sensor=true';

echo '<img id="geofence_preview" src="'.$MapURL.'" />';
}else{

$data = explode(":",$_GET['data']);
$MapLat    = $data[0]; 
$MapLng    = $data[1]; 
$MapFill   = '0082a9';   
$MapBorder = '0082a9';    
$MapWidth  = 278;        
$MapHeight = 150;   

$EncString = GMapCircle($MapLat,$MapLng, $MapRadius);
$MapAPI = 'http://maps.google.com.au/maps/api/staticmap?';

 
$MapURL = $MapAPI.'center='.$data[0].'&size='.$MapWidth.'x'.$MapHeight.'&maptype=roadmap&path=fillcolor:0x'.$MapFill.'70%7Cweight:5%7Ccolor:0x'.$MapBorder.'00|'.str_replace(":","|",substr_replace($_GET['data'] ,"",-3)).'&zoom='.($_GET['zoom']-1).'&sensor=true';

echo '<img id="geofence_preview" src="'.$MapURL.'" />';	
	
}




function GMapCircle($Lat,$Lng,$Rad,$Detail=8){
 $R    = 6371;

 $pi   = pi();

 $Lat  = ($Lat * $pi) / 180;
 $Lng  = ($Lng * $pi) / 180;
 $d    = $Rad / $R;

 $points = array();
 $i = 0;

 for($i = 0; $i <= 360; $i+=$Detail):
   $brng = $i * $pi / 180;

   $pLat = asin(sin($Lat)*cos($d) + cos($Lat)*sin($d)*cos($brng));
   $pLng = (($Lng + atan2(sin($brng)*sin($d)*cos($Lat), cos($d)-sin($Lat)*sin($pLat))) * 180) / $pi;
   $pLat = ($pLat * 180) /$pi;

   $points[] = array($pLat,$pLng);
 endfor;

 require_once('PolylineEncoder.php');
 $PolyEnc   = new PolylineEncoder($points);
 $EncString = $PolyEnc->dpEncode();

 return $EncString['Points'];
}
  ?>
  <style>
   .btn_save_gz { padding: 5px;
background-color: #008FD8;
width: 80px;
border-radius: 2px;
text-align: center;
display: inline-block;
color: #FFF;
font-weight: bold;
text-decoration: none;
   }
  </style>
    <form>
     <input type="text" id="txt_geocerca" name="txt_geocerca" placeholder="Nombre de Geocerca" style="width:195px;" />
     <select id="lst_gc_cat" name="lst_gc_cat">
       <option value="zr">Zona de Riesgo</option>
       <option value="zs">Zona Segura</option>
       <option value="cli">Cliente</option>
       <option value="base">Base</option>
     </select>
     <a href="javascript:void(0)" class="btn_save onClickSaveGeo btn_save_gz">GUARDAR</a>
     </form>
  
</div>  
<script type="text/javascript">
 $('.onClickSaveGeo').click(function(e){
      if($("#txt_geocerca").val()==""){ alert("Debe ingresar un nombre para la geocerca."); return false; }
     nombre = objTrack.geofence.name = $("#txt_geocerca").val();
     console.log(nombre)
     objTrack.geofence.category = $("#lst_gc_cat").val();
       objTrack.geofence.preview = $("#geofence_preview").attr("src");
       objClient.add_geofence(objTrack.geofence);
    });
</script>