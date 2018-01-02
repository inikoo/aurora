<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 27 December 2017 at 13:45:28 GMT, Sheffield, UK
 Copyright (c) 2017, Inikoo

 Version 3

*/

 $where = sprintf(
            ' where D.`Deal Campaign Key`=%d', $parameters['parent_key']
        );


$wheref = '';
if ($parameters['f_field'] == 'name' and $f_value != '') {
    $wheref = sprintf(
        ' and `Deal Name` REGEXP "[[:<:]]%s" ', addslashes($f_value)
    );
}elseif ($parameters['f_field'] == 'target' and $f_value != '') {
    $wheref = sprintf(
        ' and `Deal Component Allowance Target Label` REGEXP "[[:<:]]%s" ', addslashes($f_value)
    );
}

$_order = $order;
$_dir   = $order_direction;


if ($order == 'name') {
    $order = '`Deal Name`';
} elseif ($order == 'orders') {
    $order = '`Deal Total Acc Used Orders`';
} elseif ($order == 'customers') {
    $order = '`Deal Total Acc Used Customers`';
} elseif ($order == 'from') {
    $order = '`Deal Begin Date`';
} elseif ($order == 'to') {
    $order = '`Deal Expiration Date`';
} elseif ($order == 'description') {
    $order = '`Deal Term Allowances Label`';
} else {
    $order = '`Deal Key`';
}
$table  = '`Deal Component Dimension` DC left join `Deal Dimension` D on (DC.`Deal Component Deal Key`=D.`Deal Key`)  join `Deal Campaign Dimension` C on (C.`Deal Campaign Key`=D.`Deal Campaign Key`) left join `Store Dimension` on (`Deal Store Key`=`Store Key`) ';
$fields = "`Deal Key`,`Deal Name`,`Deal Term Allowances`,`Deal Term Allowances Label`,`Deal Store Key`,D.`Deal Campaign Key`,`Deal Status`,`Deal Begin Date`,`Deal Expiration Date`,`Deal Component Key`,
`Deal Total Acc Used Orders`,`Deal Total Acc Used Customers`,`Store Bulk Discounts Campaign Key`,`Deal Component Allowance Target Label`,`Deal Component Allowance`,`Deal Component Allowance Label`,`Deal Component Terms`,`Deal Term Label`,`Deal Component Allowance Target Key`";


$sql_totals = "select count(*) as num from $table $where ";


?>
