<?php

/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 28 September 2016 at 18:23:42 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'class.Product.php';


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

update_sales($db, $print_est);





function update_sales($db, $print_est) {

	$where='where `Product ID`=971';
    $where='';
	//$where='where `Product Code` like "JBB-%"';
	$sql=sprintf("select count(*) as num from `Product Dimension` %s",$where);
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


	$sql=sprintf("select `Product ID` from `Product Dimension` %s order by `Product ID` desc ",$where);


	if ($result=$db->query($sql)) {
		foreach ($result as $row) {
			$product=new Product('id', $row['Product ID']);
			$product->load_acc_data();
			$product->update_previous_quarters_data();

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
