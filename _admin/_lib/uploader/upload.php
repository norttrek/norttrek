<?php

// set error reporting level
if (version_compare(phpversion(), '5.3.0', '>=') == 1)
  error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
else
  error_reporting(E_ALL & ~E_NOTICE);

function bytesToSize1024($bytes, $precision = 2) {
    $unit = array('B','KB','MB');
    return @round($bytes / pow(1024, ($i = floor(log($bytes, 1024)))), $precision).' '.$unit[$i];
}

if (isset($_FILES['myfile'])) {
	if (!file_exists($_SERVER['DOCUMENT_ROOT']."/tmp/")){ mkdir($_SERVER['DOCUMENT_ROOT']."/tmp/", 0777); }
    $sFileName = $_FILES['myfile']['name'];
    $sFileType = $_FILES['myfile']['type'];
    $sFileSize = bytesToSize1024($_FILES['myfile']['size'], 1);
	move_uploaded_file($_FILES['myfile']['tmp_name'],$_SERVER['DOCUMENT_ROOT']."/juanjo/tmp/".$_FILES['myfile']['name']);

    echo <<<EOF
<div class="s">
    <p>Your file: {$sFileName} has been successfully received.</p>
    <p>Type: {$sFileType}</p>
    <p>Size: {$sFileSize}</p>
</div>
EOF;
} else {
    echo '<div class="f">An error occurred</div>';
}