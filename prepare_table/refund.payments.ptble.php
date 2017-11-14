<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 14 November 2017 at 11:35:03 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3

*/



$parent=get_object('Invoice',$parameters['parent_key']);



$filter_msg = '';

$where = sprintf(
    "where `Payment Order Key`=%d", $parent->get('Invoice Order Key')
);

$group = '';


$wheref = '';
if ($parameters['f_field'] == 'reference' and $f_value != '') {
    $wheref .= " and  P.`Payment Transaction ID` like '".addslashes($f_value)."%'";
}

$_order = $order;
$_dir   = $order_direction;


if ($order == 'reference') {
    $order = 'P.`Payment Transaction ID`';
} elseif ($order == 'amount') {
    $order = 'P.`Payment Transaction Amount`';
} elseif ($order == 'date') {
    $order = 'P.`Payment Last Updated Date`';
} elseif ($order == 'type') {
    $order = 'P.`Payment Type`';
} elseif ($order == 'status') {
    $order = 'P.`Payment Transaction Status`';
} else {
    $order = 'P.`Payment Key`';
}


$table = '`Payment Dimension` P left join `Payment Account Dimension` PA on (PA.`Payment Account Key`=P.`Payment Account Key`) ';

$sql_totals = "select count(P.`Payment Key`) as num from $table  $where  ";
$fields
            = "PA.`Payment Account Code`,PA.`Payment Account Name`,`Payment Account Block`,`Payment Transaction Amount Refunded`,`Payment Transaction Amount Credited`,`Payment Submit Type`,`Payment Key`,`Payment Transaction ID`,`Payment Currency Code`,`Payment Transaction Amount`,P.`Payment Type`,`Payment Last Updated Date`,`Payment Transaction Status`,`Payment Transaction Status Info`";


?>
