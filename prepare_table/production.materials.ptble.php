<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  29 January 2019 at 13:14:19 MYT+0800, Kuala Lumpur, Malaysia
 Copyright (c) 2019, Inikoo

 Version 3.1

*/
// todo
$table
    = "Production Part Dimension`   ";

$fields
    = '`Production Part Links Number`,`Part SKU`,`Part Cost in Warehouse`,`Part Status`,`Part Reference`,`Part Recommended Product Unit Name`,`Part Current Stock`,`Part Stock Status`,`Part Status`,`Part Current On Hand Stock`,`Part Units per Package`
';

$filter_msg = '';
$filter_msg = '';
$wheref     = '';


$where =" where  `Part Production Supply`='Yes'";



if ($parameters['f_field'] == 'reference' and $f_value != '') {
    $wheref .= " and  `Part Reference` like '".addslashes($f_value)."%'";
}

$_order = $order;
$_dir   = $order_direction;

if ($order == 'reference') {
    $order = '`Part Reference`';
}elseif ($order == 'stock') {
    $order = '`Part Current Stock`';
} else {

    $order = '`Part SKU`';
}


$sql_totals
    = "select count(Distinct `Part SKU`) as num from $table  $where  ";



