<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 23 April 2015 19:03:28 BST, Sheffield UK

 Copyright (c) 2015, Inikoo

 Version 2.0
*/


include_once 'utils/date_functions.php';
$period_tag = get_interval_db_name($parameters['f_period']);

$group_by       = '';
$table
                = "`Product Dimension` P left join `Product Data` PD on (PD.`Product ID`=P.`Product ID`) left join `Product DC Data` PDCD on (PDCD.`Product ID`=P.`Product ID`) left join `Store Dimension` S on (`Product Store Key`=`Store Key`)";
$where_interval = '';
$wheref         = '';

$part_fields='';




switch ($parameters['parent']) {





    case('product'):
        $where = sprintf(
            " where variant_parent_id=%d ",$parameters['parent_key']
        );
        break;




    default:


}

if(isset($parameters['elements_type'])){


    switch ($parameters['elements_type']) {

        case 'status':
            $_elements      = '';
            $count_elements = 0;
            foreach (
                $parameters['elements'][$parameters['elements_type']]['items'] as $_key => $_value
            ) {
                if ($_value['selected']) {
                    $count_elements++;
                    $_elements .= ','.prepare_mysql($_key);

                }
            }
            $_elements = preg_replace('/^\,/', '', $_elements);
            if ($_elements == '') {
                $where .= ' and false';
            } elseif ($count_elements < 4) {
                $where .= ' and P.`Product Status` in ('.$_elements.')';
            }


            break;


    }



}

if (isset($parameters['f_period'])) {

    $db_period = get_interval_db_name($parameters['f_period']);
    if (in_array(
        $db_period, array(
        'Total',
        '3 Year'
    )
    )) {
        $yb_fields = "'' as sales_1y,'' as dc_sales_1y,'' as qty_invoiced_1yb";

    } else {
        $yb_fields = "`Product $db_period Acc 1YB Invoiced Amount` as sales_1yb, `Product DC $db_period Acc 1YB Invoiced Amount` as dc_sales_1yb,  `Product $db_period Acc 1YB Quantity Invoiced` as qty_invoiced_1yb";
    }

} else {
    $db_period = 'Total';
    $yb_fields = "'' as sales_1y,'' as qty_invoiced_1yb";
}


if ($parameters['f_field'] == 'code' and $f_value != '') {
    $wheref .= " and  P.`Product Code` like '".addslashes($f_value)."%'";
} elseif ($parameters['f_field'] == 'description' and $f_value != '') {
    $wheref .= " and  `Product Name` like '%".addslashes($f_value)."%'";
}


$_dir   = $order_direction;
$_order = $order;


if ($order == 'stock') {
    $order = '`Product Availability`';
} elseif ($order == 'code') {
    $order = '`Product Code File As`';
} elseif ($order == 'name') {
    $order = '`Product Name`';
} elseif ($order == 'available_for') {
    $order = '`Product Available Days Forecast`';
} elseif ($order == 'profit') {

    $order = '`Product '.$period_tag.' Acc Profit`';


} elseif ($order == 'sales') {
    $order = '`Product '.$period_tag.' Acc Invoiced Amount`';
}elseif ($order == 'dc_sales') {
    $order = '`Product DC '.$period_tag.' Acc Invoiced Amount`';
} elseif ($order == 'sales_reorder') {
    $order = '`Product '.$period_tag.' Acc Invoiced Amount`';
} elseif ($_order == 'delta_sales') {
    $order = '`Product '.$period_tag.' Acc Invoiced Amount`';

} elseif ($order == 'margin') {
    $order = '`Product '.$period_tag.' Margin`';


} elseif ($order == 'sold') {
    $order = '`Product '.$period_tag.' Acc Quantity Invoiced`';
} elseif ($order == 'sold_reorder') {
    $order = '`Product '.$period_tag.' Acc Quantity Invoiced`';
} elseif ($order == 'family') {
    $order = '`Product Family`Code';
} elseif ($order == 'expcode') {
    $order = '`Product Tariff Code`';
} elseif ($order == 'parts') {
    $order = '`Product XHTML Parts`';
} elseif ($order == 'gmroi') {
    $order = '`Product GMROI`';
} elseif ($order == 'state') {
    $order = '`Product Sales Type`';
} elseif ($order == 'web') {
    $order = '`Product Web Configuration`';
} elseif ($order == 'stock_state') {
    $order = '`Product Availability State`';
} elseif ($order == 'stock_forecast') {
    $order = '`Product Available Days Forecast`';
}  elseif ($order == 'store') {
    $order = '`Store Code`';
} elseif ($order == 'price') {
    $order = '`Product Price`';
} elseif ($order == 'from') {
    $order = '`Product Valid From`';
} elseif ($order == 'to') {
    $order = '`Product Valid To`';
} elseif ($order == 'last_update') {
    $order = '`Product Last Updated`';
} elseif ($order == 'package_weight') {
    $order = '`Product Package Weight`';
} elseif ($order == 'unit_weight') {
    $order = '`Product Unit Weight`';
}elseif ($order == '1m_avg_sold_over_1y') {
    $order = '`Product 1 Year Acc Quantity Invoiced`';
} elseif ($order == 'days_available_over_1y') {
    $order = '`Product 1 Year Acc Days On Sale`';
} elseif ($order == 'percentage_available_1y') {
    $order
        = '`Product 1 Year Acc Days Available`/`Product 1 Year Acc Days On Sale`';
} elseif ($order == 'qty_invoiced') {
    $order = "`Product $db_period Acc Quantity Invoiced`";
} elseif ($order == 'qty_invoiced_1yb') {
    $order
        = "(`Product $db_period Acc Quantity Invoiced`-`Product $db_period Acc 1YB Quantity Invoiced` )/`Product $db_period Acc 1YB Quantity Invoiced` ";
} elseif ($order == 'delta_sales_year0') {
    $order
        = "(-1*(`Product Year To Day Acc Invoiced Amount`-`Product Year To Day Acc 1YB Invoiced Amount`)/`Product Year To Day Acc 1YB Invoiced Amount`)";
} elseif ($order == 'delta_sales_year1') {
    $order
        = "(-1*(`Product 2 Year Ago Invoiced Amount`-`Product 1 Year Ago Invoiced Amount`)/`Product 2 Year Ago Invoiced Amount`)";
} elseif ($order == 'delta_sales_year2') {
    $order
        = "(-1*(`Product 3 Year Ago Invoiced Amount`-`Product 2 Year Ago Invoiced Amount`)/`Product 3 Year Ago Invoiced Amount`)";
} elseif ($order == 'delta_sales_year3') {
    $order
        = "(-1*(`Product 4 Year Ago Invoiced Amount`-`Product 3 Year Ago Invoiced Amount`)/`Product 4 Year Ago Invoiced Amount`)";
} elseif ($order == 'sales_year0') {
    $order = "`Product Year To Day Acc Invoiced Amount`";
} elseif ($order == 'sales_year1') {
    $order = "`Product 1 Year Ago Invoiced Amount`";
} elseif ($order == 'sales_year2') {
    $order = "`Product 2 Year Ago Invoiced Amount`";
} elseif ($order == 'sales_year3') {
    $order = "`Product 3 Year Ago Invoiced Amount`";
} elseif ($order == 'sales_year4') {
    $order = "`Product 4 Year Ago Invoiced Amount`";
} elseif ($order == 'delta_sales_quarter0') {
    $order
        = "(-1*(`Product Quarter To Day Acc Invoiced Amount`-`Product Quarter To Day Acc 1YB Invoiced Amount`)/`Product Quarter To Day Acc 1YB Invoiced Amount`)";
} elseif ($order == 'delta_sales_quarter1') {
    $order
        = "(-1*(`Product 1 Quarter Ago YB Invoiced Amount`-`Product 1 Quarter Ago Invoiced Amount`)/`Product 1 Quarter Ago 1YB Invoiced Amount`)";
} elseif ($order == 'delta_sales_quarter2') {
    $order
        = "(-1*(`Product 2 Quarter Ago YB Invoiced Amount`-`Product 2 Quarter Ago Invoiced Amount`)/`Product 2 Quarter Ago 1YB Invoiced Amount`)";
} elseif ($order == 'delta_sales_quarter3') {
    $order
        = "(-1*(`Product 3 Quarter Ago YB Invoiced Amount`-`Product 3 Quarter Ago Invoiced Amount`)/`Product 3 Quarter Ago 1YB Invoiced Amount`)";
} elseif ($order == 'sales_quarter0') {
    $order = "`Product Quarter To Day Acc Invoiced Amount`";
} elseif ($order == 'sales_quarter1') {
    $order = "`Product 1 Quarter Ago Invoiced Amount`";
} elseif ($order == 'sales_quarter2') {
    $order = "`Product 2 Quarter Ago Invoiced Amount`";
} elseif ($order == 'sales_quarter3') {
    $order = "`Product 3 Quarter Ago Invoiced Amount`";
} elseif ($order == 'sales_quarter4') {
    $order = "`Product 4 Quarter Ago Invoiced Amount`";
} elseif ($order == 'sales_total') {
    $order = "`Product Total Acc Invoiced Amount`";
} elseif ($order == 'dispatched_total') {
    $order = "`Product Total Acc Quantiy Invoiced`";
} elseif ($order == 'customer_total') {
    $order = "`Product Total Acc Customers`";
} elseif ($order == 'percentage_repeat_customer_total') {
    $order = "percentage_repeat_customer_total";
}elseif ($order == 'outers_per_carton') {
    $order = "`Product Outers Per Carton`";
} else {
    $order = 'P.`Product ID`';
}


$sql_totals
    = "select count(distinct  P.`Product ID`) as num from $table $where";


$fields
    = " 
    
    `Product Outers Per Carton`,is_variant,
    `Product Type`,`Product Customer Key`, `Product Total Acc Quantity Ordered`,P.`Product ID`,P.`Product Code`,`Product Name`,`Product Price`,`Store Currency Code`,`Store Code`,S.`Store Key`,`Store Name`,`Product Web Configuration`,`Product Availability`,`Product Web State`,`Product Cost`,`Product Number of Parts`,P.`Product Status`,`Product Units Per Case`,
`Product 1 Year Ago Invoiced Amount`,`Product 2 Year Ago Invoiced Amount`,`Product 3 Year Ago Invoiced Amount`,`Product 4 Year Ago Invoiced Amount`,`Product 5 Year Ago Invoiced Amount`,
`Product 1 Quarter Ago Invoiced Amount`,`Product 2 Quarter Ago Invoiced Amount`,`Product 3 Quarter Ago Invoiced Amount`,`Product 4 Quarter Ago Invoiced Amount`,
`Product 1 Quarter Ago 1YB Invoiced Amount`,`Product 2 Quarter Ago 1YB Invoiced Amount`,`Product 3 Quarter Ago 1YB Invoiced Amount`,`Product 4 Quarter Ago 1YB Invoiced Amount`,
`Product 1 Year Ago Quantity Invoiced`,`Product 2 Year Ago Quantity Invoiced`,`Product 3 Year Ago Quantity Invoiced`,`Product 4 Year Ago Quantity Invoiced`,`Product 5 Year Ago Quantity Invoiced`,
`Product 1 Quarter Ago Quantity Invoiced`,`Product 2 Quarter Ago Quantity Invoiced`,`Product 3 Quarter Ago Quantity Invoiced`,`Product 4 Quarter Ago Quantity Invoiced`,
`Product 1 Quarter Ago 1YB Quantity Invoiced`,`Product 2 Quarter Ago 1YB Quantity Invoiced`,`Product 3 Quarter Ago 1YB Quantity Invoiced`,`Product 4 Quarter Ago 1YB Quantity Invoiced`,

`Product Total Acc Invoiced Amount`,`Product Total Acc Quantity Invoiced`,`Product Total Acc Customers`,`Product Total Acc Repeat Customers`,
`Product Year To Day Acc Invoiced Amount`,`Product Year To Day Acc 1YB Invoiced Amount`,
`Product Quarter To Day Acc Invoiced Amount`,`Product Quarter To Day Acc 1YB Invoiced Amount`,
if(`Product Total Acc Customers`=0,0,  (`Product Total Acc Repeat Customers`/`Product Total Acc Customers`)) percentage_repeat_customer_total,`Product RRP`,`Product Unit Label`,

`Product $db_period Acc Invoiced Amount` as sales,`Product DC $db_period Acc Invoiced Amount` as dc_sales,`Product $db_period Acc Quantity Invoiced` as qty_invoiced,
 $yb_fields  $part_fields


";

$sql
    = "select $fields from $table $where $wheref $group_by order by $order $order_direction limit $start_from,$number_results";

