<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  08 November 2019  13:04::40  +0100, Mijas Costa , Spain
 Copyright (c) 2019, Inikoo

 Version 3

*/


include_once 'utils/date_functions.php';


$table = "`Feedback ITF Bridge` FIB  left join `Feedback Dimension` FD on (FD.`Feedback Key`=FIB.`Feedback ITF Feedback Key`) left join `Inventory Transaction Fact` ITF on   (`Inventory Transaction Key`=`Feedback ITF Original Key`)  
    
    left join `Part Dimension` P on (ITF.`Part SKU`=P.`Part SKU`)  
        left join `Delivery Note Dimension` DN on (ITF.`Delivery Note Key`=DN.`Delivery Note Key`)  

    left join `User Dimension` U on (U.`User Key`=`Feedback User Key`) ";

$fields = "
`User Alias`,`Part Reference`,P.`Part SKU`,`Feedback Date`,`Feedback Key`,`Feedback Message`,`Part Status`,DN.`Delivery Note Key`,`Delivery Note ID`,`Delivery Note Date`
";


if($parameters['parent']=='picker'){
    $where = sprintf("where `Feedback Picker`='Yes' and `Picker Key`=%d  ", $parameters['parent_key']);

}elseif($parameters['parent']=='packer'){
    $where = sprintf("where `Feedback Packer`='Yes' and `Packer Key`=%d  ", $parameters['parent_key']);

}else{
    exit('');
}




if (isset($parameters['period'])) {
    include_once('utils/date_functions.php');
    list(
        $db_interval, $from, $to, $from_date_1yb, $to_1yb
        ) = calculate_interval_dates(
        $db, $parameters['period'], $parameters['from'], $parameters['to']
    );

    $where_interval = prepare_mysql_dates($from, $to, '`Feedback Date`')['mysql'];
} else {
    $where_interval = '';
}

$where .= $where_interval;


$filter_msg = '';
$wheref     = '';


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
} elseif ($order == 'delivery_note') {
    $order = '`Delivery Note ID`';
}elseif ($order == 'delivery_note_date') {
    $order = '`Delivery Note Date`';
} else {

    $order = 'P.`Part SKU`';
}


$sql_totals = "select count(*) as num from $table  $where  ";
