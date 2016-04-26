<?php
error_reporting(1);
//echo $_SERVER['SERVER_NAME'];
switch($_SERVER['SERVER_NAME']){
  case "localhost":
  case "dev":
  case "127.0.0.1":
  case "local":
   define("SERVER_HOST","dev");   
   define('SERVER_USER',"dev");
   define('SERVER_PASS',"qwerty123");
   define('SERVER_DB',"norttrek");
  break;
  case "local.kafeina.mx":
  case "kafeina.mx":
  case "norttrek.com":
  case "clientes.norttrek.com":
  case "admin.norttrek.com":
  case "www.norttrek.com":
   define("SERVER_HOST","localhost");   
   define('SERVER_USER',"frontend_local");
   define('SERVER_PASS',"ftKnT3CjQy");
   define('SERVER_DB',"norttrek_prod");
  break;  
}
?>
