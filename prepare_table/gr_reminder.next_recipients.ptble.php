<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 3 July 2018 at 11:24:28 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3

*/


$filter_msg = '';

$parent = get_object($_data['parameters']['parent'], $_data['parameters']['parent_key']);
$metadata=$parent->get('Metadata');
//print_r($metadata);


date_default_timezone_set($metadata['Schedule']['Timezone']);


if( date('H:i') > date('H:i',strtotime($metadata['Schedule']['Time']))) {
    $date=gmdate('Y-m-d',strtotime('today - '.($metadata['Send After']-1).' days'));

}else{
    $date=gmdate('Y-m-d',strtotime('today - '.$metadata['Send After'].' days'));

}


$where = sprintf("where `Customer Store Key`=%d and  `Customer Send Email Marketing`='Yes' and `Customer Last Dispatched Order Date`=%s",$parent->get('Store Key'),
    prepare_mysql($date)
    );


$group='';

$wheref = '';
if ($parameters['f_field'] == 'name' and $f_value != '') {
    $wheref .= " and  C.`Customer Name` like '".addslashes($f_value)."%'";
}

$_order = $order;
$_dir   = $order_direction;


if ($order == 'name') {
    $order = '`Customer Name`';
} else {
    $order = '`Customer Key`';
}


$table = '`Customer Dimension`   left join `Order Dimension` on (`Customer Last Dispatched Order Key`=`Order Key`)';

$sql_totals = "select count(distinct `Customer Key`) as num from $table  $where  ";


//print $sql_totals;
//exit;


$fields = "`Customer Store Key`,`Customer Name`,`Customer Key`,`Order Key`,`Order Public ID`,`Order Dispatched Date`";


