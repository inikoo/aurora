<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Refurbished: 2 October 2015 at 09:40:42 BST, Sheffield, UK
 Copyright (c) 2015, Inikoo

 Version 3

*/


if (count($user->stores) == 0) {
    $where = "where false";
} else {
    $where = sprintf("where S.`Store Key` in (%s)", join(',', $user->stores));
}
$filter_msg = '';


$group = '';


$wheref = '';
if ($parameters['f_field'] == 'name' and $f_value != '') {
    $wheref .= " and  `Store Name` like '%".addslashes($f_value)."%'";
} elseif ($parameters['f_field'] == 'code' and $f_value != '') {
    $wheref .= " and  `Store Code` like '".addslashes($f_value)."%'";
}


$_order = $order;
$_dir   = $order_direction;


if ($order == 'code') {
    $order = '`Store Code`';
}elseif ($order == 'name') {
    $order = '`Store Name`';
}  else {
    $order = 'S.`Store Key`';
}


$table = '`Store Dimension` S ';

$sql_totals
    = "select count(Distinct S.`Store Key`) as num from $table  $where  ";

$fields
    = " `Store Key`,`Store Code`,`Store Name`, (select count(*) from `Deal Campaign Dimension` where `Deal Campaign Status`='Active' and `Deal Campaign Store Key`=S.`Store Key` ) as campaigns
, (select count(*) from `Deal Dimension` where `Deal Status`='Active' and `Deal Store Key`=S.`Store Key` ) as deals
  ";


