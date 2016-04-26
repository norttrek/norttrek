<?php
require_once("FileHandler.class.php");
$objFile = new FileHandler();
switch($_POST['command']) {
  case "remove":
    $filename = $_POST['path'].$_POST['filename'];
    echo $filename;
    if(file_exists($filename)){ unlink($filename); } else { echo "Error: Not File Found."; }
  break;
   case "dir":
     $objFile->setPath($_POST['path']);
	 $files = $objFile->dir();
	 echo json_encode($files);
  break;
}
?>