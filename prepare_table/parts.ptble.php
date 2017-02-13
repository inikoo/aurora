<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Refurbished: 30 September 2015 20:13:47 BST, Sheffield, UK
 Copyright (c) 2015, Inikoo

 Version 3

*/


include_once 'utils/date_functions.php';

$where      = "where true  ";
$table
            = "`Part Dimension` P left join `Part Data` D on (D.`Part SKU`=P.`Part SKU`) ";
$filter_msg = '';
$sql_type   = 'part';
$filter_msg = '';
$wheref     = '';

$fields = '';

//print_r($parameters);

if (isset($parameters['awhere']) and $parameters['awhere']) {

    $tmp = preg_replace('/\\\"/', '"', $awhere);
    $tmp = preg_replace('/\\\\\"/', '"', $tmp);
    $tmp = preg_replace('/\'/', "\'", $tmp);

    $raw_data = json_decode($tmp, true);
    //$raw_data['store_key']=$store;
    //print_r($raw_data);exit;
    list($where, $table, $sql_type) = parts_awhere($raw_data);

    $where_type     = '';
    $where_interval = '';
} elseif ($parameters['parent'] == 'list') {

    $sql = sprintf(
        "SELECT * FROM `List Dimension` WHERE `List Key`=%d", $parameters['parent_key']
    );
    //print $sql;exit;
    $res = mysql_query($sql);
    if ($list_data = mysql_fetch_assoc($res)) {
        $awhere = false;
        if ($list_data['List Type'] == 'Static') {

            $table
                = '`List Part Bridge` PB left join `Part Dimension` P  on (PB.`Part SKU`=P.`Part SKU`)';
            $where .= sprintf(' and `List Key`=%d ', $parameters['parent_key']);

        } else {
            $tmp = preg_replace('/\\\"/', '"', $list_data['List Metadata']);
            $tmp = preg_replace('/\\\\\"/', '"', $tmp);
            $tmp = preg_replace('/\'/', "\'", $tmp);

            $raw_data = json_decode($tmp, true);
            //print_r($raw_data);
            //$raw_data['store_key']=$store;
            list($where, $table, $sql_type) = parts_awhere($raw_data);
        }

    } else {

    }
} elseif ($parameters['parent'] == 'category') {


    $fields = ' "" as `Warehouse Code`,';

    $where      = sprintf(
        " where `Subject`='Part' and  `Category Key`=%d", $parameters['parent_key']
    );
    $table
                = ' `Category Bridge` left join  `Part Dimension` P on (`Subject Key`=`Part SKU`) left join `Part Data` D on (D.`Part SKU`=P.`Part SKU`)';
    $where_type = '';


} elseif ($parameters['parent'] == 'account') {


} elseif ($parameters['parent'] == 'material') {


    $where = sprintf(" where `Material Key`=%d", $parameters['parent_key']);
    $table
           = ' `Part Material Bridge` B left join  `Part Dimension` P on (B.`Part SKU`=P.`Part SKU`) left join `Part Data` D on (D.`Part SKU`=P.`Part SKU`)';


} else {
    exit("parent not found ".$parameters['parent']);
}


if (isset($extra_where)) {
    $where .= $extra_where;
}


if (isset($parameters['elements_type'])) {

    switch ($parameters['elements_type']) {
        case 'stock_status':
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
            } elseif ($count_elements < 5) {
                $where .= ' and `Part Stock Status` in ('.$_elements.')';

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
        $yb_fields = " '' as dispatched_1yb,'' as sales_1yb,";

    } else {
        $yb_fields
            = "`Part $db_period Acc 1YB Dispatched` as dispatched_1yb,`Part $db_period Acc 1YB Invoiced Amount` as sales_1yb,";
    }

} else {
    $db_period = 'Total';
    $yb_fields = " '' as dispatched_1yb,'' as sales_1yb,";
}

if ($parameters['f_field'] == 'used_in' and $f_value != '') {
    $wheref .= " and  `Part XHTML Currently Used In` like '%".addslashes(
            $f_value
        )."%'";
} elseif ($parameters['f_field'] == 'reference' and $f_value != '') {
    $wheref .= " and  `Part Reference` like '".addslashes($f_value)."%'";
} elseif ($parameters['f_field'] == 'supplied_by' and $f_value != '') {
    $wheref .= " and  `Part XHTML Currently Supplied By` like '%".addslashes(
            $f_value
        )."%'";
} elseif ($parameters['f_field'] == 'sku' and $f_value != '') {
    $wheref .= " and  `Part SKU` ='".addslashes($f_value)."'";
} elseif ($parameters['f_field'] == 'description' and $f_value != '') {
    $wheref .= " and  `Part Package Description` like '".addslashes($f_value)."%'";
}

$_order = $order;
$_dir   = $order_direction;



if ($order == 'id') {
    $order = 'P.`Part SKU`';
} elseif ($order == 'stock') {
    $order = '`Part Current On Hand Stock`';
} elseif ($order == 'dispatched') {
    $order = 'dispatched';
} elseif ($order == 'stock_status') {
    $order = '`Part Stock Status`';
} elseif ($order == 'reference') {
    $order = '`Part Reference`';
} elseif ($order == 'sko_description') {
    $order = '`Part Package Description`';
} elseif ($order == 'available_for') {
    $order = '`Part Days Available Forecast`';

} elseif ($order == 'sold') {
    $order = ' sold ';
} elseif ($order == 'sales') {
    $order = ' sales ';
} elseif ($order == 'lost') {
    $order = ' lost ';
} elseif ($order == 'bought') {
    $order = ' bought ';
} elseif ($order == 'valid_from') {
    $order = '`Part Valid From`';
} elseif ($order == 'valid_to') {
    $order = '`Part Valid To`';
} elseif ($order == 'active_from') {
    $order = '`Part Active From`';
} elseif ($order == 'last_update') {
    $order = '`Part Last Updated`';
} elseif ($order == 'delta_sales_year0') {
    $order
        = "(-1*(`Part Year To Day Acc Invoiced Amount`-`Part Year To Day Acc 1YB Invoiced Amount`)/`Part Year To Day Acc 1YB Invoiced Amount`)";
} elseif ($order == 'delta_sales_year1') {
    $order
        = "(-1*(`Part 2 Year Ago Invoiced Amount`-`Part 1 Year Ago Invoiced Amount`)/`Part 2 Year Ago Invoiced Amount`)";
} elseif ($order == 'delta_sales_year2') {
    $order
        = "(-1*(`Part 3 Year Ago Invoiced Amount`-`Part 2 Year Ago Invoiced Amount`)/`Part 3 Year Ago Invoiced Amount`)";
} elseif ($order == 'delta_sales_year3') {
    $order
        = "(-1*(`Part 4 Year Ago Invoiced Amount`-`Part 3 Year Ago Invoiced Amount`)/`Part 4 Year Ago Invoiced Amount`)";
} elseif ($order == 'sales_year0') {
    $order = "`Part Year To Day Acc Invoiced Amount`";
} elseif ($order == 'sales_year1') {
    $order = "`Part 1 Year Ago Invoiced Amount`";
} elseif ($order == 'sales_year2') {
    $order = "`Part 2 Year Ago Invoiced Amount`";
} elseif ($order == 'sales_year3') {
    $order = "`Part 3 Year Ago Invoiced Amount`";
} elseif ($order == 'sales_year4') {
    $order = "`Part 4 Year Ago Invoiced Amount`";
} elseif ($order == 'delta_dispatched_year0') {
    $order
        = "(-1*(`Part Year To Day Acc Dispatched`-`Part Year To Day Acc 1YB Dispatched`)/`Part Year To Day Acc 1YB Dispatched`)";
} elseif ($order == 'delta_dispatched_year1') {
    $order
        = "(-1*(`Part 2 Year Ago Dispatched`-`Part 1 Year Ago Dispatched`)/`Part 2 Year Ago Dispatched`)";
} elseif ($order == 'delta_dispatched_year2') {
    $order
        = "(-1*(`Part 3 Year Ago Dispatched`-`Part 2 Year Ago Dispatched`)/`Part 3 Year Ago Dispatched`)";
} elseif ($order == 'delta_dispatched_year3') {
    $order
        = "(-1*(`Part 4 Year Ago Dispatched`-`Part 3 Year Ago Dispatched`)/`Part 4 Year Ago Dispatched`)";
} elseif ($order == 'dispatched_year1') {
    $order = "`Part 1 Year Ago Dispatched`";
} elseif ($order == 'dispatched_year2') {
    $order = "`Part 2 Year Ago Dispatched`";
} elseif ($order == 'dispatched_year3') {
    $order = "`Part 3 Year Ago Dispatched`";
} elseif ($order == 'dispatched_year4') {
    $order = "`Part 4 Year Ago Dispatched`";
} elseif ($order == 'dispatched_year0') {
    $order = "`Part Year To Day Acc Dispatched`";
} elseif ($order == 'has_picture') {
    $order = "`Part Main Image Key`";
} elseif ($order == 'has_stock') {
    $order = "`Part Current On Hand Stock`";
} elseif ($order == 'sales_total') {
    $order = "`Part Total Acc Invoiced Amount`";
} elseif ($order == 'dispatched_total') {
    $order = "`Part Total Acc Dispatched`";
} elseif ($order == 'customer_total') {
    $order = "`Part Total Acc Customers`";
} elseif ($order == 'percentage_repeat_customer_total') {
    $order = "percentage_repeat_customer_total";
} elseif ($order == 'dispatched_per_week') {
    $order = "`Part 1 Quarter Acc Dispatched`";
} elseif ($order == 'weeks_available') {
    $order = "`Part Days Available Forecast`";
} else {

    $order = '`Part SKU`';
}


$sql_totals
    = "select count(Distinct P.`Part SKU`) as num from $table  $where  ";

$fields
    .= "P.`Part SKU`,`Part Reference`,`Part Package Description`,`Part Current Stock`,`Part Stock Status`,`Part Days Available Forecast`,`Part Current On Hand Stock`,
`Part $db_period Acc Dispatched` as dispatched,
`Part $db_period Acc Invoiced Amount` as sales,
`Part Days Available Forecast`,$yb_fields

`Part 1 Year Ago Dispatched`,`Part 2 Year Ago Dispatched`,`Part 3 Year Ago Dispatched`,`Part 4 Year Ago Dispatched`,`Part 5 Year Ago Dispatched`,
`Part 1 Year Ago Invoiced Amount`,`Part 2 Year Ago Invoiced Amount`,`Part 3 Year Ago Invoiced Amount`,`Part 4 Year Ago Invoiced Amount`,`Part 5 Year Ago Invoiced Amount`,
`Part 1 Quarter Ago Dispatched`,`Part 2 Quarter Ago Dispatched`,`Part 3 Quarter Ago Dispatched`,`Part 4 Quarter Ago Dispatched`,
`Part 1 Quarter Ago Invoiced Amount`,`Part 2 Quarter Ago Invoiced Amount`,`Part 3 Quarter Ago Invoiced Amount`,`Part 4 Quarter Ago Invoiced Amount`,
`Part 1 Quarter Ago 1YB Dispatched`,`Part 2 Quarter Ago 1YB Dispatched`,`Part 3 Quarter Ago 1YB Dispatched`,`Part 4 Quarter Ago 1YB Dispatched`,
`Part 1 Quarter Ago 1YB Invoiced Amount`,`Part 2 Quarter Ago 1YB Invoiced Amount`,`Part 3 Quarter Ago 1YB Invoiced Amount`,`Part 4 Quarter Ago 1YB Invoiced Amount`,
`Part Total Acc Invoiced Amount`,`Part Total Acc Dispatched`,`Part Total Acc Customers`,`Part Total Acc Repeat Customers`,

`Part Year To Day Acc Invoiced Amount`,`Part Year To Day Acc 1YB Profit`,`Part Year To Day Acc Required`,`Part Year To Day Acc Dispatched`,`Part Year To Day Acc 1YB Dispatched`,`Part Year To Day Acc 1YB Invoiced Amount`,
`Part Quarter To Day Acc Invoiced Amount`,`Part Quarter To Day Acc 1YB Profit`,`Part Quarter To Day Acc Required`,`Part Quarter To Day Acc Dispatched`,`Part Quarter To Day Acc 1YB Dispatched`,`Part Quarter To Day Acc 1YB Invoiced Amount`,

`Part 1 Quarter Acc Dispatched`,
`Part Valid From`,`Part Valid From`,`Part Active From`,`Part Main Image Key`,`Part Status`,
if(`Part Total Acc Customers`=0,0,  (`Part Total Acc Repeat Customers`/`Part Total Acc Customers`)) percentage_repeat_customer_total

";


function parts_awhere($awhere) {

    $sql_type = 'part';

    $where_data = array(
        //'product_ordered1'=>'∀',
        //'price'=>array(),
        //'invoice'=>array(),
        //'web_state'=>array(),
        //'availability_state'=>array(),
        'tariff_code'          => '',
        'invalid_tariff_code'  => false,
        'geo_constraints'      => '',
        'part_valid_from'      => '',
        'part_valid_to'        => '',
        'part_dispatched_from' => '',
        'part_dispatched_to'   => '',

        //'product_valid_to'=>'',
        //'price_lower'=>'',
        //'price_upper'=>'',
        //'invoice_lower'=>'',
        // 'invoice_upper'=>''
    );


    //  $awhere=json_decode($awhere,TRUE);


    foreach ($awhere as $key => $item) {
        $where_data[$key] = $item;
    }


    $date_interval_from = prepare_mysql_dates(
        $where_data['part_valid_from'], $where_data['part_valid_to'], array(
            '`Part Valid From`',
            '`Part Valid To`'
        ), 'whole_day'
    );
    $date_dispatched    = prepare_mysql_dates(
        $where_data['part_dispatched_from'], $where_data['part_dispatched_to'], 'ITF.`Date`', 'whole_day'
    );
    if ($where_data['geo_constraints'] != '') {
        $where_geo_constraints = extract_products_geo_groups(
            $where_data['geo_constraints'], '`Dispatch Country Code`', 'CD.`World Region Code`'
        );
    } else {
        $where_geo_constraints = '';
    }


    if ($date_dispatched['mysql'] != '' or $where_geo_constraints != '') {
        $sql_type = 'itf';
        $where    = 'where  `Inventory Transaction Type` in ("Sale","OIP")  ';
        $table
                  = '`Inventory Transaction Fact` ITF  left join `Part Dimension` P on (P.`Part SKU`=ITF.`Part SKU`) ';
    } else {
        $sql_type = 'part';
        $where    = "where true  ";
        $table    = "`Part Dimension` P";
    }


    if ($where_data['invalid_tariff_code'] == 'Yes') {
        $where .= " and `Part Tariff Code Valid`='Yes'";

    } elseif ($where_data['invalid_tariff_code'] == 'No') {
        $where .= " and `Part Tariff Code Valid`='No'";
    }


    $where .= $date_dispatched['mysql'];
    $where .= $date_interval_from['mysql'];
    $where .= $where_geo_constraints;
    //print_r($where_data);
    //print "$table $where  *";exit;
    return array(
        $where,
        $table,
        $sql_type
    );
}


?>
