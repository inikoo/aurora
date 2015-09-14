<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW
include_once '../../conf/dns.php';
include_once '../../class.Address.php';
include_once '../../class.Customer.php';
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


$sql="select  `Customer Key` from `Customer Dimension` order by `Customer Key` desc";

$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {

	$customer=new Customer($row['Customer Key']);
	$address=new Address($customer->data['Customer Main Address Key']);

	$sql=sprintf("update `Customer Dimension` set `Customer Main Location`=%s where `Customer Key`=%d",
		prepare_mysql($address->display('location')),

		$customer->id
	);
	mysql_query($sql);
    //print "$sql\n";

}


?>
