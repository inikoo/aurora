<?php
/*
@author Raul Perusquia <rulovico@gmail.com>
created 30 May 2015 13:04:37 BST Sheffield UK
Copyright (c) 2015 Inikoo Ltd

*/

include_once '../../conf/dns.php';
include_once '../../class.Order.php';
include_once '../../class.Store.php';

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
require_once 'timezone.php';
mysql_set_charset('utf8');
require_once '../../conf/conf.php';
date_default_timezone_set('UTC');

$inikoo_account=new Account(1);

$editor=array(
'Author Name'=>'System Cron',
'Author Alias'=>'System Cron',
'Author Key'=>0
);

$sql="select `Store Key` from `Store Dimension`  ";
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
	$store=new Store($row['Store Key']);
    $store->editor=$editor;
    $store->cancel_old_orders_in_basket();




}




?>
