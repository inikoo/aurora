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
require_once 'class.Address.php';
require_once 'class.Product.php';
require_once 'class.Part.php';

$editor=array(
	'Author Name'=>'',
	'Author Alias'=>'',
	'Author Type'=>'',
	'Author Key'=>'',
	'User Key'=>0,
	'Date'=>gmdate('Y-m-d H:i:s')
);



update_fields_from_parts($db);
print "updated fiels from parts\n";
update_web_state($db);

function update_web_state($db) {

	$sql=sprintf('select `Product ID` from `Product Dimension` where `Product Store Key`!=9 order by `Product ID` desc ');


	if ($result=$db->query($sql)) {
		foreach ($result as $row) {
			$product=new Product('id', $row['Product ID']);

			$product->update_part_numbers();
			$product->update_availability($use_fork=false);
			$product->update_cost();
			print $product->id."\r";
		}

	}else {
		print_r($error_info=$db->errorInfo());
		exit;
	}



}


function update_fields_from_parts($db) {

	$sql=sprintf('select `Part SKU` from `Part Dimension` order by `Part SKU` desc ');


	if ($result=$db->query($sql)) {
		foreach ($result as $row) {
			$part=new Part($row['Part SKU']);
			print $part->id."\r";
			$part->updated_linked_products();


		}

	}else {
		print_r($error_info=$db->errorInfo());
		exit;
	}



}




?>
