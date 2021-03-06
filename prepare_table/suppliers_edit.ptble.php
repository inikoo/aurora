<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 26 May 2016 at 12:57:24 CEST, Mijas Costa, Spain
 Copyright (c) 2016, Inikoo

 Version 3

*/


include_once 'utils/date_functions.php';

$currency   = '';
$where      = 'where true';
$table
            = '`Supplier Dimension` S left join `Supplier Data`  D on (S.`Supplier Key`=D.`Supplier Key`)';
$group_by   = '';
$where_type = '';


if (isset($parameters['awhere']) and $parameters['awhere']) {

    $tmp = preg_replace('/\\\"/', '"', $parameters['awhere']);
    $tmp = preg_replace('/\\\\\"/', '"', $tmp);
    $tmp = preg_replace('/\'/', "\'", $tmp);

    $raw_data              = json_decode($tmp, true);
    $raw_data['store_key'] = $parameters['parent_key'];
    include_once 'list_functions_supplier.php';
    list($where, $table, $group_by) = suppliers_awhere($raw_data);


} elseif ($parameters['parent'] == 'list') {


    $sql = sprintf(
        "SELECT * FROM `List Dimension` WHERE `List Key`=%d", $parameters['parent_key']
    );

    $res = mysql_query($sql);
    if ($supplier_list_data = mysql_fetch_assoc($res)) {
        $parameters['awhere'] = false;
        if ($supplier_list_data['List Type'] == 'Static') {
            $table
                   = '`List Supplier Bridge` CB left join `Supplier Dimension` C  on (CB.`Supplier Key`=S.`Supplier Key`)';
            $where = sprintf(
                ' where `List Key`=%d ', $parameters['parent_key']
            );

        } else {

            $tmp = preg_replace(
                '/\\\"/', '"', $supplier_list_data['List Metadata']
            );
            $tmp = preg_replace('/\\\\\"/', '"', $tmp);
            $tmp = preg_replace('/\'/', "\'", $tmp);

            $raw_data = json_decode($tmp, true);

            $raw_data['store_key'] = $supplier_list_data['List Parent Key'];
            include_once 'utils/list_functions_supplier.php';

            list($where, $table, $group_by) = suppliers_awhere($raw_data);


        }

    } else {
        return;
    }


} elseif ($parameters['parent'] == 'category') {


    $where = sprintf(
        " where `Subject`='Supplier' and  `Category Key`=%d", $parameters['parent_key']
    );
    $table
           = ' `Category Bridge` C left join  `Supplier Dimension` S on (`Subject Key`=`Supplier Key`)  left join `Supplier Data`  D on (S.`Supplier Key`=D.`Supplier Key`)';

} elseif ($parameters['parent'] == 'agent') {

    $where = sprintf(
        " where `Agent Supplier Agent Key`=%d", $parameters['parent_key']
    );
    $table
           = ' `Agent Supplier Bridge` B left join  `Supplier Dimension` S on (`Agent Supplier Supplier Key`=`Supplier Key`)  left join `Supplier Data`  D on (S.`Supplier Key`=D.`Supplier Key`)';
} else {

    $where = sprintf(" where `Supplier Has Agent`='No'");

}


$filter_msg = '';
$wheref     = '';
if ($parameters['f_field'] == 'code' and $f_value != '') {
    $wheref .= " and `Supplier Code` like '".addslashes($f_value)."%'";
}
if ($parameters['f_field'] == 'name' and $f_value != '') {
    $wheref .= " and  `Supplier Name` like '".addslashes($f_value)."%'";
} elseif ($parameters['f_field'] == 'low' and is_numeric($f_value)) {
    $wheref .= " and lowstock>=$f_value  ";
} elseif ($parameters['f_field'] == 'outofstock' and is_numeric($f_value)) {
    $wheref .= " and outofstock>=$f_value  ";
}


$_order = $order;
$_dir   = $order_direction;


if ($order == 'code') {
    $order = '`Supplier Code`';
} elseif ($order == 'name') {
    $order = '`Supplier Name`';
} elseif ($order == 'location') {
    $order = '`Supplier Location`';
} elseif ($order == 'email') {
    $order = '`Supplier Main XHTML Email`';
} elseif ($order == 'telephone') {
    $order = '`Supplier Preferred Contact Number Formatted Number`';
} elseif ($order == 'contact') {
    $order = "`Supplier Main Contact Name`";
} elseif ($order == 'company') {
    $order = "`Supplier Company Name`";
} elseif ($order == 'supplier_parts') {
    $order = '`Supplier Number Parts`';
} elseif ($order == 'revenue') {
    $order = "`Supplier $db_period Acc Parts Sold Amount`";
} elseif ($order == 'pending_pos') {
    $order = '`Supplier Number Open Purchase Orders`';
} elseif ($order == 'margin') {
    $order = "`Supplier $db_period Acc Parts Margin`";
} elseif ($order == 'cost') {
    $order = "`Supplier $db_period Acc Parts Cost`";
} elseif ($order == 'origin') {
    $order = "`Supplier Products Origin Country Code`";
} elseif ($order == 'delivery_time') {
    $order = "`Supplier Average Delivery Days`";
} elseif ($order == 'low') {
    $order = "`Supplier Number Low Parts`";
} elseif ($order == 'surplus') {
    $order = "`Supplier Number Surplus Parts`";
} elseif ($order == 'optimal') {
    $order = "`Supplier Number Optimal Parts`";
} elseif ($order == 'low') {
    $order = "`Supplier Number Low Parts`";
} elseif ($order == 'critical') {
    $order = "`Supplier Number Critical Parts`";
} elseif ($order == 'out_of_stock') {
    $order = "`Supplier Number Out Of Stock Parts`";
} else {
    $order = "S.`Supplier Key`";
}

$sql_totals
    = "select count(Distinct S.`Supplier Key`) as num from $table  $where  $where_type";

$fields
    = "
S.`Supplier Key`,`Supplier Code`,`Supplier Name`,`Supplier Main XHTML Mobile`,`Supplier Main XHTML Telephone`,
`Supplier Location`,`Supplier Main Plain Email`,`Supplier Preferred Contact Number`,`Supplier Preferred Contact Number Formatted Number`,`Supplier Main Contact Name`,`Supplier Company Name`,
`Supplier Number Parts`,`Supplier Number Surplus Parts`,`Supplier Number Optimal Parts`,`Supplier Number Low Parts`,`Supplier Number Critical Parts`,`Supplier Number Critical Parts`,`Supplier Number Out Of Stock Parts`

";
/*
`Supplier Products Origin Country Code`,`Supplier $db_period Acc Parts Sold Amount`,`Supplier $db_period Acc 1YB Parts Sold Amount`,
`Supplier $db_period Acc Parts Profit`,`Supplier $db_period Acc Parts Profit After Storing`,`Supplier $db_period Acc Parts Cost`,`Supplier $db_period Acc Parts Sold`,`Supplier $db_period Acc Parts Required`,`Supplier $db_period Acc Parts Margin`,

`Supplier Average Delivery Days`,`Supplier Number Open Purchase Orders`,
`Supplier 1 Year Ago Sales Amount`,`Supplier 2 Year Ago Sales Amount`,`Supplier 3 Year Ago Sales Amount`,`Supplier 4 Year Ago Sales Amount`,
*/

?>
