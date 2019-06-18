<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Refurbished: 3 April 2016 at 18:28:53 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/


$where = "where true  ";
$table = "`Supplier Part Dimension` SP  left join `Part Dimension` P on (P.`Part SKU`=SP.`Supplier Part Part SKU`) left join `Supplier Dimension` S on (SP.`Supplier Part Supplier Key`=S.`Supplier Key`)  left join `Part Data` D on (D.`Part SKU`=P.`Part SKU`) ";


$filter_msg = '';
$sql_type   = 'part';
$filter_msg = '';
$wheref     = '';


if ($parameters['parent'] == 'supplier' or $parameters['parent'] == 'supplier_production') {
    $where = sprintf(
        " where  `Supplier Part Supplier Key`=%d", $parameters['parent_key']
    );

} elseif ($parameters['parent'] == 'account') {

} elseif ($parameters['parent'] == 'part') {
    $where = sprintf(
        " where  SP.`Supplier Part Part SKU`=%d", $parameters['parent_key']
    );
} elseif ($parameters['parent'] == 'agent') {
    $where = sprintf(
        " where  `Agent Supplier Agent Key`=%d", $parameters['parent_key']
    );
    $table .= ' left join `Agent Supplier Bridge` on (SP.`Supplier Part Supplier Key`=`Agent Supplier Supplier Key`)';

} elseif ($parameters['parent'] == 'purchase_order') {
    if ($purchase_order->get('Purchase Order Parent') == 'Supplier') {

        $where = sprintf(
            " where  `Supplier Part Supplier Key`=%d", $purchase_order->get('Purchase Order Parent Key')
        );


    } else {
        $where = sprintf(
            "  where  `Agent Supplier Agent Key`=%d", $purchase_order->get('Purchase Order Parent Key')
        );
        $table .= ' left join `Agent Supplier Bridge` on (SP.`Supplier Part Supplier Key`=`Agent Supplier Supplier Key`)';


    }


} else {
    exit("parent not found x : ".$parameters['parent']);
}


if (isset($parameters['f_period'])) {

    $db_period = get_interval_db_name($parameters['f_period']);
    if (in_array(
        $db_period, array(
                      'Total',
                      '3 Year'
                  )
    )) {
      //  $yb_fields = " '' as dispatched_1yb,'' as sales_1yb,";

        $yb_sales='0';
        $yb_dispatched='0';
    } else {
      //  $yb_fields = "`Part $db_period Acc 1YB Dispatched` as dispatched_1yb,`Part $db_period Acc 1YB Invoiced Amount` as sales_1yb,";
        $yb_sales="`Part $db_period Acc 1YB Invoiced Amount`";
        $yb_dispatched="`Part $db_period Acc 1YB Dispatched`";
    }

} else {
    $db_period = 'Total';
   // $yb_fields = " '' as dispatched_1yb,'' as sales_1yb,";
    $yb_sales='0';
    $yb_dispatched='0';
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
                    $_elements .= ','.prepare_mysql($_key);

                }
            }


            $_elements = preg_replace('/^\,/', '', $_elements);
            if ($_elements == '') {
                $where .= ' and false';
            } elseif ($count_elements < 3) {
                $where .= ' and `Supplier Part Status` in ('.$_elements.')';

            }
            break;
        case 'part_status':
            $_elements      = '';
            $count_elements = 0;
            foreach ($parameters['elements'][$parameters['elements_type']]['items'] as $_key => $_value) {
                if ($_value['selected']) {
                    $count_elements++;

                    if ($_key == "InUse") {
                        $_key = "In Use";
                    } elseif ($_key == "NotInUse") {
                        $_key = "Not In Use";
                    } elseif ($_key == 'InProcess') {
                        $_key = "In Process";
                    }


                    $_elements .= ','.prepare_mysql($_key);

                }
            }
            $_elements = preg_replace('/^\,/', '', $_elements);
            if ($_elements == '') {
                $where .= ' and false';
            } elseif ($count_elements < 4) {

                $where .= ' and `Part Status` in ('.$_elements.')';


            }
            break;


    }
}

if ($parameters['f_field'] == 'reference' and $f_value != '') {
    $wheref .= " and  `Part Reference` like '".addslashes($f_value)."%'";
} elseif ($parameters['f_field'] == 'description' and $f_value != '') {
    $wheref .= " and  `Supplier Part Description` like '".addslashes($f_value)."%'";
}


$_order = $order;
$_dir   = $order_direction;

if ($order == 'reference') {
    $order = '`Supplier Part Reference`';
} elseif ($order == 'description') {
    $order = '`Supplier Part Description`';
} elseif ($order == 'cost') {
    $order = '`Supplier Part Unit Cost`';
} elseif ($order == 'delivered_cost') {
    $order = '(`Supplier Part Unit Cost`+`Supplier Part Unit Extra Cost`)';
} elseif ($order == 'supplier_code') {
    $order = '`Supplier Code`';
}  elseif ($order == 'barcode') {
    $order = '`Part Barcode Number`';
} elseif ($order == 'barcode_sko') {
    $order = '`Part SKO Barcode`';
} elseif ($order == 'barcode_carton') {
    $order = '`Part Carton Barcode`';
} elseif ($order == 'weight_sko') {
    $order = '`Part Package Weight`';
} elseif ($order == 'cbm') {
    $order = '`Supplier Part Carton CBM`';
} elseif ($order == 'dispatched') {
    $order = "`Part $db_period Acc Dispatched` ";
} elseif ($order == 'dispatched_1yb') {
    $order = "(`Part $db_period Acc Dispatched`-$yb_dispatched) /$yb_dispatched ";
} elseif ($order == 'sales') {
    $order = "`Part $db_period Acc Invoiced Amount` ";
} elseif ($order == 'sales_1yb') {
    $order = "(`Part $db_period Acc Invoiced Amount`-$yb_sales) /$yb_sales ";
} elseif ($order == 'stock') {
    $order = '`Part Current On Hand Stock`';
}elseif ($order == 'stock_status') {
    $order = '`Part Stock Status`';
}elseif ($order == 'dispatched_per_week') {
    $order = '`Part 1 Quarter Acc Dispatched`';
}elseif ($order == 'available_forecast') {
    $order = '`Part Days Available Forecast`';
} elseif ($order == 'next_deliveries') {
    $order = "(`Part Number Active Deliveries`+`Part Number Draft Deliveries`)";
}  else {

    $order = '`Supplier Part Key`';
}


$sql_totals = "select count(Distinct SP.`Supplier Part Key`) as num from $table  $where  ";


$fields = "`Part Status`,`Supplier Code`,`Supplier Part Unit Extra Cost`,`Supplier Part Key`,`Supplier Part Part SKU`,`Part Reference`,`Supplier Part Description`,`Supplier Part Supplier Key`,`Supplier Part Reference`,`Supplier Part Status`,`Supplier Part From`,`Supplier Part To`,`Supplier Part Unit Cost`,`Supplier Part Currency Code`,`Part Units Per Package`,`Supplier Part Packages Per Carton`,`Supplier Part Carton CBM`,`Supplier Part Minimum Carton Order`,
`Part Current Stock`,`Part Stock Status`,`Part Status`,`Part Barcode Number`,`Part SKO Barcode`,`Part Current On Hand Stock`,`Part Carton Barcode`,`Part Package Weight`,`Supplier Part Carton CBM`,$yb_sales as sales_1yb,  $yb_dispatched as dispatched_1yb,
`Part Cost in Warehouse`,`Part Next Deliveries Data`,`Part On Demand`,`Part Days Available Forecast`,`Part $db_period Acc Dispatched` as dispatched,`Part $db_period Acc Invoiced Amount` as sales ,
`Part Commercial Value`,`Part 1 Quarter Acc Dispatched`,`Part Fresh`
";
//print $sql_totals;


