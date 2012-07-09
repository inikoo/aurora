<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2011 Inikoo
include_once('../../app_files/db/dns.php');
include_once('../../class.Department.php');
include_once('../../class.Family.php');
include_once('../../class.Product.php');
include_once('../../class.Supplier.php');
include_once('../../class.Part.php');
include_once('../../class.Category.php');
include_once('../../class.Node.php');

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

$sql="select `Category Key` from `Category Dimension` where `Category Subject`='Invoice' ";
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
	$category=new Category($row['Category Key']);
	
	$sql=sprintf("insert into `Invoice Category Dimension` (`Invoice Category Key`,`Invoice Category Store Key`) values (%d,%d)",
	$category->id,
	$category->data['Category Store Key']
	);
	mysql_query($sql);
	
		$category->update_invoice_category_up_today_sales();
		$category->update_invoice_category_interval_sales();
		$category->update_invoice_category_last_period_sales();
	$category->update_number_of_subjects();
	$category->update_no_assigned_subjects();
	print "Category ".$category->id."\t\t\n";
}





?>