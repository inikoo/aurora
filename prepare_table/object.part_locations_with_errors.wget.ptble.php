<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 11 October 2016 at 14:40:51 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/


include_once 'utils/date_functions.php';

$where      = "where true  ";
$filter_msg = '';
$wheref     = '';

$fields = '';


if ($parameters['parent'] == 'supplier') {
    $table = "  `Part Location Dimension` PLD  left join `Part Dimension` P on (PLD.`Part SKU`=P.`Part SKU`) left join `Supplier Part Dimension` SP on (SP.`Supplier Part Part SKU`=P.`Part SKU`) left join `Location Dimension` L on (PLD.`Location Key`=L.`Location Key`) ";

    $where = sprintf(
        "where `Supplier Part Supplier Key`=%d and `Quantity On Hand`<0 ", $parameters['parent_key']
    );
}else if ($parameters['parent'] == 'warehouse') {
    $table = "  `Part Location Dimension` PLD  left join `Part Dimension` P on (PLD.`Part SKU`=P.`Part SKU`) left join `Location Dimension` L on (PLD.`Location Key`=L.`Location Key`) ";

    $where = sprintf(
        "where `Location Warehouse Key`=%d and `Quantity On Hand`<0 ", $parameters['parent_key']
    );
} else {
    exit("parent not found ".$parameters['parent']);
}


if (isset($extra_where)) {
    $where .= $extra_where;
}


if ($parameters['f_field'] == 'reference' and $f_value != '') {
    $wheref .= " and  `Part Reference` like '".addslashes($f_value)."%'";
}


$_order = $order;
$_dir   = $order_direction;

if ($order == 'reference') {
    $order = 'P.`Part Reference`';
} else {

    $order = '`Part SKU`';
}





$sql_totals = "select count(*) as num from $table  $where  ";

$fields
    .= "
P.`Part SKU`,`Part Reference`,`Part Package Description`,`Part Current Stock`,`Part Stock Status`,`Part Days Available Forecast`,
`Location Code`,PLD.`Location Key`,`Part Location Warehouse Key`,
`Quantity On Hand`,`Quantity In Process`,`Stock Value`,`Can Pick`,`Minimum Quantity`,`Maximum Quantity`,`Moving Quantity`,`Last Updated`

";


?>
