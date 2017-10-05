<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 4 October 2017 at 22:58:28 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2017, Inikoo

 Version 3

*/

$table
            = "`Inventory Transaction Fact` ITF left join `Part Dimension` P on (ITF.`Part SKU`=P.`Part SKU`) left join `Delivery Note Dimension` DN on (DN.`Delivery Note Key`=ITF.`Delivery Note Key`)
 left join `Location Dimension` L on (ITF.`Location Key`=L.`Location Key`)  left join `User Dimension` U on (ITF.`User Key`=U.`User Key`)
 ";
$filter_msg = '';
$sql_type   = 'part';
$filter_msg = '';
$wheref     = '';

$fields = '';



$timeseries_record=get_object('timeseries_record',$parameters['parent_key']);
//print_r($timeseries_record);

$_tmp=json_decode($timeseries_record->get('Timeseries Record Metadata'),true);
$from_date=$_tmp['f'];
$to_date=$_tmp['t'];

 $where = sprintf(" where `Inventory Transaction Type` = 'Adjust' and `Inventory Transaction Section`='Audit'  AND `Warehouse Key`=%d %s %s  ",
     $timeseries_record->get('Timeseries Parent Key'),
     ($from_date ? sprintf('and  `Date`>=%s', prepare_mysql($from_date)) : ''), ($to_date ? sprintf('and `Date`<%s', prepare_mysql($to_date)) : '')
     );

if (isset($extra_where)) {
    $where .= $extra_where;
}


//print $where;

if (isset($parameters['elements_type'])) {

    switch ($parameters['elements_type']) {
        case 'type':
            $_elements      = '';
            $count_elements = 0;




            foreach (
                $parameters['elements'][$parameters['elements_type']]['items'] as $_key => $_value
            ) {
                if ($_value['selected']) {
                    $count_elements++;
                    $_elements = $_key;

                }
            }




            if ($_elements == '') {
                $where .= ' and false';
            } elseif ($count_elements < 2) {
                if($_elements=='lost'){
                    $where .= ' and `Inventory Transaction Quantity`<0 ';

                }else{
                    $where .= ' and `Inventory Transaction Quantity`>0 ';

                }


            }
            break;

    }
}


if ($parameters['f_field'] == 'reference' and $f_value != '') {
    $wheref .= " and  `Part Reference` like '".addslashes($f_value)."%'";
} elseif ($parameters['f_field'] == 'description' and $f_value != '') {
    $wheref .= " and  `Part Package Description` like '".addslashes($f_value)."%'";
}

$_order = $order;
$_dir   = $order_direction;

//$order_direction = '';

//print $order;
if ($order == 'reference') {
    $order = '`Part Reference`';
} elseif ($order == 'description') {
    $order = '`Part Package Description`';
} elseif ($order == 'location') {
    $order = '`Location Code`';
}elseif ($order == 'note') {
    $order = '`Note`';
}elseif ($order == 'date') {
    $order = '`Date`';
} elseif ($order == 'user') {
    $order = '`User Handle`';
} elseif ($order == 'change') {
    $order = '`Inventory Transaction Quantity`';
} elseif ($order == 'change_amount') {
    $order = '`Inventory Transaction Amount`';
}  else {

    $order = '`Date`  ';
}



$sql_totals
    = "select count(Distinct `Inventory Transaction Key`) as num from $table  $where  ";

$fields
    .= '`Date`,`Inventory Transaction Section`,`Inventory Transaction Key`,`Inventory Transaction Quantity`,`Warehouse Key`,`Part Package Description`,`Note`,`Inventory Transaction Amount`,
`Part Reference`,ITF.`Part SKU`,`Delivery Note ID`,ITF.`Delivery Note Key`,ITF.`Location Key`,`Location Code`,`Required`,`Part Location Stock`,`Inventory Transaction Type`,`Metadata`,
`Note`,`User Alias`,ITF.`User Key`,`User Handle`,`Given` '           ;



?>
