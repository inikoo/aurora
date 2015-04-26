<?php
/*
@author Raul Perusquia <raul@inikoo.com>
Created: 26 April 2015 09:18:00 BST, Sheffield UK

//Copyright (c) 2015 Inikoo Ltd
*/

include_once '../../conf/dns.php';
include_once '../../class.DB_Table.php';
include_once '../../class.Payment.php';

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


$sql="select `Payment Key`from `Payment Dimension` where `Payment Key`=74862  order by  `Payment Key` desc ";
$sql="select `Payment Key`from `Payment Dimension` order by  `Payment Key` desc ";

$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
	$payment=new Payment($row['Payment Key']);
	$payment->update_balance();
}





?>
