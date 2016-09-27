<?php

/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 27 September 2016 at 13:29:49 GMT+8, Kuala Lumpur, Malaysia
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


require_once 'class.Product.php';
require_once 'class.Category.php';


$editor=array(
	'Author Name'=>'',
	'Author Alias'=>'',
	'Author Type'=>'',
	'Author Key'=>'',
	'User Key'=>0,
	'Date'=>gmdate('Y-m-d H:i:s')
);

$print_est=true;

print date('l jS \of F Y h:i:s A')."\n";

update_sales($db,$print_est);





function update_sales($db,$print_est) {

	$where='where `Product ID`=259';
	$where='';

	$sql=sprintf("select count(*) as num from `Product Dimension` $where");
	if ($result=$db->query($sql)) {
		if ($row = $result->fetch()) {
			$total=$row['num'];
		}else {
			$total=0;
		}
	}else {
		print_r($error_info=$db->errorInfo());
		exit;
	}

	$lap_time0=date('U');
	$contador=0;


	$sql=sprintf("select `Product ID` from `Product Dimension` $where order by `Product ID` desc ");


	if ($result=$db->query($sql)) {
		foreach ($result as $row) {
			$product=new Product('id', $row['Product ID']);

			$product->update_sales_from_invoices('Last Month');

			$contador++;
			$lap_time1=date('U');

			if ($print_est) {
				print 'P   '.percentage($contador, $total, 3)."  lap time ".sprintf("%.2f", ($lap_time1-$lap_time0)/$contador)." EST  ".sprintf("%.1f", (($lap_time1-$lap_time0)/$contador)*($total-$contador)/3600)  ."h  ($contador/$total) \r";
			}

		}

	}else {
		print_r($error_info=$db->errorInfo());
		exit;
	}



}





?>
