<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2012 Inikoo
include_once '../../app_files/db/dns.php';
include_once '../../class.Department.php';
include_once '../../class.Family.php';
include_once '../../class.Product.php';
include_once '../../class.Supplier.php';
include_once '../../class.Part.php';
include_once '../../class.Category.php';
include_once '../../class.Node.php';




error_reporting(E_ALL);

date_default_timezone_set('UTC');


$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );

if (!$con) {
	print "Error can not connect with database server\n";
	exit;
}
$db=@mysql_select_db($dns_db, $con);
if (!$db) {
	print "Error can not access the database\n";
	exit;
}


require_once '../../common_functions.php';
mysql_query("SET time_zone ='+0:00'");
mysql_query("SET NAMES 'utf8'");
require_once '../../conf/conf.php';

global $myconf;

//'Product','Supplier','Customer','Family','Invoice','Part'

$sql=sprintf("select * from `History Dimension` where `Direct Object`='Category'  ");
$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {
	$category=new Category($row['Direct Object Key']);


	if ($category->id) {

		if ($category->data['Category Subject']=='') {
			print "Category ".$category->id." with error\n";
		}else {

			$sql=sprintf("update `History Dimension` set `Direct Object`='Category %s' where `History Key`=%d",$category->data['Category Subject'],$row['History Key']);
			mysql_query($sql);
		}
	}else {
		$sql=sprintf("delete from `History Dimension`  where `History Key`=%d",$row['History Key']);
		mysql_query($sql);

	}

}

$sql=sprintf("select * from `History Dimension` where `Indirect Object`='Category'  ");
$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {
	$category=new Category($row['Indirect Object Key']);
	if ($category->id) {
		if ($category->data['Category Subject']=='') {
			print "Category ".$category->id." with error\n";
		}else {

			$sql=sprintf("update `History Dimension` set `Indirect Object`='Category %s' where `History Key`=%d",$category->data['Category Subject'],$row['History Key']);
			mysql_query($sql);
		}
	}else {
		$sql=sprintf("delete from `History Dimension`  where `History Key`=%d",$row['History Key']);
		mysql_query($sql);

	}
}

// Part Categories


$sql=sprintf("select * from `History Dimension` where `Direct Object`='Category Part'  ");
$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {
	$category=new Category($row['Direct Object Key']);


	if ($category->id) {

		if ($category->data['Category Subject']=='') {
			print "Category ".$category->id." with error\n";
		}else {

			$sql=sprintf("insert into  `Part Category History Bridge` values (1,%d,%d,'Change')",$category->id,$row['History Key']);
			mysql_query($sql);
		}
	}
}

$sql=sprintf("select * from `History Dimension` where `Indirect Object`='Category Part'  ");
$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {
	$category=new Category($row['Indirect Object Key']);


	if ($category->id) {

		if ($category->data['Category Subject']=='') {
			print "Category ".$category->id." with error\n";
		}else {

			$sql=sprintf("insert into  `Part Category History Bridge` values (1,%d,%d,'Change')",$category->id,$row['History Key']);
			mysql_query($sql);
		}
	}
}


$sql=sprintf("select * from `History Dimension` where `Direct Object`='Category Supplier'  ");
$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {
	$category=new Category($row['Direct Object Key']);


	if ($category->id) {

		if ($category->data['Category Subject']=='') {
			print "Category ".$category->id." with error\n";
		}else {

			$sql=sprintf("insert into  `Supplier Category History Bridge` values (%d,%d,'Change')",$category->id,$row['History Key']);
			mysql_query($sql);
			print "$sql\n";
		}
	}
}

$sql=sprintf("select * from `History Dimension` where `Indirect Object`='Category Supplier'  ");
$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {
	$category=new Category($row['Indirect Object Key']);


	if ($category->id) {

		if ($category->data['Category Subject']=='') {
			print "Category ".$category->id." with error\n";
		}else {

			$sql=sprintf("insert into  `Supplier Category History Bridge` values (%d,%d,'Change')",$category->id,$row['History Key']);
			mysql_query($sql);
		}
	}
}


$sql=sprintf("select * from `History Dimension` where `Direct Object`='Category Customer'  ");
$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {
	$category=new Category($row['Direct Object Key']);


	if ($category->id) {

		if ($category->data['Category Subject']=='') {
			print "Category ".$category->id." with error\n";
		}else {

			$sql=sprintf("insert into  `Customer Category History Bridge` values (%d,%d,%d,'Change')",$category->data['Category Store Key'],$category->id,$row['History Key']);
			mysql_query($sql);
			print "$sql\n";
		}
	}
}

$sql=sprintf("select * from `History Dimension` where `Indirect Object`='Category Customer'  ");
$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {
	$category=new Category($row['Indirect Object Key']);


	if ($category->id) {

		if ($category->data['Category Subject']=='') {
			print "Category ".$category->id." with error\n";
		}else {

			$sql=sprintf("insert into  `Customer Category History Bridge` values (%d,%d,%d,'Change')",$category->data['Category Store Key'],$category->id,$row['History Key']);
			mysql_query($sql);
		}
	}
}



?>
