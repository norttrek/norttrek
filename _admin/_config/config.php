<?php
switch($_SERVER['SERVER_NAME']){
  case "localhost":
  case "dev":
  case "127.0.0.1":
   define("SERVER_HOST","");   
   define('SERVER_USER',"");
   define('SERVER_PASS',"");
   define('SERVER_DB',"");
  break;
  case "norttrek.com":
  case "dev.norttrek.com":
  case "www.norttrek.com":
   define("SERVER_HOST","external-db.s157888.gridserver.com");   
   define('SERVER_USER',"db157888_ntkprod");
   define('SERVER_PASS',"qwerty123");
   define('SERVER_DB',"db157888_ntk");
  break;  
}
?>
