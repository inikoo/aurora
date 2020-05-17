<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 30 November 2017 at 13:44:56 GMT+7, Bangkok, Thailand
 Copyright (c) 2015, Inikoo

 Version 3

*/


switch ($parameters['parent']) {
    case('deal'):
        $where = sprintf(
            ' where  `Deal Component Deal Key`=%d', $parameters['parent_key']
        );
        break;
    case('campaign'):
        $where = sprintf(
            ' where  `Deal Component Campaign Key`=%d', $parameters['parent_key']
        );
        break;

    default:
        $where = 'where false';
}



$wheref = '';
if ($parameters['f_field'] == 'target' and $f_value != '') {
    $wheref = sprintf(
        ' and `Deal Component Allowance Target Label` REGEXP "\\\\b%s" ', addslashes($f_value)
    );
}elseif ($parameters['f_field'] == 'name' and $f_value != '') {
    $wheref = sprintf(
        ' and `Deal Term Allowances Label` REGEXP "\\\\b%s" ', addslashes($f_value)
    );
}

$_order = $order;
$_dir   = $order_direction;


if ($order == 'target') {
    $order = '`Deal Component Allowance Target Label`';
} elseif ($order == 'orders') {
    $order = '`Deal Total Acc Used Orders`';
}elseif ($order == 'allowance') {
    $order = '`Deal Component Allowance`';
} elseif ($order == 'customers') {
    $order = '`Deal Component Total Acc Used Customers`';
} elseif ($order == 'from') {
    $order = '`Deal Component Begin Date`';
} elseif ($order == 'to') {
    $order = '`Deal Component Expiration Date`';
} elseif ($order == 'description') {
    $order = '`Deal Component Term Allowances Label`';
} else {
    $order = '`Deal Key`';
}
$table  = '`Deal Component Dimension` DCD left join `Deal Dimension` D on (`Deal Component Deal Key`=`Deal Key`)  left join `Deal Campaign Dimension` C on (C.`Deal Campaign Key`=D.`Deal Campaign Key`) ';
$fields = "`Deal Component Term Allowances Label`,`Deal Component Key`,`Deal Component Begin Date`,`Deal Component Expiration Date`,`Deal Component Status`,`Deal Key`,`Deal Name`,`Deal Term Allowances Label`,`Deal Store Key`,D.`Deal Campaign Key`,`Deal Status`,`Deal Begin Date`,`Deal Expiration Date`,
`Deal Total Acc Used Orders`,`Deal Total Acc Used Customers`,`Deal Component Total Acc Used Orders`,`Deal Component Total Acc Used Customers`,`Deal Name Label`,`Deal Term Label`,`Deal Component Allowance Label`,
`Deal Component Allowance Target Label`,`Deal Component Allowance Target`,`Deal Component Allowance Target Key`,`Deal Component Store Key`,`Deal Component Allowance Type`,`Deal Component Allowance`
";


$sql_totals = "select count(*) as num from $table $where ";


?>
