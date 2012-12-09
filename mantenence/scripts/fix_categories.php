<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW
include_once '../../app_files/db/dns.php';
include_once '../../class.Category.php';
include_once '../../class.Part.php';
include_once '../../class.Product.php';
include_once '../../class.Invoice.php';
include_once '../../class.Customer.php';
include_once '../../class.PartLocation.php';
include_once '../../class.Location.php';

include_once '../../class.SupplierProduct.php';
error_reporting(E_ALL);

date_default_timezone_set('UTC');
include_once '../../set_locales.php';
require '../../locale.php';
$_SESSION['locale_info'] = localeconv();

$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );

if (!$con) {print "Error can not connect with database server\n";exit;}

$db=@mysql_select_db($dns_db, $con);
if (!$db) {print "Error can not access the database\n";exit;}


require_once '../../common_functions.php';
mysql_query("SET time_zone ='+0:00'");
mysql_query("SET NAMES 'utf8'");
require_once '../../conf/conf.php';
date_default_timezone_set('UTC');



$sql="select * from `Category Bridge` where `Category Key`=26  ";
$sql="select * from `Category Bridge` order by `Category Key` ";

$counter=0;

$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
$counter++;

print 'Fix CB '.$counter.' '.$row['Category Key']." ".$row['Subject Key']."\r";
	$category=new Category($row['Category Key']);



	if ($category->id) {

		if ($category->data['Category Branch Type']=='Head') {

			$sql=sprintf("update `Category Bridge` set   `Category Head Key`=%d where `Subject`=%s and `Subject Key`=%d and `Category Key`=%d",
				$category->id,
				prepare_mysql($category->data['Category Subject']),
				$row['Subject Key'],$category->id
			);
			mysql_query($sql);
			$subject_key=$row['Subject Key'];

			foreach ($category->get_parent_keys() as $parent_key) {
				$sql=sprintf("insert into `Category Bridge` values (%d,%s,%d,%s,%d)",
					$parent_key,
					prepare_mysql($category->data['Category Subject']),
					$subject_key,
					prepare_mysql($row['Other Note']),
					$category->id
				);
				//print "$sql\n";
				mysql_query($sql);
				
				$sql=sprintf("update `Category Bridge` set `Category Head Key`=%d where `Subject`=%s and `Subject Key`=%d and `Category Key`=%d",
					$category->id,
					prepare_mysql($category->data['Category Subject']),
					$row['Subject Key'],
					$parent_key
				);
				mysql_query($sql);

			}

		}
	}else {
		$sql=sprintf("delete  from `Category Bridge` where `Category Key`=%d",$row['Category Key']);
		mysql_query($sql);
	}

}




$sql="select * from `Category Dimension` where `Category Key`=11567 ";
$sql="select * from `Category Dimension` order by `Category Key` ";
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {

print 'Cat '.$row['Category Key']."\r";

	$category=new Category($row['Category Key']);
	$category->update_branch_tree();
	$category->update_number_of_subjects();


	if ($category->data['Category Branch Type']=='Root') {
		$sql=sprintf("update `Category Dimension` set `Category Root Key`=%d where `Category Key`=%d ",
			$category->id,
			$category->id
		);
		mysql_query($sql);

	}

	foreach ($category->get_parent_keys() as $parent_key) {

		$sql=sprintf("update `Category Dimension` set `Category Root Key`=%d where `Category Key`=%d ",
			$parent_key,
			$category->id
		);
		mysql_query($sql);
		//print "$sql\n";

	}
}




?>
