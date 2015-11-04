<?php

/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 31 October 2015 at 19:48:53 CET, Milan-Venice (train), Italy
 Copyright (c) 2015, Inikoo

 Version 3

*/

chdir("../../");
require 'conf/dns.php';
require 'conf/key.php';

require 'common_functions.php';
require "class.DB_Table.php";

$db = new PDO("mysql:host=$dns_host;dbname=$dns_db;charset=utf8", $dns_user, $dns_pwd , array(\PDO::MYSQL_ATTR_INIT_COMMAND =>"SET time_zone = '+0:00';"));
$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);


require_once "class.Payment_Account.php";

$sql=sprintf("select `Payment Account Key` from `Payment Account Dimension`");
foreach ($db->query($sql) as $row) {
	
	$payment_account=new Payment_Account($row['Payment Account Key']);
	
	$payment_account->update_payments_data();
}


?>
