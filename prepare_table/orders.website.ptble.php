<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  15 February 2017 at 15:50:45 GMT+8, Cyberjaya , Malaysia
 Copyright (c) 2017, Inikoo

 Version 3

*/



$group_by = '';
$wheref   = '';

$currency = '';


$where = 'where `Order Class`="InWebsite" ';
$table = '`Order Dimension` O ';




if ($parameters['parent'] == 'store') {
    if (is_numeric($parameters['parent_key']) and in_array($parameters['parent_key'], $user->stores)) {
        $where .= sprintf(' and  `Order Store Key`=%d ', $parameters['parent_key']);
        if (!isset($store)) {
            include_once 'class.Store.php';
            $store = new Store($parameters['parent_key']);
        }
        $currency = $store->data['Store Currency Code'];
    } else {
        $where .= sprintf(' and  false');
    }


} elseif ($parameters['parent'] == 'account') {
    if (is_numeric($parameters['parent_key']) and in_array(
            $parameters['parent_key'], $user->stores
        )
    ) {

        if (count($user->stores) == 0) {
            $where .= ' and false';
        } else {

            $where .= sprintf('and  `Order Store Key` in (%s)  ', join(',', $user->stores));
        }
    }
}


if (($parameters['f_field'] == 'customer') and $f_value != '') {
    $wheref = sprintf('  and  `Order Customer Name`  REGEXP "[[:<:]]%s" ', addslashes($f_value));
}  elseif ($parameters['f_field'] == 'number' and $f_value != '') {
    $wheref = " and  `Order Public ID`  like '".addslashes($f_value)."%'";
}


$_order = $order;
$_dir   = $order_direction;


if ($order == 'public_id') {
    $order = '`Order File As`';
} elseif ($order == 'last_updated') {
    $order = 'O.`Order Last Updated Date`';
} elseif ($order == 'date') {
    $order = 'O.`Order Created Date`';
} elseif ($order == 'customer') {
    $order = 'O.`Order Customer Name`';
} elseif ($order == 'total_amount') {
    $order = 'O.`Order Total Amount`';
} elseif ($order == 'idle_time') {
    $order = 'idle_time';
} else {
    $order = 'O.`Order Key`';
}

$fields
    = '`Order Created Date`,`Order Invoiced`,`Order Number Items`,`Order Store Key`,`Order Balance Total Amount`,`Order Current Payment State`,`Order Type`,`Order Currency Exchange`,`Order Currency`,O.`Order Key`,O.`Order Public ID`,`Order Customer Key`,`Order Customer Name`,O.`Order Last Updated Date`,O.`Order Date`,`Order Total Amount`,
    DATEDIFF(NOW(), `Order Last Updated Date`)  as idle_time 
   
    
    
    ';

$sql_totals = "select count(Distinct O.`Order Key`) as num from $table $where";
$sql="select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";


?>
