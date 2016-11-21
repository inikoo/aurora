<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 21 November 2016 at 11:01:50 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'utils/natural_language.php';
require_once 'utils/date_functions.php';

$default_DB_link = @mysql_connect($dns_host, $dns_user, $dns_pwd);
if (!$default_DB_link) {
	print "Error can not connect with database server\n";
}
$db_selected = mysql_select_db($dns_db, $default_DB_link);
if (!$db_selected) {
	print "Error can not access the database\n";
	exit;
}
mysql_set_charset('utf8');
mysql_query("SET time_zone='+0:00'");


require_once 'class.Store.php';
require_once 'class.Category.php';


$editor = array(
	'Author Name'  => '',
	'Author Alias' => '',
	'Author Type'  => '',
	'Author Key'   => '',
	'User Key'     => 0,
	'Date'         => gmdate('Y-m-d H:i:s')
);

$print_est = true;

print date('l jS \of F Y h:i:s A')."\n";


$account=new Account();
$account->update_basket_data();


$sql = sprintf("SELECT `Store Key` FROM `Store Dimension`");
if ($result = $db->query($sql)) {
	foreach ($result as $row) {
		$store = new Store('id', $row['Store Key']);

		$store->load_acc_data();
		$store->update_basket_data();

	}

} else {
	print_r($error_info = $db->errorInfo());
	exit;
}

?>
