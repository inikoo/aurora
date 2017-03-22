<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Refurbished: 3 April 2016 at 18:28:53 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/




$where = "where true  ";
$table
       = "`Supplier Part Dimension` SP  left join `Part Dimension` P on (P.`Part SKU`=SP.`Supplier Part Part SKU`) left join `Supplier Dimension` S on (SP.`Supplier Part Supplier Key`=S.`Supplier Key`)  ";

$fields
    = '`Part Status`,`Supplier Code`,`Supplier Part Unit Extra Cost`,`Supplier Part Key`,`Supplier Part Part SKU`,`Part Reference`,`Part Unit Description`,`Supplier Part Supplier Key`,`Supplier Part Reference`,`Supplier Part Status`,`Supplier Part From`,`Supplier Part To`,`Supplier Part Unit Cost`,`Supplier Part Currency Code`,`Part Units Per Package`,`Supplier Part Packages Per Carton`,`Supplier Part Carton CBM`,`Supplier Part Minimum Carton Order`,
`Part Current Stock`,`Part Stock Status`,`Part Status`,`Part Barcode Number`,`Part SKO Barcode`
';

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

    $fields .= '';

} else {
    exit("parent not found x : ".$parameters['parent']);
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
            foreach ($parameters['elements'][$parameters['elements_type']]['items'] as $_key => $_value
            ) {
                if ($_value['selected']) {
                    $count_elements++;

                    if ($_key == "InUse") {
                        $_key = "In Use";
                    } elseif ($_key == "NotInUse") {
                        $_key = "Not In Use";
                    }elseif ($_key == 'InProcess') {
                        $_key = "In Process";
                    }



                    $_elements .= ','.prepare_mysql($_key);

                }
            }
            $_elements = preg_replace('/^\,/', '', $_elements);
            if ($_elements == '') {
                $where .= ' and false';
            } elseif ($count_elements <4) {

                $where .= ' and `Part Status` in ('.$_elements.')';



            }
            break;


    }
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
    $wheref .= " and  `Part Unit Description` like '".addslashes($f_value)."%'";
}

$_order = $order;
$_dir   = $order_direction;

if ($order == 'part_description') {
    $order = '`Part Reference`';
} elseif ($order == 'reference') {
    $order = '`Supplier Part Reference`';
} elseif ($order == 'cost') {
    $order = '`Supplier Part Unit Cost`';
} elseif ($order == 'delivered_cost') {
    $order = '(`Supplier Part Unit Cost`+`Supplier Part Unit Extra Cost`)';
} elseif ($order == 'supplier_code') {
    $order = '`Supplier Code`';
} elseif ($order == 'stock') {
    $order = '`Part Current Stock`';
} else {

    $order = '`Supplier Part Key`';
}


$sql_totals
    = "select count(Distinct SP.`Supplier Part Key`) as num from $table  $where  ";


//print $sql_totals;

?>
