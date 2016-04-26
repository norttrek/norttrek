<?php
require_once("../_class/class.alert.php");
require_once("../_class/class.asset.php");
require_once("../_class/class.mail.php");
require_once("../_class/class.gprs.php");
$objAlert  = new Alert();
$objAsset  = new Asset();
$objMail  = new Mail();
$GPRS = new GPRS();
$GPRS->saveTrack('56',$_POST['i'],'imagefromnode<'+$_POST['i'],'55','hell','100');
$asset = $objAsset->getAssetByIMEI($_POST['i']);
$notify = json_decode($asset[0]['notification'],true);
$data = json_decode($asset[0]['data'],true);
$buffer_html = '
<font face="Arial">
<table width="500" border="0" cellpadding="8" style="border:#e4e4e4 solid 1px;">
  <tr bgcolor="#1b1e25">
    <td height="47" colspan="2"><img src="http://norttrek.com/site/wp-content/uploads/2014/05/logo-1.png" /></td>
  </tr>
  <tr>
    <td>Unidad:</td>
    <td>'.$asset[0]['alias'].'</td>
  </tr>
  <tr>
    <td>Alerta:</td>
    <td>'.$objAlert->getAlert($_POST['c'],$_POST['v']).'</td>
  </tr>
  <tr>
    <td>Fecha y Hora</td>
    <td>'.$objAsset->formatDateTime($_POST['d'],"max").'</td>
  </tr>
  <tr>
    <td>Ubicacion</td>
    <td><a href="https://www.google.com.mx/maps/?q='.$_POST['p'].'">'.$_POST['p'].'</a></td>
  </tr>
</table>
</font>';

$objMail->send($notify[$_POST['t']],$bcc,'Alerta - Norttrek',$buffer_html);



?>