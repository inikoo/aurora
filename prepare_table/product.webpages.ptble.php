<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 05-08-2019 21:57:15 MYT , Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

//$period_tag=get_interval_db_name($parameters['f_period']);


$table = '`Webpage Dimension` P';

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
    $order = 'P.`Webpage Key`';
}


$sql_totals
    = "select count(Distinct P.`Webpage Key`) as num from $table  $where  ";

$fields
    = "
`Webpage Key`,`Webpage Code`,`Webpage Name`,`Webpage Display Probability`
";

