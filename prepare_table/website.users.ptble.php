<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Refurbished: 1 October 2015 at 12:18:06 BST, Sheffield, UK
 Copyright (c) 2015, Inikoo

 Version 3

*/


$where = " where  `User Type`='Customer' ";

switch ($parameters['parent']) {
    case 'website':
        $table    = '`User Dimension` U ';
        $group_by = '';
        $where .= sprintf('and `User Site Key`=%d ', $parameters['parent_key']);
        break;
    case 'page':
        $table
                  = '`User Dimension` U left join `User Request Dimension` URD on (URD.`User Key`=U.`User Key`) ';
        $group_by = 'group by URD.`User Key`';
        $where .= sprintf('and URD.`Page Key`=%d ', $parameters['parent_key']);
        break;
    default:
        exit('error parent not found '.$parameters['parent']);
        break;
}


$wheref = '';
if ($parameters['f_field'] == 'handle' and $f_value != '') {
    $wheref .= " and  `User Handle` like '".addslashes($f_value)."%'    ";
} else {
    if ($parameters['f_field'] == 'customer') {
        $wheref .= " and  `User Alias`like '".addslashes($f_value)."%'    ";
    }
}


$_order = $order;
$_dir   = $order_direction;

if ($order == 'customer') {
    $order = '`User Alias`';
} elseif ($order == 'user') {
    $order = '`User Handle`';
} elseif ($order == 'sessions') {
    $order = '`User Sessions Count`';
} elseif ($order == 'last_login') {
    $order = '`User Last Login`';
} else {
    $order = 'U.`User Key`';
}


$sql_totals
    = "select count(Distinct U.`User Key`) as num from $table  $where  ";

$fields
    = "`User Site Key`,`User Alias`,`User Parent Key`,`User Handle`,U.`User Key`,`User Sessions Count`,`User Last Login`";
?>
