<div id="panel_info" style="width:500px; height:500px;">
  
  
  <?php $data = explode("|",$_GET['data']); ?>
  
  
  
  <?php
 $MapLat    = $data[0]; // latitude for map and circle center
$MapLng    = $data[1]; // longitude as above
$MapRadius = $data[2]/1000;         // the radius of our circle (in Kilometres)
$MapFill   = '0082a9';    // fill colour of our circle
$MapBorder = '0082a9';    // border colour of our circle
$MapWidth  = 430;         // map image width (max 640px)
$MapHeight = 250;         // map image height (max 640px)

/* create our encoded polyline string */
$EncString = GMapCircle($MapLat,$MapLng, $MapRadius);

/* put together the static map URL */
$MapAPI = 'http://maps.google.com.au/maps/api/staticmap?';
$MapURL = $MapAPI.'center='.$MapLat.','.$MapLng.'&size='.$MapWidth.'x'.$MapHeight.'&maptype=roadmap&path=fillcolor:0x'.$MapFill.'70%7Cweight:5%7Ccolor:0x'.$MapBorder.'00%7Cenc:'.$EncString.'&zoom='.($data[3]).'&sensor=true';

/* output an image tag with our map as the source */
echo '<img id="geofence_preview" src="'.$MapURL.'" />';

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
    <form>
     <input type="text" id="txt_geocerca" name="txt_geocerca" placeholder="Ingrese un nombre para la geocerca." />
     <a href="javascript:void(0)" class="btn_save onClickSaveGeo">GUARDAR</a>
     </form>
  
</div>  

<script>
$(document).ready(function(){ 
/*
  $(".onClickSaveGeo").click(function(){ 
    data = new Object();  
    data.name = $("#txt_nombre").val();
    data.lat = <?php echo $data[0]; ?>;
    data.lng = <?php echo $data[1]; ?>;
    data.radius = <?php echo $data[2]; ?>;
    data.zoom = <?php echo $data[3]; ?>;
    objTrack.save_circle(data);
  });
  */
});
</script>