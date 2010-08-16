<?php
date_default_timezone_set('UTC');
$filenames=array();
foreach ($_FILES as $fieldName => $file) {
$filename= date('U').strip_tags(basename($file['name']));
$filenames[]=$filename;
    move_uploaded_file($file['tmp_name'], "./app_files/tmp/" .$filename);
    
}

$response=array('filenames'=>$filenames,'number'=>count($filenames));

 echo json_encode(($response));
?>