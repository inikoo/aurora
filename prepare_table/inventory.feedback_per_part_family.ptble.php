<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Refurbished: 06-09-2019 23:44:55 MYT, Sheffield, UK
 Copyright (c) 2019, Inikoo

 Version 3

*/

include_once 'utils/date_functions.php';

$where      = "where `Feedback Supplier`='Yes' ";

$filter_msg = '';
$wheref     = '';

$group_by=' group by P.`Part Family Category Key` ';




if (isset($parameters['f_period'])) {

}

if ($parameters['f_field'] == 'code' and $f_value != '') {
    $wheref .= " and  `Category Code` like '".addslashes($f_value)."%'";
}
$_order = $order;
$_dir   = $order_direction;



if ($order == 'code') {
    $order = '`Category Code`';
}elseif ($order == 'date') {
    $order = 'max(`Feedback Date`)';
}elseif ($order == 'number_feedback') {
    $order = 'count(*) ';
}else {

    $order = '`Category Key`';
}

$table
    = "`Feedback ITF Bridge` FIB  left join `Feedback Dimension` FD on (FD.`Feedback Key`=FIB.`Feedback ITF Feedback Key`) left join `Inventory Transaction Fact` ITF on   (`Inventory Transaction Key`=`Feedback ITF Original Key`)  left join `Part Dimension` P on (ITF.`Part SKU`=P.`Part SKU`)   left join `Category Dimension` C on (C.`Category Key`=P.`Part Family Category Key`) ";

$sql_totals
    = "select count(distinct `Category Key`) as num from $table  $where  ";



$fields="
`Category Code`,`Category Key`,max(`Feedback Date`) as date,count(*) as number_feedback,sum(-`Inventory Transaction Amount`) as amount
";

