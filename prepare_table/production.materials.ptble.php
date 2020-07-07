<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  29 January 2019 at 13:14:19 MYT+0800, Kuala Lumpur, Malaysia
 Copyright (c) 2019, Inikoo

 Version 3.1

*/

$table
    = "`Raw Material Dimension` M   ";

$fields='`Raw Material Key`,`Raw Material Type`,`Raw Material Type Key`,`Raw Material Code`,`Raw Material Description`,`Raw Material Unit`,`Raw Material Unit Label`,
`Raw Material Unit Cost`,`Raw Material Stock`,`Raw Material Products`,`Raw Material Production Supplier Key`,`Raw Material Stock Status` ';

$filter_msg = '';
$filter_msg = '';
$wheref     = '';


$where =" where `Raw Material Type`!='Intermediate'";



if ($parameters['f_field'] == 'reference' and $f_value != '') {
    $wheref .= " and  `Raw Material Code` like '".addslashes($f_value)."%'";
}

$_order = $order;
$_dir   = $order_direction;

if ($order == 'reference') {
    $order = '`Raw Material Code`';
}elseif ($order == 'stock') {
    $order = '`Raw Material Stock`';
} else {

    $order = '`Raw Material Key`';
}


$sql_totals
    = "select count(Distinct `Raw Material Key`) as num from $table  $where  ";


