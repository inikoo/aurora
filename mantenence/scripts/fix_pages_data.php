<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW
include_once '../../conf/dns.php';
include_once '../../class.Department.php';
include_once '../../class.Family.php';
include_once '../../class.Product.php';
include_once '../../class.Supplier.php';
include_once '../../class.Part.php';
include_once '../../class.Site.php';

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

mysql_set_charset('utf8');
require_once '../../conf/conf.php';
date_default_timezone_set('UTC');


$sql="select * from `Page Dimension` where `Page Type`='Store' ";
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {




	$sql=sprintf("insert into `Page Store Data Dimension` (`Page Key`) values (%d)",$row['Page Key']);
	mysql_query($sql);
	continue;
	$page=new Page($row['Page Key']);
	//print $page->data['Page Store Source'];

	$content=$page->get_plain_content();

	//print "$content\n";
	$sql=sprintf("insert into `Page Store Search Dimension` values (%d,%d,%s,%s,%s,%s)",
		$page->id,
		$page->data['Page Site Key'],
		prepare_mysql($page->data['Page URL']),
		prepare_mysql($page->data['Page Store Title']),
		prepare_mysql($page->data['Page Store Description']),
		prepare_mysql($content)
	);
	mysql_query($sql);

	$page->update_store_search();


}


?>
