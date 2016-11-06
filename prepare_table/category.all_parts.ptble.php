<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Refurbished: 20 June 2016 at 17:15:17 BST, Sheffield, UK
 Copyright (c) 2016, Inikoo

 Version 3

*/

$group_by = '';

include_once 'utils/date_functions.php';

$where = "where `Part Status`='In Use'  ";

$table
            = '   `Part Dimension` P left join `Part Data` D on (D.`Part SKU`=P.`Part SKU`) left join `Category Dimension` F on (`Part Family Category Key`=F.`Category Key`) ';
$filter_msg = '';
$sql_type   = 'part';
$filter_msg = '';
$wheref     = '';

$fields = '';


$associated_field = sprintf(
    "(select `Category Key` from `Category Bridge` C  where C.`Category Key`=%d and `Subject Key`=P.`Part SKU` ) as associated, ", $parameters['parent_key']
);

$where_type = '';


if (isset($extra_where)) {
    $where .= $extra_where;
}


if (isset($parameters['elements_type'])) {

    switch ($parameters['elements_type']) {
        case 'stock_status':
            $_elements      = '';
            $count_elements = 0;
            foreach (
                $parameters['elements'][$parameters['elements_type']]['items'] as $_key => $_value
            ) {
                if ($_value['selected']) {
                    $count_elements++;
                    $_elements .= ','.prepare_mysql($_key);

                }
            }


            $_elements = preg_replace('/^\,/', '', $_elements);
            if ($_elements == '') {
                $where .= ' and false';
            } elseif ($count_elements < 5) {
                $where .= ' and `Part Stock Status` in ('.$_elements.')';

            }
            break;

    }
}

$db_period = get_interval_db_name($parameters['f_period']);
if (in_array(
    $db_period, array(
    'Total',
    '3 Year'
)
)) {
    $yb_fields = " '' as sold_1y,'' as revenue_1y";

} else {
    $yb_fields
        = "`Part $db_period Acc 1YB Sold` as sold_1y,`Part $db_period Acc 1YB Sold Amount` as revenue_1y";
}

if ($parameters['f_field'] == 'used_in' and $f_value != '') {
    $wheref .= " and  `Part XHTML Currently Used In` like '%".addslashes(
            $f_value
        )."%'";
} elseif ($parameters['f_field'] == 'reference' and $f_value != '') {
    $wheref .= " and  `Part Reference` like '".addslashes($f_value)."%'";
} elseif ($parameters['f_field'] == 'supplied_by' and $f_value != '') {
    $wheref .= " and  `Part XHTML Currently Supplied By` like '%".addslashes(
            $f_value
        )."%'";
} elseif ($parameters['f_field'] == 'sku' and $f_value != '') {
    $wheref .= " and  `Part SKU` ='".addslashes($f_value)."'";
} elseif ($parameters['f_field'] == 'description' and $f_value != '') {
    $wheref .= " and  `Part Unit Description` like '".addslashes($f_value)."%'";
}

$_order = $order;
$_dir   = $order_direction;

if ($order == 'id') {
    $order = 'P.`Part SKU`';
} elseif ($order == 'stock') {
    $order = '`Part Current Stock`';
} elseif ($order == 'stock_status') {
    $order = '`Part Stock Status`';
} elseif ($order == 'reference') {
    $order = '`Part Reference`';
} elseif ($order == 'unit_description') {
    $order = '`Part Unit Description`';
} elseif ($order == 'available_for') {
    $order = '`Part Days Available Forecast`';

} elseif ($order == 'sold') {
    $order = ' sold ';
} elseif ($order == 'revenue') {
    $order = ' revenue ';
} elseif ($order == 'lost') {
    $order = ' lost ';
} elseif ($order == 'bought') {
    $order = ' bought ';
} elseif ($order == 'from') {
    $order = '`Part Valid From`';
} elseif ($order == 'to') {
    $order = '`Part Valid To`';
} elseif ($order == 'last_update') {
    $order = '`Part Last Updated`';
} else {

    $order = '`Part SKU`';
}


$sql_totals
    = "select count(Distinct P.`Part SKU`) as num from $table  $where  ";

$fields
    .= "$associated_field `Category Code`,


P.`Part SKU`,`Part Reference`,`Part Unit Description`,`Part Current Stock`,`Part Stock Status`,`Part Days Available Forecast`,
`Part $db_period Acc Sold` as sold,


`Part $db_period Acc Given` as given,
(`Part $db_period Acc Broken`+`Part $db_period Acc Lost`) as lost,

`Part $db_period Acc Sold Amount` as revenue,
`Part $db_period Acc Acquired` as bought,
`Part Days Available Forecast`,$yb_fields

";


?>
