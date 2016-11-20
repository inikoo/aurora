<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 17 November 2016 at 22:19:15 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

//$period_tag=get_interval_db_name($parameters['f_period']);


$table = '`Template Dimension` T left join  `Template Scope Dimension` TS on (T.`Template Scope Key`=TS.`Template Scope Key`) ';

switch ($parameters['parent']) {

    case('website'):
        $where = sprintf(
            ' where  `Template Website Key`=%d  ', $parameters['parent_key']
        );
        break;

    default:
        exit('parent not configured '.$parameters['parent']);

}

$group = '';



$wheref = '';
if ($parameters['f_field'] == 'code' and $f_value != '') {
    $wheref .= " and `Template Code` like '".addslashes($f_value)."%'";
}

$_order = $order;
$_dir   = $order_direction;


if ($order == 'code') {
    $order = '`Template Code`';
}
if ($order == 'device') {
    $order = '`Template Device`';
} else {
    $order = 'T.`Template Key`';
}


$sql_totals = "select count(Distinct T.`Template Key`) as num from $table  $where  ";

$fields = "T.`Template Key`,`Template Device`,`Template Code`,`Template Website Key`,`Template Scope`,`Template Base`,`Template Number Webpages`,`Template Number Webpage Versions`";



