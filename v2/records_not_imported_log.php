<?php
/*

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2011, Inikoo

 Version 2.0
*/

include_once('common.php');
include_once('class.ImportedRecords.php');

if(!isset($_REQUEST['id']) or !is_numeric($_REQUEST['id'])){
    exit();
}

$imported_records=new ImportedRecords($_REQUEST['id']);

header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=\"out.csv\"");

$out = fopen('php://output', 'w');
print $imported_records->data['Imported Records Log'];
fclose($out);



?>