<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Refurbished: 14 August 2016 at 18:51:05 GMT+8, Cyberjaya , Malayis
 Copyright (c) 2016, Inikoo

 Version 3

*/

$group_by = '';


$where = "where true  ";

$table
            = '   `Part Dimension` P  left join `Category Dimension` F on (`Part Family Category Key`=F.`Category Key`) ';
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
        case 'status':


            $_elements = '';

            $count_elements = 0;
            foreach (
                $parameters['elements'][$parameters['elements_type']]['items'] as $_key => $_value
            ) {
                if ($_value['selected']) {
                    $count_elements++;
                    $_elements .= $_key;

                }
            }


            $_elements = preg_replace('/^\,/', '', $_elements);
            if ($_elements == '') {
                $where .= ' and false';
            } elseif ($count_elements < 2) {

                if ($_elements == 'Assigned') {
                    $where .= ' and `Category Key` is not null';
                } else {
                    $where .= ' and `Category Key` is  null';

                }


            }
            break;

    }
}


if ($parameters['f_field'] == 'reference' and $f_value != '') {
    $wheref = " and  `Part Reference` like '".addslashes($f_value)."%'";
} elseif ($parameters['f_field'] == 'description' and $f_value != '') {
    $wheref = sprintf(
        ' and `Part Unit Description` REGEXP "[[:<:]]%s" ', addslashes($f_value)
    );
}

$_order = $order;
$_dir   = $order_direction;

if ($order == 'id') {
    $order = 'P.`Part SKU`';
} elseif ($order == 'reference') {
    $order = '`Part Reference`';
} elseif ($order == 'unit_description') {
    $order = '`Part Unit Description`';
} elseif ($order == 'status') {
    $order = '`Part Status`';
} else {
    $order = '`Part SKU`';
}


$sql_totals
    = "select count(Distinct P.`Part SKU`) as num from $table  $where  ";

$fields .= "$associated_field `Category Code`,`Category Key`,P.`Part SKU`,`Part Reference`,`Part Unit Description`,`Part Status`";


?>
