<?php
session_start();
require_once("../../_class/class.asset.php");
$objAsset = new Asset();
$assets = $objAsset->set_id_client($_SESSION['logged']['id_client'])->getAsset();
/* ASSETS TBODY HTML */
 $buffer = NULL;
for($i=0;$i<count($assets);$i++){
  $data = json_decode($assets[$i]['data'],true);
  $buffer .= '<tr>';
  $buffer .= '<td><input type="checkbox" id="chk[]" name="chk[]"  /></td>';
  $buffer .= '<td>'.$data['name'].'</td>';
  $buffer .= '</tr>';
}
?>
<div id="modal" class="small">
  <div class="tabs">
    <ul>
      <li><a href="#" class="onClickTab active" rel="tab_info">Seleccione Unidades</a></li>
    </ul>
    <br class="clear" />
  </div>
  
  
</div>  


