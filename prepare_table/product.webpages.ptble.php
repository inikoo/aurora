<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 05-08-2019 21:57:15 MYT , Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

//$period_tag=get_interval_db_name($parameters['f_period']);

$where=sprintf(' where `Website Webpage Scope Scope`="Product" and `Website Webpage Scope Scope Key`=%d  ',$parameters['parent_key']);
$table = '`Website Webpage Scope Map` WWSM  left join `Page Store Dimension` P on (`Website Webpage Scope Webpage Key`=`Page Key`)  '   ;

$group = '';


$wheref = '';
if ($parameters['f_field'] == 'code' and $f_value != '') {
    $wheref .= " and `Webpage Code` like '".addslashes($f_value)."%'";
} elseif ($parameters['f_field'] == 'name' and $f_value != '') {
    $wheref .= " and  `Webpage Name` like '".addslashes($f_value)."%'";
}


$_order = $order;
$_dir   = $order_direction;


if ($order == 'code') {
    $order = '`Webpage Code`';
}
if ($order == 'name') {
    $order = '`Webpage Name`';
} else {
    $order = 'P.`Page Key`';
}


$sql_totals
    = "select count(Distinct P.`Page Key`) as num from $table  $where  ";

$fields
    = "
`Page Key`,`Webpage Code`,`Webpage Name`,`Webpage State`,`Webpage Scope`,`Webpage Website Key`,`Website Webpage Scope Key`,`Website Webpage Scope Type`
";