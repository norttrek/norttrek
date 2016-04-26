<?php 
session_start(); 
echo "Reportes"; 
require_once("../../_class/class.client.php"); 
 
$objClient = new Client();
 
$imei = $_GET['imei'];
$de = $_GET['de'];
$a = $_GET['a'];
 
if($imei != ''){
$reports = $objClient->getReports($imei,$de,$a);
 
}else{
  echo "ingresa imei";
}
 ?>
 Unidad: <?php echo $reports[0][0]['alias'] ?><br>
<table border="1">
 <tr>
    <td>Id</td>
    <td>Imei</td>
    <td>status</td>
    <td>date</td>
    <td>v2_longitude</td>
    <td>v2_altitude</td>
    <td>v2_speed</td>
    <td>v2_volt</td>
    <td>v2_fuel1</td>
    <td>v2_fuel2</td>
    <td>v2_fuel3</td>
    <td>v2_battery</td>
    <td>v2_temp1</td>
    <td>v2_temp2</td>
    <td>v2_eng_status</td>
    <td>v2_power_status</td>
    <td>Event Index</td>
    <td>Event Code</td>
    <td>v2_INPUTS</td>
    <td>v2_serviceType</td>
    <td>v2_messageType</td>
    <td>v2_updateTime</td>
    <td>v2_fixTime</td>
    <td>v2_latitude</td>
    <td>v2_longitude</td>
    <td>v2_altitude</td>
    <td>v2_speed</td>
    <td>v2_heading</td>
    <td>v2_sat</td>
    <td>v2_RSSI</td>
    <td>v2_HDOP</td>
    <td>v2_eventIndex</td>
    <td>v2_eventCode</td>
    <td>v2_accumAccount</td>
    <td>v2_DI_2</td>
    <td>v2_DI_3</td>
    <td>v2_DI_4</td>
    <td>v2_DI_5</td>
    <td>v2_DI_6</td>
    <td>v2_DI_7</td>
    <td>v2_DO_3</td>
    <td>v2_DO_4</td>
    <td>v2_DO_5</td>
    <td>v2_DO_6</td>
    <td>v2_DO_7</td>
    <td>v2_EVENTOS</td>
    <td>v2_limit_vel_exc</td> 
    <td>v2_eng_block</td> 
    <td>v2_eng_status</td> 
    <td>v2_starter_block</td> 

 </tr>

 <?php
foreach ($reports[1] as $key => $value) {
 
  echo "<tr>";
  
      ?>
      <td><?php echo $value['id'] ?></td>
    <td><?php echo $value['imei'] ?></td>
    <td><?php echo $value['status'] ?></td>
    <td><?php echo $value['date'] ?></td>
    <td><?php echo $value['v2_longitude'] ?></td>
    <td><?php echo $value['v2_altitude'] ?></td>
    <td><?php echo $value['v2_speed'] ?></td>
    <td><?php echo $value['v2_volt'] ?></td>
    <td><?php echo $value['v2_fuel1'] ?></td>
    <td><?php echo $value['v2_fuel2'] ?></td>
    <td><?php echo $value['v2_fuel3'] ?></td>
    <td><?php echo $value['v2_battery'] ?></td>
    <td><?php echo $value['v2_temp1'] ?></td>
    <td><?php echo $value['v2_temp2'] ?></td>
    <td><?php echo $value['v2_eng_status'] ?></td>
    <td><?php echo $value['v2_power_status'] ?></td>
    <td><?php echo $value['v2_eventIndex'] ?></td>
    <td><?php echo $value['v2_eventCode'] ?></td>
    <td><?php echo $value['v2_INPUTS'] ?></td>
    <td><?php echo $value['v2_serviceType'] ?></td>
    <td><?php echo $value['v2_messageType'] ?></td>
    <td><?php echo $value['v2_updateTime'] ?></td>
    <td><?php echo $value['v2_fixTime'] ?></td>
    <td><?php echo $value['v2_latitude'] ?></td>
    <td><?php echo $value['v2_longitude'] ?></td>
    <td><?php echo $value['v2_altitude'] ?></td>
    <td><?php echo $value['v2_speed'] ?></td>
    <td><?php echo $value['v2_heading'] ?></td>
    <td><?php echo $value['v2_sat'] ?></td>
    <td><?php echo $value['v2_RSSI'] ?></td>
    <td><?php echo $value['v2_HDOP'] ?></td>
    <td><?php echo $value['v2_eventIndex'] ?></td>
    <td><?php echo $value['v2_eventCode'] ?></td>
    <td><?php echo $value['v2_accumAccount'] ?></td>
    <td><?php echo $value['v2_DI_2'] ?></td>
    <td><?php echo $value['v2_DI_3'] ?></td>
    <td><?php echo $value['v2_DI_4'] ?></td>
    <td><?php echo $value['v2_DI_5'] ?></td>
    <td><?php echo $value['v2_DI_6'] ?></td>
    <td><?php echo $value['v2_DI_7'] ?></td>
    <td><?php echo $value['v2_DO_3'] ?></td>
    <td><?php echo $value['v2_DO_4'] ?></td>
    <td><?php echo $value['v2_DO_5'] ?></td>
    <td><?php echo $value['v2_DO_6'] ?></td>
    <td><?php echo $value['v2_DO_7'] ?></td>
    <td><?php echo $value['v2_EVENTOS'] ?></td>
    <td><?php echo $value['v2_limit_vel_exc'] ?></td>
    <td><?php echo $value['v2_eng_block'] ?></td>
    <td><?php echo $value['v2_eng_status'] ?></td>
    <td><?php echo $value['v2_starter_block'] ?></td> 


      <?php
 
    echo "</tr>";
}
?> 