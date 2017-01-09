
<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 14 November 2016 at 14:26:58 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

$group_by = '';


$where = sprintf(
    $where = sprintf("where B.`Category Key`=%d  ", $parameters['parent_key'])
);


$filter_msg = '';

if ($parameters['f_field'] == 'code' and $f_value != '') {
    $wheref = " and  `Category Code` like '".addslashes($f_value)."%'";
} elseif ($parameters['f_field'] == 'label' and $f_value != '') {
    $wheref = sprintf(
        ' and `Category Label` REGEXP "[[:<:]]%s" ', addslashes($f_value)
    );
} else {
    $wheref = '';
}


switch ($parameters['elements_type']) {

    case 'status':
        $_elements      = '';
        $count_elements = 0;
        foreach (
            $parameters['elements'][$parameters['elements_type']]['items'] as $_key => $_value
        ) {
            if ($_value['selected']) {
                $count_elements++;

                if ($_key == 'InProcess') {
                    $_key = 'In Process';
                }

                $_elements .= ','.prepare_mysql($_key);

            }
        }
        $_elements = preg_replace('/^\,/', '', $_elements);
        if ($_elements == '') {
            $where .= ' and false';
        } elseif ($count_elements < 4) {
            $where .= ' and `Product Category Status` in ('.$_elements.')';
        }


        break;


}


$db_period = get_interval_db_name($parameters['f_period']);
if (in_array(
    $db_period, array(
    'Total',
    '3 Year'
)
)) {
    $yb_fields = " '' as sales_1yb, '' as qty_invoiced_1yb ";

} else {
    $yb_fields
        = "`Product Category $db_period Acc 1YB Invoiced Amount` as sales_1yb,  `Product Category $db_period Acc 1YB Quantity Invoiced` as qty_invoiced_1yb";
}


$_dir   = $order_direction;
$_order = $order;

if ($order == 'code') {
    $order = '`Category Code`';
} elseif ($order == 'label') {
    $order = '`Category Label`';
} elseif ($order == 'products') {
    $order = 'products';
} elseif ($order == 'status') {
    $order = '`Product Category Status`';
} elseif ($order == 'active') {
    $order = '`Product Category Active Products`';
} elseif ($order == 'in_process') {
    $order = '`Product Category In Process Products`';
} elseif ($order == 'suspended') {
    $order = '`Product Category Suspended Products`';
} elseif ($order == 'discontinued') {
    $order = '`Product Category Discontinued Products`';
} elseif ($order == 'discontinuing') {
    $order = '`Product Category Discontinuing Products`';
} elseif ($order == 'sales') {
    $order = "`Product Category $db_period Acc Invoiced Amount`";
} elseif ($order == 'sales_1yb') {
    $order
        = "(`Product Category $db_period Acc Invoiced Amount`-`Product Category $db_period Acc 1YB Invoiced Amount` )/`Product Category $db_period Acc 1YB Invoiced Amount` ";
} elseif ($order == 'qty_invoiced') {
    $order = "`Product Category $db_period Acc Quantity Invoiced`";
} elseif ($order == 'qty_invoiced_1yb') {
    $order
        = "(`Product Category $db_period Acc Quantity Invoiced`-`Product Category $db_period Acc 1YB Quantity Invoiced` )/`Product Category $db_period Acc 1YB Quantity Invoiced` ";
} elseif ($order == 'delta_sales_year0') {
    $order
        = "(-1*(`Product Category Year To Day Acc Invoiced Amount`-`Product Category Year To Day Acc 1YB Invoiced Amount`)/`Product Category Year To Day Acc 1YB Invoiced Amount`)";
} elseif ($order == 'delta_sales_year1') {
    $order
        = "(-1*(`Product Category 2 Year Ago Invoiced Amount`-`Product Category 1 Year Ago Invoiced Amount`)/`Product Category 2 Year Ago Invoiced Amount`)";
} elseif ($order == 'delta_sales_year2') {
    $order
        = "(-1*(`Product Category 3 Year Ago Invoiced Amount`-`Product Category 2 Year Ago Invoiced Amount`)/`Product Category 3 Year Ago Invoiced Amount`)";
} elseif ($order == 'delta_sales_year3') {
    $order
        = "(-1*(`Product Category 4 Year Ago Invoiced Amount`-`Product Category 3 Year Ago Invoiced Amount`)/`Product Category 4 Year Ago Invoiced Amount`)";
} elseif ($order == 'sales_year0') {
    $order = "`Product Category Year To Day Acc Invoiced Amount`";
} elseif ($order == 'sales_year1') {
    $order = "`Product Category 1 Year Ago Invoiced Amount`";
} elseif ($order == 'sales_year2') {
    $order = "`Product Category 2 Year Ago Invoiced Amount`";
} elseif ($order == 'sales_year3') {
    $order = "`Product Category 3 Year Ago Invoiced Amount`";
} elseif ($order == 'sales_year4') {
    $order = "`Product Category 4 Year Ago Invoiced Amount`";
} elseif ($order == 'delta_sales_quarter0') {
    $order
        = "(-1*(`Product Category Quarter To Day Acc Invoiced Amount`-`Product Category Quarter To Day Acc 1YB Invoiced Amount`)/`Product Category Quarter To Day Acc 1YB Invoiced Amount`)";
} elseif ($order == 'delta_sales_quarter1') {
    $order
        = "(-1*(`Product Category 1 Quarter Ago YB Invoiced Amount`-`Product Category 1 Quarter Ago Invoiced Amount`)/`Product Category 1 Quarter Ago 1YB Invoiced Amount`)";
} elseif ($order == 'delta_sales_quarter2') {
    $order
        = "(-1*(`Product Category 2 Quarter Ago YB Invoiced Amount`-`Product Category 2 Quarter Ago Invoiced Amount`)/`Product Category 2 Quarter Ago 1YB Invoiced Amount`)";
} elseif ($order == 'delta_sales_quarter3') {
    $order
        = "(-1*(`Product Category 3 Quarter Ago YB Invoiced Amount`-`Product Category 3 Quarter Ago Invoiced Amount`)/`Product Category 3 Quarter Ago 1YB Invoiced Amount`)";
} elseif ($order == 'sales_quarter0') {
    $order = "`Product Category Quarter To Day Acc Invoiced Amount`";
} elseif ($order == 'sales_quarter1') {
    $order = "`Product Category 1 Quarter Ago Invoiced Amount`";
} elseif ($order == 'sales_quarter2') {
    $order = "`Product Category 2 Quarter Ago Invoiced Amount`";
} elseif ($order == 'sales_quarter3') {
    $order = "`Product Category 3 Quarter Ago Invoiced Amount`";
} elseif ($order == 'sales_quarter4') {
    $order = "`Product Category 4 Quarter Ago Invoiced Amount`";
}elseif ($order == 'online') {
    $order = "online";
} elseif ($order == 'out_of_stock') {
    $order = "`Product Category Active Web Out of Stock`";
} elseif ($order == 'percentage_out_of_stock') {
    $order = "`Product Category Active Web Out of Stock`/online";
} else {
    $order = '`Category Code`';
}


$fields = "
`Page Key`,`Page Code`,`Page State`,
`Product Category Active Web For Sale`,`Product Category Active Web Out of Stock`,`Product Category Active Web For Sale`+`Product Category Active Web Out of Stock` as online,
    P.`Product Category Key`,C.`Category Code`,`Category Label`,C.`Category Key`,`Category Store Key`,
    (`Product Category Active Products`+`Product Category Discontinuing Products`) as products,
    `Category Number Subjects` as subjects,
    `Product Category Active Products`,`Product Category Status`,
`Product Category Active Products`,`Product Category In Process Products`,`Product Category Suspended Products`,`Product Category Discontinued Products`,`Product Category Discontinuing Products`,
`Product Category $db_period Acc Invoiced Amount` as sales,`Product Category $db_period Acc Quantity Invoiced` as qty_invoiced,
`Product Category Year To Day Acc Invoiced Amount`,`Product Category Year To Day Acc 1YB Invoiced Amount`,`Product Category 1 Year Ago Invoiced Amount`,`Product Category 2 Year Ago Invoiced Amount`,`Product Category 3 Year Ago Invoiced Amount`,`Product Category 4 Year Ago Invoiced Amount`,`Product Category 5 Year Ago Invoiced Amount`,
`Product Category Quarter To Day Acc Invoiced Amount`,`Product Category Quarter To Day Acc 1YB Invoiced Amount`,`Product Category 1 Quarter Ago Invoiced Amount`,`Product Category 2 Quarter Ago Invoiced Amount`,`Product Category 3 Quarter Ago Invoiced Amount`,`Product Category 4 Quarter Ago Invoiced Amount`,
`Product Category 1 Quarter Ago 1YB Invoiced Amount`,`Product Category 2 Quarter Ago 1YB Invoiced Amount`,`Product Category 3 Quarter Ago 1YB Invoiced Amount`,`Product Category 4 Quarter Ago 1YB Invoiced Amount`,
`Product Category Currency Code`,$yb_fields";
$table
    = '  `Category Bridge` B left join    `Category Dimension` C   on (B.`Subject Key`=C.`Category Key` and `Subject`="Category")  left join `Product Category Dimension` P on (P.`Product Category Key`=C.`Category Key`) left join `Product Category Data` D on (D.`Product Category Key`=C.`Category Key`) left join `Product Category DC Data` DC on (DC.`Product Category Key`=C.`Category Key`)
    left join `Page Store Dimension` on (`Webpage Scope Key`=B.`Subject Key` and `Webpage Scope`="Category Products"  )
    
    ';

$sql_totals
    = "select count(distinct C.`Category Key`) as num from $table $where";


?>
