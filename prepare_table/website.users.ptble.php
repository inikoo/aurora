<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Refurbished: 1 October 2015 at 12:18:06 BST, Sheffield, UK
 Copyright (c) 2015, Inikoo

 Version 3

*/



switch ($parameters['parent']) {
    case 'website':
        $table    = '`Website User Dimension` WU  left join   `Website User Data` WUD on (WU.`Website User Key`=WUD.`Website User Key`) left join `Customer Dimension` on (`Customer Key`=`Website User Customer Key`)   ';
        $group_by = '';
        $where= sprintf('where  `Website User Website Key`=%d ', $parameters['parent_key']);
        break;

    default:
        exit('error parent not found '.$parameters['parent']);
        break;
}


$wheref = '';
if ($parameters['f_field'] == 'handle' and $f_value != '') {
    $wheref .= " and  `Website User Handle` like '".addslashes($f_value)."%'    ";
} else {
    if ($parameters['f_field'] == 'customer') {
        $wheref .= " and  `Customer Name` like '".addslashes($f_value)."%'    ";
    }
}


$_order = $order;
$_dir   = $order_direction;

if ($order == 'customer') {
    $order = '`Customer Name`';
} elseif ($order == 'user') {
    $order = '`Website User Handle`';
} elseif ($order == 'sessions') {
    $order = '`Website User Sessions Count`';
} elseif ($order == 'last_login') {
    $order = '`Website User Last Login`';
} else {
    $order = 'WU.`Website User Key`';
}


$sql_totals = "select count(Distinct WU.`Website User Key`) as num from $table  $where  ";

$fields = "`Website User Website Key`,`Customer Name`,`Customer Key`,`Website User Handle`,WU.`Website User Key`,`Website User Sessions Count`,`Website User Last Login`";

