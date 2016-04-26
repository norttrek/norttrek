<?php
class FileHandler{
  var $path;
  
  public function __construct(){  }
  
  public function setPath($val = NULL){ $this->path = $val; }
  
  public function dir(){
    $fileCollection = NULL;
	//echo $_SERVER["DOCUMENT_ROOT"].$this->path;
	if(file_exists($_SERVER["DOCUMENT_ROOT"].$this->path)){
		
      if ($handle = opendir($_SERVER["DOCUMENT_ROOT"].$this->path)) {
       while (false !== ($file = readdir($handle))) {
	    if($file != ".." && $file != "." && $file != ".DS_Store"){ $fileCollection[] = $file; }
       }
       closedir($handle);
      }
	  return $fileCollection;
	}
  }

}

?>