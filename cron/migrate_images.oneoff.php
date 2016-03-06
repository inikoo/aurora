<?php

/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 6 March 2016 at 09:45:54 GMT+8, Yiwu, China
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
require_once 'utils/parse_natural_language.php';

require_once 'class.Image.php';
require_once 'class.Store.php';
require_once 'class.Warehouse.php';
require_once 'class.Part.php';

require_once 'class.StoreProduct.php';
include_once 'utils/parse_materials.php';
$editor=array(
	'Author Name'=>'',
	'Author Alias'=>'',
	'Author Type'=>'',
	'Author Key'=>'',
	'User Key'=>0,
	'Date'=>gmdate('Y-m-d H:i:s')
);




$sql=sprintf('select * from `Part Dimension`  ');

if ($result=$db->query($sql)) {
	foreach ($result as $row) {

		set_order($db, 'Part', $row['Part SKU']);



	}

}else {
	print_r($error_info=$db->errorInfo());
	exit;
}


function set_order($db, $object, $object_key) {

	$sql=sprintf("select `Image Subject Key` from `Image Subject Bridge` where `Image Subject Object`=%s and   `Image Subject Object Key`=%d order by `Image Subject Is Principal`,`Image Subject Date`,`Image Subject Key`",
		prepare_mysql($object),
		$object_key
	);
	//print $sql;
	$order=1;
	if ($result=$db->query($sql)) {
		foreach ($result as $row) {

			$sql=sprintf("update `Image Subject Bridge` set `Image Subject Order`=%d where `Image Subject Key`=%d ",
				$order,
				$row['Image Subject Key']
			);
			
			$db->exec($sql);
			$order++;
		}
	}else {
		print_r($error_info=$db->errorInfo()); print "$sql";
		exit;
	}




}


?>
