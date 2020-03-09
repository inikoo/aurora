<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: Wed 16 Oct 2019 13:51:23 +0800 MYT, Kuala Lumpur, Malaysia

 Copyright (c) 2019, Inikoo

 Version 2.0
*/


include_once 'utils/date_functions.php';

// if you change this you need to change data_feed.php
$table          =
    " `Customer Portfolio Fact` CPF left join    `Product Dimension` P  on (`Customer Portfolio Product ID`=P.`Product ID`) left join `Product Data` PD on (PD.`Product ID`=P.`Product ID`) left join `Product DC Data` PDCD on (PDCD.`Product ID`=P.`Product ID`) 
    left join `Store Dimension` S on (`Product Store Key`=`Store Key`)
    left join `Page Store Dimension` W on (`Product Webpage Key`=`Page Key`)
    left join `Image Dimension` on (`Image Key`=P.`Product Main Image Key`)
    ";
$where_interval = '';
$wheref         = '';

$group_by = ' group by CPF.`Customer Portfolio Product ID`';
$where    = sprintf(
    ' where   `Customer Portfolio Customer Key`=%d', $parameters['parent_key']
);

$where .= " and `Customer Portfolio Customers State`='Active'";


if (isset($parameters['elements_type'])) {


    switch ($parameters['elements_type']) {

        case 'availability_state':
            $_elements      = '';
            $count_elements = 0;
            foreach (
                $parameters['elements'][$parameters['elements_type']]['items'] as $_key => $_value
            ) {
                if ($_value['selected']) {
                    $count_elements++;
                    if ($_key == 'Ok') {
                        $_elements .= ',"Excess","Normal","OnDemand"';

                    }
                    if ($_key == 'OutofStock') {
                        $_elements .= ',"OutofStock","Error"';

                    } else {
                        $_elements .= ','.prepare_mysql($_key);

                    }

                }
            }
            $_elements = preg_replace('/^\,/', '', $_elements);
            if ($_elements == '') {
                $where .= ' and false';
            } elseif ($count_elements < 4) {
                $where .= ' and P.`Product Availability State` in ('.$_elements.')';
            }


            break;


    }


}


if ($parameters['f_field'] == 'code' and $f_value != '') {
    $wheref .= " and  P.`Product Code` like '".addslashes($f_value)."%'";
} elseif ($parameters['f_field'] == 'description' and $f_value != '') {
    $wheref .= " and  `Product Name` like '%".addslashes($f_value)."%'";
}


$_dir   = $order_direction;
$_order = $order;


if ($order == 'code') {
    $order = '`Product Code File As`';
} elseif ($order == 'reference') {
    $order = '`Customer Portfolio Reference`';
}elseif ($order == 'name') {
    $order = '`Product Name`';
} elseif ($order == 'status') {
    $order = '`Product Status`';
} elseif ($order == 'invoices') {
    $order = 'invoices';
} elseif ($order == 'amount') {
    $order = '`Customer Portfolio Amount`';
} elseif ($order == 'until') {
    $order = '`Customer Portfolio Removed Date`';
} elseif ($order == 'last_order') {
    $order = '`Customer Portfolio Last Ordered`';
} elseif ($order == 'orders') {
    $order = '`Customer Portfolio Orders`';
} elseif ($order == 'qty') {
    $order = '`Customer Portfolio Ordered Quantity`';
} elseif ($order == 'clients') {
    $order = '`Customer Portfolio Clients`';
} else {
    $order = 'P.`Product ID`';
}


$sql_totals = "select count(distinct  CPF.`Customer Portfolio Key`) as num from $table $where";


$fields = "P.`Product ID`,P.`Product Code`,`Product Name`,`Product Price`,`Store Currency Code`,`Store Code`,S.`Store Key`,`Product RRP`,`Product Unit Label`,`Product Availability State`,`Customer Portfolio Customer Key`,`Customer Portfolio Removed Date`,
    `Store Name`,`Product Web Configuration`,`Product Availability`,`Product Web State`,`Product Cost`,`Product Number of Parts`,P.`Product Status`,`Product Units Per Case`,`Image Key`,`Image File Format`,
       `Customer Portfolio Creation Date`,`Customer Portfolio Last Ordered`,`Customer Portfolio Orders`,`Customer Portfolio Amount` ,`Customer Portfolio Ordered Quantity`,`Customer Portfolio Clients`,`Webpage URL`,`Customer Portfolio Reference`,`Customer Portfolio Key`
  

";



