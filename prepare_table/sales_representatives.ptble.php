<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 14 August 2018 at 12:26:02 GMT+8, Kuta, Bali Indonesia
 Copyright (c) 2018, Inikoo

 Version 3

*/



$where_interval_working_hours='';


$where = " where `Invoice Sales Representative Key` is not NULL   ";


if (isset($parameters['period']) ) {

    include_once 'utils/date_functions.php';
    list($db_interval, $from, $to, $from_date_1yb, $to_1yb)
        = calculate_interval_dates(
        $db, $parameters['period'], $parameters['from'], $parameters['to']
    );
    $where_interval = prepare_mysql_dates($from, $to, '`Invoice Date`');
    $where .= $where_interval['mysql'];

}









$wheref = '';
if ($parameters['f_field'] == 'name' and $f_value != '') {
    $wheref = sprintf(
        ' and `Staff Name` REGEXP "\\\\b%s" ', addslashes($f_value)
    );
}


$_order = $order;
$_dir   = $order_direction;


if ($order == 'name') {
    $order = '`User Alias`';
}else{

    $order='`Sales Representative Key`';
}


$group_by
    = 'group by `Sales Representative Key`';

$table = ' `Sales Representative Dimension` SRD  left join `Invoice Dimension` I  on (I.`Invoice Sales Representative Key`=`Sales Representative Key`)   left join `User Dimension` S on (S.`User Key`=`Sales Representative User Key`) ';

$fields = "`Sales Representative Key`,`User Alias`,`User Key`,sum(if(`Invoice Type`='Invoice',1,0))  AS invoices, sum(if(`Invoice Type`='Refund',1,0))  AS refunds, count(Distinct `Invoice Customer Key`)  AS customers,
IFNULL(sum(`Invoice Total Net Amount`*`Invoice Currency Exchange`),0) sales  

";


$sql_totals = "select count(Distinct `Sales Representative Key` )  as num from $table  $where  ";




?>
