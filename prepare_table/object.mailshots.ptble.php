<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 3 July 2018 at 22:01:40 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/

$group='';


switch ($parameters['parent']) {
    case('email_campaign_type'):
        $where = sprintf(
            ' where `Email Campaign Email Template Type Key`=%d', $parameters['parent_key']
        );
        break;
    case('list'):
        $where = sprintf(
            ' where `Email Campaign Scope`="Customer_List" and  `Email Campaign Scope Key`=%d', $parameters['parent_key']
        );
        break;
    case('category'):
        $where = sprintf(
            ' where `Email Campaign Scope` in ("Category Wide","Category Targeted","Category Donut") and  `Email Campaign Scope Key`=%d', $parameters['parent_key']
        );
        break;
    case('product'):
        $where = sprintf(
            ' where `Email Campaign Scope` in ("Product Wide","Product Targeted","Product Donut") and  `Email Campaign Scope Key`=%d', $parameters['parent_key']
        );
        break;
    default:
        $where = 'where false';
}



$wheref = '';
if ($parameters['f_field'] == 'name' and $f_value != '') {
    $wheref = sprintf(
        ' and `Email Campaign Name` REGEXP "[[:<:]]%s" ', addslashes($f_value)
    );
}

$_order = $order;
$_dir   = $order_direction;


if ($order == 'name') {
    $order = '`Email Campaign Name`';
} elseif ($order == 'date') {
    $order = '`Email Campaign Last Updated Date`';
} elseif ($order == 'state') {
    $order = '`Email Campaign State`';
}elseif ($order == 'bounces') {
    $order = '(`Email Campaign Hard Bounces`+`Email Campaign Soft Bounces`)';
} else {
    $order = '`Email Campaign Key`';
}
$table  = '`Email Campaign Dimension`  ';
$fields = "`Email Campaign Key`,`Email Campaign Name`,`Email Campaign Store Key`,`Email Campaign Last Updated Date`,`Email Campaign State`,`Email Campaign Number Estimated Emails`,
`Email Campaign Sent`,`Email Campaign Delivered`,`Email Campaign Hard Bounces`,`Email Campaign Soft Bounces`,(`Email Campaign Hard Bounces`+`Email Campaign Soft Bounces`) as `Email Campaign Bounces`,`Email Campaign Open`,`Email Campaign Clicked`,`Email Campaign Spams`,`Email Campaign Unsubscribed`

";


$sql_totals = "select count(*) as num from $table $where ";
//print $sql_totals;

