<?php

/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 15 February 2016 at 10:45:44 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once 'common.php';


$default_DB_link=@mysql_connect($dns_host, $dns_user, $dns_pwd );
if (!$default_DB_link) {
	print "Error can not connect with database server\n";
}
$db_selected=mysql_select_db($dns_db, $default_DB_link);
if (!$db_selected) {
	print "Error can not access the database\n";
	exit;
}
mysql_set_charset('utf8');
mysql_query("SET time_zone='+0:00'");



require_once 'utils/get_addressing.php';

require_once 'class.Customer.php';
require_once 'class.Store.php';
require_once 'class.Warehouse.php';
require_once 'class.Part.php';

require_once 'class.StoreProduct.php';

$editor=array(
	'Author Name'=>'',
	'Author Alias'=>'',
	'Author Type'=>'',
	'Author Key'=>'',
	'User Key'=>0,
	'Date'=>gmdate('Y-m-d H:i:s')
);



$sql=sprintf('select * from `Part Dimension` where `Part SKU`=1443');

$sql=sprintf('select * from `Part Dimension` ');

if ($result=$db->query($sql)) {
	foreach ($result as $row) {
    $part=new Part($row['Part SKU']);
		if (!($row['Part Barcode Data Source']=='Other' and $row['Part Barcode Data']=='')) {

			$barcode_data=array(
				'type'=>$row['Part Barcode Type'],
				'source'=>$row['Part Barcode Data Source'],
				'data'=>$row['Part Barcode Data']);
				
				
		}
		$part->update(array('Part Barcode'=>json_encode($barcode_data)),'no_history');

        


	}

}else {
	print_r($error_info=$db->errorInfo());
	exit;
}



?>
