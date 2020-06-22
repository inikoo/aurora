<?php
/*

 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 13 May 2016 at 14:15:15 GMT+8, Kuala Lumpur, Malaysia

 Copyright (c) 2015, Inikoo

 Version 2.0
*/


$where  = sprintf(
    ' where POTF.`Purchase Order Key`=%d', $parameters['parent_key']
);



if(isset($parameters['elements_type'])) {
    switch ($parameters['elements_type']) {

        case 'state':
            $_elements      = '';
            $count_elements = 0;
            foreach (
                $parameters['elements'][$parameters['elements_type']]['items'] as $_key => $_value
            ) {
                if ($_value['selected']) {
                    $count_elements++;


                    if ($_key == 'InProcess') {
                        $_elements .= ",'InProcess','ProblemSupplier'";
                    } elseif ($_key == 'Submitted') {
                        $_elements .= ",'Submitted','ReceivedAgent','Confirmed'";

                    } elseif ($_key == 'InDelivery') {
                        $_elements .= ",'InDelivery','Dispatched','Inputted'";

                    } elseif ($_key == 'Receiving') {
                        $_elements .= ",'Received','Checked','Inputted'";

                    } elseif ($_key == 'Received') {
                        $_elements .= ",'Placed','InvoiceChecked'";

                    } elseif ($_key == 'Cancelled') {
                        $_elements .= ",'Cancelled','NoReceived'";

                    }

                }
            }
            $_elements = preg_replace('/^\,/', '', $_elements);


            if ($_elements == '') {
                $where .= ' and false';
            } elseif ($count_elements < 6) {
                $where .= ' and `Purchase Order Transaction State` in ('.$_elements.')';
            }
            break;

    }

}



$wheref = '';
if ($parameters['f_field'] == 'code' and $f_value != '') {
    $wheref .= " and `Supplier Part Reference` like '".addslashes($f_value)."%'";
}

//


$_order = $order;
$_dir   = $order_direction;

if ($order == 'reference') {
    $order = '`Supplier Part Reference`';
} elseif ($order == 'description_units' or $order == 'description_skos' or $order == 'description_cartons' ) {
    $order = '`Supplier Part Description`';
}elseif ($order == 'created') {
    $order = '`Order Date`';
}elseif ($order == 'items_qty') {
    $order = '`Purchase Order Submitted Units`';
}elseif ($order == 'amount') {
    $order = '`Purchase Order Net Amount`';
}elseif ($order == 'weight') {
    $order = '`Part Package Weight`*`Purchase Order Submitted Units`';
}elseif ($order == 'cbm') {
    $order = '`Supplier Part Carton CBM`*`Purchase Order Submitted Units`';
} elseif ($order == 'last_updated') {
    $order = '`Order Last Updated Date`';
} elseif ($order == 'item_index') {
    $order = '`Purchase Order Item Index`';
} elseif ($order == 'state') {
    $order = '`Purchase Order Transaction State`';
} else {
    $order = '`Purchase Order Transaction Fact Key`';
}


$table
    = "
  `Purchase Order Transaction Fact` POTF
left join `Supplier Part Historic Dimension` SPH on (POTF.`Supplier Part Historic Key`=SPH.`Supplier Part Historic Key`)
 left join  `Supplier Part Dimension` SP on (POTF.`Supplier Part Key`=SP.`Supplier Part Key`)
 left join  `Part Dimension` P on (P.`Part SKU`=SP.`Supplier Part Part SKU`)
 left join  `Part Data` PD on (PD.`Part SKU`=SP.`Supplier Part Part SKU`)
 left join `Supplier Dimension` S on (`Supplier Part Supplier Key`=S.`Supplier Key`)
left join `Supplier Delivery Dimension` SD on (POTF.`Supplier Delivery Key`=SD.`Supplier Delivery Key`)
";

$sql_totals
    = "select count(distinct  `Purchase Order Transaction Fact Key`) as num from $table $where";


$fields
    = "`Part Barcode Number`,`Part SKO Barcode`,`Part Materials`,`Supplier Part Status`,`Purchase Order Ordering Units`,`Purchase Order Submitted Units`,`Supplier Delivery Public ID`,`Supplier Delivery Parent`,`Note to Supplier`,
    `Part Main Image Key`,`Part Barcode Number`,`Purchase Order Transaction State`,
    `Supplier Delivery Units`,POTF.`Supplier Delivery Key`,`Purchase Order Item Index`,`Supplier Part Currency Code`,`Supplier Part Historic Unit Cost`,`Metadata`,`Currency Code`,
`Purchase Order Transaction Fact Key`,`Purchase Order Submitted Units`,POTF.`Supplier Part Key`,`Supplier Part Reference`,POTF.`Supplier Part Historic Key`,`Purchase Order Net Amount`,
`Supplier Part Description`,`Part Units Per Package`,`Supplier Part Packages Per Carton`,`Supplier Part Carton CBM`,POTF.`Purchase Order Key`,`Purchase Order Submitted Unit Extra Cost Percentage`,
`Supplier Part Unit Cost`,`Part Package Weight`,`Purchase Order CBM`,`Purchase Order Weight`,S.`Supplier Key`,`Supplier Code`,`Supplier Part Minimum Carton Order`,
`Part 1 Quarter Ago Dispatched`,`Part 2 Quarter Ago Dispatched`,`Part 3 Quarter Ago Dispatched`,`Part 4 Quarter Ago Dispatched`,`Purchase Order Submitted Unit Cost`,`Supplier Part Unit Extra Cost Percentage`,
`Part 1 Quarter Ago Invoiced Amount`,`Part 2 Quarter Ago Invoiced Amount`,`Part 3 Quarter Ago Invoiced Amount`,`Part 4 Quarter Ago Invoiced Amount`,
`Part 1 Quarter Ago 1YB Dispatched`,`Part 2 Quarter Ago 1YB Dispatched`,`Part 3 Quarter Ago 1YB Dispatched`,`Part 4 Quarter Ago 1YB Dispatched`,
`Part 1 Quarter Ago 1YB Invoiced Amount`,`Part 2 Quarter Ago 1YB Invoiced Amount`,`Part 3 Quarter Ago 1YB Invoiced Amount`,`Part 4 Quarter Ago 1YB Invoiced Amount`,
`Part Quarter To Day Acc Dispatched`,`Part Stock Status`,`Part Current On Hand Stock`,`Part Reference`,`Part Total Acc Dispatched`,
`Part Products Web Status`,`Part On Demand`,`Part Days Available Forecast`,`Part Fresh`,P.`Part SKU`,`Part 1 Year Acc Dispatched`,`Part Main Image Key`,`Part Next Deliveries Data`,`Supplier Name`,
`Purchase Order Submitted Units Per SKO`,`Purchase Order Submitted SKOs Per Carton`,`Purchase Order Submitted Units Per SKO`,
`Purchase Order Submitted Cancelled Units`,`Purchase Order Manufactured Units`

";



