<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 22 December 2017 at 16:38:21 GMT, Sheffield, UK
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



if ($order == 'number') {
    $order = 'Order File As';
}elseif ($order == 'date') {
    $order = '`Delivery Note Date`';
}elseif ($order == 'customer') {
    $order = '`Order Customer Name`';
}else{

    $order='OTF.`Order Key`';
}


$group_by
    = ' group by OTF.`Order Key` ';

$table = ' `Order Transaction Fact` OTF left join `Product Dimension` P on (P.`Product ID`=OTF.`Product ID`)  left join `Order Dimension` O  on (OTF.`Order Key`=O.`Order Key`) left join `Delivery Note Dimension` DN  on (OTF.`Delivery Note Key`=DN.`Delivery Note Key`) ';

$sql_totals = "";


$fields = "OTF.`Order Key`,O.`Order Public ID`,`Order Customer Name`,`Delivery Note Date`,`Order Store Key`,`Order Customer Key`,`Order Total Amount`,`Order Currency Code`";


?>
