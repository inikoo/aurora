<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 15 November 2016 at 13:22:02 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

//$period_tag=get_interval_db_name($parameters['f_period']);


$table = '`Webpage Version Dimension` V left join  `Webpage Dimension` P on (`Webpage Version Webpage Key`=`Webpage Key`) ';

switch ($parameters['parent']) {

    case('page'):
        $where = sprintf(
            ' where  `Webpage Version Webpage Key`=%d  ', $parameters['parent_key']
        );
        break;

    default:
        exit('parent not configured '.$parameters['parent']);

}

$group = '';



$wheref = '';
if ($parameters['f_field'] == 'code' and $f_value != '') {
    $wheref .= " and `Webpage Version Code` like '".addslashes($f_value)."%'";
}

$_order = $order;
$_dir   = $order_direction;


if ($order == 'code') {
    $order = '`Webpage Version Code`';
}
if ($order == 'device') {
    $order = '`Webpage Version Device`';
} else {
    $order = 'V.`Webpage Version Key`';
}


$sql_totals = "select count(Distinct V.`Webpage Version Key`) as num from $table  $where  ";

$fields = "V.`Webpage Version Key`,`Webpage Version Device`,`Webpage Version Code`,`Webpage Key`,`Webpage Code`,`Webpage Name`,`Webpage Version Display Probability`";



