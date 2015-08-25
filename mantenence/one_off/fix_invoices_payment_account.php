<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW
include_once '../../conf/dns.php';

include_once '../../class.Invoice.php';

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




$sql="select `Invoice Key` from `Invoice Dimension` order by `Invoice Key` desc";

$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
	$invoice=new Invoice($row['Invoice Key']);


	list($a,$b,$c)=$invoice->get_main_payment_method();

	$sql=sprintf("update `Invoice Dimension`  set `Invoice Main Payment Method`=%s ,`Invoice Payment Account Key`=%s,`Invoice Payment Account Code`=%s  where `Invoice Key`=%d",
		prepare_mysql($a),
		prepare_mysql($b),
		prepare_mysql($c),
		$invoice->id);
	mysql_query( $sql );
    //print "$sql\n";
}


?>
