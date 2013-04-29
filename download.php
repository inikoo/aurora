<?php
//@author Raul Perusquia <raul@inikoo.com>
//Copyright (c) 2013 Inikoo

if(!isset($_REQUEST['f'])){
exit();
}



$file=$_REQUEST['f'];

if (!$file or !file_exists($file)) 
{
exit();
}

if(preg_match('/\.csv$/i',$file)){
	$type='text/csv';
}else if(preg_match('/\.xls$/i',$file)){
	$type='application/vnd.ms-excel';
}else{
$type='text/txt';
}


header("Content-type:  $type");
header('Content-Length: ' . filesize($file));
header("Content-Disposition: attachment; filename=".basename($file));
header("Pragma: no-cache");
header("Expires: 0");
readfile($file);
unlink($file);
ignore_user_abort(true);
if (connection_aborted()) {
unlink($file);
}
?>