<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Refurbished:18 October 2015 at 12:55:26 BST, Sheffield, UK
 Copyright (c) 2015, Inikoo

 Version 3

*/


$table
    = '`User Request Dimension` URD left join  `User Dimension` U on (URD.`User Key`=U.`User Key`) left join  `Page Store Dimension` PS on (URD.`Page Key`=PS.`Page Key`) ';


switch ($parameters['parent']) {
    case 'website':
        $table    = '`User Dimension` U ';
        $group_by = '';
        $where    = sprintf(
            'where  URD.`Site Key`=%d ', $parameters['parent_key']
        );
        break;
    case 'user':
        $group_by = '';
        $where    = sprintf(
            'where  URD.`User Key`=%d ', $parameters['parent_key']
        );
        break;
    default:
        exit('error parent not found '.$parameters['parent']);
        break;
}


$wheref = '';
if ($parameters['f_field'] == 'page' and $f_value != '') {
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
} elseif ($order == 'page') {
    $order = '`Page Code`';
} elseif ($order == 'date') {
    $order = '`Date`';
} elseif ($order == 'title') {
    $order = '`Page Store Title`';
} else {
    $order = 'URD.`User Request Key`';
}


$sql_totals = "select count(*) as num from $table  $where  ";

$fields
    = "`Page Parent Key`,`Page Parent Code`,`Page Store Section`,`Page Store Title`,`Page Site Key`,`User Request Key`,URD.`Page Key`,`Page Code`,`User Site Key`,`User Alias`,`User Parent Key`,`User Handle`,U.`User Key`,`Date`";


//	$sql="select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";
//print $sql;

?>
