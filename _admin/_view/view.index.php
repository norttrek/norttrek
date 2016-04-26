<div id="unidad">
  <h1 class="title">Home (Servicios)</h1> 
  <div id="result" style="padding:20px;">
  <table width="50%" class="result" cellpadding="0" cellspacing="0" align="left" style="width:500px;">
  <thead>
    <tr>
      <th width="20" align="left";>No.</th>
      <th width="100" align="left">Servicio</th>
      <th width="100" align="left";> Credito</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>1.</td>
      <td>Nexmo</td>
      <td><strong>$<?php echo number_format(get_nexmo_balance(),2); ?></strong></td>
    </tr>
     <tr>
      <td>2.</td>
      <td>GeoCode Farm</td>
      <td><strong><?php echo get_geocodefarm_balance(); ?></strong></td>
    </tr>
  </tbody>
  
  </table>
  </div>
</div>

<?php

function get_nexmo_balance(){
  $url = 'https://rest.nexmo.com/account/get-balance/16541359/b5b1d20d';
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL,$url);
  curl_setopt($ch, CURLOPT_FAILONERROR,1);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
  curl_setopt($ch, CURLOPT_TIMEOUT, 15);
  $retValue = curl_exec($ch);	
  $data = json_decode($retValue,true);
  curl_close($ch);
  return $data['value'];
}

function get_geocodefarm_balance(){
  $url = 'http://www.geocodefarm.com/api/reverse/json/e609ccad34060a43a59a4351a8d28f1c2588c5e8/0/0';
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL,$url);
  curl_setopt($ch, CURLOPT_FAILONERROR,1);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
  curl_setopt($ch, CURLOPT_TIMEOUT, 15);
  $retValue = curl_exec($ch);	
  $data = json_decode($retValue,true);
  curl_close($ch);
  return $data['geocoding_results']['ACCOUNT']['remaining_queries'];
}



?>
