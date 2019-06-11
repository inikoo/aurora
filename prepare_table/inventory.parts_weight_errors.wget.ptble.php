<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Refurbished: 13 January 2017 at 17:59:11 GMT, Sheffield, UK
 Copyright (c) 2017, Inikoo

 Version 3

*/


include_once 'utils/date_functions.php';

$where      = "where `Part Status`!='Not In Use' and   `Part Package Weight Status`!='OK'   ";
$table  = "`Part Dimension` P  ";
$filter_msg = '';
$sql_type   = 'part';
$filter_msg = '';
$wheref     = '';
$group_by     = '';



switch ($parameters['elements_type']) {

    case 'type':
        $_elements      = '';
        $count_elements = 0;
        foreach (
            $parameters['elements'][$parameters['elements_type']]['items'] as $_key => $_value
        ) {
            if ($_value['selected']) {
                $count_elements++;

                if($_key=='Underweight'){
                    $_elements .= ",'Underweight Cost','Underweight Web'";

                }elseif($_key=='Overweight'){
                    $_elements .= ",'Overweight Cost','Overweight Web'";

                }else{
                    $_elements .= ','.prepare_mysql($_key);

                }

            }
        }
        $_elements = preg_replace('/^\,/', '', $_elements);
        if ($_elements == '') {
            $where .= ' and false';
        } elseif ($count_elements < 3) {
            $where .= ' and `Part Package Weight Status` in ('.$_elements.')';
        }
        break;

        ;


}


if ($parameters['f_field'] == 'reference' and $f_value != '') {
    $wheref .= " and  `Part Reference` like '".addslashes($f_value)."%'";
}  elseif ($parameters['f_field'] == 'sku' and $f_value != '') {
    $wheref .= " and  `Part SKU` ='".addslashes($f_value)."'";
} elseif ($parameters['f_field'] == 'description' and $f_value != '') {
    $wheref .= " and  `Part Package Description` like '".addslashes($f_value)."%'";
}


$_order = $order;
$_dir   = $order_direction;

if ($order == 'id') {
    $order = 'P.`Part SKU`';
} elseif ($order == 'reference') {
    $order = '`Part Reference`';
} elseif ($order == 'description') {
    $order = '`Part Package Description`';
}elseif ($order == 'weight') {
    $order = '`Part Package Weight`';
}  else {

    $order = '`Part SKU`';
}

$sql_totals = "select count(*) as num from $table  $where  ";

$fields= "P.`Part SKU`,`Part Reference`,`Part Package Description`,`Part Package Weight`,`Part Main Image Key`,`Part Package Weight Status`";




