<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:  6 February 2018 at 15:50:38 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/


$group_by = '';
$wheref   = '';

$currency = '';


$where = 'where `Order State`="InBasket" and `Customer Main Plain Email`!="" and `Customer Send Basket Emails`="Yes" ';
$table = '`Order Dimension` O  left join `Customer Dimension` on (`Order Customer Key`=`Customer Key`) ';


if ($parameters['parent'] == 'mailshot') {

    $mailshot = get_object('mailshot', $parameters['parent_key']);

    $metadata  = $mailshot->get('Metadata');
    $days      = 0;
    $store_key = 0;
    // todo
    /*

    $sql=sprintf('select `Email Campaign Abandoned Cart Days Inactive in Basket`,`Email Campaign Store Key` from `Email Campaign Abandoned Cart Dimension` left join `Email Campaign Dimension` on (`Email Campaign Abandoned Cart Email Campaign Key`=`Email Campaign Key`)  where `Email Campaign Abandoned Cart Email Campaign Key`=%d ',
                 $parameters['parent_key']
    );


    if ($result=$db->query($sql)) {
        if ($row = $result->fetch()) {
            $days=$row['Email Campaign Abandoned Cart Days Inactive in Basket'];
            $store_key=$row['Email Campaign Store Key'];
    	}
    }else {
    	print_r($error_info=$db->errorInfo());
    	print "$sql\n";
    	exit;
    }
*/

    $where .= sprintf(' and `Order Store Key`=%d  and `Order Last Updated by Customer`<= CURRENT_DATE - INTERVAL %d DAY ', $mailshot->get('Store Key'), $metadata['Days Inactive in Basket']);


} else {
    exit('error abandoned_cart.mail_list E.l.1');
}


if (($parameters['f_field'] == 'customer') and $f_value != '') {
    $wheref = sprintf('  and  `Order Customer Name`  REGEXP "\\\\b%s" ', addslashes($f_value));
} elseif ($parameters['f_field'] == 'number' and $f_value != '') {
    $wheref = " and  `Order Public ID`  like '".addslashes($f_value)."%'";
}


$_order = $order;
$_dir   = $order_direction;


if ($order == 'order') {
    $order = '`Order File As`';
} elseif ($order == 'formatted_id') {
    $order = '`Customer Key`';
} elseif ($order == 'inactive_since') {
    $order = 'O.`Order Last Updated by Customer`';
} elseif ($order == 'email') {
    $order = '`Customer Main Plain Email`';
} elseif ($order == 'name') {
    $order = '`Customer Name`';
} elseif ($order == 'inactive_days') {
    $order = 'DATEDIFF(NOW(), `Order Last Updated by Customer`) ';
} else {
    $order = '`Customer Main Plain Email`';
}

$fields
    = '`Order Created Date`,`Order Invoiced`,`Order Number Items`,`Order Store Key`,`Order Balance Total Amount`,`Order Payment State`,`Order Type`,`Order Currency Exchange`,`Order Currency`,O.`Order Key`,O.`Order Public ID`,`Order Customer Key`,`Order Customer Name`,O.`Order Last Updated by Customer`,O.`Order Date`,`Order Total Amount`,
    DATEDIFF(NOW(), `Order Last Updated by Customer`)  as inactive_days ,`Customer Key`,`Customer Store Key`,`Customer Name`,`Customer Main Contact Name`,`Customer Main Plain Email`,`Customer Company Name`
   
    
    
    ';

$sql_totals = "select count(Distinct O.`Order Key`) as num from $table $where";

$sql = "select $fields from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";


?>
