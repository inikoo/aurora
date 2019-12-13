<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 24 May 2018 at 23:02:46 GMT+8, Kuala Lumpur Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/

$currency = '';
$where    = 'where true';
$table    = '`Prospect Dimension` P ';
$group_by = '';


if ($parameters['parent'] == 'store') {
    include_once('class.Store.php');

    if (in_array($parameters['parent_key'], $user->stores)) {
        $where_stores = sprintf(
            ' and  `Prospect Store Key`=%d ', $parameters['parent_key']
        );
    } else {
        $where_stores = ' and false';
    }

    $store    = new Store($parameters['parent_key']);
    $currency = $store->data['Store Currency Code'];
    $where    .= $where_stores;
} else {
    exit();
}

switch ($parameters['elements_type']) {

    case 'status':
        $_elements      = '';
        $count_elements = 0;
        foreach ($parameters['elements'][$parameters['elements_type']]['items'] as $_key => $_value) {
            if ($_value['selected']) {
                $count_elements++;
                $_elements .= ','.prepare_mysql($_key);

            }
        }


        $_elements = preg_replace('/^\,/', '', $_elements);
        if ($_elements == '') {
            $where .= ' and false';
        } elseif ($count_elements < 6) {
            $where .= ' and `Prospect Status` in ('.$_elements.')';
        }
        break;


}


$filter_msg = '';
$wheref     = '';


if (($parameters['f_field'] == 'name') and $f_value != '') {
    $wheref = sprintf(
        ' and `Prospect Name` REGEXP "[[:<:]]%s" ', addslashes($f_value)
    );


}

$_order = $order;
$_dir   = $order_direction;
if ($order == 'name') {
    $order = '`Prospect Name`';
} elseif ($order == 'location') {
    $order = '`Prospect Location`';
} elseif ($order == 'status') {
    $order = '`Prospect Status`';
} elseif ($order == 'email') {
    $order = '`Prospect Main Plain Email`';
} elseif ($order == 'contact_since') {
    $order = '`Prospect First Contacted Date`';
} elseif ($order == 'Status') {
    $order = '`Prospect Status`';
} elseif ($order == 'telephone') {
    $order = '`Prospect Main Plain Telephone`';
} elseif ($order == 'mobile') {
    $order = '`Prospect Main Plain Mobile`';
} elseif ($order == 'contact_name') {
    $order = '`Prospect Main Contact Name`';
} elseif ($order == 'company_name') {
    $order = '`Prospect Company Name`';
} elseif ($order == 'address') {
    $order = '`Prospect Main Plain Address`';
} elseif ($order == 'town') {
    $order = '`Prospect Main Town`';
} elseif ($order == 'postcode') {
    $order = '`Prospect Main Postal Code`';
} elseif ($order == 'region') {
    $order = '`Prospect Main Country First Division`';
} elseif ($order == 'country') {
    $order = '`Prospect Main Country`';
} else {
    $order = '`Prospect Key`';
}


$sql_totals = "select count(Distinct P.`Prospect Key`) as num from $table  $where ";
$fields     = ' `Prospect Key`,`Prospect Name`,`Prospect Location`,`Prospect Main Plain Email`,`Prospect Main Plain Telephone`,`Prospect Main Contact Name`,`Prospect Status`,
 `Prospect First Contacted Date`,`Prospect Store Key`,`Prospect Company Name`,`Prospect Contact Address Formatted`,`Prospect Main XHTML Telephone`,`Prospect Main XHTML Mobile`
 ';
