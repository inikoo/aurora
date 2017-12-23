<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 23 December 2017 at 11:45:36 GMT, Sheffield, UK
 Copyright (c) 2017, Inikoo

 Version 3

*/





if($parameters['tariff_code']=='missing'){
    $where = sprintf(' where `Delivery Note Address Country 2 Alpha Code`=%s and (`Product Tariff Code` is null or `Product Tariff Code`="")  and DN.`Delivery Note Key` is not null  ',prepare_mysql($parameters['country_code']));

}else{
    $where = sprintf(' where `Delivery Note Address Country 2 Alpha Code`=%s and `Product Tariff Code` like "%s%%"  and DN.`Delivery Note Key` is not null  ',prepare_mysql($parameters['country_code']),addslashes($parameters['tariff_code']));

}




if (isset($parameters['parent_period']) ) {


    include_once 'utils/date_functions.php';


    list($db_interval, $from, $to, $from_date_1yb, $to_1yb)
        = calculate_interval_dates(
        $db, $parameters['parent_period'], $parameters['parent_from'], $parameters['parent_to']
    );


    $where_interval = prepare_mysql_dates($from, $to, '`Delivery Note Date`');


    $where .= $where_interval['mysql'];


}


$wheref = '';
if ($parameters['f_field'] == 'number' and $f_value != '') {
    $wheref .= " and  `Order Public ID` like '".addslashes($f_value)."%'    ";
}elseif ($parameters['f_field'] == 'customer' and $f_value != '') {
    $wheref = sprintf(
        ' and `Order Customer Name` REGEXP "[[:<:]]%s" ', addslashes($f_value)
    );}


$_order = $order;
$_dir   = $order_direction;



if ($order == 'code') {
    $order = 'Product File As';
}elseif ($order == 'name') {
    $order = '`Product Name`';
}elseif ($order == 'units_send') {
    $order = 'units_send`';
}else{

    $order='OTF.`Product ID`';
}


$group_by
    = ' group by OTF.`Product ID` ';

$table = ' `Order Transaction Fact` OTF left join `Product Dimension` P on (P.`Product ID`=OTF.`Product ID`) left join `Store Dimension` S on (P.`Product Store Key`=S.`Store Key`)  left join `Delivery Note Dimension` DN  on (OTF.`Delivery Note Key`=DN.`Delivery Note Key`) ';

$sql_totals = "";


$fields = "OTF.`Product ID`,P.`Product Code`,`Product Name`,`Product Store Key`,`Product Units Per Case`,`Product Tariff Code`,`Product Price`,`Order Currency Code`,`Product Unit Weight`,`Store Code`,`Store Name`,
sum(`Delivery Note Quantity`*`Product Units Per Case`) as units_send
";


?>
