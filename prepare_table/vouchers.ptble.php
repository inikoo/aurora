<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 23 February 2019 at 21:36:56 GMT+8, Kuala Lumpur , Malaysia
 Copyright (c) 2019, Inikoo

 Version 3

*/


switch ($parameters['parent']) {
    case('store'):
        $where = sprintf(
            ' where  `Deal Store Key`=%d  and D.`Deal Campaign Key` is NULL ', $parameters['parent_key']
        );
        break;
    case('campaign'):
        $where = sprintf(
            ' where D.`Deal Campaign Key`=%d', $parameters['parent_key']
        );
        break;
    case('account'):

        $where = sprintf(' where true ');
        break;

    case('category'):



        $where = sprintf(
            ' where  `Deal Store Key`=%d', $parameters['parent_key']
        );

        break;

    default:
        $where = 'where false';
}


if (isset($parameters['elements_type'])) {
    switch ($parameters['elements_type']) {
        case 'status':

            $_elements      = '';
            $count_elements = 0;
            foreach (
                $parameters['elements'][$parameters['elements_type']]['items'] as $_key => $_value
            ) {
                if ($_value['selected']) {
                    $count_elements++;
                    $_elements .= ",'".addslashes($_key)."'";


                }
            }

            $_elements = preg_replace('/^\,/', '', $_elements);
            if ($_elements == '') {
                $where .= ' and false';
            } elseif ($count_elements < 4) {
                $where .= ' and `Deal Status` in ('.$_elements.')';


            }

            break;
      

            break;

    }
}


$wheref = '';
if ($parameters['f_field'] == 'name' and $f_value != '') {
    $wheref = sprintf(
        ' and `Deal Name` REGEXP "\\\\b%s" ', addslashes($f_value)
    );
}

$_order = $order;
$_dir   = $order_direction;


if ($order == 'name') {
    $order = '`Deal Name`';
} elseif ($order == 'orders') {
    $order = '`Deal Total Acc Used Orders`';
} elseif ($order == 'customers') {
    $order = '`Deal Total Acc Used Customers`';
} elseif ($order == 'from') {
    $order = '`Deal Begin Date`';
} elseif ($order == 'to') {
    $order = '`Deal Expiration Date`';
} elseif ($order == 'description') {
    $order = '`Deal Term Allowances Label`';
} else {
    $order = '`Deal Key`';
}
$table  = '`Deal Dimension` D left join `Deal Campaign Dimension` C on (C.`Deal Campaign Key`=D.`Deal Campaign Key`) left join `Store Dimension` on (`Deal Store Key`=`Store Key`) ';
$fields = "`Deal Key`,`Deal Name`,`Deal Term Allowances Label`,`Deal Store Key`,D.`Deal Campaign Key`,`Deal Status`,`Deal Begin Date`,`Deal Expiration Date`,
`Deal Total Acc Used Orders`,`Deal Total Acc Used Customers`,`Store Bulk Discounts Campaign Key`";


$sql_totals = "select count(*) as num from $table $where ";


?>
