<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 28 June 2021 16:12 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2021, Inikoo

 Version 3

*/



$where = "where true  ";
$table = "`Customer Part Dimension` SP  left join `Part Dimension` P on (P.`Part SKU`=SP.`Customer Part Part SKU`) left join `Customer Dimension` S on (SP.`Customer Part Customer Key`=S.`Customer Key`)  left join `Part Data` D on (D.`Part SKU`=P.`Part SKU`) ";


$filter_msg = '';
$sql_type   = 'part';
$wheref     = '';




if ($parameters['parent'] == 'customer') {
    $where = sprintf(
        " where  `Customer Part Customer Key`=%d", $parameters['parent_key']
    );

} elseif ($parameters['parent'] == 'account') {



    if($parameters['tab']=='customers.customer_parts.surplus'){
        $where =  " where  `Part Stock Status`='Surplus'  and `Part Status`='In Use'  ";
    }elseif($parameters['tab']=='customers.customer_parts.ok'){
        $where =  " where  `Part Stock Status`='Optimal'   and `Part Status`='In Use' ";
    }elseif($parameters['tab']=='customers.customer_parts.low'){
        $where =  " where  `Part Stock Status`='Low'   and `Part Status`='In Use'  ";
    }elseif($parameters['tab']=='customers.customer_parts.critical'){
        $where =  " where  `Part Stock Status`='Critical'   and `Part Status`='In Use' ";
    }elseif($parameters['tab']=='customers.customer_parts.out_of_stock'){
        $where =  " where  `Part Stock Status`='Out_Of_Stock'   and `Part Status`='In Use' ";
    }


} elseif ($parameters['parent'] == 'part') {
    $where = sprintf(
        " where  SP.`Customer Part Part SKU`=%d", $parameters['parent_key']
    );
}elseif ($parameters['parent'] == 'purchase_order') {

        $where = sprintf(
            " where  `Customer Part Customer Key`=%d", $purchase_order->get('Purchase Order Parent Key')
        );




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
                $where .= ' and `Customer Part Status` in ('.$_elements.')';

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
    $wheref .= " and ( `Part Reference` like '".addslashes($f_value)."%'   or  `Customer Part Reference` like '".addslashes($f_value)."%' ) "  ;
} elseif ($parameters['f_field'] == 'description' and $f_value != '') {
    $wheref .= " and  `Customer Part Description` like '".addslashes($f_value)."%'";
}



$_order = $order;
$_dir   = $order_direction;

if ($order == 'reference') {
    $order = '`Customer Part Reference`';
} elseif ($order == 'description') {
    $order = '`Customer Part Description`';
} elseif ($order == 'cost') {
    $order = '`Customer Part Unit Cost`';
} elseif ($order == 'delivered_cost') {
    $order = '(`Customer Part Unit Cost`+`Customer Part Unit Extra Cost`)';
} elseif ($order == 'customer_code') {
    $order = '`Customer Code`';
}  elseif ($order == 'barcode') {
    $order = '`Part Barcode Number`';
} elseif ($order == 'barcode_sko') {
    $order = '`Part SKO Barcode`';
} elseif ($order == 'barcode_carton') {
    $order = '`Part Carton Barcode`';
} elseif ($order == 'weight_sko') {
    $order = '`Part Package Weight`';
} elseif ($order == 'cbm') {
    $order = '`Customer Part Carton CBM`';
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

    $order = '`Customer Part Key`';
}


$sql_totals = "select count(Distinct SP.`Customer Part Key`) as num from $table  $where  ";


$fields = "`Part Status`,`Customer Name`,`Customer Part Key`,`Customer Part Part SKU`,`Part Reference`,`Customer Part Description`,`Customer Part Customer Key`,`Customer Part Reference`,`Customer Part Status`,`Customer Part From`,`Customer Part To`,`Customer Part Unit Cost`,`Customer Part Currency Code`,`Part Units Per Package`,`Customer Part Packages Per Carton`,`Customer Part Carton CBM`,
`Part Current Stock`,`Part Stock Status`,`Part Status`,`Part Barcode Number`,`Part SKO Barcode`,`Part Current On Hand Stock`,`Part Carton Barcode`,`Part Package Weight`,`Customer Part Carton CBM`,$yb_sales as sales_1yb,  $yb_dispatched as dispatched_1yb,
`Part Cost in Warehouse`,`Part Next Deliveries Data`,`Part On Demand`,`Part Days Available Forecast`,`Part $db_period Acc Dispatched` as dispatched,`Part $db_period Acc Invoiced Amount` as sales ,
`Part Commercial Value`,`Part 1 Quarter Acc Dispatched`
";
//print $sql_totals;


