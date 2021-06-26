<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 18 Jun 2021 01:16 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2021, Inikoo

 Version 2.0
*/


$currency = '';
$group_by = ' group by C.`Customer Key` ';


$table = '`Customer Fulfilment Dimension` CFD  left join `Customer Dimension` C on (CFD.`Customer Fulfilment Customer Key`=C.`Customer Key`) 
    left join `Store Dimension` S on (S.`Store Key`=C.`Customer Store Key`)
    ';

$where = sprintf(' where  `Customer Fulfilment Warehouse Key`=%d ', $parameters['parent_key']);
if(isset($parameters['extra']) and $parameters['extra']=='only_with_stored_parts'){
    $where .= ' where  `Customer Fulfilment Status`="Storing" ';

}

$filter_msg = '';
$wheref     = '';


if (($parameters['f_field'] == 'name') and $f_value != '') {
    $wheref = sprintf(
        ' and `Customer Name` REGEXP "\\\\b%s" ', addslashes($f_value)
    );


}

$_order = $order;
$_dir   = $order_direction;
if ($order == 'name') {
    $order = '`Customer Name`';
} elseif ($order == 'formatted_id') {
    $order = 'C.`Customer Key`';
} elseif ($order == 'location') {
    $order = '`Customer Location`';
} else {
    $order = '`Customer Name`';
}


$sql_totals = "select count(Distinct C.`Customer Key`) as num from $table  $where ";

include_once 'utils/object_functions.php';



$fields = 'C.`Customer Key`,`Customer Name`,`Customer Location`,`Customer Type by Activity`,`Customer Store Key`,`Customer Fulfilment Status`
  
  ';

