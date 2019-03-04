<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 1 March 2019 at 17:41:45 GMT+8, Kuala Lumpur Malysia
 Copyright (c) 2017, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'vendor/autoload.php';

require_once 'utils/get_addressing.php';
require_once 'utils/parse_natural_language.php';
require_once 'utils/object_functions.php';
include_once 'class.Billing_To.php';
include_once 'class.Store.php';


$editor = array(
    'Author Name'  => '',
    'Author Alias' => '',
    'Author Type'  => '',
    'Author Key'   => '',
    'User Key'     => 0,
    'Date'         => gmdate('Y-m-d H:i:s')
);

$account = new Account();

$store_key = 7;




$sql = sprintf('update `Order Transaction Fact` set  `Order Transaction Gross Amount`=`Invoice Transaction Gross Amount` , `Order Transaction Total Discount Amount`=`Invoice Transaction Total Discount Amount` where `Store Key`=%d  and  `Invoice Key` is not null   ',$store_key);
$db->exec($sql);
$sql = sprintf('update `Order Transaction Fact` set `Order Transaction Amount`=`Order Transaction Gross Amount`+`Order Transaction Total Discount Amount` where `Store Key`=%d  and  `Invoice Key` is not null   ',$store_key);
$db->exec($sql);







?>
