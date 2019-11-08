<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Refurbished: 30 September 2015 20:13:47 BST, Sheffield, UK
 Copyright (c) 2015, Inikoo

 Version 3

*/


include_once 'utils/date_functions.php';


$table = "`Feedback ITF Bridge` FIB  left join `Feedback Dimension` FD on (FD.`Feedback Key`=FIB.`Feedback ITF Feedback Key`) left join `Inventory Transaction Fact` ITF on   (`Inventory Transaction Key`=`Feedback ITF Original Key`)  
    
    left join `Part Dimension` P on (ITF.`Part SKU`=P.`Part SKU`)  
     left join `Supplier Part Dimension` SPD on (SPD.`Supplier Part Part SKU`=P.`Part SKU`)  
    left join `User Dimension` U on (U.`User Key`=`Feedback User Key`) ";

$fields = "
`User Alias`,`Part Reference`,P.`Part SKU`,`Feedback Date`,`Feedback Key`,`Feedback Message`,`Supplier Part Key`,`Supplier Part Reference`,`Supplier Part Supplier Key`,`Supplier Part Part SKU`,`Part Status`
";


switch ($parameters['parent']) {


    case 'supplier':
        $where = sprintf("where `Feedback Supplier`='Yes'  and `Supplier Key`=%d ", $parameters['parent_key']);

        break;
    case 'supplier_part':
        $where = sprintf("where `Feedback Supplier`='Yes'  and `Supplier Part Key`=%d ", $parameters['parent_key']);

        break;
    case 'part':
        $where = sprintf("where `Feedback Supplier`='Yes'  and ITF.`Part SKU`=%d ", $parameters['parent_key']);

        break;
    case 'category':


        $table = "`Feedback ITF Bridge` FIB  left join `Feedback Dimension` FD on (FD.`Feedback Key`=FIB.`Feedback ITF Feedback Key`)
             left join `Inventory Transaction Fact` ITF on   (`Inventory Transaction Key`=`Feedback ITF Original Key`)  
    
    left join `Part Dimension` P on (ITF.`Part SKU`=P.`Part SKU`)  
     left join `Category Dimension` C on (C.`Category Key`=P.`Part Family Category Key`)
    left join `User Dimension` U on (U.`User Key`=`Feedback User Key`) ";

        $fields = "
`User Alias`,`Part Reference`,P.`Part SKU`,`Feedback Date`,`Feedback Key`,`Feedback Message`,`Part Status`
";


        $where = sprintf("where `Feedback Supplier`='Yes'  and `Part Family Category Key`=%d ", $parameters['parent_key']);

        break;
    case 'warehouse':
        $where = sprintf("where `Feedback Warehouse`='Yes' ");

        $table = "`Feedback ITF Bridge` FIB  left join `Feedback Dimension` FD on (FD.`Feedback Key`=FIB.`Feedback ITF Feedback Key`)
             left join `Inventory Transaction Fact` ITF on   (`Inventory Transaction Key`=`Feedback ITF Original Key`)  
    
    left join `Part Dimension` P on (ITF.`Part SKU`=P.`Part SKU`)  
    left join `User Dimension` U on (U.`User Key`=`Feedback User Key`) ";

        $fields = "
`User Alias`,`Part Reference`,P.`Part SKU`,`Feedback Date`,`Feedback Key`,`Feedback Message`,`Part Status`
";
        break;
    case 'account':
        $where = sprintf("where `Feedback Supplier`='Yes' ");

        $table = "`Feedback ITF Bridge` FIB  left join `Feedback Dimension` FD on (FD.`Feedback Key`=FIB.`Feedback ITF Feedback Key`)
             left join `Inventory Transaction Fact` ITF on   (`Inventory Transaction Key`=`Feedback ITF Original Key`)  
    
    left join `Part Dimension` P on (ITF.`Part SKU`=P.`Part SKU`)  
    left join `User Dimension` U on (U.`User Key`=`Feedback User Key`) ";

        $fields = "
`User Alias`,`Part Reference`,P.`Part SKU`,`Feedback Date`,`Feedback Key`,`Feedback Message`,`Part Status`
";


        break;
    default:
        exit('unknown parent '.$parameters['parent']);
        break;
}


$filter_msg = '';
$wheref     = '';


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
} elseif ($parameters['f_field'] == 'description' and $f_value != '') {
    $wheref .= " and  `Part Package Description` like '".addslashes($f_value)."%'";
}

$_order = $order;
$_dir   = $order_direction;


if ($order == 'reference') {
    $order = 'P.`Part Reference`';
} elseif ($order == 'date') {
    $order = '`Feedback Date`';
} elseif ($order == 'author') {
    $order = '`User Alias`';
} else {

    $order = 'P.`Part SKU`';
}


$sql_totals = "select count(*) as num from $table  $where  ";

print $sql_totals;
exit;