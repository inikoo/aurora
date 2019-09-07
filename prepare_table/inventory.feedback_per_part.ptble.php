<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Refurbished: 30 September 2015 20:13:47 BST, Sheffield, UK
 Copyright (c) 2019, Inikoo

 Version 3

*/




include_once 'utils/date_functions.php';

$where      = "where `Feedback Supplier`='Yes' ";

$filter_msg = '';
$wheref     = '';

$group_by=' group by P.`Part SKU` ';



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

if (isset($parameters['f_period'])) {

}

if ($parameters['f_field'] == 'reference' and $f_value != '') {
    $wheref .= " and  `Part Reference` like '".addslashes($f_value)."%'";
}elseif ($parameters['f_field'] == 'description' and $f_value != '') {
    $wheref .= " and  `Part Package Description` like '".addslashes($f_value)."%'";
}

$_order = $order;
$_dir   = $order_direction;



if ($order == 'reference') {
    $order = 'P.`Part Reference`';
}elseif ($order == 'date') {
    $order = 'max(`Feedback Date`)';
}elseif ($order == 'number_feedback') {
    $order = 'count(*) ';
}else {

    $order = 'P.`Part SKU`';
}

$table
    = "`Feedback ITF Bridge` FIB  left join `Feedback Dimension` FD on (FD.`Feedback Key`=FIB.`Feedback ITF Feedback Key`) left join `Inventory Transaction Fact` ITF on   (`Inventory Transaction Key`=`Feedback ITF Original Key`)  left join `Part Dimension` P on (ITF.`Part SKU`=P.`Part SKU`) ";

$sql_totals
    = "select count(distinct P.`Part SKU`) as num from $table  $where  ";



$fields="
`Part Reference`,P.`Part SKU`,max(`Feedback Date`) as date,count(*) as number_feedback,sum(-`Inventory Transaction Amount`) as amount
";

